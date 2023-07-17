<?php
# ============================================================
# DATABASE CONNECTION
# ============================================================
$online_version = $_SERVER['SERVER_NAME'] == 'localhost' ? 0 : 1;

if ($online_version) {
  $db_server = "localhost";
  $db_user = "siakadikmiac_admsiakad";
  $db_pass = "SiakadIKMICirebon2022";
  $db_name = "siakadikmiac_siakad_v6";
}else{
  $db_server = "localhost";
  $db_user = "root";
  $db_pass = '';

  $db_name = "db_siakad";
  $db_name = "db_siakad_online_v6";
}

$cn = new mysqli($db_server, $db_user, $db_pass, $db_name);
if ($cn -> connect_errno) {
  echo "Error Konfigurasi# Tidak dapat terhubung ke MySQL Server :: $db_name";
  exit();
}

date_default_timezone_set("Asia/Jakarta");

function erid($a){
    return "Error, index $a belum terdefinisi.";
}

function clean_sql($a){
  $a = str_replace('\'','`',$a);
  $a = str_replace('"','`',$a);
  $a = str_replace(';','',$a);
  return $a;
}
