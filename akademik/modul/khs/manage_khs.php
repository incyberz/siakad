

<div class="master-home">

  <?php
  $rmanage[0] = ['input KHS SIAKAD','input_khs'];
  $rmanage[1] = ['import KHS','import_khs'];
  $rmanage[2] = ['Verifikasi Draft KHS','verifikasi_draft_khs'];


  for ($i=0; $i < count($rmanage); $i++) { 
    echo "
    <div class='item-master'>
      <div><a href='?".$rmanage[$i][1]."'>".$rmanage[$i][0]."</a></div>
    </div>
    ";
  }
  ?>
</div>

<div class="master-home">

  <?php
  $rmanual[0] = ['List KHS Manual','list_khs_manual'];
  $rmanual[1] = ['input KHS Manual','input_khs_manual'];
  $rmanual[2] = ['import KHS Manual','import_khs_manual'];


  for ($i=0; $i < count($rmanual); $i++) { 
    echo "
    <div class='item-master'>
      <div><a href='?".$rmanual[$i][1]."'>".$rmanual[$i][0]."</a></div>
    </div>
    ";
  }
  ?>
</div>
