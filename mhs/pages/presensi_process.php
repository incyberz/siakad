<section>
  <div class="container">
    <?php

    if(isset($_POST['btn_check_in'])){
      $id_sesi = $_POST['btn_check_in'];
      $id = $id_mhs."-$id_sesi";
      $s = "INSERT INTO tb_presensi (id,id_mhs,id_sesi) VALUES ('$id','$id_mhs','$id_sesi') ON DUPLICATE KEY UPDATE timestamp_masuk=CURRENT_TIMESTAMP";
      $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
      echo div_alert('success','Check-In sukses.');
    }

    if(isset($_POST['btn_check_out'])){
      $id_presensi = $_POST['btn_check_out'];
      if($id_presensi=='') die(erid('id_presensi'));
      $s = "UPDATE tb_presensi SET timestamp_keluar=CURRENT_TIMESTAMP WHERE id='$id_presensi'";
      $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
      echo div_alert('success','Check-Out sukses.');
    }

    // echo '<script>location.replace("?jadwal")</script>';
    exit;
    ?>
  </div>
</section>