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

<?php
$rmanage[0] = ['kalender','master&p=kalender'];
$rmanage[1] = ['kurikulum','master&p=kurikulum'];
$rmanage[2] = ['jadwal','manage_jadwal'];
$rmanage[3] = ['kelas','manage_kelas'];
$rmanage[4] = ['sesi','manage_sesi'];
$rmanage[5] = ['peserta','manage_peserta'];
$rmanage[6] = ['mhs','manage_mhs'];

for ($i=0; $i < count($rmanage); $i++) { 
  echo "
  <div class='item-master'>
    <div><a href='?$rmanage[$i][1]'>manage<br> $rmanage[$i][0]</a></div>
  </div>
  ";
}
?>
