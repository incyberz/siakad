<?php 
$s = "SELECT jenjang,jumlah_semester FROM tb_jenjang ";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$rjenjang = [];
$rjumlah_semester = [];
while ($d=mysqli_fetch_assoc($q)) {
  array_push($rjenjang,$d['jenjang']);
  $rjumlah_semester[$d['jenjang']] = $d['jumlah_semester'];
}

foreach ($rjenjang as $key => $jenjang) {
  $jumlah_jenjang[$jenjang]=0;
}

$s = "SELECT jenjang FROM tb_prodi";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
while ($d=mysqli_fetch_assoc($q)) {
  $jumlah_jenjang[$d['jenjang']]++;
}
?>