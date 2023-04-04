<?php 
include '../../conn.php';
include 'session_security.php';

# ================================================
# GET VARIABLES
# ================================================
$id_mhs = isset($_GET['id_mhs']) ? $_GET['id_mhs'] : die(erid("id_mhs"));
$kelas = isset($_GET['kelas']) ? $_GET['kelas'] : die(erid("kelas"));
$aksi = isset($_GET['aksi']) ? $_GET['aksi'] : die(erid("aksi"));

if ($id_mhs=='' || $kelas=='') die("Error @ajax. Salah satu index masih kosong.");

# ================================================
# GET ANGKATAN FROM KELAS
# ================================================
$s = "SELECT angkatan from tb_kelas where kelas='$kelas'";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
$d = mysqli_fetch_assoc($q);
$angkatan = $d['angkatan'];

# ================================================
# DROP HANDLER
# ================================================
if($aksi=='drop'){
  $s = "DELETE from tb_kelas_angkatan where kelas='$kelas' and angkatan=$angkatan and id_mhs=$id_mhs";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  die('sukses');
}

# ================================================
# CEK DUPLIKAT
# ================================================
$s = "SELECT 1 from tb_kelas_angkatan where kelas='$kelas' and angkatan=$angkatan and id_mhs=$id_mhs";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
if(mysqli_num_rows($q)==1){
  die('Mahasiswa sudah terdaftar pada kelas tersebut.');
}else{
  # ================================================
  # SET KELAS ANGKATAN
  # ================================================
  $s = "INSERT INTO tb_kelas_angkatan 
  (angkatan,kelas,id_mhs) VALUES 
  ($angkatan,'$kelas',$id_mhs) ";
  // die($s);
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  // $tmp = $s;

  // die($tmp);
  die('sukses');
}