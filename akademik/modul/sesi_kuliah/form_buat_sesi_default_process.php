<?php
if(isset($_POST['btn_buat_sesi_default'])){
  $id_dosen = $_POST['id_dosen'];
  $id_jadwal = $_POST['id_jadwal'];
  $jumlah_sesi = $_POST['jumlah_sesi'];
  $sesi_uts = $_POST['sesi_uts'];
  $sesi_uas = $_POST['sesi_uas'];

  $awal_perkuliahan = "$_POST[awal_perkuliahan] $_POST[pukul_p1]";

  // $new_tgl = strtotime('+57 day',strtotime($awal_perkuliahan));
  // die("$awal_perkuliahan new_tgl: ".date('Y-m-d H:i:s',$new_tgl));
  
  $values = '__';
  for ($i=1; $i <= $jumlah_sesi ; $i++) {

    // ADD 7 DAYS IN JAVASCRIPT
    // var date = new Date();
    // date.setDate(date.getDate() + 7);

    // $date = "Mar 03, 2011";
    // $date = strtotime($date);
    // $date = strtotime("+7 day", $date);
    // echo date('M d, Y', $date);
    $selisih = ($i-1)*7;
    $new_tgl = date('Y-m-d H:i',strtotime("+$selisih day",strtotime($awal_perkuliahan)));

    $nama_sesi = "NEW P$i";
    $nama_sesi = $i==$sesi_uts ? 'UTS' : $nama_sesi;
    $nama_sesi = $i==$sesi_uas ? 'UAS' : $nama_sesi;
    
    $values .= ",(
    $id_jadwal,
    $i,
    $id_dosen,
    '$nama_sesi',
    '$new_tgl'
    )";
  }
  $values = str_replace('__,','',$values);

  $s = "INSERT INTO tb_sesi_kuliah (
    id_jadwal,
    pertemuan_ke,
    id_dosen,
    nama,
    tanggal_sesi
    ) VALUES $values";
    // die($s);
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));

  echo div_alert('success',"Membuat $jumlah_sesi Sesi Kuliah Default berhasil.<hr><a href='?manage_sesi&id_jadwal=$id_jadwal'>Lanjutkan Proses</a>");
  exit;
  
}
