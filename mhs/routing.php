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
    die('Index parameter: '. $parameter . ' belum terdefinisi.');
  }

  // if($admin_level==2 or $admin_level==9){
  //   # =================================================
  //   # KHUSUS GM
  //   # =================================================
  //   switch ($parameter) {
  //     case 'rpresensi': include "pages/gm/presensi_rekap.php"; break;
  //     default: include "na.php"; break;
  //   }
  // }else{
  //   include "na.php"; 
  // }

  // break;
}
?>