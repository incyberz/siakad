<?php 
$debug_mode = 0;
$msg = "<table class='table table-hover table-bordered table-striped'>
  <thead><th style='color:red'>Error. Can't get data table.</th></thead></table>";
// if (!isset($_GET['id_angkatan']))die("$msg #1");
// if (!isset($_GET['id_jndaftar']))die("$msg #2");
// if (!isset($_GET['nama_calon']))die("$msg #3");
// if (!isset($_GET['status_daftar']))die("$msg #4");
// if (!isset($_GET['id_prodi']))die("$msg #5");
// if (!isset($_GET['include_rejected']))die("$msg #6");

if (!isset($_GET['cpage']))die("$msg #7");

// $get_id_angkatan = $_GET['id_angkatan'];
// $id_jndaftar = $_GET['id_jndaftar'];
// $nama_calon = $_GET['nama_calon'];
// $status_daftar = $_GET['status_daftar'];
// $id_prodi = $_GET['id_prodi'];
// $include_rejected = $_GET['include_rejected'];

$cpage = $_GET['cpage'];
if($cpage=='' or $cpage<=0) $cpage=1;
$limit_start = $cpage*10-10;


include "../../config.php";



$hasil="
<table class='table table-hover table-bordered table-striped'>
  <thead>
    <th>No</th>
    <th>Prodi</th>
    <th>Mata Kuliah</th>
    <th>Sesi Kuliah</th>
    <th>Nama Mhs</th>
    <th>Poin Presensi</th>
    <th>Start Kuliah</th>
    <th>Jam Masuk</th>
    <th>Status</th>
    <th>Menit Kuliah</th>
    <th>Aksi</th>
  </thead>
";

$s = "SELECT 
a.*,
b.id_sesi_kuliah,
b.nama_sesi_kuliah,
b.tanggal_pelaksanaan,
d.id_mk,
d.nama_mk,
e.id_mhs,
e.nama_mhs,
f.singkatan_prodi 

FROM tb_presensi_kuliah a 
JOIN tb_sesi_kuliah b ON a.id_sesi_kuliah=b.id_sesi_kuliah 
JOIN tb_jadwal_kuliah c ON b.id_jadwal_kuliah=c.id_jadwal_kuliah  
JOIN tb_mk d ON c.id_mk=d.id_mk  
JOIN tb_mhs e ON a.id_mhs=e.id_mhs 
JOIN tb_prodi f ON c.id_prodi = f.id_prodi 
order by e.nama_mhs  
";


$q = mysqli_query($cn,$s) or die("Tidak dapat mengakses data presensi_kuliah.".mysqli_error($cn));
$jumlah_records = mysqli_num_rows($q);

$s .= " limit $limit_start,10 ";
$q = mysqli_query($cn,$s) or die("Tidak dapat melimit data presensi_kuliah.".mysqli_error($cn));
$jumlah_records_limited = mysqli_num_rows($q);

if ($jumlah_records_limited>0) {
  $i=0+($cpage-1)*10;
  while ($d = mysqli_fetch_array($q)) {
    $i++;
    $id_presensi_kuliah = $d['id_presensi_kuliah'];
    // $id_prodi = $d['id_prodi'];
    $id_sesi_kuliah = $d['id_sesi_kuliah'];
    $nama_sesi_kuliah = $d['nama_sesi_kuliah'];
    $id_mk = $d['id_mk'];
    $nama_mk = $d['nama_mk'];
    $id_mhs = $d['id_mhs'];
    $nama_mhs = $d['nama_mhs'];
    $singkatan_prodi = $d['singkatan_prodi'];



    $poin_presensi = $d['poin_presensi'];

    $tanggal_pelaksanaan = $d['tanggal_pelaksanaan'];
    $jam_masuk_kuliah = $d['jam_masuk_kuliah'];
    $jam_keluar_kuliah = $d['jam_keluar_kuliah'];
    $fjam_start_kuliah = date("h:i",strtotime($tanggal_pelaksanaan));
    $fjam_masuk_kuliah = date("h:i",strtotime($jam_masuk_kuliah));
    $fjam_keluar_kuliah = date("h:i",strtotime($jam_keluar_kuliah));

    $status_presensi_kuliah = "Ontime"; //zzz

    $hasil.= "
    <tr>
      <td class='tdcenter'>$i</td>
      <td class='tdcenter'>$singkatan_prodi</td>
      <td class='' style='padding-left:10px'>
        <a href='?mkdet&id_mk=$id_mk'>
          $nama_mk
        </a>
      </td>
      <td class='' style='padding-left:10px'>
        <a href='?sesikuldet&id_sesi_kuliah=$id_sesi_kuliah'>
          $nama_sesi_kuliah
        </a>
      </td>
      <td class='' style='padding-left:10px'>
        <a href='?mhsdet&id_mhs=$id_mhs'>
          $nama_mhs
        </a>
      </td>
      <td class='tdcenter'>$poin_presensi</td>
      <td class='tdcenter'>$fjam_start_kuliah</td>
      <td class='tdcenter'>$fjam_masuk_kuliah</td>
      <td class='tdcenter'>$status_presensi_kuliah</td>
      <td class='tdcenter'>
        <a href='?reject&id_calon=$id_presensi_kuliah'><img src='img/icons/reject.png' width='20px'></a> 
        <a href='?delete&id_calon=$id_presensi_kuliah'><img src='img/icons/delete.png' width='20px'></a> 
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