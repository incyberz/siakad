<?php $izin = ($admin_level==3||$admin_level==6||$admin_level==7) ? 1 : 0;
if(!$izin) echo div_alert('danger','Maaf, hanya Bagian Akademik yang berhak mengakses Menu ini.');
?>
<div class="master-home">


<?php
$rmanage[0] = ['kalender','master&p=kalender'];
$rmanage[1] = ['kurikulum','master&p=kurikulum'];
$rmanage[2] = ['kurikulum semester','manage_semester'];
$rmanage[3] = ['jadwal','manage_jadwal'];
$rmanage[4] = ['kelas','manage_kelas'];
$rmanage[5] = ['sesi','manage_sesi'];
$rmanage[6] = ['peserta','manage_peserta'];
$rmanage[7] = ['mhs','manage_mhs'];
$rmanage[8] = ['dosen','monitoring_sks_dosen'];
$rmanage[9] = ['mhs aktif','mhs_aktif'];


for ($i=0; $i < count($rmanage); $i++) { 
  $href = $izin ? '?'.$rmanage[$i][1] : '#';
  echo "
  <div class='item-master'>
    <div><a href='$href'>manage<br> ".$rmanage[$i][0]."</a></div>
  </div>
  ";
}
?>
</div>
