<?php 
# ========================================================
# MANAGE URI
# ========================================================
$a = $_SERVER['REQUEST_URI'];
if (!strpos($a, "?")) $a.="?";
if (!strpos($a, "&")) $a.="&";

$b = explode("?", $a);
$c = explode("&", $b[1]);
$konten = 'na.php';

switch ($c[0]){
  case '':
  case 'dashboard': $konten = 'modul/dashboard/siakad_dashboard.php';break;
  case 'master': $konten = 'modul/master.php';break;
  case 'manage': $konten = 'modul/manage.php';break;
 
  case 'manage_kalender': $konten = 'modul/kalender/manage_kalender.php';break;
  case 'manage_semester': $konten = 'modul/semester/manage_semester.php';break;
  case 'manage_kurikulum': $konten = 'modul/kurikulum/manage_kurikulum.php';break;
  case 'manage_jadwal': $konten = 'modul/jadwal/manage_jadwal.php';break;
  case 'manage_jadwal_dosen': $konten = 'modul/jadwal/manage_jadwal_dosen.php';break;
  case 'manage_sesi': $konten = 'modul/sesi_kuliah/manage_sesi_kuliah.php';break;
  case 'cek_all_sesi': $konten = 'modul/sesi_kuliah/cek_all_sesi.php';break;
  case 'manage_kelas': $konten = 'modul/kelas/manage_kelas.php';break;
  case 'manage_peserta': $konten = 'modul/peserta/manage_peserta.php';break;
  case 'manage_mhs': $konten = 'modul/mhs/manage_mhs.php';break;
  case 'monitoring_sks_dosen': $konten = 'modul/dosen/monitoring_sks_dosen.php';break;
  case 'manage_presensi': $konten = 'modul/presensi/manage_presensi.php';break;
  case 'manage_presensi_per_mhs': $konten = 'modul/presensi/manage_presensi_per_mhs.php';break;
  case 'presensi': $konten = 'modul/presensi/presensi_per_mahasiswa.php';break;
  case 'presensi_per_mhs': $konten = 'modul/presensi/presensi_per_mahasiswa.php';break;
  case 'dpnu': $konten = 'modul/presensi/dpnu.php';break;
  case 'assign_ruang': $konten = 'modul/ruang/assign_ruang.php';break;
 
  case 'assign_mk': $konten = 'modul/kurikulum/assign_mk.php';break;


  case 'khs': $konten = 'modul/khs/manage_khs.php';break;
  case 'input_khs': $konten = 'modul/khs/input_khs.php';break;
  case 'import_khs': $konten = 'modul/khs/import_khs.php';break;
  case 'input_khs_manual': $konten = 'modul/khs/input_khs_manual.php';break;
  case 'export_khs': $konten = 'modul/khs/export_khs.php';break;
  case 'verifikasi_draft_khs': $konten = 'modul/khs/verifikasi_draft_khs.php';break;

  case 'list_khs_manual': $konten = 'modul/khs/list_khs_manual.php';break;
  case 'input_khs_manual': $konten = 'modul/khs/input_khs_manual.php';break;
  case 'import_khs_manual': $konten = 'modul/khs/import_khs_manual.php';break;
}

$manage_kalender = '<a href="?manage_kalender" class="proper">manage kalender</a>';
$manage_kurikulum = '<a href="?manage_kurikulum" class="proper">manage kurikulum</a>';
$manage_jadwal = '<a href="?manage_jadwal" class="proper">manage jadwal</a>';
$manage_sesi = '<a href="?manage_sesi" class="proper">manage sesi</a>';
$manage_kelas = '<a href="?manage_kelas" class="proper">manage kelas</a>';
$manage_peserta = '<a href="?manage_peserta" class="proper">manage peserta</a>';
$manage_mhs = '<a href="?manage_mhs" class="proper">manage mhs</a>';
$manage_presensi = '<a href="?manage_presensi" class="proper">manage presensi</a>';
$dpnu = '<a href="?dpnu">Manage DPNU</a>';