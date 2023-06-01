<?php 
// ubah password default
// $parameter = ($is_depas && $parameter!='logout') ? 'ubah_password' : $parameter;

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