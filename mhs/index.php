<?php
# ========================================================
# INDEX MAHASISWA
# ========================================================
session_start();
if (0) {
  session_unset();
  exit();
}
// if(!isset($_SESSION['siakad_mhs'])){
//   header("Location: ../?");
//   die("Anda belum login. Silahkan <a href='../'>login dahulu</a>.");
// } 
include "../config.php";

# ========================================================
# INCLUDE INSHO STYLES
# ========================================================
$insho_styles = '../../insho_styles.php';
if(file_exists($insho_styles)){
  include $insho_styles;
}else{
  echo "Warning! InSho Styles Sheet not found.";
}

if(!isset($_SESSION['siakad_mhs'])){
  // $_SESSION['siakad_mhs'] = "51220001";
  // die("Auto Login enabled. Please Refresh!");
  // include 'pages/login.php';
  // exit;
  $is_login=0;
}else{
  $is_login=1;
  $nim = $_SESSION['siakad_mhs'];
  // $nim = '31229996'; /// zzz debug ahmad firdaus dummy


  # ========================================================
  # GLOBAL VARIABLE FILES
  # ========================================================
  $undef = '<span style="color:#f77; font-style:italic">undefined</span>';
  include "mhs_var.php";
  include "akd_var.php";



  # ========================================================
  # MANAGE URI
  # ========================================================
  $a = $_SERVER['REQUEST_URI'];
  if (!strpos($a, "?")) $a.="?";
  if (!strpos($a, "&")) $a.="&";

  $b = explode("?", $a);
  $c = explode("&", $b[1]);
  $parameter = $c[0];
  $is_edit = 0;
  if($parameter=="edit")$is_edit=1;


  $img_ucons="<img src='../assets/img/under_cons.jpg' width='150px' class='img_zoom'>";

}


?>



<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Portal Mhs - <?=$nama_mhs ?></title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <link href="assets/img/favicon.png" rel="icon">
  <!-- <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon"> -->

  <!-- <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet"> -->

  <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/vendor/icofont/icofont.min.css" rel="stylesheet">
  <link href="../assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="../assets/vendor/venobox/venobox.css" rel="stylesheet">
  <link href="../assets/vendor/owl.carousel/assets/owl.carousel.min.css" rel="stylesheet">
  <link href="../assets/vendor/aos/aos.css" rel="stylesheet">

  <link href="assets/css/style.css" rel="stylesheet">
  <link href="assets/css/mhs.css" rel="stylesheet">
  <script src="../assets/vendor/jquery/jquery.min.js"></script>

  <style type="text/css">
    .var{
      color: #a6f;
      /*text-decoration: underline;*/
    }
    .debug{
      background:red;
      display: nonea;
      border: solid 3px red;
      padding: 3px;
    }
  </style>

  <?php ?>

</head>

<body class='gradasi-hijau'>

  <?php if($is_login){ ?>
  <button type="button" class="mobile-nav-toggle d-xl-none"><i class="icofont-navigation-menu"></i></button>
  <?php include "header.php";?>
  <main id="main"><?php include 'routing.php' ; ?></main>
  <?php include "pages/footer.php";?>
  <a href="#" class="back-to-top"><i class="icofont-simple-up"></i></a>
  <?php }else{ include 'pages/login.php'; }?>

  <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../assets/vendor/jquery.easing/jquery.easing.min.js"></script>
  <script src="../assets/vendor/php-email-form/validate.js"></script>
  <script src="../assets/vendor/waypoints/jquery.waypoints.min.js"></script>
  <script src="../assets/vendor/counterup/counterup.min.js"></script>
  <script src="../assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="../assets/vendor/venobox/venobox.min.js"></script>
  <script src="../assets/vendor/owl.carousel/owl.carousel.min.js"></script>
  <script src="../assets/vendor/typed.js/typed.min.js"></script>
  <script src="../assets/vendor/aos/aos.js"></script>

  <script src="assets/js/main.js"></script>
</body>
</html>


<script type="text/javascript">
  $(document).on("click",".not_ready",function(){
    return alert("Maaf, fitur civitas ini sedang dalam tahap pengembangan. Terimakasih sudah mencoba!");
  })
</script>