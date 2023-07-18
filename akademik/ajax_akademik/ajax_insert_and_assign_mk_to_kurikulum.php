<?php 
include 'session_security.php';
include '../../conn.php';

# ================================================
# GET VARIABLES
# ================================================
$koloms = isset($_GET['koloms']) ? $_GET['koloms'] : die(erid("koloms"));
$isis = isset($_GET['isis']) ? $_GET['isis'] : die(erid("isis"));
$id_semester = isset($_GET['id_semester']) ? $_GET['id_semester'] : die(erid("id_semester"));
$id_kurikulum = isset($_GET['id_kurikulum']) ? $_GET['id_kurikulum'] : die(erid("id_kurikulum"));

if ($koloms=='' OR $isis=='' OR $id_semester=='' OR $id_kurikulum=='') die("Error AJAX-global-assign. Salah satu index masih kosong.");

# ================================================
# GET NEW ID FROM AUTO_INCREMENT
# ================================================
$s = "SELECT auto_increment from information_schema.tables 
where table_schema = '$db_name' 
and table_name = 'tb_mk'";
$q = mysqli_query($cn,$s) or die("Error @AJAX get auto_increment.");
$d = mysqli_fetch_array($q);
$new_id = $d['auto_increment'];
// $tmp = $s;

# ================================================
# MAIN HANDLE
# ================================================
$s = "INSERT INTO tb_mk (id,$koloms) VALUES ($new_id,$isis)";
$q = mysqli_query($cn,$s) or die("Error @ajax. ".mysqli_error($cn));
// $tmp .= "\n\n$s";

$s = "INSERT INTO tb_kurikulum_mk (id_semester,id_mk,id_kurikulum) VALUES ($id_semester,$new_id,$id_kurikulum)";
$q = mysqli_query($cn,$s) or die("Error Second @ajax. ".mysqli_error($cn));

// die("$tmp\n\n$s");
die('sukses');
?>