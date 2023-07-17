<?php
if(isset($cn)){
  $s = "SELECT id, jenjang,angkatan FROM tb_kalender";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  $rid_kalender = [];
  $rnama_kalender = [];
  while ($d=mysqli_fetch_assoc($q)) {
    $rid_kalender[$d['id']] = $d['id'];
    $rnama_kalender[$d['id']] = "Kalender $d['jenjang']-$d['angkatan']";
  }
}