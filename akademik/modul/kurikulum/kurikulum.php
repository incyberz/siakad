<h1>MANAGE KURIKULUM</h1>
<style>
  .ids-kurikulum h2{margin-top:0; color: darkblue; }
  .kurikulum {}
  .semester-ke {font-size:24px !important; color:darkblue !important; margin-bottom:10px}
  #btn_tambah {margin-bottom:10px}
</style>
<?php
$id = isset($_GET['id']) ? $_GET['id'] : '';
if($id<1) die('<script>location.replace("?master&p=kurikulum")</script>');

# ==============================================================
# DESCRIBING COLUMNS
# ==============================================================
// $s = "DESCRIBE tb_kurikulum";
// $q = mysqli_query($cn, $s)or die(mysqli_error($cn));
// $Field = [];
// $Type = [];
// $Null = [];
// $Key = [];
// $i=0;
// while ($d=mysqli_fetch_assoc($q)) {
//   if($d['Extra']=='auto_increment') continue;
//   if($d['Field']=='folder_uploads') continue;
//   $Field[$i] = $d['Field'];
//   $Type[$i] = $d['Type'];
//   $Null[$i] = $d['Null'];
//   $Key[$i] = $d['Key'];
//   $i++;
// }



# ==============================================================
# GET KURIKULUM DATA
# ==============================================================
$s = "SELECT 
b.nama as nama_prodi, 
a.nama as nama_kurikulum, 
c.angkatan,
d.nama as jenjang,
a.basis, 
a.is_publish, 
a.tanggal_penetapan, 
a.ditetapkan_oleh

FROM tb_kurikulum a 
JOIN tb_prodi b ON b.id=a.id_prodi 
JOIN tb_kalender c ON c.id=a.id_kalender  
JOIN tb_jenjang d ON d.jenjang=c.jenjang  
WHERE a.id='$id'";
$q = mysqli_query($cn, $s)or die(mysqli_error($cn));
if(!mysqli_num_rows($q)) die('Data kurikulum tidak ditemukan.');
$d = mysqli_fetch_assoc($q);

$tr='';
foreach($d as $kolom=>$isi){
  if($kolom=='is_publish') {$isi = $isi==0 ? 'belum' : 'sudah'; $isi="<span class='abu miring'>-- $isi --</span>"; }
  $kolom = str_replace('_',' ',$kolom);
  $isi = $isi=='' ? '<span class="abu miring">-- null --</span>' : $isi;
  $tr.="<tr><td class=upper>$kolom</td><td>$isi</td></td>";
}


echo "
<div class='wadah ids-kurikulum'>
<h2>Identitas Kurikulum</h2>
<table class=table>
  $tr
</table>
<div class=text-right><a href='?master&p=kurikulum&aksi=update&id=$id'>Update Identitas Kurikulum</a></div>
</div>";


# ==============================================================
# TAMBAH SEMESTER
# ==============================================================
$btn_tambah = "<button class='btn btn-primary btn-aksi' id=btn_tambah>Tambah Semester</button>";
echo $btn_tambah;


# ==============================================================
# TAMPIL SEMESTERS
# ==============================================================
$s = "SELECT 
a.id as id_semester,
a.nomor as no_semester,
a.tanggal_awal, 
a.tanggal_akhir  
FROM tb_semester a 
JOIN tb_kurikulum b ON b.id=a.id_kurikulum 

WHERE b.id='$id'";
$q = mysqli_query($cn, $s)or die(mysqli_error($cn));

$jumlah_semester = mysqli_num_rows($q);
$semesters = '';
$i=0;
while ($d=mysqli_fetch_assoc($q)) {
  $i++; 

  $tr = '';

  $s2 = "SELECT *   
  FROM tb_mk a 
  JOIN tb_kurikulum_mk b ON a.id=b.id_mk 
  WHERE b.id='$d[id_semester]'";
  $q2 = mysqli_query($cn, $s2)or die(mysqli_error($cn));  

  $semesters .= "
  <div class='col-lg-6'>
  <div class=wadah>
  <div class='semester-ke'>Semester $d[no_semester]</div>
  <table class=table>
    <thead>
      <th>No</th>
      <th>Kode</th>
      <th>Mata Kuliah</th>
      <th>SKS</th>
      <th>Prasyarat</th>
    </thead>
    
    <tr>
      <td>1</td>
      <td>MK-001</td>
      <td>Pemrograman Web</td>
      <td>2</td>
      <td>--null--</td>
    </tr>

  </table>
  </div>
  </div>
  ";
}


$kurikulum = $semesters=='' ? 'Belum ada semester' : "<div class='row kurikulum'>$semesters</div>";
echo $kurikulum;