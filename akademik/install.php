<?php
include 'include/rmaster.php';

$s = 'SELECT ';
for ($i=0; $i < count($rmaster); $i++) { 
  $s.= "(SELECT count(1) FROM tb_$rmaster[$i]) as jumlah_$rmaster[$i],";
}
$s.='1';

$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
$d = mysqli_fetch_assoc($q);

$li_master = '';
for ($i=0; $i < count($rmaster); $i++) {
  $wajib = ucwords(strtolower($rmaster[$i]));
  $jumlah = $d['jumlah_'.$rmaster[$i]];
  $li = $jumlah ? 
  "<li class='ok'>Jumlah $wajib: $jumlah ... OK</li>" : 
  "<li class='not_ok'>Jumlah $wajib: $jumlah ".go("Manage $wajib")."</li>";
  $li_master .= $li; 
}


$rpublish = [
'mk',
'kurikulum'
];

$s = 'SELECT ';
for ($i=0; $i < count($rpublish); $i++) { 
  $s.= "(SELECT count(1) FROM tb_$rpublish[$i] WHERE is_publish=1) as publish_$rpublish[$i],";
}
$s.='1';

$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
$d = mysqli_fetch_assoc($q);

$li_publish = '';
for ($i=0; $i < count($rpublish); $i++) {
  $wajib = ucwords(strtolower($rpublish[$i]));
  $publish = $d['publish_'.$rpublish[$i]];
  $li = $publish ? 
  "<li class='ok'>Jumlah $wajib: $publish ... OK</li>" : 
  "<li class='not_ok'>Jumlah $wajib: $publish ".go("Publish $wajib")."</li>";
  $li_publish .= $li; 
}

?>

<style>
  .ceklis li{
    margin-bottom: 7px;
    font-family: consolas;
    color: black;
    font-weight: bold;
    font-size: 16px;
  }
  .not_ok {color: red !important}
  .ok {color: green !important}
</style>
<h1>Instalasi SIAKAD</h1>
<h2>Database:</h2>
<ol class=ceklis>
  <li class=''>Database name: <?=$db_name?> ... OK</li>
</ol>
<hr>

<h2>Data Master:</h2>
<ol class=ceklis>
  <?=$li_master?>
</ol>

<h2>Publishing:</h2>
<ol class=ceklis>
  <?=$li_publish?>
</ol>