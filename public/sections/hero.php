<?php 
if($is_login){
  $text_welcome = "Halo $nama_user You are login as <span class='biru tebal'>$login_as</span> !";
  $text_welcome2 = "<a href='#fitur' class='btn-get-started scrollto' id='btn_goto_fitur'>Access Features</a>";
  $hide_hero = 'none';
}else{
  $text_welcome = "Please Login to access sub-features !";
  $text_welcome2 = "<a href='#' class='btn-get-started scrollto' id='btn_login_hero'>Login</a>";
  $hide_hero = '';
}
?>

<div style="display: <?=$hide_hero ?> ">
  <section id="hero" class="d-flex align-items-center">
    
    <div class="container">
      <div class="row">
        <div class="col-lg-6 pt-5 pt-lg-0 order-2 order-lg-1 d-flex flex-column justify-content-center">
          
          <?php include "public/login/login_process.php"; ?>

        </div>
        <div class="col-lg-6 order-1 order-lg-2 hero-img" data-aos="fade-left" data-aos-delay="200">
          <img src="assets/img/hero-img.png" class="img-fluid animated">
        </div>
      </div>
    </div>

  </section>  
</div>
