<?php 
include 'session_security.php';
include '../../conn.php';
include 'keuangan_only.php';

# ================================================
# GET VARIABLES
# ================================================
$aksi = isset($_GET['aksi']) ? $_GET['aksi'] : die(erid("aksi"));
$nim = isset($_GET['nim']) ? $_GET['nim'] : die(erid("nim"));
// $kolom = isset($_GET['kolom']) ? $_GET['kolom'] : die(erid("kolom"));

if ($nim=='') die("Error @ajax. Index nim masih kosong.");
if ($aksi=='') die("Error @ajax. Index aksi masih kosong.");

if($aksi=='set_aktif' || $aksi=='set_non'){
  $status_mhs = $aksi=='set_aktif' ? 1 : 0;
  $s = "UPDATE tb_mhs SET status_mhs = '$status_mhs' WHERE nim='$nim'";
}elseif($aksi=='set_lunas' || $aksi=='set_belum_bayar' ){
  $is_lunas = $aksi=='set_lunas' ? 1 : 0;
  $s = "UPDATE tb_mhs SET status_bayar_manual='$is_lunas' WHERE nim='$nim'";
}else{
  die("aksi '$aksi' belum didefinisikan pada ajax.");
}

$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
die('sukses');