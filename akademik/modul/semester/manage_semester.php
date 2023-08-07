<h1>Manage Penanggalan Semester</h1>
<?php
$id_semester = isset($_GET['id_semester']) ? $_GET['id_semester'] : '';

include 'manage_semester_custom_tanggal_process.php';

if($id_semester==''){
  die('<script>location.replace("?manage_kalender")</script>');
}

# ==========================================================
# IDENTITAS SEMESTER
# ==========================================================
$s = "SELECT 
a.nomor as semester_ke, 
a.tanggal_awal as batas_awal, 
a.tanggal_akhir as batas_akhir, 
a.id_kalender, 
a.last_update,
b.*   
FROM tb_semester a 
JOIN tb_kalender b on a.id_kalender=b.id 
WHERE a.id=$id_semester";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
if(mysqli_num_rows($q)==0) die('<span class=red>Data SEMESTER tidak ditemukan.');
$d = mysqli_fetch_assoc($q);

$semester_ke = $d['semester_ke'];
$last_update = $d['last_update'];
$batas_awal = date('d-M-Y',strtotime($d['batas_awal']));
$batas_akhir = date('d-M-Y',strtotime($d['batas_akhir']));
if($last_update==''){
  include 'manage_semester_settings_apply.php';
  include 'manage_semester_settings.php';
  exit;
}

$last_update_show = date('d-M-Y',strtotime($d['last_update']));

echo "<p><a href='?manage_kalender&id_kalender=$d[id_kalender]'>Back</a> | Awal Seting Tanggal dapat dilakukan secara otomatis dan untuk selanjutnya dapat Anda edit tanggal-tanggal akademik per semester secara manual.</p>";

# ==========================================================
# FINAL OUTPUT
# ==========================================================
echo "
<div class=wadah>
  <div class='wadah bg-white'>Semester $d[semester_ke] | $d[jenjang]-$d[angkatan] | $batas_awal - $batas_akhir | Last update: $last_update_show</white>
</div>
";

include 'manage_semester_custom_tanggal.php';
include 'preview_kalender.php';
