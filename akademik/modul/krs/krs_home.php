<?php $izin = ($admin_level==3||$admin_level==6||$admin_level==7) ? 1 : 0;
if(!$izin) echo div_alert('danger','Maaf, hanya Bagian Akademik yang berhak mengakses Menu ini.');
?>


<div class="master-home">

  <?php
  // $rmanage[0] = ['input KHS SIAKAD','manage_khs'];
  // $rmanage[1] = ['import KHS','import_khs'];
  // $rmanage[2] = ['Verifikasi Draft KHS','verifikasi_draft_khs'];


  // for ($i=0; $i < count($rmanage); $i++) { 
  //   $href = $izin ? '?'.$rmanage[$i][1] : '#';
  //   echo "
  //   <div class='item-master'>
  //     <div><a href='$href'>".$rmanage[$i][0]."</a></div>
  //   </div>
  //   ";
  // }
  ?>
</div>

<div class="master-home">

  <?php
  $rmanual[0] = ['Event KRS','event_krs'];
  // $rmanual[1] = ['input KHS Manual','input_khs_manual'];
  // $rmanual[2] = ['import KHS Manual','import_khs_manual'];


  for ($i=0; $i < count($rmanual); $i++) { 
    $href = $izin ? '?'.$rmanual[$i][1] : '#';
    echo "
    <div class='item-master'>
      <div><a href='$href'>".$rmanual[$i][0]."</a></div>
    </div>
    ";
  }
  ?>
</div>
