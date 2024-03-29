<h1>Manage Sesi Kuliah</h1>
<p>Manage Sesi hanya bisa dilakukan setelah adanya Penjadwalan Dosen dan Awal Perkuliahan sudah ditentukan.</p>
<?php 
$id_kurikulum = $_GET['id_kurikulum'] ?? '';
if($id_kurikulum==''){
  $s = "SELECT 
  a.id as id_kurikulum,
  b.angkatan,
  b.jenjang,
  c.singkatan, 
  (
    SELECT count(1) FROM tb_kurikulum p 
    JOIN tb_kurikulum_mk q ON q.id_kurikulum=p.id 
    JOIN tb_jadwal r ON r.id_kurikulum_mk=q.id
    JOIN tb_sesi s ON s.id_jadwal=r.id 
    WHERE r.shift='sore' AND p.id=a.id AND r.awal_kuliah is not null AND s.pertemuan_ke=1) count_sesi_sore,   
  (
    SELECT count(1) FROM tb_kurikulum p 
    JOIN tb_kurikulum_mk q ON q.id_kurikulum=p.id 
    JOIN tb_jadwal r ON r.id_kurikulum_mk=q.id
    JOIN tb_sesi s ON s.id_jadwal=r.id 
    WHERE r.shift='pagi' AND p.id=a.id AND r.awal_kuliah is not null AND s.pertemuan_ke=1) count_sesi_pagi,   
  (
    SELECT count(1) FROM tb_kurikulum p 
    JOIN tb_kurikulum_mk q ON q.id_kurikulum=p.id 
    JOIN tb_jadwal r ON r.id_kurikulum_mk=q.id
    WHERE r.shift='pagi' AND p.id=a.id AND r.awal_kuliah is not null) count_jadwal_pagi,   
  (
    SELECT count(1) FROM tb_kurikulum p 
    JOIN tb_kurikulum_mk q ON q.id_kurikulum=p.id 
    JOIN tb_jadwal r ON r.id_kurikulum_mk=q.id
    WHERE r.shift='sore' AND p.id=a.id AND r.awal_kuliah is not null) count_jadwal_sore   
  FROM tb_kurikulum a 
  JOIN tb_kalender b ON a.id_kalender=b.id
  JOIN tb_prodi c ON a.id_prodi=c.id
  WHERE 1 
  ORDER BY b.angkatan DESC, c.id
  ";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  if(mysqli_num_rows($q)==0) die(div_alert('danger', "Belum ada Kurikulum. Silahkan <a href='?manage_kurikulum'>Manage Kurikulum</a> !"));
  $tr='';
  $i=0;
  $last_angkatan = '';
  while ($d=mysqli_fetch_assoc($q)) {
    $i++;
    $border = $last_angkatan==$d['angkatan'] ? '' : 'style="border-top: solid 6px #faf"';
    $green = $d['jenjang']=='D3' ? 'green gradasi-hijau' : 'darkblue gradasi-biru';
    $primary = $d['jenjang']=='D3' ? 'success' : 'primary';

    $count_jadwal_pagi = $d['count_jadwal_pagi'] ? "<div>$d[count_jadwal_pagi] Jadwal Pagi</div>" : '<div>-</div>';
    $count_jadwal_sore = $d['count_jadwal_sore'] ? "<div>$d[count_jadwal_sore] Jadwal Sore</div>" : '<div>-</div>';

    $unsesi_pagi = $d['count_jadwal_pagi']-$d['count_sesi_pagi'];
    if($unsesi_pagi){
      $belum_ada_sesi_pagi = "<div class='red bold'>$unsesi_pagi | belum ada Sesi Pagi</div>";
    }else{
      $belum_ada_sesi_pagi = $d['count_jadwal_pagi'] ? '<div class=green>Sesi Lengkap</div>' : '<div>-</div>';
    }

    $unsesi_sore = $d['count_jadwal_sore']-$d['count_sesi_sore'];
    $belum_ada_sesi_sore = $unsesi_sore 
    ? "<div class='red bold'>$unsesi_sore | belum ada Sesi Sore</div>" 
    : '<div>-</div>';

    $tr .= "
    <tr class='$green' $border>
      <td>$i</td>
      <td>$d[jenjang]-$d[singkatan]-$d[angkatan]</td>
      <td>
        $count_jadwal_pagi
        $count_jadwal_sore
      </td>
      <td>
        $belum_ada_sesi_pagi
        $belum_ada_sesi_sore
      </td>
      <td>
        <div class=mb1><a class='btn btn-$primary btn-sm' href='?manage_sesi&id_kurikulum=$d[id_kurikulum]&shift=pagi'>Manage Sesi Pagi</a></div>
        <a class='btn btn-$primary btn-sm' href='?manage_sesi&id_kurikulum=$d[id_kurikulum]&shift=sore'>Manage Sesi sore</a>
      </td>
    </tr>
    ";
    $last_angkatan=$d['angkatan'];
  }

  echo "
  <p class=biru>Silahkan pilih link manage dari salah satu Kurikulum!</p>
  <table class='table'>
    <thead>
      <th>No</th>
      <th>Kurikulum</th>
      <th>Jadwal dg Awal Kuliah Terisi</th>
      <th>Belum Punya Sesi</th>
      <th>Aksi</th>
    </thead>
    $tr
  </table>";
  exit;
}

$shift = $_GET['shift'] ?? '';
if($shift==''){
  $s = "SELECT * FROM tb_shift";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  while ($d=mysqli_fetch_assoc($q)) {
    echo "<a href='?manage_sesi&id_kurikulum=$id_kurikulum&shift=$d[shift]' class='btn btn-info proper mr2'>kelas $d[shift]</a> ";
  }
  exit;
}


include 'include/akademik_icons.php';


# ==============================================================
# GET KURIKULUM DATA
# ==============================================================
$s = "SELECT 
a.id as id_kurikulum, 
b.id as id_prodi, 
b.singkatan as prodi, 
c.id as id_kalender, 
c.angkatan,
c.jenjang,
d.jumlah_semester  

FROM tb_kurikulum a 
JOIN tb_prodi b ON b.id=a.id_prodi 
JOIN tb_kalender c ON c.id=a.id_kalender  
JOIN tb_jenjang d ON d.jenjang=c.jenjang  
WHERE a.id='$id_kurikulum' 
";
$q = mysqli_query($cn, $s)or die(mysqli_error($cn));
if(!mysqli_num_rows($q)) die('Data kurikulum tidak ditemukan.');
$d = mysqli_fetch_assoc($q);
$jumlah_semester = $d['jumlah_semester'];
$id_kalender = $d['id_kalender'];
$id_prodi = $d['id_prodi'];
$prodi = $d['prodi'];
$jenjang = $d['jenjang'];

$tb_kurikulum = "
<div class='wadah bg-white'>Kurikulum <a href='?manage_sesi'>$d[jenjang]-$d[prodi]-$d[angkatan]</a> ~ Kelas <a href='?manage_sesi&id_kurikulum=$id_kurikulum' id=shift class=proper>$shift</a></div>
";





# ==============================================================
# TAMPIL SEMESTERS
# ==============================================================
$s = "SELECT 
a.id as id_semester,
a.nomor as no_semester,
a.tanggal_awal, 
a.tanggal_akhir  
FROM tb_semester a 
JOIN tb_kalender b ON b.id=a.id_kalender 
JOIN tb_kurikulum c ON c.id_kalender=b.id  

WHERE c.id='$id_kurikulum' 
ORDER BY a.nomor 
";
$q = mysqli_query($cn, $s)or die(mysqli_error($cn));

$jumlah_semester_real = mysqli_num_rows($q);


$semesters = '';
$rnomor_semester = [];
$total_mk = 0;
$i=0;
while ($d=mysqli_fetch_assoc($q)) {
  $i++; 
  array_push($rnomor_semester,$d['no_semester']);

  # ==============================================================
  # LIST MATA KULIAH
  # ==============================================================
  $s2 = "SELECT 
  a.id as id_mk,
  a.kode as kode_mk,
  a.nama as nama_mk,
  b.id as id_kurikulum_mk, 
  (
    SELECT id FROM tb_jadwal WHERE id_kurikulum_mk=b.id AND shift='$shift') as id_jadwal,  
  (
    SELECT awal_kuliah FROM tb_jadwal WHERE id_kurikulum_mk=b.id AND shift='$shift') as awal_kuliah,  
  (
    SELECT count(1) FROM tb_sesi c 
    JOIN tb_jadwal d on c.id_jadwal=d.id  
    WHERE d.id_kurikulum_mk=b.id) as jumlah_sesi   


  FROM tb_mk a 
  JOIN tb_kurikulum_mk b ON a.id=b.id_mk 
  JOIN tb_semester c ON b.id_semester=c.id  
  JOIN tb_kurikulum d ON b.id_kurikulum=d.id  
  WHERE c.id='$d[id_semester]' 
  AND d.id_prodi=$id_prodi
  ";
  $q2 = mysqli_query($cn, $s2)or die(mysqli_error($cn));
  $jumlah_mk = mysqli_num_rows($q2);
  echo "<span class=debug>jumlah_mk__$d[id_semester]: <span id='jumlah_mk__$d[id_semester]'>$jumlah_mk</span></span> ";

  $tr = '';
  $j=0;
  // list MK looping
  while ($d2=mysqli_fetch_assoc($q2)) { 
    $j++;
    $total_mk++;

    $red_bold_sesi = $d2['jumlah_sesi'] ? '' : 'red bold';
    $img_next_of_sesi = $d2['jumlah_sesi'] ? $img_aksi['check'] : $img_aksi['next'] ;
    $link_manage_sesi = "<span><a href='?manage_sesi_detail&id_jadwal=$d2[id_jadwal]' target=_blank>$img_next_of_sesi</a></span>";
    

    $red_bold = ($d2['id_jadwal']=='' || $d2['awal_kuliah']=='') ? 'red bold' : '';
    $sesi_show = ($d2['id_jadwal']=='' || $d2['awal_kuliah']=='') ? '-' : "$d2[jumlah_sesi] $link_manage_sesi";

    $jadwal_icon = $d2['id_jadwal']=='' ? "$img_aksi[prev] | <span class=red>manage</span>" : $img_aksi['check'];
    $jadwal_show = "<a href='?manage_jadwal_dosen&id_kurikulum=$id_kurikulum&shift=$shift' target=_blank onclick='return confirm(\"Kembali ke Penjadwalan Dosen?\")'>$jadwal_icon</a>";

    $awal_kuliah_icon = $d2['awal_kuliah']=='' ? "$img_aksi[prev] | <span class=red>manage</span>" : $img_aksi['check'];
    $awal_kuliah_show = "<a href='?manage_awal_kuliah&id_kurikulum=$id_kurikulum&shift=$shift' target=_blank onclick='return confirm(\"Kembali ke Manage Awal Kuliah?\")'>$awal_kuliah_icon</a>";
    $awal_kuliah_show = $d2['id_jadwal']=='' ? '-' : $awal_kuliah_show;

    $tr.="
    <tr id='tr__$d2[id_mk]'>
      <td>$j</td>
      <td class='$red_bold'>$d2[nama_mk] | $d2[kode_mk]</td>
      <td class='$red_bold'>$jadwal_show</td>
      <td class='$red_bold'>$awal_kuliah_show</td>
      <td class='$red_bold_sesi'>$sesi_show</td>
    </tr>    
    ";
  } //end while list MK



  $tr = $tr=='' ? "<tr><td class='red miring' colspan=9>Belum ada MK pada semester ini.</td></tr>" : $tr;

  $tanggal_awal_sty = strtotime($d['tanggal_awal']) < strtotime('2018-1-1') ? 'merah tebal' : '';
  $tanggal_akhir_sty = strtotime($d['tanggal_akhir']) < strtotime('2018-1-1') ? 'merah tebal' : '';
  $tanggal_awal_show = "<span class='$tanggal_awal_sty'>".date('d M Y', strtotime($d['tanggal_awal'])).'</span>';
  $tanggal_akhir_show = "<span class='$tanggal_awal_sty'>".date('d M Y', strtotime($d['tanggal_akhir'])).'</span>';

  $wadah = strtotime($d['tanggal_akhir']) < strtotime($today) ? 'wadah gradasi-kuning' : 'wadah'; 
  $wadah = (strtotime($d['tanggal_awal']) <= strtotime($today) and strtotime($d['tanggal_akhir']) >= strtotime($today)) ? 'wadah_active' : $wadah; 
  $semester_aktif = $wadah=='wadah_active' ? '(Semester Aktif)' : ''; 
  $semester_lampau = $wadah=='wadah gradasi-kuning' ? '(Semester Lampau)' : ''; 


  $semesters .= "
  <div class='col-lg-12' id='semester__$d[id_semester]'>
    <div class='$wadah'>
      <h4 class='darkblue'>
        Semester $d[no_semester] $semester_aktif $semester_lampau
      </h4>
      <p>$tanggal_awal_show s.d $tanggal_akhir_show</p>
      <table class='table tb-semester-mk'>
        <thead>
          <th>No</th>
          <th>Mata Kuliah</th>
          <th class=proper>Dosen $shift</th>
          <th>Awal Kuliah</th>
          <th>Sesi Kuliah</th>
        </thead>
        
        $tr
        
      </table>
    </div>
  </div>
  ";

  if($i % 2 ==0) $semesters .= '</div><div class=row>';
} // end while semesters





$blok_semesters = $semesters=='' ? '<div class="alert alert-danger">Belum ada data semester</div>' : "<div class='row kurikulum'>$semesters</div>";

# ==============================================================
# TAMBAH SEMESTER
# ==============================================================
$btn_tambah = $jumlah_semester==$jumlah_semester_real ? '' 
: die("
<div class=wadah>
  <p>Jumlah semester pada Kalender ini adalah $jumlah_semester_real of $jumlah_semester. Anda dapat menambahkannya pada Manage Kalender.</p>
  <a href='?manage_kalender&id_kalender=$id_kalender' class='btn btn-primary'>Tambah Semester</a>
</div>");
// echo $btn_tambah;





# ==============================================================
# FINAL OUTPUT SEMESTERS
# ==============================================================
echo "
$tb_kurikulum
$blok_semesters
";





?>
<!-- <div style="position:fixed; top:5px; right: 5px; z-index:9999; display:none; cursor:pointer" id="blok_refresh">
  <div class="alert alert-info" style="border-radius: 10px; border:solid 3px white">
    <span style="display:inline-block; margin-right:15px">Anda melakukan perubahan.</span> <button class="btn btn-info btn-sm" onclick="location.reload()">Refresh</button>
  </div>
</div> -->



















