<?php 
# ========================================================
# SIAKAD PUBLIC INDEX
# ========================================================
session_start();
echo '<pre>'; var_dump($_SESSION); echo '</pre>';
$dm = 0;
$now = date('Y-m-d H:i:s');
$today = date('Y-m-d');

$nama_user = "Pengunjung";
$login_as = "Pengunjung";
$is_login = 0;

include 'config.php';
if (isset($_SESSION['siakad_username'])) {
  $username = $_SESSION['siakad_username'];
  include 'user_vars.php';
  $is_login=1;
}


# ========================================================
# MANAGE URL PARAMETER
# ========================================================
$a = $_SERVER['REQUEST_URI'];
if (!strpos($a, "?")) $a.="?";
if (!strpos($a, "&")) $a.="&";

$b = explode("?", $a);
$c = explode("&", $b[1]);
$url_parameter = $c[0];



# ========================================================
# 12 FITUR SIAKAD 
# ========================================================
# 1. DC-MHS        5. SKRIPSI          9.  TENDIK
# 2. KRS           6. TRANSKRIP        10. KEUANGAN
# 3. KHS           7. LULUSAN          11. INVENTORY
# 4. JADWAL        8. DOSEN            12. PERPUS
# ========================================================
include 'public/fitur_routing.php';


?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>SIAKAD IKMI Cirebon :: Academic Enterprise System</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <!-- <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet"> -->

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/icofont/icofont.min.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/owl.carousel/assets/owl.carousel.min.css" rel="stylesheet">
  <link href="assets/vendor/venobox/venobox.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">

  <link href="assets/css/style.css" rel="stylesheet">
  <link href="assets/css/siakad.css" rel="stylesheet">
  <script src="assets/vendor/jquery/jquery.min.js"></script>
  <?php include 'include/insho_styles_link.php'; ?>
</head>

<body>

  <?php 
  include 'public/header.php';
  include 'public/sections/hero.php'; 
  ?>

  <main id='main'>

    <?php 
    if($url_parameter!='logout'){
      include 'public/fitur_siakad.php'; 
      include 'public/sections/informasi.php'; 
    }
    // include 'public/sections/progres.php'; 
    include 'public/sections/team.php'; 
    // include 'cari_profil_dosen.php'; 

    ?>
  </main>

  <?php include 'public/footer.php'; ?>


  <a href="#" class="back-to-top"><i class="icofont-simple-up"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/jquery.easing/jquery.easing.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/waypoints/jquery.waypoints.min.js"></script>
  <script src="assets/vendor/counterup/counterup.min.js"></script>
  <script src="assets/vendor/owl.carousel/owl.carousel.min.js"></script>
  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="assets/vendor/venobox/venobox.min.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>



  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>