<?php
session_start();
// session_destroy(); exit;
$dm=0;
include "../config.php";
include "../../insho_styles.php";
$folder_rps = "../uploads/rps";

$id_dosen = '';
if(isset($_GET['id_dosen'])) $id_dosen = $_GET['id_dosen'];




# ========================================================
# MANAGE URI
# ========================================================
$a = $_SERVER['REQUEST_URI'];
if (!strpos($a, "?")) $a.="?";
if (!strpos($a, "&")) $a.="&";

$b = explode("?", $a);
$c = explode("&", $b[1]);
$parameter = $c[0];
$parameter = $parameter=='' ? 'jadwal_mingguan' : $parameter;








# ========================================================
# LOGOUT OR LOGIN PROCESS
# ========================================================
?> 
<style type="text/css">
  .pesan_logout{border: solid 1px #aaa; margin: 15px; padding: 15px; background-color: #dfd; border-radius: 15px; text-align: center}
</style>
<?php

if($parameter=="logout"){
  # =======================================================
  # LOGOUT
  # =======================================================
  if(!isset($_SESSION)) session_start();
  session_unset();
  echo '<script>location.replace("?")</script>';

}




# ========================================================
# CHECK IF NOT LOGIN
# ========================================================
// if(!isset($_SESSION['siakad_username'])){
  // include "login.php";
  // exit();
// }


# ========================================================
# ID DOSEN NOT SET
# ========================================================
if(isset($_SESSION['siakad_dosen'])) {
  # ========================================================
  # DETAIL DOSEN
  # ========================================================
  $s = "SELECT 
  a.*,
  (
    SELECT nama from tb_prodi where id=a.homebase 
  ) as nama_prodi 


  from tb_dosen a 
  join tb_user b on a.id_user=b.id  

  where b.username='$_SESSION[siakad_dosen]'";
  $q = mysqli_query($cn,$s) or die("Error @Index. ".mysqli_error($cn));
  if(mysqli_num_rows($q)!=1) die("No Data. id_dosen:$id_dosen");
  $d_dosen = mysqli_fetch_assoc($q);

  $id_dosen = $d_dosen['id'];
  $nama_dosen = $d_dosen['nama'];
  $nama_dosen = ucwords(strtolower($nama_dosen));
  $nama_kec = "Kec: none";
  $nama_kab = "Kab ?";
  $nama_kec_kab = '';


  $folder_uploads = $d_dosen['folder_uploads'];
  if($folder_uploads==""){
    $folder_uploads = "_".date("ymdhis")."_".substr(microtime(), 2,6);
    $ss = "UPDATE tb_dosen set folder_uploads='$folder_uploads' where id='$id_dosen'";
    $qq = mysqli_query($cn,$ss) or die("Update new folder_uploads. $ss. ".mysqli_error($cn));
  }

  $img_profile = "uploads/$folder_uploads/img_profile_$id_dosen.jpg";
  $img_bg = "uploads/$folder_uploads/img_bg_$id_dosen.jpg";

  if(!file_exists($img_profile)) $img_profile = "uploads/profile_na.jpg";
  if(!file_exists($img_bg)) $img_bg = "uploads/bg_na.jpg";

  $homebase_prodi = $d_dosen['nama_prodi'];
  $link_logout = "<p class=mt-2>Selamat Datang $nama_dosen | <a href='?logout' onclick='return confirm(\"Yakin untuk Logout?\")'>Logout</a></p>";
  $nav = "
    <div style='position:sticky; top:0;padding:5px;border:solid 1px #ccc;background:linear-gradient(#fafffa,#efe);font-size:small; z-index:999;margin-bottom:15px'>
      <a href='?'>Jadwal</a> | 
      <a href='?mk_saya'>MK Saya</a>  
    </div>
  ";
}else{
  $nama_dosen = 'Dosen';
  $parameter = 'login_dosen';
  $link_logout = '';
  $nav = '';
}







?>


<!DOCTYPE html>
<html lang='en'>

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Portal Dosen - <?=$nama_dosen ?></title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <script src="../assets/vendor/jquery/jquery.min.js"></script>

</head>

<body>
  <div class="container">
    <?=$link_logout?>
    <?=$nav?>
    <?php include "modul/$parameter.php"; ?>
  </div>
</body>
</html>

<?php
// echo '<pre>';
// var_dump($d_dosen);
// echo '</pre>';
