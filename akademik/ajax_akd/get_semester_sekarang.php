<?php 
$msg = "Error @AJAX. Missing index field";
if (!isset($_GET['id_angkatan']))die("$msg #1");
if (!isset($_GET['jenjang']))die("$msg #2");

$id_angkatan = $_GET['id_angkatan'];
$jenjang = $_GET['jenjang'];

include "../../config.php";

$tanggal_sekarang = date("Y-m-d");

$s = "
SELECT a.* FROM tb_semester a 
JOIN tb_kalender_akd b ON a.id_kalender_akd=b.id_kalender_akd 
JOIN tb_angkatan c ON b.id_angkatan=c.id_angkatan 
WHERE c.id_angkatan = $id_angkatan 
AND b.jenjang='$jenjang' 
AND a.tanggal_akhir_semester>='$tanggal_sekarang' 
ORDER BY a.tanggal_akhir_semester 
";
$q = mysqli_query($cn,$s) or die("Error @AJAX. Tidak dapat mengakses data Join Semester #1. ".mysqli_error($cn));
if(mysqli_num_rows($q)==0) die("Tidak ada semester yang berlaku untuk Tahun Angkatan $id_angkatan");

$options_semester='';

$tanggal_berlaku = '';

while ($d=mysqli_fetch_assoc($q)) {
	$id_semester = $d['id_semester'];
	$no_semester = $d['no_semester'];

	$tanggal_awal_semester = $d['tanggal_awal_semester'];
	$tanggal_akhir_semester = $d['tanggal_akhir_semester'];

	$tanggal_awal_semester_html = date("d M Y",strtotime($tanggal_awal_semester));
	$tanggal_akhir_semester_html = date("d M Y",strtotime($tanggal_akhir_semester));

	$options_semester.="<option value='$id_semester'>$no_semester</option>";
	$tanggal_berlaku.="<option value='$id_semester'>$tanggal_awal_semester_html s.d $tanggal_akhir_semester_html</option>";

}


echo "1__$options_semester"."__$tanggal_berlaku";
?>