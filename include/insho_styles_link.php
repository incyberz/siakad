<?php
if(file_exists('../insho_styles.php')){
  include '../insho_styles.php';
}elseif(file_exists('insho_styles.php')){
  include 'insho_styles.php';
}else{
  echo '<div>Perhatian, SIAKAD Styles (CSS) is missing.</div>';
}