<?php 
# ==================================================
# ROUTING MAHASISWA
# ==================================================

# ==================================================
# redirect to home mahasiswa non-aktif
# ==================================================
$parameter = ($status_mhs==0 and $parameter!='logout') ? '' : $parameter;


# ==================================================
# ubah password default
# ==================================================
$skip_ubah_password=0;
if($skip_ubah_password){
  echo '<div class="red tebal">Perhatian! Mode Pass Ubah Password is ON.</div>';
}else{
  if($is_depas && $parameter!='logout'){
    if(isset($_SESSION['siakad_username'])){
      echo '<div class="red tebal">Perhatian! Ubah Password di skip karena sedang Login As.</div>';
    }else{
      $parameter = 'ubah_password';
    }
  }
}

# ==================================================
# SHOW ALWAYS UPLOAD PROFILE
# ==================================================
if($img_profile==$profile_na and $parameter!='upload_profile') include 'must_upload_profile.php';

switch ($parameter) {
  case '': 
  case 'home': include "pages/home.php"; break;

  default:
  $auto_page = "pages/$parameter.php";
  if(file_exists($auto_page)){
    include $auto_page;
  }else{
    die(div_alert('danger','Index parameter: '. $parameter . ' belum terdefinisi.'));
  }
}
?>