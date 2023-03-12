<?php
if(isset($_POST['btn_buat_kurikulum'])){
  echo "<pre>";
  var_dump($_POST);
  echo "</pre>";

  $s = "SELECT angkatan,jenjang from tb_kalender where id_kalender=$_POST[id_kalender]";
  $q = mysqli_query
  exit;
}