<?php 
$msg = "Error @AJAX. Missing index field";
if (!isset($_GET['id_kurikulum_add']))die("$msg #1");
if (!isset($_GET['id_konsentrasi_add']))die("$msg #2");
if (!isset($_GET['no_semester_add']))die("$msg #3");

$id_konsentrasi_add = $_GET['id_konsentrasi_add'];
$id_kurikulum_add = $_GET['id_kurikulum_add'];
$no_semester_add = $_GET['no_semester_add'];

include "../../config.php";

$sql_semester = " a.no_semester=$no_semester_add ";
if(strtolower($no_semester_add)=="all")$sql_semester=" 1 ";

$sql_konsentrasi = " a.id_konsentrasi=$id_konsentrasi_add ";
if(strtolower($id_konsentrasi_add)=="none")$sql_konsentrasi=" a.id_konsentrasi is null ";
if(strtolower($id_konsentrasi_add)=="all")$sql_konsentrasi=" 1 ";


$s = "SELECT 

a.id_mk,
a.nama_mk,
a.id_konsentrasi,
b.id_prodi,
b.jenjang 

from tb_mk a 
JOIN tb_prodi b ON a.id_prodi=b.id_prodi 
WHERE a.id_kurikulum=$id_kurikulum_add 
AND $sql_semester 
AND $sql_konsentrasi 
ORDER BY a.nama_mk 
";

$q = mysqli_query($cn,$s) or die("Error @AJAX. Tidak dapat mengakses data MK. ".mysqli_error($cn));
$jumlah_records = mysqli_num_rows($q);
if($jumlah_records==0) die("Mata Kuliah belum ada. Silahkan filter ulang atau Anda dapat membuatnya dahulu!");

$singkatan_konsentrasi = '';

$hasil="<option value='0' selected>--Pilih--</option>";
while ($d=mysqli_fetch_assoc($q)) {
	$id_mk = $d['id_mk'];
	$id_konsentrasi = $d['id_konsentrasi'];
	$nama_mk = $d['nama_mk'];
	$id_prodi = $d['id_prodi'];
	$jenjang = $d['jenjang'];

	if($id_konsentrasi!=""){
		$ss = "SELECT singkatan_konsentrasi from tb_konsentrasi WHERE id_konsentrasi=$id_konsentrasi";
		$qq = mysqli_query($cn,$ss) or die("Error @AJAX. Tidak dapat mengakses data Konsentrasi Prodi. ".mysqli_error($cn));
		$dd = mysqli_fetch_assoc($qq);
		$singkatan_konsentrasi = $dd['singkatan_konsentrasi']." ~ ";
	}

	$hasil.= "<option value='$id_mk'>$singkatan_konsentrasi$nama_mk</option>";

}


echo "1__$jumlah_records"."__$hasil"."__$id_prodi"."__$jenjang";
?>