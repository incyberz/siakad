<?php 
include 'session_security.php';
include '../../conn.php';
include 'keuangan_only.php';

# ================================================
# GET VARIABLES
# ================================================
$id_biaya = $_GET['id_biaya'] ?? die(erid('id_biaya'));
$angkatan = $_GET['angkatan'] ?? die(erid('angkatan'));
$id_prodi = $_GET['id_prodi'] ?? die(erid('id_prodi'));
$persen_biaya = $_GET['persen_biaya'] ?? die(erid('persen_biaya'));
$event = $_GET['event'] ?? die(erid('event'));

$s = "SELECT id FROM tb_syarat_biaya 
WHERE id_biaya = '$id_biaya' 
AND id_prodi = '$id_prodi' 
AND angkatan = '$angkatan' 
AND event = '$event' 
";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
if(mysqli_num_rows($q)){
  $s = "UPDATE tb_syarat_biaya SET persen_biaya = '$persen_biaya',tanggal=CURRENT_TIMESTAMP WHERE id = $d[id]";
}else{
  $s = "INSERT INTO tb_syarat_biaya 
  (id_biaya,id_prodi,angkatan,persen_biaya,event) VALUES 
  ('$id_biaya','$id_prodi','$angkatan','$persen_biaya','$event')";
}

// die($s);
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
die('sukses');