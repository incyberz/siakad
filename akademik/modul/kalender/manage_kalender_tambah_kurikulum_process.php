<?php
if(isset($_POST['btn_buat_kurikulum'])){
  // echo "<pre>";
  // var_dump($_POST);
  // echo "</pre>";

  # =================================================
  # CEK JIKA DUPLIKAT
  # =================================================
  $s = "SELECT id as id_kurikulum FROM tb_kurikulum WHERE id_kalender=$_POST[id_kalender] AND  id_prodi=$_POST[id_prodi]";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  if(mysqli_num_rows($q)>1)die('Double Kurikulum detected. Segera lapor programmer!');
  if(mysqli_num_rows($q)==1){
    $d=mysqli_fetch_assoc($q);
    echo "<div class='alert alert-info'>Kurikulum sudah ada.<hr><a href='?manage_kurikulum&id_kurikulum=$d[id_kurikulum]'>Manage Kurikulum</a></div>";
    exit;
  }



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