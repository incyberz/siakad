<?php
if(isset($cn)){
  $s = "SELECT id, nama,singkatan FROM tb_prodi";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  $rid_prodi = [];
  $rprodi = [];
  $rnama_prodi = [];
  while ($d=mysqli_fetch_assoc($q)) {
    array_push($rid_prodi,$d['id']);
    $rprodi[$d['id']] = $d['singkatan'];
    $rnama_prodi[$d['id']] = $d['nama'];
  }
}