<?php
if(isset($cn)){
  $s = "SELECT angkatan FROM tb_angkatan";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  $rangkatan = [];
  while ($d=mysqli_fetch_assoc($q)) {
    array_push($rangkatan,$d['angkatan']);
  }
}