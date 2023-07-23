<?php
if(isset($_POST['btn_simpan_aturan_tanggal'])){
  // echo '<pre>';
  // var_dump($_POST);
  // echo '</pre>';

  $rtg = ['bayar','krs','kuliah_uts','uts','kuliah_uas','uas'];
  $sets = '__';
  foreach ($rtg as $value){
    $awal_value = "awal_$value";
    $akhir_value = "akhir_$value";
    $sets.= ",awal_$value = '$_POST[$awal_value]',akhir_$value = '$_POST[$akhir_value]'";
  } 
  $sets = str_replace('__,','',$sets);

  $s = "UPDATE tb_semester SET $sets WHERE id=$_POST[id_semester]";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  
  echo div_alert('success',"Update penanggalan semester sukses. <hr><a href='?manage_semester&id_semester=$_POST[id_semester]'>Back to Manage Semester</a>");

  exit;
}
