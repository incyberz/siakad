<?php
$mtc = $_GET['mtc'] ?? 1;
if(0){
  if($mtc){
    die("
    <style>body{margin:0;padding:0}</style>
    <div style='min-height: 100vh; background:black; color:white; padding:15px'>
      <div>
        <div>Mohon Maaf</div>
        <h1>Server SIAKAD sedang maintenance.</h1>
        <p style='color:yellow'>Sedang Sinkronisasi Data KHS, MK, dan Kurikulum.</p>
        <hr>
        <p style='color:gray; font-size:small; font-family: consolas'>this process initialize by: programmer (Iin Sholihin) </p>
      </div>
    </div>
    ");
  }
}
# ============================================================
# DATABASE CONNECTION
# ============================================================
$online_version = $_SERVER['SERVER_NAME'] == 'localhost' ? 0 : 1;

if ($online_version) {
  $db_server = "localhost";
  $db_user = "siakadikmiac_admsiakad";
  $db_pass = "SiakadIKMICirebon2022";
  $db_name = "siakadikmiac_siakad_v7";
}else{
  $db_server = "localhost";
  $db_user = "root";
  $db_pass = '';

  $db_name = "db_siakad";
  $db_name = "db_siakad_v8";
  $db_name = "db_siakad_v9_lokal";
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
