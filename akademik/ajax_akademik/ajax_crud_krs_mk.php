<?php 
include '../../conn.php';
include 'session_security.php';

# ================================================
# GET VARIABLES
# ================================================
$aksi = isset($_GET['aksi']) ? $_GET['aksi'] : die(erid("aksi"));if($aksi=='')die(erid('aksi(NULL)'));
$id_mk_manual = isset($_GET['id_mk_manual']) ? $_GET['id_mk_manual'] : die(erid("id_mk_manual"));if($id_mk_manual=='')die(erid('id_mk_manual(NULL)'));
$id_krs_manual = isset($_GET['id_krs_manual']) ? $_GET['id_krs_manual'] : die(erid("id_krs_manual"));if($id_krs_manual=='')die(erid('id_krs_manual(NULL)'));
$id_krs_mk_manual = isset($_GET['id_krs_mk_manual']) ? $_GET['id_krs_mk_manual'] : die(erid("id_krs_mk_manual"));if($id_krs_mk_manual=='')die(erid('id_krs_mk_manual(NULL)'));

# ================================================
# DELETE / NEW
# ================================================
if($aksi=='tambah'){
  $s =  "INSERT INTO tb_krs_mk_manual (id_krs_manual,id_mk_manual) VALUES ($id_krs_manual,$id_mk_manual)";
}elseif($aksi=='hapus'){
  $s = "DELETE FROM tb_krs_mk_manual WHERE id=$id_krs_mk_manual";
}else{
  die("aksi $aksi belum terdapat handler.");
}

// die($s);
$q = mysqli_query($cn,$s) or die('Error @ajax. '.mysqli_error($cn));
die('sukses');
?>