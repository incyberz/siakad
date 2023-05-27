<header id="header">
  <div class="d-flex flex-column">

    <div class="profile">
      <img src="<?=$img_profile ?>" alt="" class="img-fluid rounded-circle" id="img_user_profile">
      <!-- <h1 class="text-light">
        <a href="?edit" id="nama_mhs">
          <?=$nama_mhs?> 
          <img id="img_edit_civitas" src="assets/img/icons/edit.png" width="20px" style="margin-bottom: 5px">
        </a>
      </h1> -->
      <!-- <p style="color: white; text-align: center;font-size: small"><?=$nama_kec ?> - <?=$nama_kab ?></p> -->
      <!-- <div class="social-links mt-3 text-center">
        <?php 
        if ($no_wa_mhs!="") echo "<a href='$link_wa' class='whatsapp' target='_blank'><i class='bx bxl-whatsapp'></i></a> ";  
        if ($link_twitter!="") echo "<a href='$link_twitter' class='twitter' target='_blank'><i class='bx bxl-twitter'></i></a> ";  
        if ($link_facebook!="") echo "<a href='$link_facebook' class='facebook' target='_blank'><i class='bx bxl-facebook'></i></a> ";
        if ($link_instagam!="") echo "<a href='$link_instagam' class='instagram' target='_blank'><i class='bx bxl-instagram'></i></a> ";
        if ($link_linkedin!="") echo "<a href='$link_linkedin' class='linkedin' target='_blank'><i class='bx bxl-linkedin'></i></a> ";  
        ?>
        
        
        
        
      </div> -->
    </div>
    <div class='text-light'><span id=nama_mhs><?=$nama_mhs?></span></div>
    <div class='text-light'>
      <small>
        NIM: <span id=nim><?=$nim?></span>
        <span class=debug>id_mhs: <span id=id_mhs><?=$id_mhs?></span></span>
      </small>
    </div>

    <?php include "nav.php"; ?>
    <button type="button" class="mobile-nav-toggle d-xl-none"><i class="icofont-navigation-menu"></i></button>

  </div>
</header>