<section id="about" class="about">
  <div class="container">

    <div class="section-title">
      <h2>Upload Profile</h2>
      <p>Silahkan Anda upload Foto Profile Anda!</p>
    </div>

    <?php
    $fuploads = "uploads/$folder_uploads"; if(!file_exists($fuploads)) mkdir($fuploads);
    if(isset($_POST['btn_upload'])){
      $target = "$fuploads/profil-$nim.jpg";
      
      if(move_uploaded_file($_FILES['profil']['tmp_name'],$target)){
        echo div_alert('success',"Upload profil berhasil.");
      }else{
        echo div_alert('danger',"Upload gagal.");
      }
      
      echo '<hr><a class="btn btn-primary" href="?about">Kembali ke About Page</a>';

      exit;
    }
    
    $src_formal_profil = "$fuploads/profile_formal.jpg";
    $punya_profil = file_exists($src_formal_profil);
    $src_formal_profil = $punya_profil ? $src_formal_profil : $profile_na;

    $alert = $punya_profil ? 'Foto ini akan menjadi profil kamu.' : 'Kamu belum upload profile.';





    ?>
    <div class="row">
      <div class="col-lg-6 darkblue">
        <div class="wadah" data-aos="fade-up" data-aos-delay="450">
          <h4>Foto Profile</h4>
          <p>Foto profil berikut akan ditampilkan ke dosen dan untuk kebutuhan dokumentasi lainnya.</p>
          <div class="text-center">
            <img onclick='alert("<?=$alert?>")' class='foto_profil' src='<?=$src_formal_profil?>'>
          </div>
          <form method=post enctype='multipart/form-data'>
            <div class="mb-2 mt-2">
              <input accept='.jpg' class='form-control' type="file" name="profil" required>
            </div>
            <button class='btn btn-info btn-block' name=btn_upload>Upload</button>
            <div class="kecil miring abu mt-2">)* wajib foto formal dg kemeja, background polos, selayak foto sertifikat</div>
          </form>
        </div>
      </div>
    </div>    


  </div>
</section>