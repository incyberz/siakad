<?php 
$debug_mode = 0;
$msg = "<table class='table table-hover table-bordered table-striped'>
  <thead><th style='color:red'>Error. Can't get data table.</th></thead></table>";
if (!isset($_GET['id_angkatan']))die("$msg #1");
if (!isset($_GET['id_jndaftar']))die("$msg #2");
if (!isset($_GET['nama_calon']))die("$msg #3");
if (!isset($_GET['status_daftar']))die("$msg #4");
if (!isset($_GET['id_prodi']))die("$msg #5");
if (!isset($_GET['include_rejected']))die("$msg #6");
if (!isset($_GET['cpage']))die("$msg #7");

$get_id_angkatan = $_GET['id_angkatan'];
$id_jndaftar = $_GET['id_jndaftar'];
$nama_calon = $_GET['nama_calon'];
$status_daftar = $_GET['status_daftar'];
$id_prodi = $_GET['id_prodi'];
$include_rejected = $_GET['include_rejected'];
$cpage = $_GET['cpage'];
if($cpage=='' or $cpage<=0) $cpage=1;
$limit_start = $cpage*10-10;


include "../../config.php";



$hasil="
<table class='table table-hover table-bordered table-striped'>
  <thead>
    <th>No</th>
    <th>Tanggal Masuk</th>
    <th>Masa Kerja</th>
    <th>Divisi</th>
    <th>Jabatan</th>
    <th>NIK Tendik</th>
    <th>Nama Tendik</th>
    <th>Status</th>
    <th>Aksi</th>
  </thead>
";

$s = "SELECT 
a.*
FROM tb_tendik a 
order by a.nama_tendik  
";


$q = mysqli_query($cn,$s) or die("Tidak dapat mengakses data tendik.".mysqli_error($cn));
$jumlah_records = mysqli_num_rows($q);

$s .= " limit $limit_start,10 ";
$q = mysqli_query($cn,$s) or die("Tidak dapat melimit data tendik.".mysqli_error($cn));
$jumlah_records_limited = mysqli_num_rows($q);

if ($jumlah_records_limited>0) {
  $i=0+($cpage-1)*10;
  while ($d = mysqli_fetch_array($q)) {
    $i++;
    $id_tendik = $d['id_tendik'];
    $nik_tendik = $d['nik_tendik'];
    $nama_tendik = $d['nama_tendik'];
    $status_tendik = $d['status_tendik'];
    $no_wa_tendik = $d['no_wa_tendik'];
    $email_tendik = $d['email_tendik'];
    $divisi_tendik = $d['divisi_tendik'];
    $jabatan_tendik = $d['jabatan_tendik'];

    // $tanggal_masuk = $d['tanggal_masuk'];
    // $masa_kerja = $d['masa_kerja'];
    // $homebase = $d['homebase'];
    // $jabatan = $d['jabatan'];

    $tanggal_masuk = "1 Jun 2021"; //zzz
    $masa_kerja = "3 th";

    $nama_tendik = ucwords(strtolower($nama_tendik));

    $img_wa = "wa";
    $link_wa = "https://api.whatsapp.com/send?phone=62$no_wa_tendik&text=Selamat $waktu $nama_tendik";
    $link_email = "mailto:$email_tendik?subject=INFO%20PMB%20IKMI&body=Selamat $waktu $nama_tendik";


    $hasil.= "
    <tr>
      <td class='tdcenter'>$i</td>
      <td class='tdcenter'>$tanggal_masuk</td>
      <td class='tdcenter'>$masa_kerja</td>
      <td class='tdcenter'>$divisi_tendik</td>
      <td class='tdcenter'>$jabatan_tendik</td>
      <td class='tdcenter'>$nik_tendik</td>
      <td class='' style='padding-left:10px'>
        <a href='?pmbdetail&id_calon=$id_tendik'>
          $nama_tendik
        </a>
      </td>
      <td class='tdcenter'>$status_tendik</td>
      <td class='tdcenter'>
        <a href='presensi_calon.php?email=$email_tendik&nama_calon=$nama_tendik' target='_blank'><img src='img/icons/presensi.png' width='18px'></a> 
        <a href='?resetpaswd&id_calon=$id_tendik'><img src='img/icons/set_pass_sm.png' width='22px'></a> 
        <a href='$link_email' target='_blank'><img src='img/icons/mail.png' width='20px'></a> 
        <a href='$link_wa' target='_blank'><img src='img/icons/$img_wa.png' width='20px'></a> 
        <a href='?reject&id_calon=$id_tendik'><img src='img/icons/reject.png' width='20px'></a> 
        <a href='?delete&id_calon=$id_tendik'><img src='img/icons/delete.png' width='20px'></a> 
      </td>
    </tr>
    ";
  }
}else{
  $hasil .= "<tr><td colspan=9 style='color:darkred; text-align:center'>No Data Available. $s</td></tr>";
}


$hasil.="</table>";

echo "1__$jumlah_records"."__$hasil";
?>