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

$tabel2 = isset($_GET['tabel2']) ? $_GET['tabel2'] : die(erid("tabel2"));
$id2 = isset($_GET['id2']) ? $_GET['id2'] : die(erid("id2"));
$kolom2 = isset($_GET['kolom2']) ? $_GET['kolom2'] : die(erid("kolom2"));

// var_dump($_GET);

if ($tabel=='' OR $tabel2=='' OR $koloms=='' OR $isis=='' OR $id2=='' OR $kolom2=='') die("Error AJAX-global-assign. Salah satu index masih kosong.");


# ================================================
# GET NEW ID FROM AUTO_INCREMENT
# ================================================
$s = "SELECT auto_increment from information_schema.tables 
where table_schema = '$db_name' 
and table_name = 'tb_$tabel'";
$q = mysqli_query($cn,$s) or die("Error @AJAX get auto_increment.");
$d = mysqli_fetch_array($q);
$new_id = $d['auto_increment'];
// $tmp = $s;

# ================================================
# MAIN HANDLE
# ================================================
$s = "INSERT INTO tb_$tabel (id,$koloms) VALUES ($new_id,$isis)";
$q = mysqli_query($cn,$s) or die("Error @ajax. ".mysqli_error($cn));
// $tmp .= "\n\n$s";

$s = "INSERT INTO tb_$tabel2 (id_$tabel, $kolom2) VALUES ($new_id , $id2)";
$q = mysqli_query($cn,$s) or die("Error Second @ajax. ".mysqli_error($cn));

// die("$tmp\n\n$s");
die('sukses');
?>