<?php 
# ========================================================
# AKADEMIK INDEX
# ========================================================
session_start();
include "../config.php";
$insho_styles = '../../insho_styles.php';
if(file_exists($insho_styles)){

}

include $insho_styles;

if(1){
  $cusername = "insho";
  $cnama_pegawai = "Iin Sholihin";
  $cjenis_user = "Staf Akademik";
  $cadmin_level = 6;
  $is_login = 1;
  $img_pegawai = "img/pegawai/admin.jpg";
}



# ========================================================
# SIAKAD RULE
# ========================================================
$is_ready = 0;
// include 'install.php';



# ========================================================
# ROUTING < AFTER INSTALL READY
# ========================================================
include 'routing_akd.php';











?>







<!-- ========================================================================== -->
<!-- HTML BEGIN -->
<!-- ========================================================================== -->
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="PMB Admin STMIK IKMI Cirebon">
  <meta name="author" content="Iin Sholihin">
  <meta name="keyword" content="SIAKAD, Sistem Informasi, akademik, Dashboard, Admin, STMIK, IKMI, Cirebon, Pendaftaran, Kuliah">
  <link rel="shortcut icon" href="img/icons/favicon.png">

  <title>SIAKAD Admin</title>


  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="css/bootstrap-theme.css" rel="stylesheet">
  <link href="css/elegant-icons-style.css" rel="stylesheet" />
  <link href="css/font-awesome.min.css" rel="stylesheet" />
  <link href="css/owl.carousel.css" rel="stylesheet" type="text/css">
  <link href="css/style.css" rel="stylesheet">
  <link href="css/style-responsive.css" rel="stylesheet" />

  <script src="js/jquery-1.8.3.min.js"></script>

  <link href="css/admin_siakad.css" rel="stylesheet">
  <link href="css/akd.css" rel="stylesheet">
</head>

<body>




  <script type="text/javascript">
    var i = 0;
    var j = setInterval(function(){ 
      // alert("Hello "+i);

      i++;
      if(i==3) clearInterval(j); 
    }, 5000);
  </script>






  <!-- =================================================================== -->
  <!-- HTML BODY BEGIN -->
  <!-- =================================================================== -->
  <section id="container" class="">

    <?php include "header.php"; ?>
    <?php include "sidebar.php"; ?>

    <section id="main-content">
      <section class="wrapper">
        <?php if (file_exists($konten)) {include $konten;}else{include "na.php";} ?>
      </section>

      <div class="text-right hideit">
        <div class="credits" style="color: #eeeeee">
          Designed by <a href="https://bootstrapmade.com/" style="color: #eee">BootstrapMade</a>
        </div>
      </div>
    </section>
  </section>

  <!-- javascripts -->
  <script src="js/jquery-ui-1.10.4.min.js"></script>
  <script type="text/javascript" src="js/jquery-ui-1.9.2.custom.min.js"></script>
  <!-- bootstrap -->
  <script src="js/bootstrap.min.js"></script>
  <!-- nice scroll -->
  <script src="js/jquery.scrollTo.min.js"></script>
  <script src="js/jquery.nicescroll.js" type="text/javascript"></script>

  <script src="js/scripts.js"></script>
</body>
</html>

<script type="text/javascript">
  $(document).on("click",".not_ready",function(){
    return alert("Maaf, fitur ini sedang dalam tahap pengembangan. Terimakasih sudah mencoba!");
  })
</script>