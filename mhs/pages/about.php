<section id="about" class="about">
  <div class="container">

    <div class="section-title">
      <h2>About</h2>
      <p><?=$about_intro ?></p>
    </div>

    <div class="row">
      <div class="col-lg-4" data-aos="fade-right">
        <img src="<?=$img_profile ?>" class="img-fluid" alt="">
      </div>
      <div class="col-lg-8 pt-4 pt-lg-0 content" data-aos="fade-left">
        <h3><?=$about_header ?></h3>
        <p class="font-italic">
          <?=$about_subheader ?>
        </p>
        <div class="row">
          <div class="col-lg-6">
            <ul>
              <li><i class="icofont-rounded-right"></i> <strong>Tempat Lahir:</strong> <?=$tempat_lahir_mhs ?></li>
              <li><i class="icofont-rounded-right"></i> <strong>Tanggal Lahir:</strong> <?=$tanggal_lahir_mhs ?></li>
              <li><i class="icofont-rounded-right"></i> <strong>Status:</strong> <?=$status_pernikahan ?></li>
              <li><i class="icofont-rounded-right"></i> <strong>Jumlah anak:</strong> <?=$jumlah_anak ?></li>
            </ul>
          </div>
          <div class="col-lg-6">
            <ul>
              <li><i class="icofont-rounded-right"></i> <strong>Pendidikan:</strong> <?=$pendidikan_mhs ?></li>
              <li><i class="icofont-rounded-right"></i> <strong>Lulusan:</strong> <?=$lulusan_mhs ?></li>
              <li><i class="icofont-rounded-right"></i> <strong>Jabatan:</strong> <?=$jabatan_mhs ?></li>
              <li><i class="icofont-rounded-right"></i> <strong>Divisi:</strong> <?=$divisi_mhs ?></li>
            </ul>
          </div>
        </div>
        <p>
          <?=$about_details ?>
        </p>
      </div>
    </div>

  </div>
</section>