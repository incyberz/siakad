<?php
$id_dosen = $_GET['id_dosen'] ?? '';
$nidn = $_GET['nidn'] ?? '';
if($id_dosen=='' and $nidn=='') die(div_alert('danger','both nidn and id_dosen is null.'));
if($nidn==''){
  $s = "SELECT nidn FROM tb_dosen WHERE id='$id_dosen'";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  if(mysqli_num_rows($q)==0) die('Data NIDN Dosen tidak ditemukan.');
  $d = mysqli_fetch_assoc($q);
  $nidn = $d['nidn'];
  if($nidn=='') die(div_alert('danger', 'Dosen ini belum memiliki NIDN. <hr>id: '.$id_dosen));
}
$_SESSION['siakad_dosen'] = $nidn;

echo div_alert('success','Set SESSION Dosen sukses. <a href="../dosen/">silahkan menuju Laman Dosen</a>');
exit;