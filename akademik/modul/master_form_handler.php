<?php
if(isset($_POST['btn_update']) || isset($_POST['btn_hapus']) || isset($_POST['btn_tambah'])){
  $tabel = $_POST['tabel'];
  $kolom_acuan = $_POST['kolom_acuan'];
  $id = $_POST['id'];
  $aksi = 'unknown';
  if(isset($_POST['btn_hapus'])){
    $aksi = 'hapus';
    $s = "DELETE FROM tb_$tabel WHERE $kolom_acuan = '$id'";
  }elseif(isset($_POST['btn_tambah']) || isset($_POST['btn_update'])){
    $aksi = isset($_POST['btn_tambah']) ? 'tambah' : 'update';

    $koloms = '__';
    $isis = '__';
    $sets = '__';
    foreach($_POST as $a=>$x){
      if($a=='tabel' || $a=='kolom_acuan' || $a=='id' || $a=='btn_tambah' || $a=='btn_update') continue;
      $isi = $x=='' ? 'NULL' : "'$x'";
      $koloms .= ",$a";
      $isis .= ",$isi";
      $sets .= ",$a=$isi";
    }
    $koloms = str_replace('__,','',$koloms);
    $isis = str_replace('__,','',$isis);
    $sets = str_replace('__,','',$sets);

    $s = $aksi=='tambah' 
    ? "INSERT INTO tb_$tabel ($koloms) VALUES ($isis)"
    : "UPDATE tb_$tabel SET $sets WHERE $kolom_acuan = '$id'";
    ;

    $q = mysqli_query($cn, $s)or die(mysqli_error($cn));

    $pesan = $aksi=='tambah'
    ? "<script>location.replace('?master&p=$tabel&pesan=Data $tabel baru berhasil ditambahkan')</script>"
    : "<script>location.replace('?master&p=$tabel&pesan=Update data $tabel berhasil.')</script>"
    ;
    die($pesan);

  }else{
    die('POST handler tanpa tombol aksi.');
  }

  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  die("<div class='alert alert-success'>Proses $aksi berhasil.<hr><a href='?master&p=$tabel' class='upper'>Back to List $tabel</a></div>");
}
