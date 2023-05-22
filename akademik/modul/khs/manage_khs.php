<style>
  .master-home{
    display:flex;
    flex-wrap: wrap;
    border: solid 1px #ccc;
    padding: 10px;
    border-radius: 10px;
    background: linear-gradient(#fff,#efe);
    margin-bottom: 15px;

  }

  .item-master{
    width: 200px;
    height: 150px;
    background: linear-gradient(#afa,#fff,#afa);
    margin: 0 15px 15px 0;
    border-radius: 10px;
    text-align: center;
    padding: 15px;
    text-transform: uppercase;
    font-size: 24px;
    display:flex;
    justify-content:center;
    align-items:center;

  }


</style>

<div class="master-home">

  <?php
  $rmanage[0] = ['input KHS SIAKAD','input_khs'];
  $rmanage[1] = ['input KHS Manual','input_khs_manual'];
  $rmanage[2] = ['import KHS','import_khs'];
  $rmanage[3] = ['Verifikasi Draft KHS','verifikasi_draft_khs'];


  for ($i=0; $i < count($rmanage); $i++) { 
    echo "
    <div class='item-master'>
      <div><a href='?".$rmanage[$i][1]."'>".$rmanage[$i][0]."</a></div>
    </div>
    ";
  }
  ?>
</div>
