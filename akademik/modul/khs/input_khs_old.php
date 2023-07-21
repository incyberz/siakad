<h1>Input KHS</h1>
<?php
$kelas = isset($_GET['kelas']) ? $_GET['kelas'] : '';
if(!$kelas){
  include 'list_kelas_angkatan_for_input_khs.php';
  exit;
}

echo 'This page ready to code.';