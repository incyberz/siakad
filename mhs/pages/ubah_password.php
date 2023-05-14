<?php
echo "d_mhs[pass]: $d_mhs[password] post[password]: $_POST[password]";
$hideit='';
$password='';
$cpassword='';
$password_lama='';
$depas_note = $is_depas?div_alert('warning','Password Anda masih default (masih kosong atau sama dengan NIM). Anda wajib mengubahnya untuk meningkatkan keamanan akun Anda.'):'Silahkan Anda ubah password:';

if(isset($_POST['btn_ubah_password'])){
  echo '<pre>';
  var_dump($_POST);
  echo '</pre>';
  if($_POST['password']==$_POST['nim']){
    echo div_alert('danger', 'Password tidak boleh sama dengan NIM.');
  }elseif($_POST['password']==$_POST['cpassword']){
    if($d_mhs['password']!=md5($_POST['password_lama'])){
      echo div_alert('danger', 'Password lama Anda tidak sesuai.'.$debug_note);
    }else{
      $sql_password_lama = $_POST['password_lama']=='' ? 'password IS NULL' : 'password = \''.md5($_POST['password_lama']).'\'';
      $s = "UPDATE tb_mhs SET password=md5('$_POST[password]') WHERE $sql_password_lama AND nim='$nim'";
      echo $s;
      $q=mysqli_query($cn,$s) or die(mysqli_error($cn));
      // echo '<script>location.replace("?")</script>';
      echo div_alert('success','Ubah Password berhasil.'.$s);
      exit;
    }
  }else{
    echo div_alert('danger', 'Konfirmasi password tidak sama dengan password baru.');
  }
}

$hideit = $d_mhs['password']==''?'hideit':'';

?>
<section id="ubah_password" class="" data-aos="fade-left">
  <div class="container">

    <div class="section-title">
      <h2>Ubah Password</h2>
      <p><?=$depas_note?></p>
    </div>
    
    <div class="wadah">
      <form method="post">
        <div class="form-group">
          <label for="nim">NIM</label>
          <input type="text" class="form-control" id="nim" value="<?=$nim?>" disabled>
          <input type="hidden" value="<?=$nim?>" name="nim">
        </div>

        <div class="form-group <?=$hideit?>">
          <label for="password_lama">Password Lama</label>
          <input type="password_lama" minlength=3 maxlength=20 class="form-control" id="password_lama" name="password_lama" value="<?=$password_lama?>">
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