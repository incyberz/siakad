<?php
if(isset($_POST['btn_apply_setting'])){
  // $s = "SELECT 1 FROM tb_jadwal WHERE id_semester=$_POST[id_semester]";
  // $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  // echo "<pre>";
  // var_dump($_POST);
  // echo "</pre>";

  $awal_bayar = $_POST['senin_pertama'];
  $akhir_bayar = date('Y-m-d',strtotime("+$_POST[durasi_pembayaran] day",strtotime($awal_bayar)));

  $geser_bayar = $_POST['geser_pembayaran']+1;
  $awal_krs = date('Y-m-d',strtotime("+$geser_bayar day",strtotime($akhir_bayar)));
  $durasi_krs = $_POST['durasi_krs']-1;
  $akhir_krs = date('Y-m-d',strtotime("+$durasi_krs day",strtotime($awal_krs)));

  $w = date('w',strtotime($akhir_krs));
  $add_days = $w<=1 ? (1-$w) : (8-$w);
  $awal_kuliah_uts = date('Y-m-d',strtotime("+$add_days day",strtotime($akhir_krs)));

  $durasi_kuliah_uts = 8*7; // 8 sesi
  $durasi_kuliah_uts -= 2; // kurangi 2 hari
  $akhir_kuliah_uts = date('Y-m-d',strtotime("+$durasi_kuliah_uts day",strtotime($awal_kuliah_uts)));

  $minggu_tenang_uts = $_POST['minggu_tenang_uts']*7 + 2; //tambah 2 agar hari senin
  $awal_uts = date('Y-m-d',strtotime("+$minggu_tenang_uts day",strtotime($akhir_kuliah_uts)));
  $durasi_uts = $_POST['durasi_uts']*7; 
  $durasi_uts -= 2; // kurangi 2 hari
  $akhir_uts = date('Y-m-d',strtotime("+$durasi_uts day",strtotime($awal_uts)));

  $awal_kuliah_uas = date('Y-m-d',strtotime("2 day",strtotime($akhir_uts))); // tambah 2 hari agar hari senin
  $durasi_kuliah_uas = 8*7; // 8 sesi
  $durasi_kuliah_uas -= 2; // kurangi 2 hari
  $akhir_kuliah_uas = date('Y-m-d',strtotime("+$durasi_kuliah_uas day",strtotime($awal_kuliah_uas)));

  $minggu_tenang_uas = $_POST['minggu_tenang_uas']*7 + 2; //tambah 2 agar hari senin
  $awal_uas = date('Y-m-d',strtotime("+$minggu_tenang_uas day",strtotime($akhir_kuliah_uas)));
  $durasi_uas = $_POST['durasi_uas']*7; 
  $durasi_uas -= 2; // kurangi 2 hari
  $akhir_uas = date('Y-m-d',strtotime("+$durasi_uas day",strtotime($awal_uas)));

  // echo "
  // <br>awal_bayar: $awal_bayar
  // <br>akhir_bayar: $akhir_bayar
  // <br>awal_krs: $awal_krs
  // <br>akhir_krs: $akhir_krs
  // <br>awal_kuliah_uts: $awal_kuliah_uts
  // <br>awal_kuliah_uas: $awal_kuliah_uas
  // <br>awal_uts: $awal_uts
  // <br>awal_uas: $awal_uas
  // ";

  $s = "UPDATE tb_semester SET 
  awal_bayar = '$awal_bayar',
  akhir_bayar = '$akhir_bayar',
  awal_krs = '$awal_krs',
  akhir_krs = '$akhir_krs',
  awal_kuliah_uts = '$awal_kuliah_uts',
  awal_kuliah_uas = '$awal_kuliah_uas',
  awal_uts = '$awal_uts',
  awal_uas = '$awal_uas',

  akhir_kuliah_uts = '$akhir_kuliah_uts',
  akhir_kuliah_uas = '$akhir_kuliah_uas',
  akhir_uts = '$akhir_uts',
  akhir_uas = '$akhir_uas',

  last_update = CURRENT_TIMESTAMP 
  WHERE id = $id_semester
  ";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  die(div_alert('success',"Automatic Settings Semester berhasil tersimpan.<hr><a href='?manage_semester&id_semester=$id_semester' class='btn btn-primary'>Lihat Hasil Kalender</a>"));
}
