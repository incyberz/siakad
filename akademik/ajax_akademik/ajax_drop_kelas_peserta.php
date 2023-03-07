<?php 
include '../../conn.php';
include 'session_security.php';

# ================================================
# GET VARIABLES
# ================================================
$id_kelas_peserta = isset($_GET['id_kelas_peserta']) ? $_GET['id_kelas_peserta'] : die(erid("id_kelas_peserta"));

if ($id_kelas_peserta=='') die("Error @ajax. Salah satu index masih kosong.");

# ================================================
# DELETE CHILD RELATION FROM TABEL2
# ================================================
$s = "DELETE FROM tb_kelas_peserta WHERE id = '$id_kelas_peserta'  ";
// die($s);
$q = mysqli_query($cn,$s) or die("Error @ajax. Tidak bisa menghapus child relation. ".mysqli_error($cn));
// $tmp = $s;

// die($tmp);
die('sukses');
?>