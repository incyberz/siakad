<?php 
include 'session_security.php';
include '../../conn.php';
include 'akademik_only.php';

# ================================================
# GET VARIABLES
# ================================================
$id_nilai = $_GET['id_nilai'] ?? die(erid("id_nilai"));
$id_kurikulum_mk = $_GET['id_kurikulum_mk'] ?? die(erid("id_kurikulum_mk"));
$na = $_GET['na'] ?? die(erid("na"));
$hm = $_GET['hm'] ?? die(erid("hm"));
$untuk_nim = $_GET['untuk_nim'] ?? die(erid("untuk_nim"));


if($na=='NULL'){
  $s = "DELETE FROM tb_nilai WHERE id='$id_nilai'";
}else{
  if($id_nilai=='new'){
    $id = "$untuk_nim-$id_kurikulum_mk";
    $s = "INSERT INTO tb_nilai 
    (id,nim,id_kurikulum_mk,na,hm,input_by) VALUES 
    ('$id','$untuk_nim','$id_kurikulum_mk','$na','$hm','$id_user')";
  }elseif(strlen($id_nilai)>10){ //lenstr nim+idkmk
    $s = "UPDATE tb_nilai SET na='$na',hm='$hm' WHERE id='$id_nilai'";
  }else{
    die("id_nilai: '$id_nilai' invalid.");
  }
}

// die($s);
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
die('sukses');