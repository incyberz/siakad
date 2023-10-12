<?php
$pesan = '<p>Masukan username dan password untuk mengakses fitur dosen di SIAKAD STMIK IKMI Cirebon.</p>';
if(isset($_POST['btn_login_dosen'])){
  $s = "SELECT 1 from tb_dosen WHERE username='$_POST[username]' and (password=md5('$_POST[password]') OR password is null )";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  if(mysqli_num_rows($q)==1){
    $_SESSION['siakad_dosen'] = $_POST['username'];
    echo '<script>location.replace("?")</script>';
  }else{
    $pesan = div_alert('danger','Maaf, username dan password tidak tepat. Silahkan coba kembali!'."zzz  debug <hr>$s");
  }
}
?>
<style>
  .full{
    display:flex;
    /* border: solid 3px red; */
    height: 100vh;
  }
  .form-login{
    max-width: 400px;
    margin:auto;
  }
</style>
<div class="full">
  <div class="wadah gradasi-hijau form-login">
    <h3>Login Dosen</h3>
    <?=$pesan?>
    <hr>
    <form method="post">
      <div class="form-group">
        <label for="username">Username</label>
        <input type="text" class="form-control" minlength=3 maxlength=20 required id="username" name="username">
      </div>

      <div class="form-group">
        <label for="username">Password</label>
        <input type="password" class="form-control" minlength=3 maxlength=20 required id="password" name="password">
      </div>

      <div class="form-group">
        <button class='btn btn-primary btn-block' name='btn_login_dosen'>Login</button>
      </div>      
    </form>
  </div>
</div>
