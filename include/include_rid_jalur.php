<?php
if(isset($cn)){
  $s = "SELECT id, nama,singkatan FROM tb_jalur";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  $rid_jalur = [];
  $rjalur = [];
  $rnama_jalur = [];
  while ($d=mysqli_fetch_assoc($q)) {
    array_push($rid_jalur,$d['id']);
    $rjalur[$d['id']] = $d['singkatan'];
    $rnama_jalur[$d['id']] = $d['nama'];
  }
}