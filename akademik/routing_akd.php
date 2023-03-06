<?php 
# ========================================================
# MANAGE URI
# ========================================================
$a = $_SERVER['REQUEST_URI'];
if (!strpos($a, "?")) $a.="?";
if (!strpos($a, "&")) $a.="&";

$b = explode("?", $a);
$c = explode("&", $b[1]);

switch ($c[0]){
  case '':
  case 'dashboard': $konten = 'modul/dashboard/siakad_dashboard.php';break;
  case 'master': $konten = 'modul/master.php';break;
  case 'kurikulum': $konten = 'modul/kurikulum/manage_kurikulum.php';break;
  case 'manage_jadwal': $konten = 'modul/jadwal/manage_jadwal.php';break;
  case 'manage_sesi': $konten = 'modul/sesi_kuliah/manage_sesi_kuliah.php';break;
  case 'batch_tanggal_sesi': $konten = 'modul/sesi_kuliah/batch_tanggal_sesi.php';break;
  case 'manage_kelas': $konten = 'modul/kelas/manage_kelas.php';break;
  case 'assign_mk': $konten = 'modul/kurikulum/assign_mk.php';break;
}