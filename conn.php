<?php
# ============================================================
# DATABASE CONNECTION
# ============================================================
$online_version = 1;
if ($_SERVER['SERVER_NAME'] == "localhost") $online_version = 0;

if ($online_version) {
  $db_server = "localhost";
  $db_user = "siakadikmiac_admsiakad";
  $db_pass = "SiakadIKMICirebon2022";
  $db_name = "siakadikmiac_siakad";
}else{
  $db_server = "localhost";
  $db_user = "root";
  $db_pass = '';

  $db_name = "db_siakad";
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