<h1>Login As Mahasiswa</h1>
<style>th{text-align:left}</style>
<?php
$id_mhs = isset($_GET['id_mhs']) ? $_GET['id_mhs'] : '';

if($id_mhs==''){
  include 'modul/mhs/list_mhs.php';
  exit;
}


# ==========================================================
# SAAT INI
# ==========================================================
$s = "SELECT 
a.nama as nama_mhs,
a.nim,
a.kelas,
b.angkatan,
c.id as id_prodi,
c.nama as prodi,
c.jenjang,
d.nama as nama_jenjang,
(SELECT id from tb_kalender where angkatan=b.angkatan and jenjang=c.jenjang) as id_kalender

FROM tb_mhs a 
JOIN tb_kelas b on a.kelas=b.kelas 
JOIN tb_prodi c on c.id=b.id_prodi 
JOIN tb_jenjang d on d.jenjang=c.jenjang  
WHERE a.id=$id_mhs 
";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
if(mysqli_num_rows($q)==0) die('Data mahasiswa tidak ditemukan.');
if(mysqli_num_rows($q)>1) die('Data mahasiswa harus unik.');
$d = mysqli_fetch_assoc($q);
$id_kalender = $d['id_kalender'];
$id_prodi = $d['id_prodi'];

if($id_kalender==''){
  die("<div class='alert alert-danger'>Belum ada kalender akademik untuk angkatan $d[angkatan] jenjang $d[jenjang]<hr><a href='?manage_kalender'>Manage Kalender</a></div>");
}
?>
<div class="wadah">
  <ul>
    <li>Nama: <?=$d['nama_mhs']?></li>
    <li>NIM: <?=$d['nim']?></li>
    <li>Kelas: <h3><?=$d['kelas']?></h3></li>
  </ul>
</div>
<div class="wadah">
  <h3>Posisi Mahasiswa:</h3>
  <div class="wadah">
    <ul>
      <li>Sekarang Tanggal: <?=$today?></li>
      <li>Angkatan: <?=$d['angkatan']?></li>
      <li>Prodi: <?=$d['prodi']?></li>
      <li>Jenjang: <?=$d['nama_jenjang']?></li>
      <li>ID-Kalender: <?=$d['id_kalender']?></li>
    </ul>
  </div>
</div>
<?php





# ==========================================================
# CEK KALENDER FOR MHS
# ==========================================================
$s_kalender = "SELECT 
a.id as id_semester, 
a.nomor, 
a.tanggal_awal, 
a.tanggal_akhir 

FROM tb_semester a 
JOIN tb_kalender b on b.id=a.id_kalender 
WHERE a.id_kalender=$id_kalender 
";
// die($s_kalender);

$q = mysqli_query($cn,$s_kalender) or die(mysqli_error($cn));
if(mysqli_num_rows($q)==0) die(div_alert('danger',"Kalender Akademik untuk mahasiswa ini belum ada. | <a href='?manage_kalender&id=$id_kalender' >Manage Kalender</a>"));

# ==========================================================
# GET KURIKULUM
# ==========================================================
$s = "SELECT id as id_kurikulum,nama as nama_kurikulum from tb_kurikulum where id_kalender = $id_kalender and id_prodi = $id_prodi ";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
if(mysqli_num_rows($q)>1) die(div_alert('danger','Jumlah kurikulum harus unik.'));
if(mysqli_num_rows($q)==0) die(div_alert('danger',"Kurikulum untuk kalender dan prodi ini belum ada. | <a href='?manage_kurikulum'>Manage Kurikulum</a>"));
$d = mysqli_fetch_assoc($q);
$id_kurikulum = $d['id_kurikulum'];
$manage_kurikulum = "<a href='?manage_kurikulum&id=$id_kurikulum'>Manage Kurikulum</a>";
$nama_kurikulum = $d['nama_kurikulum'];
echo "<div class=wadah>Kurikulum: <span id=nama_kurikulum>$nama_kurikulum</span> <span class=debug id=id_kurikulum>$id_kurikulum</span> </div>";

# ==========================================================
# CEK SEMESTER FOR MHS DAN KURIKULUM-MK
# ==========================================================
$manage_kalender = "<a href='?manage_kalender&id=$id_kalender' >Manage Kalender</a>";
$s_semester = $s_kalender." AND a.tanggal_awal <= '$today'";
echo "<pre>$s_semester</pre>";
$q = mysqli_query($cn,$s_semester) or die(mysqli_error($cn));
if(mysqli_num_rows($q)==0) die("<div class='alert alert-danger'>Tidak ada Semester yang cocok pada Kalender Akademik. | $manage_kalender</div>");

$thead = '<thead>
  <th>Semester</th>
  <th>Mata Kuliah, Sesi, dan Presensi</th>
</thead>';
$tr = '';
$no_mk=0;
while ($d=mysqli_fetch_assoc($q)) {
  $id_semester = $d['id_semester'];
  $s2 = "SELECT 
  a.id as id_kurikulum_mk,
  a.id_mk,
  b.nama as nama_mk,
  (SELECT id from tb_jadwal) as id_jadwal  
  from tb_kurikulum_mk a 
  join tb_mk b on b.id=a.id_mk 
  where id_semester=$id_semester and id_kurikulum=$id_kurikulum  ";
  $q2 = mysqli_query($cn,$s2) or die(mysqli_error($cn));
  $jumlah_mk = mysqli_num_rows($q2);
  $thead_mk='<thead>
    <th>No</th>
    <th>Mata Kuliah</th>
    <th>Sesi Kuliah</th>
    <th>Presensi</th>
  </thead>';
  $tr_mk='';
  while ($d2=mysqli_fetch_assoc($q2)) {
    $no_mk++;
    $id_kurikulum_mk = $d2['id_kurikulum_mk'];

    # ==========================================================
    # CEK JADWAL FOR THIS KURIKULUM-MK
    # ==========================================================

    # ==========================================================
    # CEK SEMESTER FOR MHS DAN KURIKULUM-MK
    # ==========================================================
    $s3 = "SELECT * from tb_sesi_kuliah where id_kurikulum_mk=$id_kurikulum_mk";
    $q3 = mysqli_query($cn,$s3) or die(mysqli_error($cn));
    while ($d3=mysqli_fetch_assoc($q3)) {
      # code...
    }



    $tr_mk .= "
    <tr>
      <td>$no_mk</td>
      <td>$d2[nama_mk]</td>
      <td>Sesi</td>
      <td>Presensi</td>
    </tr>";
  }
  $tb_mk = $tr_mk==''?div_alert('danger',"MK pada semester ini belum ada. | $manage_kurikulum"):"<table class=table>$tr_mk</table>";
  
  $tr .= "
  <tr>
    <td><h3 class=' biru'>Semester $d[nomor]</h3>$d[tanggal_awal] s.d $d[tanggal_akhir]</td>
    <td>$tb_mk</td>
  </tr>";
}

$tb = $tr=='' ? div_alert('danger',"Belum ada semester yang dilalui. | $manage_kalender") : "<h3>Semester yang dilalui:</h3><table class=table>$thead$tr</tr>";
echo $tb;



# ==========================================================
# GET MK IN LAST SEMESTER
# ==========================================================


exit;
$tr='';
foreach($d as $kolom=>$isi){
  $debug = substr($kolom,0,3)=='id_' ? 'debug' : '';
  $kolom_caption = str_replace('_',' ',$kolom);
  $isi = $isi=='' ? '<span class="abu miring">-- null --</span>' : $isi;
  $tr.="<tr class=$debug><td class=upper>$kolom_caption</td><td id='$kolom'>$isi</td></td>";
}


echo "
<div class='wadah'>
  <h2>Data Akademik Mahasiswa</h2>
  <table class=table>
    $tr
  </table>
  <div class=text-right><a href='?master&p=mhs&aksi=update&id=$id_mhs'>Edit Data Mhs</a></div>
</div>";






# ==========================================================
# DATA AKADEMIK MHS
# ==========================================================
$s = "SELECT 
a.nama,
a.kelas,
a.nim,
a.no_wa,
a.status_mhs,   
a.id as id_mhs,
a.id_pmb 
from tb_mhs a WHERE a.id=$id_mhs";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
if(mysqli_num_rows($q)==0) die('Data mahasiswa tidak ditemukan.');
$d = mysqli_fetch_assoc($q);


$tr='';
foreach($d as $kolom=>$isi){
  if($kolom=='is_publish') {$isi = $isi==0 ? 'belum' : 'sudah'; $isi="<span class='abu miring'>-- $isi --</span>"; }
  $debug = substr($kolom,0,3)=='id_' ? 'debug' : '';
  $kolom_caption = str_replace('_',' ',$kolom);
  $isi = $isi=='' ? '<span class="abu miring">-- null --</span>' : $isi;
  $tr.="<tr class=$debug><td class=upper>$kolom_caption</td><td id='$kolom'>$isi</td></td>";
}


echo "
<div class='wadah'>
  <h2>Data Akademik Mahasiswa</h2>
  <table class=table>
    $tr
  </table>
  <div class=text-right><a href='?master&p=mhs&aksi=update&id=$id_mhs'>Edit Data Mhs</a></div>
</div>";


# ==========================================================
# KRS
# ==========================================================
echo "<h2>KRS</h2>";


?>

<div class="wadah">
  <h3>Persyaratan KRS</h3>
  <div class="wadah">
    <ul>
      <li>Pembayaran Semester 3: ... Sudah</li>
    </ul>
  </div>
</div>