<?php
if(isset($cn)){
  $s = "SELECT id, nama,singkatan FROM tb_jalur";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  $rid_jalur = [];
  $rjalur = [];
  $rnama_jalur = [];
  $rwarna = ['#F1B327','#C544C3','#5470C6','#73C0DE','#6BAC4C'];
  #           MI        KA        TI        RPL        SI
  $i=0;
  while ($d=mysqli_fetch_assoc($q)) {
    array_push($rid_jalur,$d['id']);
    $rjalur[$d['id']] = $d['singkatan'];
    $rnama_jalur[$d['id']] = $d['nama'];
    $rwarna_jalur[$d['id']] = $rwarna[$i];
    $i++;
  }
}