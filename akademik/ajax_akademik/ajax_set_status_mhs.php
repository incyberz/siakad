<?php 
include '../../conn.php';
include 'session_security.php';

# ================================================
# GET VARIABLES
# ================================================
$aksi = isset($_GET['aksi']) ? $_GET['aksi'] : die(erid("aksi"));
$nim = isset($_GET['nim']) ? $_GET['nim'] : die(erid("nim"));
$kolom = isset($_GET['kolom']) ? $_GET['kolom'] : die(erid("kolom"));

if ($nim=='') die("Error @ajax. Index nim masih kosong.");
if ($aksi=='') die("Error @ajax. Index aksi masih kosong.");

if($aksi=='set_aktif' || $aksi=='set_non'){
  $status_mhs = $aksi=='set_aktif' ? 1 : 0;
  $s = "UPDATE tb_mhs SET $kolom='$status_mhs' WHERE nim='$nim'";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  die('sukses');
}else{
  die("aksi '$aksi' belum didefinisikan pada ajax.");
}
