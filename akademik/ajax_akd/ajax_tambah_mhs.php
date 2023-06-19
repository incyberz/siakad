<?php 
$dm = 0;
$msg = "Error @AJAX. Missing index field";
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
    <th>Angkatan</th>
    <th>Jalur Daftar</th>
    <th>Prodi</th>
    <th>NIM</th>
    <th>Nama Mahasiswa</th>
    <th>IPK</th>
    <th>Status</th>
    <th>Aksi</th>
  </thead>
";

$s = "SELECT 
a.*,
b.singkatan_prodi,
c.ket_status_mhs 

FROM tb_mhs a 
JOIN tb_prodi b ON a.id_prodi=b.id_prodi 
JOIN tb_status_mhs c ON a.status_mhs=c.status_mhs  

WHERE id_angkatan = $get_id_angkatan   
order by a.nama_mhs  
";


$q = mysqli_query($cn,$s) or die("Tidak dapat mengakses data mhs.".mysqli_error($cn));
$jumlah_records = mysqli_num_rows($q);

$s .= " limit $limit_start,10 ";
$q = mysqli_query($cn,$s) or die("Tidak dapat melimit data mhs.".mysqli_error($cn));
$jumlah_records_limited = mysqli_num_rows($q);

if ($jumlah_records_limited>0) {
  $i=0+($cpage-1)*10;
  while ($d = mysqli_fetch_array($q)) {
    $i++;
    $id_mhs = $d['id_mhs'];
    $id_angkatan = $d['id_angkatan'];
    $id_daftar = $d['id_daftar'];
    $id_biaya = $d['id_biaya'];
    $id_prodi = $d['id_prodi'];
    $id_jalur = $d['id_jalur'];
    $nim = $d['nim'];
    $nama_mhs = $d['nama_mhs'];
    $status_mhs = $d['status_mhs'];
    $ipk_terakhir = $d['ipk_terakhir'];
    $jenis_tinggal = $d['jenis_tinggal'];
    $jenis_kendaraan = $d['jenis_kendaraan'];
    $no_wa_mhs = $d['no_wa_mhs'];
    $email_mhs = $d['email_mhs'];
    $singkatan_prodi = $d['singkatan_prodi'];
    $ket_status_mhs = $d['ket_status_mhs'];

    $nama_mhs = ucwords(strtolower($nama_mhs));

    $img_wa = "wa";
    $link_wa = "https://api.whatsapp.com/send?phone=62$no_wa_mhs&text=Selamat $waktu $nama_mhs";
    $link_email = "mailto:$email_mhs?subject=INFO%20PMB%20IKMI&body=Selamat $waktu $nama_mhs";


    $hasil.= "
    <tr>
      <td class='tdcenter'>$i</td>
      <td class='tdcenter'>$id_angkatan</td>
      <td class='tdcenter'>$id_jalur</td>
      <td class='tdcenter'>$singkatan_prodi</td>
      <td class='tdcenter'>$nim</td>
      <td class='' style='padding-left:10px'>
        <a href='?pmbdetail&id_calon=$id_mhs'>
          $nama_mhs
        </a>
      </td>
      <td class='tdcenter'>$ipk_terakhir</td>
      <td class='tdcenter'>$ket_status_mhs</td>
      <td class='tdcenter'>
        <a href='presensi_calon.php?email=$email_mhs&nama_calon=$nama_mhs' target='_blank'><img src='img/icons/presensi.png' width='18px'></a> 
        <a href='?resetpaswd&id_calon=$id_mhs'><img src='img/icons/set_pass_sm.png' width='22px'></a> 
        <a href='$link_email' target='_blank'><img src='img/icons/mail.png' width='20px'></a> 
        <a href='$link_wa' target='_blank'><img src='img/icons/$img_wa.png' width='20px'></a> 
        <a href='?reject&id_calon=$id_mhs'><img src='img/icons/reject.png' width='20px'></a> 
        <a href='?delete&id_calon=$id_mhs'><img src='img/icons/delete.png' width='20px'></a> 
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