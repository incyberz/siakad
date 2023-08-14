<?php 
include '../../conn.php';
include 'session_security.php';

# ================================================
# GET VARIABLES
# ================================================
$id_tipe_sesi = $_GET['id_tipe_sesi'] ?? die(erid("id_tipe_sesi"));
$nilai = $_GET['nilai'] ?? die(erid("nilai"));
$nim = $_GET['nim'] ?? die(erid("nim"));
$id_kurikulum_mk = $_GET['id_kurikulum_mk'] ?? die(erid("id_kurikulum_mk"));

# ================================================
# STOP IF EMPTY
# ================================================
//if($id_tipe_sesi=='' || $nim=='' || $nilai=='') die(erid('empty-value'));

# ================================================
# CLEAN INPUTS
# ================================================
if($nilai<0 || $nilai>100) die('Nilai harus antara 0 s.d 100');


# ================================================
# MAIN HANDLE
# ================================================
$kolom_nilai = $id_tipe_sesi==8 ? 'nuts' : '';
$kolom_nilai = $id_tipe_sesi==16 ? 'nuas' : $kolom_nilai;
$kolom_nilai = $kolom_nilai=='' ? die(erid('kolom_nilai')) : $kolom_nilai;

$id = "$nim-$id_kurikulum_mk";
$s = "INSERT INTO tb_nilai (id,nim,id_kurikulum_mk,$kolom_nilai) VALUES ('$id','$nim','$id_kurikulum_mk','$nilai') ON DUPLICATE KEY UPDATE $kolom_nilai='$nilai'";

// die($s);
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));

die('sukses');
?>