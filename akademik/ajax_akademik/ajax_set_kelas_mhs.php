<?php 
include 'session_security.php';
include '../../conn.php';

# ================================================
# GET VARIABLES
# ================================================
$id_kelas_angkatan_detail = isset($_GET['id_kelas_angkatan_detail']) ? $_GET['id_kelas_angkatan_detail'] : die(erid("id_kelas_angkatan_detail"));
$id_kelas_ta = isset($_GET['id_kelas_ta']) ? $_GET['id_kelas_ta'] : die(erid("id_kelas_ta"));
$aksi = isset($_GET['aksi']) ? $_GET['aksi'] : die(erid("aksi"));
$id_mhs = isset($_GET['id_mhs']) ? $_GET['id_mhs'] : die(erid("id_mhs"));

if ($id_mhs=='' || $aksi=='' || $id_kelas_angkatan_detail=='') die("Error @ajax. Salah satu index masih kosong.");


# ================================================
# DROP HANDLER
# ================================================
if($aksi=='drop'){
  $s = "DELETE from tb_kelas_ta_detail where id=$id_kelas_angkatan_detail";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  die('sukses');
}

# ================================================
# CEK DUPLIKAT
# ================================================
$s = "SELECT 1 from tb_kelas_ta_detail where id='$id_kelas_angkatan_detail' and id_mhs=$id_mhs";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
if(mysqli_num_rows($q)==1){
  die('Mahasiswa sudah terdaftar pada kelas detail tersebut.');
}else{
  # ================================================
  # SET KELAS ANGKATAN
  # ================================================
  $s = "INSERT INTO tb_kelas_ta_detail 
  (id_kelas_ta,id_mhs) VALUES 
  ('$id_kelas_ta',$id_mhs) ";
  // die($s);
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  // $tmp = $s;

  // die($tmp);
  die('sukses');
}