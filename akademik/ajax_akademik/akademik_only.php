<?php
include '../../user_vars.php';
if($admin_level==3 || $admin_level==6 || $admin_level==7){
  // boleh mengakses
}else{
  die('Maaf, fitur ini hanya bisa diakses oleh bagian akademik, sekprodi, atau kaprodi. ');
} 
