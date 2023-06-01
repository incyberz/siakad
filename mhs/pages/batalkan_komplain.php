<?php
$id_nilai = isset($_GET['id_nilai']) ? $_GET['id_nilai'] : die(erid('id_nilai'));

$s = "DELETE FROM tb_komplain_nilai WHERE id_nilai=$id_nilai";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
echo '<script>location.replace("?khs")</script>';