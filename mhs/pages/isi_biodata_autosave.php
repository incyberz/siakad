<?php
include '../../conn.php';

$nim = $_GET['nim'] ?? die(erid('nim'));
$id_semester = $_GET['id_semester'] ?? die(erid('id_semester'));
$kolom = $_GET['kolom'] ?? die(erid('kolom')); if($kolom=='') die(erid('null::kolom'));
$value = $_GET['value'] ?? die(erid('value'));


$s = "INSERT INTO tb_biodata (nim) VALUES ('$nim') ON DUPLICATE KEY UPDATE id_semester=$id_semester, last_modified=CURRENT_TIMESTAMP";
// die($s);
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));

$value = ($value=='-' || strtoupper($value)=='NULL') ? 'NULL' : "'$value'";
$s = "UPDATE tb_biodata set $kolom=$value WHERE nim='$nim'";

if($kolom=='set_domisili_as_ktp'){
  $s = "UPDATE tb_biodata set 
  alamat_blok_domisili = alamat_blok,
  alamat_rt_domisili = alamat_rt,
  alamat_rw_domisili = alamat_rw,
  alamat_desa_domisili = alamat_desa,
  alamat_kecamatan_domisili = alamat_kecamatan 
  WHERE nim='$nim'";
}elseif($kolom=='set_tidak_bekerja'){
  $s = "UPDATE tb_biodata set 
  bekerja_sebagai = NULL,
  jabatan_bekerja = NULL,
  instansi_bekerja = NULL,
  alamat_bekerja = NULL 
  WHERE nim='$nim'";
}


// die($s);
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));

echo "sukses";