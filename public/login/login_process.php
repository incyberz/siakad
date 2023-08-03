<h1 data-aos="fade-up">Welcome to SIAKAD</h1>
<?php 

if(isset($_POST['btn_login'])){

  $username = filter_var($_POST['username']);
  $password = filter_var($_POST['password']);

  $s = "SELECT 1 FROM tb_user a WHERE a.username='$username' and a.password = md5('$password')";
  $q = mysqli_query($cn,$s) or die("Tidak dapat mengakses data login #1");

  if(mysqli_num_rows($q)==1){
    $is_login = 1;
    $_SESSION['siakad_username'] = $username;
    echo '<h1>Login Success. Redirecting...</h1><script>location.replace("index.php")</script>';

  }else{
    // password salah
    echo "
    <div class='alert alert-danger' data-aos='fade-up' data-aos-delay='400'>
      Maaf, Username dan Password Anda tidak cocok.
      <hr>
      <a href='?username=$username' class='btn btn-primary' style='border-radius:10px'>Coba Lagi</a>
    </div>";

  }

}else{ 
  # =======================================================
  # NOT POST BTN_LOGIN CLICK
  # =======================================================
  echo "
  <h2 data-aos='fade-up' data-aos-delay='400'>Please Login to access sub-features !</h2>
  <div id='blok_login' data-aos='fade-up' data-aos-delay='400'>";
  include "login.php";
  echo "</div>";

}