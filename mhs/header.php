<style>.foto_profil{box-shadow: 0 0 19px gray}</style>
<header id="header">
  <div class="d-flex flex-column">

    <div class="profile">
      <a href="?upload_profile" onclick='return confirm("Ingin mengubah Profil?")'>
        <img src="<?=$img_profile ?>" class="foto_profil " id="img_user_profile" style='width:120px;height:120px'>
      </a>
     
      <?php 
      // if ($no_wa_mhs!="") echo "<a href='$link_wa' class='whatsapp' target='_blank'><i class='bx bxl-whatsapp'></i></a> ";  
      // if ($link_twitter!="") echo "<a href='$link_twitter' class='twitter' target='_blank'><i class='bx bxl-twitter'></i></a> ";  
      // if ($link_facebook!="") echo "<a href='$link_facebook' class='facebook' target='_blank'><i class='bx bxl-facebook'></i></a> ";
      // if ($link_instagam!="") echo "<a href='$link_instagam' class='instagram' target='_blank'><i class='bx bxl-instagram'></i></a> ";
      // if ($link_linkedin!="") echo "<a href='$link_linkedin' class='linkedin' target='_blank'><i class='bx bxl-linkedin'></i></a> ";  
      ?>
      
    </div>
    <div class='text-light tengah'>
        <div id=nama_mhs>
          <a href="?about" onclick='return confirm("Menuju Page About?")'>
            <span style='color:#ff0'><?=$nama_mhs?></span>
          </a>
        </div>
        <div class=small>
          <span id=nim><?=$nim?></span> |  <?=$kelas_ta?>
        </div>
    </div>
    
    <?php include "nav.php"; ?>
    <span class=debug>id_mhs: <span id=id_mhs><?=$id_mhs?></span></span>
    <button type="button" class="mobile-nav-toggle d-xl-none"><i class="icofont-navigation-menu"></i></button>

  </div>
</header>