<?php
// echo "d_user[pass]: $d_user[password] post[password]: $_POST[password]";
$pesan='';
$hideit='';
$password='';
$cpassword='';
$password_lama='';
$depas_note = $is_password_default?div_alert('warning','Password Anda masih default (masih kosong atau sama dengan Username). Anda wajib mengubahnya untuk meningkatkan keamanan akun Anda.'):'Silahkan Anda ubah password:';

if(isset($_POST['btn_ubah_password'])){
  // echo '<pre>';
  // var_dump($_POST);
  // echo '</pre>';
  if($_POST['password']==$_POST['username']){
    $pesan = div_alert('danger', 'Password tidak boleh sama dengan Username.');
  }elseif($_POST['password']==$_POST['cpassword']){
    if($d_user['password']!=md5($_POST['password_lama']) and $d_user['password']!=''){
      $pesan = div_alert('danger', 'Password lama Anda tidak sesuai.');
    }else{
      $sql_password_lama = $_POST['password_lama']=='' ? 'password IS NULL' : 'password = \''.md5($_POST['password_lama']).'\'';
      $s = "UPDATE tb_user SET password=md5('$_POST[password]') WHERE $sql_password_lama AND username='$username'";
      // echo $s;
      $q=mysqli_query($cn,$s) or die(mysqli_error($cn));
      // echo '<script>location.replace("?")</script>';
      unset($_SESSION['siakad_username']);
      echo '<section><div class=container>'. div_alert('success','Ubah Password berhasil. Silahkan relogin!<hr><a href="?login" class="btn btn-primary btn-block">Relogin</a>').'</div></section>';
      exit;
    }
  }else{
    $pesan = div_alert('danger', 'Konfirmasi password tidak sama dengan password baru.');
  }
}

// $hideit = $d_user['password']==''?'hideit':'';
$hideit = ''; //zzz
// die($username);
?>
<section id="ubah_password" class="" data-aos="fade-left">
  <div class="container">

    <div class="section-title">
      <p><?=$pesan?></p>
      <h2>Ubah Password</h2>
      <p><?=$depas_note?></p>
    </div>
    
    <div class="wadah">
      <form method="post">
        <div class="form-group">
          <label for="username">Username</label>
          <input type="text" class="form-control" id="username" value="<?=$username?>" disabled>
          <input type="hidden" value="<?=$username?>" name="username">
        </div>

        <div class="form-group <?=$hideit?>">
          <label for="password_lama">Password Lama</label>
          <input type="password" minlength=3 maxlength=20 class="form-control" id="password_lama" name="password_lama" value="<?=$password_lama?>">
        </div>


        <div class="form-group">
          <label for="password">Password Baru</label>
          <input type="password" minlength=3 maxlength=20 class="form-control" id="password" name="password" value="<?=$password?>">
        </div>

        <div class="form-group">
          <label for="cpassword">Konfirmasi Password</label>
          <input type="password" minlength=3 maxlength=20 class="form-control" id="cpassword" name="cpassword" value="<?=$cpassword?>">
        </div>

        <div class="form-group">
          <button class="btn btn-primary btn-block" name="btn_ubah_password">Ubah Password</button>
        </div>


      </form>
    </div>
  </div>
</section>