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
  $rmanage = [];
  array_push($rmanage, ['master','manage_master','Awal seting Data Master']);
  array_push($rmanage, ['kalender','manage_kalender','Awal seting Kalender Induk']);
  array_push($rmanage, ['semester','manage_kalender','Seting penanggalan semester']);
  array_push($rmanage, ['kurikulum','manage_kurikulum','assign mk ke kurikulum']);
  array_push($rmanage, ['kelas','manage_kelas','assign kelas ke jadwal']);
  array_push($rmanage, ['peserta','manage_peserta','assign mhs ke kelasnya']);
  array_push($rmanage, ['jadwal#1','manage_jadwal','assign dosen ke kurikulum']);
  array_push($rmanage, ['jadwal#2','manage_awal_kuliah','manage awal perkuliahan']);
  array_push($rmanage, ['sesi kuliah','manage_sesi','seting tanggal tiap sesi']);
  array_push($rmanage, ['ruang#1','manage_ruang_mengajar_dosen','assign ruang untuk dosen mengajar ']);
  array_push($rmanage, ['ruang#2','manage_ruang_belajar_mhs','assign ruang untuk belajar mhs ']);
  array_push($rmanage, ['mhs','manage_mhs','manage aktifitas mhs']);
  array_push($rmanage, ['dosen','monitoring_sks_dosen','manage aktifitas dosen']);
  array_push($rmanage, ['mhs aktif','mhs_aktif','manage mhs aktif']);


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
