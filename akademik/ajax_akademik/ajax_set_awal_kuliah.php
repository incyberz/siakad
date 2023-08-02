<?php 
include 'session_security.php';
include '../../conn.php';
include 'akademik_only.php';

# ================================================
# GET VARIABLES
# ================================================
$id_jadwal = $_GET['id_jadwal'] ?? die(erid("id_jadwal"));
$awal_kuliah = $_GET['awal_kuliah'] ?? die(erid("awal_kuliah"));

$s = "UPDATE tb_jadwal SET awal_kuliah='$awal_kuliah' WHERE id='$id_jadwal'";

die($s);
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
die('sukses');