<?php
$insho_styles = '../../insho_styles.php';
if(file_exists($insho_styles)){
  include $insho_styles;
}else{
  echo "Warning! InSho Styles Sheet not found.";
}
