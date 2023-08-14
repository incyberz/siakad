<?php
include 'session_security.php';
include '../../conn.php';

$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : die(erid('keyword'));
$kelas = isset($_GET['kelas']) ? $_GET['kelas'] : die(erid('kelas'));
$tahun_ajar = isset($_GET['tahun_ajar']) ? $_GET['tahun_ajar'] : die(erid('tahun_ajar'));
$punya_kelas = isset($_GET['punya_kelas']) ? $_GET['punya_kelas'] : die(erid('punya_kelas'));
// $punya_kelas = 1;

# ===================================================
# LIST JADWAL
# ===================================================
$limit = 50;
$s = "SELECT 
a.id as id_mhs,
a.nim,
a.nama as nama_mhs,
(
  SELECT b.kelas from tb_kelas_ta b 
  JOIN tb_kelas_ta_detail c ON b.id=c.id_kelas_ta 
  where c.id_mhs=a.id and b.tahun_ajar=$tahun_ajar) as kelas,  
(
  SELECT id from tb_kelas_ta_detail b 
  where b.id_mhs=a.id) as id_kelas_ta_detail  

FROM tb_mhs a 
WHERE (a.nim like '%$keyword%' OR a.nama like '%$keyword%') 

ORDER BY a.nama    
";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
$jumlah_row = mysqli_num_rows($q);

$s .= " LIMIT $limit 
";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
echo "<span class=debug>$s</span>";

$thead = "
  <thead>
    <th>No</th>
    <th>NIM</th>
    <th>Nama</th>
    <th>Kelas (TA-$tahun_ajar)</th>
    <th>Aksi</th>
  </thead>
";
$tr = '';
$i=0;
while ($d = mysqli_fetch_assoc($q)) {
  // if($d['kelas']==$kelas) continue;
  if(!$punya_kelas and $d['kelas']!='') continue;
  $i++;
  // $bg_merah = $d['kelas']=='' ? '' : 'bg-merah'; 
  $tr_sty = ($d['kelas']=='') ? '' : 'style="background:linear-gradient(#fee,#fcc)"'; 
  $tr_sty = ($d['kelas']==$kelas) ? 'style="background:linear-gradient(#efe,#cfc)"' : $tr_sty; 

  $id_kelas_ta_detail = $d['id_kelas_ta_detail']==''? 'new' : $d['id_kelas_ta_detail'];

  $btn_assign = $d['kelas']=='' 
  ? "<button class='btn btn-primary btn-sm btn_aksi' id='assign__$d[id_mhs]__$id_kelas_ta_detail'>Assign ke $kelas</button>"
  : "<button class='btn btn-danger btn-sm btn_aksi' id='drop__$d[id_mhs]__$id_kelas_ta_detail'>Drop</button>";

  $btn_assign = $d['kelas']==$kelas ? '' : $btn_assign;
  $kelas_ini = $d['kelas']==$kelas ? '<div class="kecil miring">sudah terdaftar di kelas ini.</div>' : '';

  $kelas_show = $d['kelas']==''?'<span class="abu miring">null</span>':$d['kelas'];
  $tr .= "<tr $tr_sty class='bg_merah' id='tr__$d[id_mhs]'>
    <td>$i</td>
    <td>$d[nim]</td>
    <td>$d[nama_mhs]</td>
    <td id=kelas_asal__$d[id_mhs]>$kelas_show$kelas_ini</td>
    <td>
      $btn_assign
    </td>
  </tr>";
}

$jumlah_row_of = "Tampil $i data dari $jumlah_row total";
$sisa_data = $i==$limit ? "$jumlah_row_of <span class=red>(masih ada data sisa)</span> silahkan filter dengan lebih spesifik." : $jumlah_row_of;

$tb = $tr=='' ? '<div class="alert alert-info">Mahasiswa tidak ditemukan. | <a href="?master&p=mhs">Master Mhs</a></div>' : "<table class='table table-hover'>$thead$tr</table>";

$debug = "<div class=debug>$s</div>";
echo "<div class=wadah>$sisa_data</div>$tb$debug";