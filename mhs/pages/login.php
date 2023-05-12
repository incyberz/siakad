<?php
$pesan = '<p>Masukan NIM dan password untuk login. Default password adalah NIM.</p>';
$nim = '';
$password = '';


$nim = '41205262'; // zzz debug
$password = '41205262';



if(isset($_POST['btn_login_mhs'])){
  $nim = clean_sql($_POST['nim']);
  $password = clean_sql($_POST['password']);

  if($nim===$password){

  }

  $sql_password = $nim===$password ? 'password is null' : "password=md5('$password')";
  $s = "SELECT 1 from tb_mhs WHERE nim='$nim' and $sql_password";
  // echo $s;
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  if(mysqli_num_rows($q)==1){
    $_SESSION['siakad_mhs'] = $nim;
    echo '<script>location.replace("?")</script>';
    exit;
  }else{
    $pesan = div_alert('danger','Maaf, nim dan password tidak tepat. Silahkan coba kembali!');
  }
}
?>
<style>
  .full{
    display:flex;
    height: 100vh;
  }
  .form-login{
    max-width: 400px;
    margin:auto;
  }
</style>
<div class="full">
  <div class="wadah gradasi-biru form-login p-4">
    <h3>Login Mahasiswa</h3>
    <?=$pesan?>
    <hr>
    <form method="post">
      <div class="form-group">
        <label for="nim">NIM</label>
        <input type="text" class="form-control" minlength=8 maxlength=8 required id="nim" name="nim" value="<?=$nim?>">
      </div>

      <div class="form-group">
        <label for="nim">Password</label>
        <input type="password" class="form-control" minlength=3 maxlength=20 required id="password" name="password" value="<?=$password?>">
      </div>

      <div class="form-group">
        <button class='btn btn-primary btn-block' name='btn_login_mhs'>Login</button>
      </div>      
    </form>
  </div>
</div>
