<?php
include 'session_security.php';
include '../conn.php';

$s = "INSERT INTO tb_fasilitas (nama_fasilitas) values ('AAAA - FASILITAS BARU (CLICK TO EDIT)')";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
?>
sukses