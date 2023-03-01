<?php 
# ========================================================
# MANAGE URI
# ========================================================
$a = $_SERVER['REQUEST_URI'];
if (!strpos($a, "?")) $a.="?";
if (!strpos($a, "&")) $a.="&";

$b = explode("?", $a);
$c = explode("&", $b[1]);

switch ($c[0]){
  case '':
  case 'dashboard': $konten = 'modul/dashboard/dashboard.php';break;
  case 'master': $konten = 'modul/master.php';break;
  case 'kurikulum': $konten = 'modul/kurikulum/kurikulum.php';break;
}

/*
# ========================================================
# MENU CONTROLLERS 
# ========================================================
#     is_show  param          script-name                          menu-caption         icon         styling
#           0  1 -------------2 ---------------------------------- 3 ------------------ 4 ---------- 5
$menu[0] = [0,"na"          ,"na.php"                            ,""                  ,"house_alt" ," "];

$mbold = "style='color:yellow;font-weight:bold;'";

$file_modul = "modul/$c[0].php";
if(file_exists($file_modul)){
  $konten = $file_modul;
}else{
  if($is_ready){
    $menu[1] = [0,""            ,"modul/dashboard/siakad_dashboard.php"                     ,""                  ,"genius"    ," "];
    $menu[2] = [1,"dashboard"   ,"modul/dashboard/siakad_dashboard.php"                     ,"Dashboard"         ,"easel_alt"    ," "];
    $menu[3] = [1,"datamhs"     ,"modul/data_mhs/data_mhs.php"    ,"Data Mhs"          ,"documents"    ," "];
    $menu[4] = [1,"datadosen"   ,"modul/data_dosen/data_dosen.php"  ,"Data Dosen"        ,"documents"    ," "];
    $menu[5] = [1,"datatendik"  ,"modul/data_tendik/data_tendik.php" ,"Data Tendik"       ,"documents"    ," "];
    $menu[6] = [1,"datamk"      ,"modul/data_mk/data_mk.php"     ,"Data MK"           ,"documents"    ," "];
    $menu[7] = [1,"jadwalkul"   ,"modul/jadwal_kuliah/jadwal_kuliah.php"     ,"Jadwal Kuliah"           ,"table"    ," "];
    $menu[8] = [1,"sesikul"     ,"modul/sesi_kuliah/sesi_kuliah.php"     ,"Sesi Kuliah"           ,"table"    ," "];
    $menu[9] = [1,"presensi"    ,"modul/presensi_kuliah/presensi_kuliah.php"     ,"Rekap Presensi"           ,"table"    ," "];
    $menu[10] = [1,"config"     ,"modul/konfigurasi_sistem/konfigurasi_sistem.php"     ,"Konfigurasi"           ,"genius"    ," "];

    switch ($c[0]) {
      case $menu[1][1]: $konten=$menu[1][2];$menu[1][5]=$mbold;break;
      case $menu[2][1]: $konten=$menu[2][2];$menu[2][5]=$mbold;break;
      case $menu[3][1]: $konten=$menu[3][2];$menu[3][5]=$mbold;break;
      case $menu[4][1]: $konten=$menu[4][2];$menu[4][5]=$mbold;break;
      case $menu[5][1]: $konten=$menu[5][2];$menu[5][5]=$mbold;break;
      case $menu[6][1]: $konten=$menu[6][2];$menu[6][5]=$mbold;break;
      case $menu[7][1]: $konten=$menu[7][2];$menu[7][5]=$mbold;break;
      case $menu[8][1]: $konten=$menu[8][2];$menu[8][5]=$mbold;break;
      case $menu[9][1]: $konten=$menu[9][2];$menu[9][5]=$mbold;break;
      case $menu[10][1]: $konten=$menu[10][2];$menu[10][5]=$mbold;break;

      default:          $konten=$menu[0][2];break;
    }

  }else{
    # ========================================================
    # NOT READY MENU
    # ========================================================
    #     is_show  param          script-name                          menu-caption         icon         styling
    #           0  1 -------------2 ---------------------------------- 3 ------------------ 4 ---------- 5
    $menu[1] = [0,""            ,"install.php"                     ,""                  ,"genius"    ," "];
    $menu[2] = [1,"dashboard"   ,"install.php"                     ,"Instalasi"         ,"easel_alt"    ," "];

    switch ($c[0]) {
      case $menu[1][1]: $konten=$menu[1][2];$menu[1][5]=$mbold;break;
      case $menu[2][1]: $konten=$menu[2][2];$menu[2][5]=$mbold;break;

      default:          $konten=$menu[0][2];break;
    }
  }
}

*/
