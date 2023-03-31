<?php 
if(!isset($default_option)) die('option_prodi membutuhkan default_option');
$s = "SELECT id,nama FROM tb_prodi ";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$option_prodi = '';
while ($dopt=mysqli_fetch_assoc($q)) {
  $selected = $dopt['id']==$default_option ? 'selected' : '';
  $option_prodi .= "<option value='$dopt[id]' $selected>$dopt[nama]</option>";
}
?>