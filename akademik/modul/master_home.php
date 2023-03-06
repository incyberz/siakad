<?php
include 'include/rmaster.php';

?>
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
for ($i=0; $i < count($rmaster); $i++) { 
  $text = str_replace('_',' ',$rmaster[$i]);
  $ui_master[$rmaster[$i]] = "
  <div class='item-master'>
    <div><a href='?master&p=$rmaster[$i]'>master<br> $text</a></div>
  </div>
  ";
}

?>

<div class="master-home">
  <?php  echo "$ui_master[pt] $ui_master[fakultas] $ui_master[jenjang] $ui_master[prodi] ";?>
</div>

<div class="master-home">
  <?php  echo "$ui_master[angkatan] $ui_master[kalender] $ui_master[semester]";?>
</div>

<div class="master-home">
  <?php  echo "$ui_master[bk] $ui_master[mk] $ui_master[dosen] ";?>
</div>

<div class="master-home">
  <?php  echo "$ui_master[jalur] $ui_master[kelas] ";?>
</div>

<div class="master-home">
  <?php  echo "$ui_master[output_pmb] $ui_master[mhs] ";?>
</div>

<div class="master-home">
  <?php  echo "$ui_master[kurikulum] $ui_master[semester] $ui_master[kurikulum_mk] ";?>
</div>

<div class="master-home">
  <?php  echo "$ui_master[jadwal] $ui_master[peserta_kelas] $ui_master[ruang] $ui_master[sesi_kuliah] ";?>
</div>

<div class="master-home">
  <?php  echo "$ui_master[presensi] ";?>
</div>