<?php 
$msg = "Error @AJAX. Missing index field";
if (!isset($_GET['modul_sistem']))die("$msg #1");
if (!isset($_GET['fitur_sistem']))die("$msg #2");

$modul_sistem = $_GET['modul_sistem'];
$fitur_sistem = $_GET['fitur_sistem'];

if (!isset($_GET['cpage']))die("$msg #12");
$cpage = $_GET['cpage'];
if($cpage=='' or $cpage<=0) $cpage=1;
$limit_start = $cpage*10-10;

$sql_modul_admin = " modul_sistem like '%$modul_sistem%' ";
$sql_fitur_admin = " fitur_sistem like '%$fitur_sistem%' ";

if(trim($modul_sistem)=='') $sql_modul_admin = " 1 ";
if(trim($fitur_sistem)=='') $sql_fitur_admin = " 1 ";

include "../../config.php";

$s = "SELECT * FROM tb_admin_level WHERE admin_level>0 order by admin_level";
$q = mysqli_query($cn,$s) or die("Tidak dapat mengakses data admin_level.".mysqli_error($cn));
$i=0;
while ($d=mysqli_fetch_array($q)) {
  $admin_level = $d['admin_level'];
  $jenis_user = $d['jenis_user'];
  $radmin_level[$i] = $admin_level;
  $rjenis_user[$i] = $jenis_user;
  $i++;
}

// die(var_dump($radmin_level));

$s = "SELECT * FROM tb_fitur_sistem WHERE $sql_modul_admin 
and $sql_fitur_admin 
order by modul_sistem, fitur_sistem";
$q = mysqli_query($cn,$s) or die("Tidak dapat mengakses data fitur sistem.".mysqli_error($cn));
$jumlah_records = mysqli_num_rows($q);

$s .= " limit $limit_start,10 ";
$q = mysqli_query($cn,$s) or die("Tidak dapat melimit data mhs.".mysqli_error($cn));
$jumlah_records_limited = mysqli_num_rows($q);

$hasil="
<table class='table table-hover table-bordered table-striped'>
  <thead>
    <th>No</th>
    <th>Modul</th>
    <th>Fitur Sistem</th>
    <th>Keterangan</th>
    <th>Accessed By</th>
  </thead>
";

if ($jumlah_records_limited>0) {
  $i=0+($cpage-1)*10;
  while ($d = mysqli_fetch_array($q)) {
    $i++;
    $modul_sistem = $d['modul_sistem'];
    $fitur_sistem = $d['fitur_sistem'];
    $ket_fitur_sistem = $d['ket_fitur_sistem'];

    $accessed_by='';

    for ($j=0; $j < count($rjenis_user) ; $j++) { 
      $accessed_by.="<label><input type='checkbox'> $rjenis_user[$j]</label><br>";
    }

    $hasil.= "
    <tr>
      <td class='tdcenter'>$i</td>
      <td>$modul_sistem</td>
      <td>$fitur_sistem</td>
      <td>$ket_fitur_sistem</td>
      <td>$accessed_by</td>
    </tr>
    ";

  }
}else{
  $hasil .= "<tr><td colspan=9 class='merah tengah'><h4>No Data Available.</h4> <hr>$s</td></tr>";
}


$hasil.="</table>";

echo "1__$jumlah_records"."__$hasil";
?>