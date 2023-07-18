<?php 
include 'session_security.php';
include '../../conn.php';

# ================================================
# GET VARIABLES
# ================================================
$isi_baru = isset($_GET['isi_baru']) ? $_GET['isi_baru'] : die(erid("isi_baru"));if($isi_baru=='')die(erid('isi_baru(NULL)'));
$kolom = isset($_GET['kolom']) ? $_GET['kolom'] : die(erid("kolom"));if($kolom=='')die(erid('kolom(NULL)'));
$id_biaya = isset($_GET['id_biaya']) ? $_GET['id_biaya'] : die(erid("id_biaya"));if($id_biaya=='')die(erid('id_biaya(NULL)'));

# ================================================
# GET ID BIAYA ANGKATAN (IF EXISTS)
# ================================================
$isi_baru = strtolower($isi_baru)=='null' ? 'NULL' : "'$isi_baru'";
$s = "UPDATE tb_biaya SET $kolom = $isi_baru WHERE id=$id_biaya";
// die($s);
$q = mysqli_query($cn,$s) or die('Error @ajax. '.mysqli_error($cn));
die('sukses');
?>