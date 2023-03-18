<?php
$judul = '<h1>Manage Sesi Kuliah</h1>';
include 'form_buat_sesi_default_process.php';

$id_jadwal = isset($_GET['id_jadwal']) ? $_GET['id_jadwal'] : '';

if($id_jadwal==''){
  // include 'modul/jadwal_kuliah/list_jadwal.php';
  include 'modul/sesi_kuliah/manage_multiple_sesi.php';
  exit;
}
echo "<span class=debug id=id_jadwal>$id_jadwal</span>";
$s = "SELECT 
concat('JADWAL',c.nama,' / ', h.jenjang,'-', g.nama, ' ', h.angkatan) as jadwal,
b.id as id_kurikulum_mk,
b.id_semester,
b.id_kurikulum,
d.id as id_dosen,
d.nama as dosen_koordinator,  
a.sesi_uts,  
a.sesi_uas,  
a.jumlah_sesi,
a.tanggal_jadwal,   
e.nomor as nomor_semester,   
e.awal_kuliah_uts as awal_perkuliahan,   
e.id_kalender    

FROM tb_jadwal a 
JOIN tb_kurikulum_mk b on b.id=a.id_kurikulum_mk 
JOIN tb_mk c on c.id=b.id_mk 
JOIN tb_dosen d on d.id=a.id_dosen 
JOIN tb_semester e on b.id_semester=e.id 
JOIN tb_kurikulum f on f.id=b.id_kurikulum 
JOIN tb_prodi g on g.id=f.id_prodi 
JOIN tb_kalender h on h.id=f.id_kalender 

WHERE a.id=$id_jadwal";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
if(mysqli_num_rows($q)==0) die('Data Jadwal tidak ditemukan.');
$d = mysqli_fetch_assoc($q);
$id_kurikulum = $d['id_kurikulum'];
$id_kurikulum_mk = $d['id_kurikulum_mk'];
$id_dosen = $d['id_dosen'];
$id_semester = $d['id_semester'];
$id_kalender = $d['id_kalender'];
$nomor_semester = $d['nomor_semester'];
$awal_perkuliahan = $d['awal_perkuliahan'];
$jumlah_sesi = $d['jumlah_sesi'];
$sesi_uts = $d['sesi_uts'];
$sesi_uas = $d['sesi_uas'];

$back_to = "Back to: 
<a href='?manage_kalender&id_kalender=$id_kalender' class=proper>manage kalender</a> | 
<a href='?manage_kurikulum&id_kurikulum=$id_kurikulum' class=proper>manage kurikulum</a> | 
<a href='?manage_jadwal&id_kurikulum_mk=$id_kurikulum_mk' class=proper>manage jadwal</a> | 
<a href='?manage_kelas&id_jadwal=$id_jadwal' class=proper>manage kelas peserta</a> 
";

$koloms = [];
$i=0;
$tr = '';
foreach ($d as $key => $value) {
  if($key=='nomor_semester' || $key=='awal_perkuliahan') continue;
  $koloms[$i] = str_replace('_',' ',$key);
  $debug = substr($key,0,2)=='id' ? 'debug' : 'upper';
  $tr .= "<tr class=$debug><td>$koloms[$i]</td><td id=$key>$value</td></tr>";
  $i++;
}

echo "<div class=mb2>$back_to</div>$judul<table class=table>$tr</table>";



# ====================================================
# LIST SESI KULIAH
# ====================================================
$s = "SELECT 
a.id,
a.pertemuan_ke,
a.nama as nama_sesi,
a.id_dosen, 
a.tanggal_sesi,
b.nama as nama_dosen,
(SELECT r.nama from tb_ruang r where r.id=a.id_ruang) as nama_ruang  

from tb_sesi_kuliah a 
join tb_dosen b on b.id=a.id_dosen 
where a.id_jadwal=$id_jadwal order by a.pertemuan_ke";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
if(mysqli_num_rows($q)==0){
  include 'form_buat_sesi_default.php';
}else{

  $thead = "
  <thead>
    <th class='text-left upper'>Pertemuan ke</th>
    <th class='text-left upper'>Nama Sesi</th>
    <th class='text-left upper'>Pengajar</th>
    <th class='text-left upper'>Tanggal Sesi</th>
    <th class='text-left upper'>Ruang</th>
    <th class='text-left upper'>Aksi</th>
  </thead>"; 
  $tr = '';
  while ($d=mysqli_fetch_assoc($q)) {
    $tr .= "
    <tr>
      <td class='upper'>$d[pertemuan_ke]</td>
      <td class='upper'>$d[nama_sesi]</td>
      <td class='upper'>$d[nama_dosen]</td>
      <td class='upper'>$d[tanggal_sesi]</td>
      <td class='upper'>$d[nama_ruang]</td>
      <td>
        <a href='?master&p=sesi_kuliah&aksi=update&id=$d[id]' class='btn btn-info btn-sm' target='_blank'>edit</a>
        <a href='?master&p=sesi_kuliah&aksi=hapus&id=$d[id]' class='btn btn-danger btn-sm' target='_blank'>hapus</a>
      </td>
    </tr>"; 
  }

  $batch = "<div class=wadah>
  <p>Untuk setting tanggal sesi dari P1 s.d P$jumlah_sesi secara terurut per minggu silahkan lakukan Batch Tanggal Sesi</p>
  <a href='?batch_tanggal_sesi&id_jadwal=$id_jadwal' class='btn btn-info'>Batch Tanggal Sesi</a>
  </div>";

  echo "$batch<table class='table table-striped table-hover'>$thead$tr</table>$back_to";
}
