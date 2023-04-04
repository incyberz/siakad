<?php 
session_start();
# ================================================
# SESSION SECURITY
# ================================================
include "ajax_session_security.php";

# ================================================
# GET VARIABLES
# ================================================
// include "ajax_global_getting_variables.php";
$tabel = isset($_GET['tabel']) ? $_GET['tabel'] : die(erid("tabel"));
$kolom_acuan = isset($_GET['kolom_acuan']) ? $_GET['kolom_acuan'] : die(erid("kolom_acuan"));
$acuan = isset($_GET['acuan']) ? $_GET['acuan'] : die(erid("acuan"));

if ($tabel=='' OR $kolom_acuan=='' OR $acuan=='') die("Error AJAX-global-delete. Salah satu index masih kosong.");

# ================================================
# MAIN HANDLE
# ================================================
$s = "DELETE FROM tb_$tabel WHERE $kolom_acuan = '$acuan' ";
// die($s);
$q = mysqli_query($cn,$s) or die("Error @ajax. Tidak bisa menghapus data. \n\nSQL: $s\n\n".mysqli_error($cn));
die('sukses');
?>