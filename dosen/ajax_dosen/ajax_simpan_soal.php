<?php 
include '../../conn.php';
include 'session_security.php';

# ================================================
# GET VARIABLES
# ================================================
$id_tipe_sesi = isset($_GET['id_tipe_sesi']) ? $_GET['id_tipe_sesi'] : die(erid("id_tipe_sesi"));
$id_jadwal = isset($_GET['id_jadwal']) ? $_GET['id_jadwal'] : die(erid("id_jadwal"));
$id_soal = isset($_GET['id_soal']) ? $_GET['id_soal'] : die(erid("id_soal"));
$no_soal = isset($_GET['no_soal']) ? $_GET['no_soal'] : die(erid("no_soal"));
$soal = isset($_GET['soal']) ? $_GET['soal'] : die(erid("soal"));
$opsi_a = isset($_GET['opsi_a']) ? $_GET['opsi_a'] : die(erid("opsi_a"));
$opsi_b = isset($_GET['opsi_b']) ? $_GET['opsi_b'] : die(erid("opsi_b"));
$opsi_c = isset($_GET['opsi_c']) ? $_GET['opsi_c'] : die(erid("opsi_c"));
$opsi_d = isset($_GET['opsi_d']) ? $_GET['opsi_d'] : die(erid("opsi_d"));
$kj = isset($_GET['kj']) ? $_GET['kj'] : die(erid("kj"));

# ================================================
# CLEAN INPUTS
# ================================================
function clean($a){
  $a = str_replace('\'','`',$a);
  $a = str_replace('"','`',$a);
  $a = str_replace('<','< ',$a);
  $a = str_replace('#','(tanda pagar)',$a);
  $a = str_replace('  ',' ',$a);
  $a = str_replace('  ',' ',$a);
  return $a;
}
$soal = clean($soal);
$opsi_a = clean($opsi_a);
$opsi_b = clean($opsi_b);
$opsi_c = clean($opsi_c);
$opsi_d = clean($opsi_d);

# ================================================
# MAIN HANDLE
# ================================================
if($id_soal=='new'){
  $s = "INSERT INTO tb_soal 
  (
    id_jadwal,
    id_tipe_sesi,
    no_soal,
    soal,
    opsi_a,
    opsi_b,
    opsi_c,
    opsi_d,
    last_update,
    kj
    ) VALUES (
    '$id_jadwal',
    '$id_tipe_sesi',
    '$no_soal',
    '$soal',
    '$opsi_a',
    '$opsi_b',
    '$opsi_c',
    '$opsi_d',
    CURRENT_TIMESTAMP,
    '$kj'
    )";
}else{
  $s = "UPDATE tb_soal SET 
  soal='$soal',
  opsi_a='$opsi_a',
  opsi_b='$opsi_b',
  opsi_c='$opsi_c',
  opsi_d='$opsi_d',
  last_update=CURRENT_TIMESTAMP,
  kj='$kj' 
  WHERE id=$id_soal
  ";
}
// die($s);
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));

die('sukses');
?>