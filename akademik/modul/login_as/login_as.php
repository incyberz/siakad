<h1>Login As Mahasiswa</h1>
<?php
$id_mhs = isset($_GET['id_mhs']) ? $_GET['id_mhs'] : '';

if($id_mhs==''){
  include 'modul/mhs/list_mhs.php';
  exit;
}