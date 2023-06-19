<?php 
$dm = 0;
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
    <th>Dosen Pengampu</th>
    <th>Tanggal Sesi</th>
    <th>Ruang Kuliah</th>
    <th>Terlaksana</th>
    <th>Peserta</th>
    <th>Hadir</th>
    <th>Aksi</th>
  </thead>
";

$s = "SELECT 
a.*,
b.id_prodi,
c.nama_dosen,
d.singkatan_prodi,
e.id_mk,
e.nama_mk,
e.singkatan_mk,
e.kode_mk  

FROM tb_sesi_kuliah a 
JOIN tb_jadwal_kuliah b ON a.id_jadwal_kuliah=b.id_jadwal_kuliah 
JOIN tb_dosen c ON a.id_dosen_pengampu=c.id_dosen 
JOIN tb_prodi d ON b.id_prodi=d.id_prodi 
JOIN tb_mk e ON b.id_mk=e.id_mk 
order by a.nama_sesi_kuliah  
";


$q = mysqli_query($cn,$s) or die("Tidak dapat mengakses data sesi_kuliah.".mysqli_error($cn));
$jumlah_records = mysqli_num_rows($q);

$s .= " limit $limit_start,10 ";
$q = mysqli_query($cn,$s) or die("Tidak dapat melimit data sesi_kuliah.".mysqli_error($cn));
$jumlah_records_limited = mysqli_num_rows($q);

if ($jumlah_records_limited>0) {
  $i=0+($cpage-1)*10;
  while ($d = mysqli_fetch_array($q)) {
    $i++;
    $id_sesi_kuliah = $d['id_sesi_kuliah'];
    $id_prodi = $d['id_prodi'];
    $singkatan_prodi = $d['singkatan_prodi'];
    $id_dosen_pengampu = $d['id_dosen_pengampu'];
    $tanggal_sesi_kuliah = $d['tanggal_sesi_kuliah'];
    $tanggal_pelaksanaan = $d['tanggal_pelaksanaan'];
    $ruang_kuliah = $d['ruang_kuliah'];
    $status_sesi_kuliah = $d['status_sesi_kuliah'];
    $nama_sesi_kuliah = $d['nama_sesi_kuliah'];
    $nama_dosen_pengampu = $d['nama_dosen'];

    $id_mk = $d['id_mk'];
    $kode_mk = $d['kode_mk'];
    $nama_mk = $d['nama_mk'];
    $singkatan_mk = $d['singkatan_mk'];

    $ftanggal_sesi_kuliah = date("d M Y",strtotime($tanggal_sesi_kuliah));
    $ftanggal_pelaksanaan = date("d M Y",strtotime($tanggal_pelaksanaan));

    switch ($status_sesi_kuliah) {
      case '-1': $arti_status_sesi_kuliah = "<span class='merah tebal'>Molor</span>";break;
      case '0': $arti_status_sesi_kuliah = "<span class=''>Belum</span>";break;
      case '1': $arti_status_sesi_kuliah = "<span class='ijo'>$ftanggal_pelaksanaan</span>";break;
    }

    $jumlah_peserta = 34; //zzz
    $jumlah_hadir = 28; //zzz
    if($status_sesi_kuliah == 0) $jumlah_hadir = "-";

    $hasil.= "
    <tr>
      <td class='tdcenter'>$i</td>
      <td class='tdcenter'>$singkatan_prodi</td>
      <td class='tdcenter'>
        <a href='?datamk&id_mk=$id_mk'>$singkatan_mk</a>
      </td>

      <td class='' style='padding-left:10px'>
        <a href='?sesi_kuliahdet&id_sesi_kuliah=$id_sesi_kuliah'>
          $nama_sesi_kuliah
        </a>
      </td>
      <td class='' style='padding-left:10px'>
        <a href='?dosendet&id_dosen=$id_dosen_pengampu'>
          $nama_dosen_pengampu
        </a>
      </td>
      <td class='tdcenter'>$ftanggal_sesi_kuliah</td>
      <td class='tdcenter'>$ruang_kuliah</td>
      <td class='tdcenter'>$arti_status_sesi_kuliah</td>
      <td class='tdcenter'>$jumlah_peserta</td>
      <td class='tdcenter'>$jumlah_hadir</td>
      <td class='tdcenter'>
        <a href='?reject&id_calon=$id_sesi_kuliah'><img src='img/icons/reject.png' width='20px'></a> 
        <a href='?delete&id_calon=$id_sesi_kuliah'><img src='img/icons/delete.png' width='20px'></a> 
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