<?php
if(isset($_POST['btn_buat_kurikulum'])){
  // echo "<pre>";
  // var_dump($_POST);
  // echo "</pre>";

  $s = "SELECT angkatan,jenjang,(SELECT nama FROM tb_prodi WHERE id=$_POST[id_prodi]) as nama_prodi FROM tb_kalender WHERE id=$_POST[id_kalender]";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  $d = mysqli_fetch_assoc($q);
  $angkatan = $d['angkatan'];
  $jenjang = $d['jenjang'];
  $nama_prodi = $d['nama_prodi'];

  $s = "SELECT auto_increment from information_schema.tables 
  WHERE table_schema = '$db_name' 
  and table_name = 'tb_kurikulum'";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  $d = mysqli_fetch_assoc($q);
  $new_id_kurikulum = $d['auto_increment'];


  $nama_kurikulum = "Kurikulum $jenjang-$nama_prodi Angkatan $angkatan";
  $s = "INSERT INTO tb_kurikulum (id,id_prodi,id_kalender,nama) VALUES ($new_id_kurikulum,$_POST[id_prodi],$id_kalender,'$nama_kurikulum') ";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));

  echo div_alert('success',"Kurikulum Berhasil dibuat.
  <hr>
  <a href='?manage_kalender&id_kalender=$id_kalender' class='btn btn-info'>Kembali ke Manage Kalender</a>
  <a href='?manage_kurikulum&id_kurikulum=$new_id_kurikulum' class='btn btn-primary'>Manage Kurikulum ini.</a>
  ");

  exit;
}