<?php
if(isset($cn)){
  $s = "SELECT id, nama,singkatan FROM tb_prodi";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  $rprodi = [];
  $rnama_prodi = [];
  while ($d=mysqli_fetch_assoc($q)) {
    $rprodi[$d['id']] = $d['singkatan'];
    $rnama_prodi[$d['id']] = $d['nama'];
  }
}