<?php 
include 'session_security.php';
include '../../conn.php';
include 'akademik_only.php';

# ================================================
# GET VARIABLES
# ================================================
$id_jadwal = $_GET['id_jadwal'] ?? die(erid("id_jadwal"));
$awal_kuliah = $_GET['awal_kuliah'] ?? die(erid("awal_kuliah"));
$bobot = $_GET['bobot'] ?? die(erid("bobot"));

$akhir_kuliah = date('Y-m-d H:i',strtotime($awal_kuliah)+$bobot*45*60);

$s = "UPDATE tb_jadwal SET awal_kuliah='$awal_kuliah' WHERE id='$id_jadwal'";

// get id_semester
$s = "SELECT a.id,a.nomor as no FROM tb_semester a 
JOIN tb_kurikulum_mk b ON a.id=b.id_semester
JOIN tb_jadwal c ON b.id=c.id_kurikulum_mk 
WHERE c.id=$id_jadwal
";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
if(mysqli_num_rows($q)!=1) die('AJAX. Data semester tidak ditemukan.');
$d=mysqli_fetch_assoc($q);
$id_semester = $d['id'];
$no_semester = $d['no'];

// get list jadwal pada id_semester
$s = "SELECT a.*,
(d.bobot_teori + d.bobot_praktik) bobot  
FROM tb_jadwal a 
JOIN tb_kurikulum_mk b ON a.id_kurikulum_mk=b.id 
JOIN tb_semester c ON b.id_semester=c.id 
JOIN tb_mk d ON b.id_mk=d.id 
WHERE c.id=$id_semester 
AND a.awal_kuliah is not null
";
// die($s);
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
$tr = '';
$taw = strtotime($awal_kuliah);
$tak = strtotime($akhir_kuliah);

while ($d=mysqli_fetch_assoc($q)) {
  $tawal = strtotime($d['awal_kuliah']);
  $takhir = $tawal+($d['bobot']*45*60);
  $ak = date('Y-m-d H:i',$takhir);
  if($taw >= $tawal AND $taw <$takhir){
    $tr.="<div>jam awal bentrok: $d[awal_kuliah] - $ak</div>";
  }
}

die($tr);


die("
<div class='wadah gradasi-merah mt1'>
  $tr
  <div>Awal: $awal_kuliah ($bobot SKS)</div>
  <div>Akhir: $akhir_kuliah</div>
</div>");
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
die('sukses');