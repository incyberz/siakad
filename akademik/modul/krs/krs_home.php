<?php $izin = ($admin_level==3||$admin_level==4||$admin_level==6||$admin_level==7) ? 1 : 0;
if(!$izin) echo div_alert('danger','Maaf, hanya Bagian Akademik yang berhak mengakses Menu ini.');
?>


<div class="master-home">

  <?php
  $rmanage[0] = ['manage KRS','manage_krs'];
  $rmanage[1] = ['manage Syarat biaya','manage_syarat_biaya'];

  for ($i=0; $i < count($rmanage); $i++) { 
    $href = $izin ? '?'.$rmanage[$i][1] : '#';
    echo "
    <div class='item-master'>
      <div><a href='$href'>".$rmanage[$i][0]."</a></div>
    </div>
    ";
  }
  ?>
</div>