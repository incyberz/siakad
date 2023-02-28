<?php 
$msg = "Error @AJAX. Missing index field";
if (!isset($_GET['id_semester']))die("$msg #1");
if (!isset($_GET['id_prodi']))die("$msg #2");
if (!isset($_GET['id_mk']))die("$msg #2");
if (!isset($_GET['id_dosen']))die("$msg #2");
if (!isset($_GET['id_kelas']))die("$msg #2");

$id_semester = $_GET['id_semester'];
$id_prodi = $_GET['id_prodi'];
$id_mk = $_GET['id_mk'];
$id_dosen = $_GET['id_dosen'];
$id_kelas = $_GET['id_kelas'];

$kelas_kedua = 0;
switch ($id_kelas) {
	case 'p': $kelas_pertama=1; break;
	case 's': $kelas_pertama=2; break;
	case 'ps': $kelas_pertama=1; $kelas_kedua=2; break;
}

$id_jadwal_kuliah = "$id_semester-$id_mk-$kelas_pertama";

include "../../config.php";

$tanggal_sekarang = date("Y-m-d");

$s = "
INSERT into tb_jadwal_kuliah (

id_jadwal_kuliah,
id_semester,
id_prodi,
id_mk,
id_dosen,
id_kelas

) values (

'$id_jadwal_kuliah',
'$id_semester',
'$id_prodi',
'$id_mk',
'$id_dosen',
'$kelas_pertama'
)
";

$q = mysqli_query($cn,$s) or die("Error @AJAX. Tidak dapat menyimpan Jadwal Baru #1. ".mysqli_error($cn));

if($kelas_kedua){

	$id_jadwal_kuliah = "$id_semester-$id_mk-$kelas_kedua";

	$s = "
	INSERT into tb_jadwal_kuliah (

	id_jadwal_kuliah,
	id_semester,
	id_prodi,
	id_mk,
	id_dosen,
	id_kelas

	) values (

	'$id_jadwal_kuliah',
	'$id_semester',
	'$id_prodi',
	'$id_mk',
	'$id_dosen',
	'$kelas_kedua'
	)
	";

	$q = mysqli_query($cn,$s) or die("Error @AJAX. Tidak dapat menyimpan Jadwal Baru #2. ".mysqli_error($cn));

}
echo "1__";
?>