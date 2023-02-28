<?php
include '../conn.php';
include 'session_security.php';

$id = isset($_GET['id']) ? $_GET['id'] : die(erid('id'));

$s = "DELETE FROM tb_fasilitas where id='$id'";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
?>
sukses