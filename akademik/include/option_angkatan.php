<?php 
if(!isset($default_option)) die('option_angkatan membutuhkan default_option');
$s = "SELECT angkatan from tb_angkatan ";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$option_angkatan = '';
while ($dopt=mysqli_fetch_assoc($q)) {
  $selected = $dopt['angkatan']==$default_option ? 'selected' : '';
  $option_angkatan .= "<option $selected>$dopt[angkatan]</option>";
}
?>