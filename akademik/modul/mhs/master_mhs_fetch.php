<?php
include '../../../conn.php';
include '../../../include/include_rid_prodi.php';
include '../../../include/include_rid_jalur.php';
include '../../../include/include_rangkatan.php';
include '../../../include/include_rshift.php';

$null = '<span class="red kecil miring consolas">null</span>';
$unset = '<span class="red kecil miring consolas">unset</span>';

$keyword = $_GET['keyword'] ?? die(erid('keyword'));
$angkatan = $_GET['angkatan'] ?? die(erid('angkatan'));
$status_mhs = $_GET['status_mhs'] ?? die(erid('status_mhs'));
$id_prodi = $_GET['id_prodi'] ?? die(erid('id_prodi'));
$id_jalur = $_GET['id_jalur'] ?? die(erid('id_jalur'));
$shift = $_GET['shift'] ?? die(erid('shift'));
$limit = $_GET['limit'] ?? die(erid('limit'));
$order_by = $_GET['order_by'] ?? die(erid('order_by'));
$get_csv = $_GET['get_csv'] ?? die(erid('get_csv'));

$filter_keyword = $keyword=='' ? '1' : "(a.nama like '%$keyword%' OR a.nim like '%$keyword%')";
$filter_angkatan = $angkatan=='all' ? '1' : "a.angkatan='$angkatan'";
$filter_shift = $shift=='all' ? '1' : "a.shift='$shift'";
$filter_jalur = $id_jalur=='all' ? '1' : "a.id_jalur='$id_jalur'";
$filter_prodi = $id_prodi=='all' ? '1' : "a.id_prodi='$id_prodi'";

$tr='';

# ================================================
$sql_columns = "
a.id as id_mhs,
a.angkatan,
a.id_jalur,
a.id_prodi,
a.shift,
a.nama as nama_mhs,
a.nim,
c.last_semester_aktif as semester, 
(a.angkatan + FLOOR((c.last_semester_aktif-1)/2)) tahun_ajar,
(
  SELECT p.id FROM tb_kurikulum p 
  JOIN tb_kalender q ON p.id_kalender=q.id 
  WHERE p.id_prodi=a.id_prodi 
  AND q.angkatan=a.angkatan 
) id_kurikulum, 
(
  SELECT p.kelas FROM tb_kelas_ta p 
  JOIN tb_kelas_ta_detail q ON p.id=q.id_kelas_ta 
  WHERE 1 
  AND q.nim = a.nim 
  AND p.tahun_ajar = (a.angkatan + FLOOR((c.last_semester_aktif-1)/2)) ) kelas_ta
";

# ================================================
$sql_join = "
JOIN tb_prodi b ON a.id_prodi=b.id
JOIN tb_angkatan c ON a.angkatan=c.angkatan 
";

# ================================================
$sql_where = "
status_mhs = $status_mhs  
AND $filter_angkatan 
AND $filter_prodi 
AND $filter_jalur 
AND $filter_shift 
AND $filter_keyword 
";



# ================================================
# GET CSV HANDLER
# ================================================
$rstatus = ['Non-Aktif','Aktif'];
if($get_csv){
  $s = "SELECT $sql_columns FROM tb_mhs a $sql_join WHERE $sql_where ORDER BY $order_by";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));

  $prodi = ($id_prodi!='all' and $id_prodi!='null') ? $rprodi[$id_prodi] : $id_prodi;
  $jalur = ($id_jalur!='all' and $id_jalur!='null') ? $rjalur[$id_jalur] : $id_jalur;

  $isi_csv = "GET CSV MASTER PMB :: SIAKAD STMIK IKMI CIREBON";
  $isi_csv .= "\nTanggal,:,".date('Y-m-d H:i:s');
  $isi_csv .= "\nstatus_mhs,:,$rstatus[$status_mhs]";
  $isi_csv .= "\nangkatan,:,$angkatan";
  $isi_csv .= "\nid_prodi,:,$prodi";
  $isi_csv .= "\nid_jalur,:,$jalur";
  $isi_csv .= "\nshift,:,$shift";
  $isi_csv .= "\norder_by,:,$order_by";
  $isi_csv .= "\n";

  $i=0;
  $isi_csv .= "\nNO,MHS,NIM,PRODI,ANGKATAN,JALUR,SHIFT,SEMESTER,KELAS-TA";
  while ($d=mysqli_fetch_assoc($q)) {
    $i++;
    $prodi = $d['id_prodi']=='' ? 'NULL' : $rprodi[$d['id_prodi']];
    $jalur = $d['id_jalur']=='' ? 'NULL' : $rjalur[$d['id_jalur']];
    $kelas_ta = $d['kelas_ta']=='' ? 'NULL' : "$d[kelas_ta] ~ $d[tahun_ajar]";
    $isi_csv .= "\n$i,$d[nama_mhs],$d[nim],$prodi,$d[angkatan],$jalur,$d[shift],$d[semester],$kelas_ta";
  }
  $path_csv = "csv/master_mhs.csv";
  $fcsv = fopen("../../$path_csv", "w+") or die("$path_csv cannot accesible.");
  fwrite($fcsv, $isi_csv);
  fclose($fcsv);
}else{ // tanpa get_csv
  $s = "SELECT 1 FROM tb_mhs a $sql_join WHERE $sql_where";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
}
$jumlah_records = mysqli_num_rows($q);

# ================================================
$s = "SELECT $sql_columns FROM tb_mhs a $sql_join WHERE $sql_where
ORDER BY $order_by LIMIT $limit
";
// die($s);
// echo "<pre>$s</pre>";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));

while ($d=mysqli_fetch_assoc($q)) {
  $id_mhs = $d['id_mhs'];
  $jalur = $d['id_jalur']=='' ? $null : $rjalur[$d['id_jalur']];
  $prodi = $rprodi[$d['id_prodi']];
  $nama_mhs = ucwords(strtolower($d['nama_mhs']));
  $nama_mhs = "<span id=nama_mhs__$id_mhs>$nama_mhs</span>";
  $nim = "<span id=nim__$id_mhs>$d[nim]</span>";
  $nama = $order_by=='a.nama' ? "$nama_mhs | $nim" : "$nim | $nama_mhs";
  $kelas_ta = $d['kelas_ta']=='' ? $unset : "$d[kelas_ta] ~ TA.$d[tahun_ajar]";
  $kelas_ta = "<a href='?manage_grup_kelas&id_kurikulum=$d[id_kurikulum]' target=_blank onclick='return confirm(\"Menuju manage kelas untuk Mhs ini?\")'>$kelas_ta</a>";
  $tr.="
  <tr class='tr_mhs' id='tr_mhs__$id_mhs'>
    <td>$prodi<span class=debug id=id_prodi__$id_mhs>$d[id_prodi]</span></td>
    <td id=angkatan__$id_mhs>$d[angkatan]</td>
    <td>$jalur<span class=debug id=id_jalur__$id_mhs>$d[id_jalur]</span></td>
    <td class=proper id=shift__$id_mhs>$d[shift]</td>
    <td>$nama</td>
    <td>$d[semester]</td>
    <td>$kelas_ta</td>
    <td><span id=edit__$id_mhs class='edit_mhs pointer blue'>Edit</span> | Delete</td>
  </tr>
  ";
}

$limit = $limit>$jumlah_records ? $jumlah_records : $limit;
$btn_download_csv = $get_csv ? "<a class='btn btn-primary btn-sm' href='$path_csv' target=_blank>Download CSV</a>" : '';
$info_server = $limit<$jumlah_records ? '<div class="alert alert-info" id=info_data_sisa>Data sisa tidak ditampilkan untuk menjaga kestabilan server. Silahkan Re-Filter, Get CSV, atau Show-All (not recomended).</div>' : '';


# ===========================================================
# QUICK EDIT 
# ===========================================================
$opt_prodi = ''; foreach ($rid_prodi as $id_prodi) $opt_prodi.="<option value=$id_prodi>$rprodi[$id_prodi]</option>";
$opt_jalur = ''; foreach ($rid_jalur as $id_jalur) $opt_jalur.="<option value=$id_jalur>$rjalur[$id_jalur]</option>";
$opt_angkatan = ''; foreach ($rangkatan as $angkatan) $opt_angkatan.="<option>$angkatan</option>";
$opt_shift = ''; foreach ($rshift as $shift) $opt_shift.="<option>$shift</option>";

$select_prodi = "<select class='form-control' id=edit_prodi disabled>$opt_prodi</select>";
$select_angkatan = "<select class='form-control' id=edit_angkatan disabled>$opt_angkatan</select>";
$select_jalur = "<select class='form-control' id=edit_jalur>$opt_jalur</select>";
$select_shift = "<select class='form-control' id=edit_shift>$opt_shift</select>";
$input_nama = "<input class='form-control' id=edit_nama_mhs>";
$input_nim = "<input class='form-control' id=edit_nim>";
$select_status_mhs = "<select class='form-control' id=edit_status_mhs><option value=1>Aktif</option><option value=0>Non-Aktif</option></select>";

$tr_edit = "
  <tr id='tr_edit' class=hideit>
    <td>$select_prodi</td>
    <td>$select_angkatan</td>
    <td>$select_jalur</td>
    <td>$select_shift</td>
    <td><div class=flexy><div>$input_nama</div><div>$input_nim</div></div></td>
    <td colspan=2>
      <div class=flexy>
        <div>Status:</div>
        <div>$select_status_mhs</div>
      </div>
    </td>
    <td><button class='btn btn-primary btn-sm btn-block' id=btn_save>Save</button></td>
  </tr>
";

# ===========================================================
# FINAL OUTPUT 
# ===========================================================
echo "
<div class='show_records'>
  <div>Show $limit of <span style='color:blue; font-size:20px; display:inline-block; margin-left:5px'>$jumlah_records records</span></div>
  <div>$btn_download_csv</div>
</div>
<table class=table>
  <thead>
    <th>Prodi</th>
    <th>Angkatan</th>
    <th>Jalur</th>
    <th>Shift</th>
    <th>Mahasiswa</th>
    <th>Semester</th>
    <th>Kelas-TA</th>
    <th>Aksi</th>
  </thead>
  $tr
  $tr_edit
</table>
$info_server
";

?>
