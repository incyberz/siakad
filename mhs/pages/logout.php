<?php
if(isset($_GET['logout'])){
  // session_unset();
  unset($_SESSION['siakad_mhs']);
  echo '<script>location.replace("?")</script>';
  exit;
}
