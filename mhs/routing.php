<?php 
// ubah password default
$skip_ubah_password=0;
if($skip_ubah_password){
  echo '<div class="red tebal">Perhatian! Mode Pass Ubah Password is ON.</div>';
}else{
  $parameter = ($is_depas && $parameter!='logout') ? 'ubah_password' : $parameter;
}

// redirect to home mahasiswa non-aktif
$parameter = ($status_mhs==0 and $parameter!='logout') ? '' : $parameter;


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