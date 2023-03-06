<?php 
include '../../conn.php';
include 'session_security.php';

# ================================================
# GET VARIABLES
# ================================================
$id_peserta_kelas = isset($_GET['id_peserta_kelas']) ? $_GET['id_peserta_kelas'] : die(erid("id_peserta_kelas"));

if ($id_peserta_kelas=='') die("Error @ajax. Salah satu index masih kosong.");

# ================================================
# DELETE CHILD RELATION FROM TABEL2
# ================================================
$s = "DELETE FROM tb_peserta_kelas WHERE id = '$id_peserta_kelas'  ";
// die($s);
$q = mysqli_query($cn,$s) or die("Error @ajax. Tidak bisa menghapus child relation. ".mysqli_error($cn));
// $tmp = $s;

// die($tmp);
die('sukses');
?>