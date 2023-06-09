<?php
include 'include/rmaster.php';

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
  <?php  echo "$ui_master[pt] $ui_master[fakultas] $ui_master[angkatan] $ui_master[jenjang] ";?>
</div>

<div class="master-home">
  <?php  echo " $ui_master[kalender] $ui_master[semester] $ui_master[prodi]";?>
</div>

<div class="master-home">
  <?php  echo "$ui_master[bk] $ui_master[mk] $ui_master[dosen] ";?>
</div>

<div class="master-home">
  <?php  echo "$ui_master[kurikulum] $ui_master[semester] $ui_master[kurikulum_mk] ";?>
</div>  

<div class="master-home">
  <?php  echo "$ui_master[jalur] $ui_master[kelas] ";?>
</div>

<div class="master-home">
  <?php  echo "$ui_master[output_pmb] $ui_master[mhs] ";?>
</div>

<div class="master-home">
  <?php  echo "$ui_master[jadwal] $ui_master[kelas_peserta] $ui_master[ruang] $ui_master[sesi_kuliah] ";?>
</div>

<div class="master-home">
  <?php  echo "$ui_master[presensi] ";?>
</div>

<div class="wadah">
  <h3>Master Data yang Belum</h3>
  <ul>
    <li>Master Petugas</li>
    <li>Master Nilai</li>
    <li>Master Biaya</li>
    <li>Master Data Lainnya...</li>
  </ul>
</div>