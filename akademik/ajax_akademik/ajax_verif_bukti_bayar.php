<?php 
include 'session_security.php';
include '../../conn.php';
include 'keuangan_only.php';

# ================================================
# GET VARIABLES
# ================================================
$aksi = $_GET['aksi'] ?? die(erid("aksi"));
$verif_status = $_GET['verif_status'] ?? die(erid('verif_status'));
$alasan_reject = $_GET['alasan_reject'] ?? die(erid('alasan_reject'));
$id_bayar = $_GET['id_bayar'] ?? die(erid('id_bayar'));

$alasan_reject = str_replace('\'','`',$alasan_reject);
$alasan_reject = str_replace('"','`',$alasan_reject);
$alasan_reject = $alasan_reject=='' ? 'NULL' : " '$alasan_reject' ";

if($aksi=='verif' || $aksi=='reject'){
  $s = "UPDATE tb_bayar SET 
  verif_date = CURRENT_TIMESTAMP, 
  verif_by = '$id_user $nama_user', 
  verif_status = '$verif_status', 
  alasan_reject = $alasan_reject 
  WHERE id='$id_bayar'";
}else{
  die("aksi '$aksi' belum didefinisikan pada ajax.");
}

// die($s);
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
die('sukses');