
<?php $izin = ($admin_level==4) ? 1 : 0;
if(!$izin) echo div_alert('danger','Maaf, hanya Bagian Keuangan yang berhak mengakses Menu ini.');
?>

<div class="master-home">

  <?php
  $rmanage[0] = ['Komponen Biaya','manage_komponen_biaya'];
  $rmanage[1] = ['Biaya per Angkatan','manage_biaya_angkatan'];
  $rmanage[2] = ['Penagihan','manage_penagihan'];
  $rmanage[3] = ['Pembayaran','manage_pembayaran'];


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



<!-- System Manual -->
<div class="master-home">

  <?php
  $rmanual[0] = ['Rekap Pembayaran','rekap_pembayaran_manual'];
  $rmanual[1] = ['Status Pembayaran','list_sudah_bayar'];


  for ($i=0; $i < count($rmanual); $i++) { 
    $href = $izin ? '?'.$rmanual[$i][1] : '#';
    echo "
    <div class='item-master'>
      <div><a href='$href'>manage<br> ".$rmanual[$i][0]."</a></div>
    </div>
    ";
  }
  ?>
</div>
