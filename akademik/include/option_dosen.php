<?php 
if(!isset($default_option)) die('</select><span class=red>option_dosen membutuhkan $default_option for selected item</span>');
$s = "SELECT id,nama from tb_dosen order by nama";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$option_dosen = '';
while ($dopt=mysqli_fetch_assoc($q)) {
  $selected = $dopt['id']==$default_option ? 'selected' : '';
  $option_dosen .= "<option value='$dopt[id]' $selected>$dopt[nama]</option>";
}
?>