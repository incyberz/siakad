<style>
  .img_aksi{
    height: 20px;
    width: 20px;
    opacity: 60%;
    transition:.2s;
    cursor: pointer;
  }
  .img_aksi:hover{
    transform:scale(1.2);
    opacity: 100%;
  }
</style>
<?php
$path_icons = '../assets/img/icons';
$files = scandir($path_icons);
$arr_aksi = [];
foreach ($files as $key => $file) {
  $tmp = explode('.',$file);
  if(strlen($tmp[0])>0) array_push($arr_aksi,$tmp[0]);
}

foreach ($arr_aksi as $key => $aksi) {
  $img_aksi[$aksi] = "<img class='img_aksi' src='$path_icons/$aksi.png'>";
}
