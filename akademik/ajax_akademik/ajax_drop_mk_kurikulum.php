<?php 
include 'session_security.php';
include '../../conn.php';
include 'akademik_only.php';

# ================================================
# GET VARIABLES
# ================================================
$id_kurikulum = $_GET['id_kurikulum'] ?? die(erid("id_kurikulum"));
$id_mk = $_GET['id_mk'] ?? die(erid("id_mk"));

if(!$id_kurikulum or !$id_mk) die('Error @ajax. id_kurikulum atau id_mk invalid.');

$s = "DELETE FROM tb_kurikulum_mk WHERE id_kurikulum=$id_kurikulum AND id_mk=$id_mk";

// die($s);
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
die('sukses');