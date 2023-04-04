<?php 
session_start();
# ================================================
# SESSION SECURITY
# ================================================
include "ajax_session_security.php";

# ================================================
# GET VARIABLES
# ================================================
$tabel = isset($_GET['tabel']) ? $_GET['tabel'] : die(erid("tabel"));
$koloms = isset($_GET['koloms']) ? $_GET['koloms'] : die(erid("koloms"));
$isis = isset($_GET['isis']) ? $_GET['isis'] : die(erid("isis"));

// var_dump($_GET);

if ($tabel=='' OR $koloms=='' OR $isis=='') die("Error AJAX-global-insert. Salah satu index masih kosong.");

# ================================================
# MAIN HANDLE
# ================================================
$s = "INSERT INTO tb_$tabel ($koloms) VALUES ($isis)";
$q = mysqli_query($cn,$s) or die("Error @ajax. ".mysqli_error($cn));

die('sukses');
?>