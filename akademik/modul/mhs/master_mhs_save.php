<?php
include '../../ajax_akademik/session_security.php';
include '../../../conn.php';
include '../../../user_vars.php';
if($admin_level==3 || $admin_level==6 || $admin_level==7){
  // boleh mengakses
}else{
  die('Maaf, fitur ini hanya bisa diakses oleh bagian akademik, sekprodi, atau kaprodi. ');
} 



$id_mhs = $_GET['id_mhs'] ?? die(erid('id_mhs'));
$angkatan = $_GET['angkatan'] ?? die(erid('angkatan'));
$id_prodi = $_GET['id_prodi'] ?? die(erid('id_prodi'));
$id_jalur = $_GET['id_jalur'] ?? die(erid('id_jalur'));
$shift = $_GET['shift'] ?? die(erid('shift'));
$status_mhs = $_GET['status_mhs'] ?? die(erid('status_mhs'));
$nama_mhs = $_GET['nama_mhs'] ?? die(erid('nama_mhs'));
$nim = $_GET['nim'] ?? die(erid('nim'));

$s = "UPDATE tb_mhs SET nim='$nim' WHERE id='$id_mhs'";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));

$s = "UPDATE tb_mhs SET 
angkatan='$angkatan', 
id_prodi='$id_prodi', 
id_jalur='$id_jalur', 
shift='$shift', 
nama='$nama_mhs'  
WHERE id='$id_mhs'";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
die('sukses');
