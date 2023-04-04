<?php 
session_start();
# ================================================
# SESSION SECURITY
# ================================================
include "ajax_session_security.php";

# ================================================
# GET VARIABLES
# ================================================
$tabel2 = isset($_GET['tabel2']) ? $_GET['tabel2'] : die(erid("tabel2"));
$kolom_acuan = isset($_GET['kolom_acuan']) ? $_GET['kolom_acuan'] : die(erid("kolom_acuan"));
$acuan = isset($_GET['acuan']) ? $_GET['acuan'] : die(erid("acuan"));
$kolom_acuan2 = isset($_GET['kolom_acuan2']) ? $_GET['kolom_acuan2'] : die(erid("kolom_acuan2"));
$acuan2 = isset($_GET['acuan2']) ? $_GET['acuan2'] : die(erid("acuan2"));

if ($tabel2=='' OR $kolom_acuan=='' OR $acuan=='' OR $kolom_acuan2=='' OR $acuan2=='') die("Error AJAX-global-delete. Salah satu index masih kosong.");

# ================================================
# DELETE CHILD RELATION FROM TABEL2
# ================================================
$s = "DELETE FROM tb_$tabel2 WHERE $kolom_acuan = '$acuan' AND $kolom_acuan2 = '$acuan2' ";
$q = mysqli_query($cn,$s) or die("Error @ajax. Tidak bisa menghapus child relation. \n\nSQL: $s\n\n".mysqli_error($cn));
// $tmp = $s;

// die($tmp);
die('sukses');
?>