<?php
if(isset($cn)){
  $s = "SELECT shift FROM tb_shift";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  $rshift = [];
  while ($d=mysqli_fetch_assoc($q)) {
    array_push($rshift,$d['shift']);
  }
}