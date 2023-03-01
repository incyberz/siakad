<?php
if(isset($_GET['pesan'])){
  $pesan = $_GET['pesan'];
  echo "<div class='alert alert-info'>$pesan</div>";
}