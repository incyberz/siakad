<?php 
include '../../conn.php';
include 'session_security.php';

# ================================================
# GET VARIABLES
# ================================================
$id_soal = isset($_GET['id_soal']) ? $_GET['id_soal'] : die(erid("id_soal"));
$no_soal = isset($_GET['no_soal']) ? $_GET['no_soal'] : die(erid("no_soal"));
$id_jadwal = isset($_GET['id_jadwal']) ? $_GET['id_jadwal'] : die(erid("id_jadwal"));
$id_tipe_sesi = isset($_GET['id_tipe_sesi']) ? $_GET['id_tipe_sesi'] : die(erid("id_tipe_sesi"));


# ================================================
# MAIN HANDLE
# ================================================
$s = "DELETE FROM tb_soal WHERE id=$id_soal";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));

# ================================================
# HAPUS MEDIA SOAL
# ================================================
$folder_media_soal = '../../uploads/media_soal';
$file = "$folder_media_soal/$id_jadwal/$id_tipe_sesi/$no_soal.jpg";
if(file_exists($file)) unlink($file);
die('sukses');
?>