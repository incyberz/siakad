<?php 
include '../../conn.php';
include 'session_security.php';

# ================================================
# GET VARIABLES
# ================================================
$id_mhs = isset($_GET['id_mhs']) ? $_GET['id_mhs'] : die(erid("id_mhs"));

if ($id_mhs=='') die("Error @ajax. Salah satu index masih kosong.");

# ================================================
# DROP KELAS PADA MHS
# ================================================
$s = "UPDATE tb_mhs set kelas=NULL WHERE id = '$id_mhs'  ";
// die($s);
$q = mysqli_query($cn,$s) or die("Error @ajax. Tidak bisa drop kelas. ".mysqli_error($cn));
// $tmp = $s;

// die($tmp);
die('sukses');
?>