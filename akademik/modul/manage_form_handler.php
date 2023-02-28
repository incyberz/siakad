<?php
if(isset($_POST['btn_update']) || isset($_POST['btn_hapus']) || isset($_POST['btn_tambah'])){
  $tabel = $_POST['tabel'];
  $kolom_acuan = $_POST['kolom_acuan'];
  $id = $_POST['id'];
  $aksi = 'unknown';
  if(isset($_POST['btn_hapus'])){
    $aksi = 'hapus';
    $s = "DELETE from tb_$tabel WHERE $kolom_acuan = '$id'";
  }elseif(isset($_POST['btn_update'])){
    $aksi = 'update';
    die('POST update ready.');
  }elseif(isset($_POST['btn_tambah'])){
    $aksi = 'tambah';

    $s = "INSERT INTO tb_$tabel 
    () VALUES 
    ()
    ";

    echo "<pre>";
    echo "$s<hr>";
    var_dump($_POST);
    echo "</pre>";
    die();

  }else{
    die('POST handler tanpa tombol aksi.');
  }

  // $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  die("<div class='alert alert-success'>Proses $aksi berhasil.<hr><a href='?manage&p=$tabel' class='upper'>Back to List $tabel</a></div>");
}
