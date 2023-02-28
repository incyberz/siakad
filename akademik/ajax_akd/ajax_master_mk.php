<?php 
$debug_mode = 0;
$msg = "Error @AJAX. Missing index field";
if (!isset($_GET['id_kurikulum']))die("$msg #1");
if (!isset($_GET['id_bk']))die("$msg #2");
if (!isset($_GET['id_konsentrasi']))die("$msg #3");
if (!isset($_GET['jenis_mk']))die("$msg #4");
if (!isset($_GET['no_semester']))die("$msg #5");
if (!isset($_GET['nama_mk']))die("$msg #6");
if (!isset($_GET['cpage']))die("$msg #7");
if (!isset($_GET['is_mk_suspend']))die("$msg #9");

$id_kurikulum = $_GET['id_kurikulum'];
$id_bk = $_GET['id_bk'];
$id_konsentrasi = $_GET['id_konsentrasi'];
$jenis_mk = $_GET['jenis_mk'];
$no_semester = $_GET['no_semester'];
$nama_mk = $_GET['nama_mk'];
$is_mk_suspend = $_GET['is_mk_suspend'];

$sql_id_bk = " a.id_bk=$id_bk ";
$sql_id_konsentrasi = " a.id_konsentrasi=$id_konsentrasi ";
$sql_jenis_mk = " a.jenis_mk='$jenis_mk' ";
$sql_no_semester = " a.no_semester='$no_semester' ";
$sql_nama_mk = " (a.nama_mk like '%$nama_mk%' or a.kode_mk like '%$nama_mk%') ";
$sql_suspend = " (a.status_mk=1) ";

if($id_bk=="all") $sql_id_bk = " 1 ";
if($id_bk=="none") $sql_id_bk = " a.id_bk is null ";
if($id_konsentrasi=="all") $sql_id_konsentrasi = " 1 ";
if($id_konsentrasi=="none") $sql_id_konsentrasi = " a.id_konsentrasi is null ";
if($jenis_mk=="all") $sql_jenis_mk = " 1 ";
if($no_semester=="all") $sql_no_semester = " 1 ";
if(trim($nama_mk)=="") $sql_nama_mk = " 1 ";
if($is_mk_suspend=="true") $sql_suspend = " (a.status_mk=1 or a.status_mk=0) ";

$cpage = $_GET['cpage'];
if($cpage=="" or $cpage<=0) $cpage=1;
$limit_start = $cpage*10-10;


include "../../config.php";



$hasil="
<table class='table table-hover table-bordered table-striped'>
  <thead>
    <th>No</th>
    <th>Kurikulum</th>
    <th>Bidang Keahlian</th>
    <th>Konsentrasi</th>
    <th>Jenis</th>
    <th>Smt</th>
    <th>Kode MK</th>
    <th>Nama MK</th>
    <th>Status</th>
    <th>Aksi</th>
  </thead>
";

$s = "SELECT 
a.*,
b.singkatan_prodi,
c.id_kurikulum,  
c.nama_kurikulum  

FROM tb_mk a 
JOIN tb_prodi b ON a.id_prodi=b.id_prodi 
JOIN tb_kurikulum c ON a.id_kurikulum=c.id_kurikulum 

WHERE c.id_kurikulum = $id_kurikulum 
AND $sql_id_bk 
AND $sql_id_konsentrasi 
AND $sql_jenis_mk 
AND $sql_no_semester 
AND $sql_nama_mk 
AND $sql_suspend 
order by c.nama_kurikulum, a.no_semester, a.nama_mk  
";


$q = mysqli_query($cn,$s) or die("Tidak dapat mengakses data mk. zzz: $s, ".mysqli_error($cn));
$jumlah_records = mysqli_num_rows($q);

$s .= " limit $limit_start,10 ";
$q = mysqli_query($cn,$s) or die("Tidak dapat melimit data mk.".mysqli_error($cn));
$jumlah_records_limited = mysqli_num_rows($q);

if ($jumlah_records_limited>0) {
  $i=0+($cpage-1)*10;
  while ($d = mysqli_fetch_array($q)) {
    $i++;
    $id_mk = $d['id_mk'];
    $id_prodi = $d['id_prodi'];
    $singkatan_prodi = $d['singkatan_prodi'];
    $id_kurikulum = $d['id_kurikulum'];
    $nama_kurikulum = $d['nama_kurikulum'];
    $kode_mk = $d['kode_mk'];
    $status_mk = $d['status_mk'];
    $no_semester = $d['no_semester'];

    # ======================================================================
    # JENIS MK
    $jenis_mk = $d['jenis_mk'];
    $jenis_mk_link="... | <a href='#' style='color:red'>Set</a>";
    if($jenis_mk!=""){
      $jenis_mk_link = "<a href='#'>$jenis_mk</a>";
    }


    # ======================================================================
    # BIDANG KEAHLIAN
    $id_bk = $d['id_bk'];
    $nama_bk_link="... | <a href='#' style='color:red'>Set</a>";
    if($id_bk!=""){
      $ss = "SELECT nama_bk from tb_bk where id_bk=$id_bk";
      $qq = mysqli_query($cn,$ss) or die("Tidak dapat mengakses data bidang keahlian, id: $id_bk zzzSQL: $ss");
      $dd = mysqli_fetch_assoc($qq);
      $nama_bk = $dd['nama_bk'];
      $nama_bk_link = "<a href='#'>$nama_bk</a>";
    }

    # ======================================================================
    # KONSENTRASI
    $id_konsentrasi = $d['id_konsentrasi'];
    $nama_konsentrasi_link="... | <a href='#' style='color:red'>Set</a>";
    if($id_konsentrasi!=""){
      $ss = "SELECT nama_konsentrasi from tb_konsentrasi where id_konsentrasi=$id_konsentrasi";
      $qq = mysqli_query($cn,$ss) or die("Tidak dapat mengakses data konsentrasi prodi, id: $id_konsentrasi zzzSQL: $ss".mysqli_error($cn));
      $dd = mysqli_fetch_assoc($qq);
      $nama_konsentrasi = $dd['nama_konsentrasi'];
      $nama_konsentrasi_link = "<a href='#'>$nama_konsentrasi</a>";
    }

    # ======================================================================
    # KONSENTRASI
    $nama_mk = strtoupper($d['nama_mk']);
    $show_nama_mk = $nama_mk;
    if(strlen($nama_mk)>33) $show_nama_mk = substr($nama_mk, 0, 30)."..."; 


    $hasil.= "
    <tr>
      <td class='tdcenter'>$i</td>
      <td class='tdcenter'><a href='#'>$nama_kurikulum</a></td>
      <td class='tdcenter'>$nama_bk_link</td>
      <td class='tdcenter'>$nama_konsentrasi_link</td>
      <td class='tdcenter'>$jenis_mk_link</td>
      <td class='tdcenter'>$no_semester</td>
      <td class='tdcenter'>$kode_mk</td>
      <td class='' style='padding-left:10px'>
        <a href='?mkdet&id_mk=$id_mk'>
          $show_nama_mk
        </a>
      </td>
      <td class='tdcenter'>$status_mk</td>
      <td class='tdcenter'>
        <a href='?reject&id_calon=$id_mk'><img src='img/icons/reject.png' width='20px'></a> 
        <a href='?delete&id_calon=$id_mk'><img src='img/icons/delete.png' width='20px'></a> 
      </td>
    </tr>
    ";
  }
}else{
  $hasil .= "<tr><td colspan=9 style='color:darkred; text-align:center'><h3>No Data Available.</h3></td></tr>";
}


$hasil.="</table>";

echo "1__$jumlah_records"."__$s $hasil";
?>