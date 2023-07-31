<?php 
include 'session_security.php';
include '../../conn.php';
include 'akademik_only.php';

# ================================================
# GET VARIABLES
# ================================================
$id_kelas_ta = $_GET['id_kelas_ta'] ?? die(erid("id_kelas_ta"));
$nim = $_GET['nim'] ?? die(erid("nim"));
$mode = $_GET['mode'] ?? die(erid("mode"));

if($mode=='add'){
  $s = "SELECT 1 FROM tb_kelas_ta_detail WHERE id_kelas_ta=$id_kelas_ta AND nim=$nim";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  if(mysqli_num_rows($q)==1) die('sukses');

  $s = "INSERT INTO tb_kelas_ta_detail 
  (id_kelas_ta,nim) VALUES 
  ('$id_kelas_ta','$nim')";
}elseif($mode=='drop'){
  $s = "DELETE FROM tb_kelas_ta_detail WHERE nim='$nim' AND id_kelas_ta='$id_kelas_ta'";
}

// die($s);
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
die('sukses');