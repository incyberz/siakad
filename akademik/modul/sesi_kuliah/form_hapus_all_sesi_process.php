<?php
if(isset($_POST['btn_hapus_all_sesi'])){
  $id_jadwal = $_POST['id_jadwal'];

  $s = "DELETE FROM tb_sesi_kuliah WHERE id_jadwal=$id_jadwal";
    // die($s);
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));

  echo div_alert('info',"Menghapus semua sesi kuliah berhasil.<hr><a href='?manage_sesi&id_jadwal=$id_jadwal'>Lanjutkan Proses</a>");
  exit;
  
}
