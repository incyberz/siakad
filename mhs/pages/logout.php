<?php
if(isset($_GET['logout'])){
  session_unset();
  echo '<script>location.replace("?")</script>';
  exit;
}
