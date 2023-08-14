<?php
if(isset($cn)){
  $s = "SELECT id, nama,singkatan,jenjang FROM tb_prodi";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  $rid_prodi = [];
  $rprodi = [];
  $rnama_prodi = [];
  $rjenjang_prodi = [];
  $rwarna = ['#F1B327','#C544C3','#5470C6','#73C0DE','#6BAC4C'];
  #           MI        KA        TI        RPL        SI
  $i=0;
  while ($d=mysqli_fetch_assoc($q)) {
    array_push($rid_prodi,$d['id']);
    $rprodi[$d['id']] = $d['singkatan'];
    $rnama_prodi[$d['id']] = $d['nama'];
    $rjenjang_prodi[$d['id']] = $d['jenjang'];
    $rwarna_prodi[$d['id']] = $rwarna[$i];
    $i++;
  }
}