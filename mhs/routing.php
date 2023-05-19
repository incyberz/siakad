<?php 
// $parameter = ($is_depas && $parameter!='logout') ? 'ubah_password' : $parameter;
// zzz debug skip password
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