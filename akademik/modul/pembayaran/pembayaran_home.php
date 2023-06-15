

<div class="master-home">

  <?php
  $rmanage[0] = ['Manage Komponen Biaya','manage_komponen_biaya'];
  $rmanage[1] = ['Manage Biaya per Angkatan','manage_biaya_angkatan'];
  $rmanage[2] = ['Manage Penagihan','penagihan'];
  $rmanage[3] = ['Manage Pembayaran','pembayaran'];


  for ($i=0; $i < count($rmanage); $i++) { 
    echo "
    <div class='item-master'>
      <div><a href='?".$rmanage[$i][1]."'>".$rmanage[$i][0]."</a></div>
    </div>
    ";
  }
  ?>
</div>



<!-- System Manual -->
<div class="master-home">

  <?php
  $rmanual[0] = ['Rekap Pembayaran','rekap_pembayaran_manual'];
  $rmanual[1] = ['Manage Status Pembayaran','pembayaran_manual'];


  for ($i=0; $i < count($rmanual); $i++) { 
    echo "
    <div class='item-master'>
      <div><a href='?".$rmanual[$i][1]."'>".$rmanual[$i][0]."</a></div>
    </div>
    ";
  }
  ?>
</div>
