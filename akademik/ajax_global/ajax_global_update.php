<?php 
session_start();
# ================================================
# SESSION SECURITY
# ================================================
include "ajax_session_security.php";

# ================================================
# GET VARIABLES
# ================================================
$tabel = isset($_GET['tabel']) ? $_GET['tabel'] : die(erjx("tabel"));
$kolom_target = isset($_GET['kolom_target']) ? $_GET['kolom_target'] : die(erjx("kolom_target"));
$kolom_acuan = isset($_GET['kolom_acuan']) ? $_GET['kolom_acuan'] : die(erjx("kolom_acuan"));
$acuan = isset($_GET['acuan']) ? $_GET['acuan'] : die(erjx("acuan"));
$isi_baru = isset($_GET['isi_baru']) ? $_GET['isi_baru'] : die(erjx("isi_baru"));

if ($tabel=='' OR $kolom_target=='' OR $kolom_acuan=='' OR $acuan=='' OR $isi_baru=='') die("Error AJAX-global-update. Salah satu index masih kosong.");

# ================================================
# MAIN HANDLE
# ================================================
$isi_baru = strtoupper($isi_baru)=='NULL' ? 'NULL' : "'$isi_baru'";
$s = "UPDATE tb_$tabel SET $kolom_target = $isi_baru WHERE $kolom_acuan = '$acuan' ";
$q = mysqli_query($cn,$s) or die("Error @ajax. Tidak bisa mengupdate values. SQL:$s. ".mysqli_error($cn));
die('sukses');
?>