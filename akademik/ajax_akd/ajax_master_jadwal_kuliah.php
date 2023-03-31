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
    <th>Angkatan</th>
    <th>Smt</th>
    <th>Kls</th>
    <th>Mata Kuliah</th>
    <th>Jumlah Sesi</th>
    <th>Dosen Koordinator</th>
    <th>Tgl Awal Smt</th>
    <th>Tgl Akhir Smt</th>
    <th>Status</th>
    <th>Aksi</th>
  </thead>
";

$s = "SELECT 
a.*,
b.singkatan_prodi,
c.id_mk,
c.kode_mk,
c.nama_mk,
d.nama_dosen,
d.gelar_depan,
d.gelar_belakang,
e.no_semester,
e.tanggal_awal_semester,
e.tanggal_akhir_semester,
f.jenjang,
g.id_angkatan,
h.singkatan_kelas 

FROM tb_jadwal_kuliah a 
JOIN tb_prodi b on a.id_prodi=b.id_prodi 
JOIN tb_mk c ON a.id_mk=c.id_mk 
JOIN tb_dosen d ON a.id_dosen=d.id_dosen 
JOIN tb_semester e ON a.id_semester=e.id_semester 
JOIN tb_kalender_akd f ON e.id_kalender_akd=f.id_kalender_akd 
JOIN tb_angkatan g ON f.id_angkatan = g.id_angkatan 
JOIN tb_kelas h ON a.id_kelas=h.id_kelas 
order by c.nama_mk   
";


$q = mysqli_query($cn,$s) or die("Tidak dapat mengakses data jadwal_kuliah.".mysqli_error($cn));
$jumlah_records = mysqli_num_rows($q);

$s .= " limit $limit_start,10 ";
$q = mysqli_query($cn,$s) or die("Tidak dapat melimit data jadwal_kuliah.".mysqli_error($cn));
$jumlah_records_limited = mysqli_num_rows($q);

if ($jumlah_records_limited>0) {
  $i=0+($cpage-1)*10;
  while ($d = mysqli_fetch_array($q)) {
    $i++;
    $id_jadwal_kuliah = $d['id_jadwal_kuliah'];
    $id_prodi = $d['id_prodi'];
    $id_mk = $d['id_mk'];
    $id_dosen = $d['id_dosen'];
    $nama_dosen = $d['nama_dosen'];
    $gelar_depan = $d['gelar_depan'];
    $gelar_belakang = $d['gelar_belakang'];

    if($gelar_depan!="")$gelar_depan.=".";
    $gelar_depan = str_replace("..", ".", $gelar_depan);
    $nama_dosen = "$gelar_depan $nama_dosen, $gelar_belakang";
    $nama_dosen = trim(ucwords(strtolower($nama_dosen)));

    $tanggal_awal_semester = $d['tanggal_awal_semester'];
    $tanggal_akhir_semester = $d['tanggal_akhir_semester'];
    $show_tanggal_awal_semester = date("d M y",strtotime($tanggal_awal_semester));
    $show_tanggal_akhir_semester = date("d M y",strtotime($tanggal_akhir_semester));

    $status_jadwal_kuliah = $d['status_jadwal_kuliah'];

    $singkatan_prodi = $d['singkatan_prodi'];
    $id_mk = $d['id_mk'];
    $kode_mk = $d['kode_mk'];
    $id_angkatan = $d['id_angkatan'];
    // $id_semester = $d['id_semester'];
    $no_semester = $d['no_semester'];
    $singkatan_kelas = $d['singkatan_kelas'];

    $nama_mk = $d['nama_mk'];
    $show_nama_mk = $nama_mk;
    if(strlen($nama_mk)>33) $show_nama_mk = substr($nama_mk, 0,30);

    $jenjang = $d['jenjang'];
    $nama_jenjang="Undefined";
    if($jenjang=="C")$nama_jenjang="D3";
    if($jenjang=="D")$nama_jenjang="D4";
    if($jenjang=="E")$nama_jenjang="S1";


    $ss = "SELECT 1 FROM tb_sesi_kuliah WHERE id_jadwal_kuliah='$id_jadwal_kuliah'";
    $qq = mysqli_query($cn,$ss) or die("Tidak bisa mengakses data sesi dg id_jadwal_kuliah: $id_jadwal_kuliah");
    $jumlah_sesi = mysqli_num_rows($qq);

    $hasil.= "
    <tr>
      <td class='tdcenter'>$i</td>
      <td class='tdcenter'>$nama_jenjang-$singkatan_prodi</td>
      <td class='tdcenter'>$id_angkatan</td>
      <td class='tdcenter'>$no_semester</td>
      <td class='tdcenter'>$singkatan_kelas</td>
      <td class='tdcenter'>
        <a href='?mkdet&id_mk=$id_mk'>$show_nama_mk</a>
      </td>
      <td class='tdcenter'>
        <a href='?sesikul&nama_mk=$kode_mk'>$jumlah_sesi</a> | <a href='?sesikul&aksi=tambah&id_jadwal_kuliah=$id_jadwal_kuliah'>Add</a>
      </td>
      <td class='tdcenter'>
        <a href='#' class='not_ready'>$nama_dosen</a> | <a href='#' class='not_ready'>Change</a>
      </td>
      <td class='tdcenter'>$show_tanggal_awal_semester</td>
      <td class='tdcenter'>$show_tanggal_akhir_semester</td>
      <td class='tdcenter'>$status_jadwal_kuliah</td>
      <td class='tdcenter'>
        <a href='?reject&id_calon=$id_jadwal_kuliah'><img src='img/icons/reject.png' width='20px'></a> 
        <a href='?delete&id_calon=$id_jadwal_kuliah'><img src='img/icons/delete.png' width='20px'></a> 
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