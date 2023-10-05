<section id="rank_mhs" class="about">
  <div class="container">

    <?php
    if(isset($_POST['btn_upload'])){
      echo "Sedang proses uploading... jangan ditutup atau di-close browser!<hr>";
      // echo '<pre>';
      // var_dump($_FILES);
      // echo '</pre>';

      $folder = "uploads/$folder_uploads";
      if(!file_exists($folder)) mkdir($folder);
      if(move_uploaded_file($_FILES['file_upload']['tmp_name'],"$folder/$nim-$_POST[file].jpg")){
        if($_POST['file']=='ktp'){
          $s = "INSERT INTO tb_biodata (nim, id_semester, id_semester_upload_ktp) VALUES ('$nim',$id_semester,$id_semester) ON DUPLICATE KEY UPDATE id_semester_upload_ktp = $id_semester ";
          $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
        }

        echo div_alert('success','Upload sukses.');
      }else{
        die(div_alert('danger','Upload gagal.'));
      }
      ;

      exit;
    }

    $file = $_GET['file'] ?? die(erid('file'));


    ?>
    <div class="section-title">
      <h2>Upload Persyaratan</h2>
      <p>Silahkan upload file persyaratan Anda:</p>
    </div>

    <div class="wadah" data-aos="fade-right">
      <p>File yang harus Anda upload : <b><u><?=$file?></u></b></p>
      <form method=post enctype='multipart/form-data'>
        <input type="hidden" name=file id=file value='<?=$file?>'>
        <input type="file" name=file_upload id=file_upload accept='.jpg'>
        <div class="kecil miring abu mt1 mb1">Format gambar: JPG</div>

        <div class='mt-2'>
          <button class='btn btn-primary btn-block' name=btn_upload>Upload</button>
        </div>
      </form>
    </div>

  </div>
</section>