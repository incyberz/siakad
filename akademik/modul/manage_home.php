<h1>Manage SIAKAD</h1>
<p class='miring'>Menu Manage digunakan untuk proses assign, drop, atau pengelolaan lainnya antar data master. Proses <code>assign</code> adalah proses penggabungan data baru dari beberapa data master, misal membuat <code>Kalender Akademik</code> dari data master <code>jenjang</code> dan <code>angkatan</code>. Proses CRUD pada fitur manage sangat dibatasi atau tidak ada.</p>
<?php $izin = (
  $admin_level==3
||$admin_level==6
||$admin_level==7
||$admin_level==8
||$admin_level==9
) ? 1 : 0;
if(!$izin) echo div_alert('danger','Maaf, hanya Bagian Akademik yang berhak mengakses Menu ini.');
?>
<div class="master-home">

  <?php
  $rmanage[0] = ['master','manage_master','Awal seting Data Master'];
  $rmanage[1] = ['kalender','manage_kalender','Awal seting Kalender Induk'];
  $rmanage[2] = ['semester','manage_kalender','Seting penanggalan semester'];
  $rmanage[3] = ['kurikulum','manage_kurikulum','assign mk ke kurikulum'];
  $rmanage[4] = ['jadwal','manage_jadwal','assign dosen ke kurikulum'];
  $rmanage[5] = ['kelas','manage_kelas','assign kelas ke jadwal'];
  $rmanage[6] = ['peserta','manage_peserta','assign mhs ke kelasnya'];
  $rmanage[7] = ['sesi','manage_sesi','seting tanggal tiap sesi'];
  $rmanage[8] = ['mhs','manage_mhs','manage aktifitas mhs'];
  $rmanage[9] = ['dosen','monitoring_sks_dosen','manage aktifitas dosen'];
  $rmanage[10] = ['mhs aktif','mhs_aktif','manage mhs aktif'];


  for ($i=0; $i < count($rmanage); $i++) { 
    $href = $izin ? '?'.$rmanage[$i][1] : '#';
    $no_manage = $i+1;
    echo "
    <div class='item-master'>
      <div>
        <div class=tengah>
          <div class=no_manage>$no_manage</div>
        </div>
        <a href='$href'>manage<br> ".$rmanage[$i][0]."</a>
        <div class=ket_manage>".$rmanage[$i][2]."</div>
      </div>
    </div>
    ";
  }
  ?>
</div>
