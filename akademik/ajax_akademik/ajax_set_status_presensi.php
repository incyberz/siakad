<?php 
include '../../conn.php';
include 'session_security.php';

# ================================================
# GET VARIABLES
# ================================================
$id_sesi_kuliah = isset($_GET['id_sesi_kuliah']) ? $_GET['id_sesi_kuliah'] : die(erid("id_sesi_kuliah"));
$id_mhs = isset($_GET['id_mhs']) ? $_GET['id_mhs'] : die(erid("id_mhs"));
$status_presensi = isset($_GET['status_presensi']) ? $_GET['status_presensi'] : die(erid("status_presensi"));
$status_presensi = strtolower($status_presensi);

if ($id_sesi_kuliah=='' || $status_presensi=='') die("Error @ajax. Salah satu index masih kosong.");

# ================================================
# INSERT IF NOT EXIST
# ================================================
$s = "SELECT 1 FROM tb_presensi WHERE id_mhs=$id_mhs and id_sesi_kuliah=$id_sesi_kuliah ";
$q = mysqli_query($cn,$s) or die("Error @ajax. Tidak bisa cek exist presensi. ".mysqli_error($cn));
if(mysqli_num_rows($q)>1) die('Tidak boleh double data presensi');
if(mysqli_num_rows($q)==0){
  $s = "INSERT INTO tb_presensi (id_sesi_kuliah,id_mhs) VALUES ($id_sesi_kuliah,$id_mhs)";
  $q = mysqli_query($cn,$s) or die("Error @ajax. Tidak bisa insert new presensi. ".mysqli_error($cn));
}

# ================================================
# SET STATUS SESI KULIAH
# ================================================
$status = $status_presensi=='null'? 'NULL' : "'$status_presensi'";
$timestamp_masuk = $status_presensi=='null'? 'NULL' : 'CURRENT_TIMESTAMP';

$s = "UPDATE tb_presensi set status=$status, timestamp_masuk=$timestamp_masuk WHERE id_sesi_kuliah=$id_sesi_kuliah AND  id_mhs=$id_mhs";
// die($s);
$q = mysqli_query($cn,$s) or die("Error @ajax. Tidak bisa update presensi. ".mysqli_error($cn));
// $tmp = $s;

// die($tmp);
die('sukses');
?>