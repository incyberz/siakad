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
$tabel = isset($_GET['tabel']) ? $_GET['tabel'] : die(erjx("tabel"));
$kolom_acuan = isset($_GET['kolom_acuan']) ? $_GET['kolom_acuan'] : die(erjx("kolom_acuan"));
$acuan = isset($_GET['acuan']) ? $_GET['acuan'] : die(erjx("acuan"));

$tabel2 = isset($_GET['tabel2']) ? $_GET['tabel2'] : die(erjx("tabel2"));
$kolom_acuan2 = isset($_GET['kolom_acuan2']) ? $_GET['kolom_acuan2'] : die(erjx("kolom_acuan2"));
$acuan2 = isset($_GET['acuan2']) ? $_GET['acuan2'] : die(erjx("acuan2"));

if ($tabel=='' OR $kolom_acuan=='' OR $acuan=='' OR $tabel2=='' OR $kolom_acuan2=='' OR $acuan2=='') die("Error AJAX-global-delete. Salah satu index masih kosong.");

# ================================================
# DELETE CHILD RELATION FROM TABEL2
# ================================================
$s = "DELETE FROM tb_$tabel2 WHERE $kolom_acuan2 = '$acuan2' ";
$q = mysqli_query($cn,$s) or die("Error @ajax. Tidak bisa menghapus child relation. \n\nSQL: $s\n\n".mysqli_error($cn));
// $tmp = $s;

# ================================================
# DELETE MAIN TABEL
# ================================================
$s = "DELETE FROM tb_$tabel WHERE $kolom_acuan = '$acuan' ";
// $tmp .= "\n\n$s";
$q = mysqli_query($cn,$s) or die("Error @ajax. Tidak bisa menghapus pada main tabel. \n\nSQL: $s\n\n".mysqli_error($cn));
// die($tmp);
die('sukses');
?>