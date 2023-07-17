<?php 
$s = "SELECT a.id, b.jenjang,b.angkatan FROM tb_kurikulum a JOIN tb_kalender b ON a.id_kalender=b.id";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$rid_kurikulum = [];
$rnama_kurikulum = [];
while ($d=mysqli_fetch_assoc($q)) {
  array_push($rid_kurikulum,$d['id']);
  $rnama_kurikulum[$d['id']] = 'Kurikulum '.$d['jenjang'].'-'.$d['angkatan'];
}
?>