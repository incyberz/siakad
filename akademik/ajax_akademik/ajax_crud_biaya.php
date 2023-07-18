<?php 
include 'session_security.php';
include '../../conn.php';

# ================================================
# GET VARIABLES
# ================================================
$aksi = isset($_GET['aksi']) ? $_GET['aksi'] : die(erid("aksi"));if($aksi=='')die(erid('aksi(NULL)'));
$id_biaya = isset($_GET['id_biaya']) ? $_GET['id_biaya'] : die(erid("id_biaya"));if($id_biaya=='')die(erid('id_biaya(NULL)'));

# ================================================
# DELETE / NEW
# ================================================
if($aksi=='tambah'){
  $s =  "INSERT INTO tb_biaya (nama,nominal_default,`no`) VALUES ('NEW BIAYA',999,0)";
}elseif($aksi=='hapus'){
  $s = "DELETE FROM tb_biaya WHERE id=$id_biaya";
}else{
  die("aksi $aksi belum terdapat handler.");
}

// die($s);
$q = mysqli_query($cn,$s) or die('Error @ajax. '.mysqli_error($cn));
die('sukses');
?>