<?php 
include '../../conn.php';
include 'session_security.php';

# ================================================
# GET VARIABLES
# ================================================
$nominal = isset($_GET['nominal']) ? $_GET['nominal'] : die(erid("nominal"));
$angkatan = isset($_GET['angkatan']) ? $_GET['angkatan'] : die(erid("angkatan"));
$id_prodi = isset($_GET['id_prodi']) ? $_GET['id_prodi'] : die(erid("id_prodi"));
$kolom = isset($_GET['kolom']) ? $_GET['kolom'] : die(erid("kolom"));
$id_biaya = isset($_GET['id_biaya']) ? $_GET['id_biaya'] : die(erid("id_biaya"));

if ($nominal==0 || $nominal=='' || $angkatan=='' || $id_prodi=='' || $kolom=='' || $id_biaya=='') die("Error @ajax. Salah satu index masih kosong.");

# ================================================
# GET ID BIAYA ANGKATAN (IF EXISTS)
# ================================================
$s = "SELECT id FROM tb_biaya_angkatan WHERE angkatan=$angkatan and id_prodi=$id_prodi and id_biaya=$id_biaya ";
$q = mysqli_query($cn,$s) or die('Error @ajax. '.mysqli_error($cn));
if(mysqli_num_rows($q)==1){
  $d = mysqli_fetch_assoc($q);
  $id_biaya_angkatan = $d['id'];
  $s = "UPDATE tb_biaya_angkatan SET $kolom = '$nominal' WHERE id=$id_biaya_angkatan";
}else{
  $s = "INSERT INTO tb_biaya_angkatan 
  (id_biaya, angkatan, id_prodi, $kolom) VALUES 
  ('$id_biaya', '$angkatan', '$id_prodi', '$nominal')";
}

$q = mysqli_query($cn,$s) or die('Error @ajax. '.mysqli_error($cn));
die('sukses');
?>