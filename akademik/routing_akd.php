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
  case 'manage': $konten = 'modul/manage_home.php';break;
 
  case 'manage_master': $konten = 'modul/master/manage_master.php';break;
  case 'manage_kalender': $konten = 'modul/kalender/manage_kalender.php';break;
  case 'manage_semester': $konten = 'modul/semester/manage_semester.php';break;
  case 'manage_kurikulum': $konten = 'modul/kurikulum/manage_kurikulum.php';break;
  case 'merge_mk': $konten = 'modul/kurikulum/merge_mk.php';break;
  case 'trx_mk': $konten = 'modul/kurikulum/trx_mk.php';break;

  case 'manage_jadwal': $konten = 'modul/jadwal/manage_jadwal.php';break;
  case 'manage_jadwal_dosen': $konten = 'modul/jadwal/manage_jadwal_dosen.php';break;
  case 'manage_awal_kuliah': $konten = 'modul/jadwal/manage_awal_kuliah.php';break;
  case 'manage_ruang_mengajar_dosen': $konten = 'modul/jadwal/manage_ruang_mengajar_dosen.php';break;
  case 'assign_ruang_mengajar_dosen': $konten = 'modul/jadwal/assign_ruang_mengajar_dosen.php';break;
  
  case 'manage_sesi': $konten = 'modul/sesi/manage_sesi.php';break;
  case 'manage_sesi_detail': $konten = 'modul/sesi/manage_sesi_detail.php';break;
  case 'reset_assign_ruang': $konten = 'modul/sesi/reset_assign_ruang.php';break;
  case 'reset_presensi_dosen': $konten = 'modul/sesi/reset_presensi_dosen.php';break;
  case 'reset_presensi_mhs': $konten = 'modul/sesi/reset_presensi_mhs.php';break;
  case 'sesi_mingguan': $konten = 'modul/sesi/sesi_mingguan.php';break;
  
  case 'cek_all_sesi': $konten = 'modul/sesi_kuliah/cek_all_sesi.php';break;
  
  case 'manage_kelas': $konten = 'modul/kelas/manage_kelas.php';break;
  case 'manage_grup_kelas': $konten = 'modul/kelas/manage_grup_kelas.php';break;
  case 'manage_kelas_ta': $konten = 'modul/kelas/manage_kelas_ta.php';break;
  case 'tambah_grup_kelas': $konten = 'modul/kelas/tambah_grup_kelas.php';break;
  case 'manage_peserta': $konten = 'modul/peserta/manage_peserta.php';break;


  case 'manage_presensi': $konten = 'modul/presensi/manage_presensi.php';break;
  case 'manage_presensi_per_mhs': $konten = 'modul/presensi/manage_presensi_per_mhs.php';break;
  case 'presensi': $konten = 'modul/presensi/presensi_per_mahasiswa.php';break;
  case 'presensi_per_mhs': $konten = 'modul/presensi/presensi_per_mahasiswa.php';break;
  case 'dpnu': $konten = 'modul/presensi/dpnu.php';break;
  case 'assign_ruang': $konten = 'modul/ruang/assign_ruang.php';break;
 
  case 'assign_mk': $konten = 'modul/kurikulum/assign_mk.php';break;


  case 'list_khs_manual': $konten = 'modul/khs/list_khs_manual.php';break;
  case 'input_khs_manual': $konten = 'modul/khs/input_khs_manual.php';break;
  case 'import_khs_manual': $konten = 'modul/khs/import_khs_manual.php';break;

  case 'manage_mhs': $konten = 'modul/mhs/manage_mhs.php';break;
  case 'rekap_mhs_aktif': $konten = 'modul/mhs/rekap_mhs_aktif.php';break;
  case 'master_mhs': $konten = 'modul/mhs/master_mhs.php';break;
  case 'login_as': $konten = 'modul/mhs/login_as.php';break;

  case 'login_as_dosen': $konten = 'modul/dosen/login_as_dosen.php';break;
  case 'monitoring_sks_dosen': $konten = 'modul/dosen/monitoring_sks_dosen.php';break;
  case 'lihat_dosen': $konten = 'modul/dosen/lihat_dosen.php';break;

  case 'pembayaran_home': $konten = 'modul/pembayaran/pembayaran_home.php';break;
  case 'pembayaran_manual': $konten = 'modul/pembayaran/pembayaran_manual.php';break;
  case 'rekap_pembayaran_manual': $konten = 'modul/pembayaran/rekap_pembayaran_manual.php';break;
  case 'manage_pembayaran': $konten = 'modul/pembayaran/manage_pembayaran.php';break;
  case 'manage_komponen_biaya': $konten = 'modul/pembayaran/manage_komponen_biaya.php';break;
  case 'manage_biaya_angkatan': $konten = 'modul/pembayaran/manage_biaya_angkatan.php';break;
  case 'manage_penagihan': $konten = 'modul/pembayaran/manage_penagihan.php';break;
  case 'list_sudah_bayar': $konten = 'modul/pembayaran/list_sudah_bayar.php';break;
  case 'penagihan_biaya': $konten = 'modul/pembayaran/penagihan_biaya.php';break;
  case 'penagihan_semester': $konten = 'modul/pembayaran/penagihan_semester.php';break;
  case 'penagihan_lainnya': $konten = 'modul/pembayaran/penagihan_lainnya.php';break;
  case 'history_pembayaran': $konten = 'modul/pembayaran/history_pembayaran.php';break;
  case 'manage_syarat_biaya': $konten = 'modul/pembayaran/manage_syarat_biaya.php';break;
  case 'test_pembayaran': $konten = 'modul/pembayaran/test_pembayaran.php';break;
  

  case 'ambil_krs': $konten = 'modul/krs/krs_home.php';break;
  case 'manage_krs': $konten = 'modul/krs/manage_krs.php';break;
  case 'manage_krs_mk_manual': $konten = 'modul/krs/manage_krs_mk_manual.php';break;
  case 'test_krs': $konten = 'modul/krs/test_krs.php';break;


  case 'khs': $konten = 'modul/khs/khs_home.php';break;
  case 'manage_khs': $konten = 'modul/khs/manage_khs.php';break;
  case 'import_khs': $konten = 'modul/khs/import_khs.php';break;
  case 'input_khs_manual': $konten = 'modul/khs/input_khs_manual.php';break;
  case 'ubah_nilai_khs': $konten = 'modul/khs/ubah_nilai_khs.php';break;
  case 'export_khs': $konten = 'modul/khs/export_khs.php';break;
  case 'verifikasi_draft_khs': $konten = 'modul/khs/verifikasi_draft_khs.php';break;
  case 'manage_mk_manual': $konten = 'modul/khs/manage_mk_manual.php';break;
  case 'drop_kurikulum_mk': $konten = 'modul/khs/drop_kurikulum_mk.php';break;
  case 'history_nilai': $konten = 'modul/khs/history_nilai.php';break;


  case 'manage_ujian_perbaikan': $konten = 'modul/ujian/manage_ujian_perbaikan.php';break;


  case 'super_delete_prodi': $konten = 'modul/super_user/super_delete_prodi.php';break;
  case 'super_delete_angkatan': $konten = 'modul/super_user/super_delete_angkatan.php';break;
  case 'super_delete_prodi': $konten = 'modul/super_user/super_delete_prodi.php';break;
  case 'super_delete_kurikulum': $konten = 'modul/super_user/super_delete_kurikulum.php';break;
  case 'delete_empty_mk': $konten = 'modul/super_user/delete_empty_mk.php';break;

}

// $manage_kalender = '<a href="?manage_kalender" class="proper">manage kalender</a>';
// $manage_kurikulum = '<a href="?manage_kurikulum" class="proper">manage kurikulum</a>';
// $manage_jadwal = '<a href="?manage_jadwal" class="proper">manage jadwal</a>';
// $manage_sesi = '<a href="?manage_sesi" class="proper">manage sesi</a>';
// $manage_kelas = '<a href="?manage_kelas" class="proper">manage kelas</a>';
// $manage_peserta = '<a href="?manage_peserta" class="proper">manage peserta</a>';
// $manage_mhs = '<a href="?manage_mhs" class="proper">manage mhs</a>';
// $manage_presensi = '<a href="?manage_presensi" class="proper">manage presensi</a>';
// $dpnu = '<a href="?dpnu">Manage DPNU</a>';