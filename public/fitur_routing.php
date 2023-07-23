<?php 
// $fitur = [
//   "akademik/?",
//   "akademik/?ambil_krs",
//   "akademik/?khs",
//   "akademik/?manage_jadwal",
//   "akademik/?master",
//   "akademik/?khs",
//   "akademik/?manage_mhs",
//   "akademik/?master&p=dosen",
//   "akademik/?master&p=tendik",
//   "akademik/?pembayaran_home",
//   "inventory",
//   "perpus"
// ];

// $nama_fitur = [
//   "Data Mahasiswa",
//   "KRS Online",
//   "Layanan KHS",
//   "Jadwal Kuliah",
//   "Skripsi",
//   "Transkrip",
//   "Data Lulusan",
//   "Data Dosen",
//   "Data Tendik",
//   "Keuangan",
//   "Inventory",
//   "Perpustakaan"
// ];






// if($is_login){
//   # =====================================================================================
//   # LINK CONTROLLERS 
//   # =====================================================================================
//   $mbold = "style='color:yellow;font-weight:bold;'";
//   # =====================================================================================
//   #                                                                   ICON      STY  SHOW
//   # INDEX ---- 0 ---------- 1 ----------------- 2 --------------- 3 -------- 4 - 5
//   $menu0 = [""              ,"dashboard.php"    ,"Dashboard"      ,"house_alt" ," " ,1];
//   $menu1 = [$fitur[0]       ,$fitur[0].".php"   ,$nama_fitur[0]   ,"genius"    ," " ,0];
//   $menu2 = [$fitur[1]       ,$fitur[1].".php"   ,$nama_fitur[1]   ,"genius"    ," " ,0];
//   $menu3 = [$fitur[2]       ,$fitur[2].".php"   ,$nama_fitur[2]   ,"genius"    ," " ,0];
//   $menu4 = [$fitur[3]       ,$fitur[3].".php"   ,$nama_fitur[3]   ,"genius"    ," " ,0];
//   $menu5 = [$fitur[4]       ,$fitur[4].".php"   ,$nama_fitur[4]   ,"genius"    ," " ,0];
//   $menu6 = [$fitur[5]       ,$fitur[5].".php"   ,$nama_fitur[5]   ,"genius"    ," " ,0];
//   $menu7 = [$fitur[6]       ,$fitur[6].".php"   ,$nama_fitur[6]   ,"genius"    ," " ,0];
//   $menu8 = [$fitur[7]       ,$fitur[7].".php"   ,$nama_fitur[7]   ,"genius"    ," " ,0];
//   $menu9 = [$fitur[8]       ,$fitur[8].".php"   ,$nama_fitur[8]   ,"genius"    ," " ,0];
//   $menu10 = [$fitur[9]      ,$fitur[9].".php"   ,$nama_fitur[9]   ,"genius"    ," " ,0];
//   $menu11 = [$fitur[10]     ,$fitur[10].".php"  ,$nama_fitur[10]  ,"genius"    ," " ,0];
//   $menu12 = [$fitur[11]     ,$fitur[11].".php"  ,$nama_fitur[11]  ,"genius"    ," " ,0];

//   $a = $_SERVER['REQUEST_URI'];
//   if (!strpos($a, "?")) $a.="?";
//   if (!strpos($a, "&")) $a.="&";

//   $b = explode("?", $a);
//   $c = explode("&", $b[1]);

//   switch ($c[0]) {
//     case $menu1[0]: $page_content=$menu1[1];$menu1[4]=$mbold;break;
//     case $menu2[0]: $page_content=$menu2[1];$menu2[4]=$mbold;break;
//     case $menu3[0]: $page_content=$menu3[1];$menu3[4]=$mbold;break;
//     case $menu4[0]: $page_content=$menu4[1];$menu4[4]=$mbold;break;
//     case $menu5[0]: $page_content=$menu5[1];$menu5[4]=$mbold;break;
//     case $menu6[0]: $page_content=$menu6[1];$menu6[4]=$mbold;break;
//     case $menu7[0]: $page_content=$menu7[1];$menu7[4]=$mbold;break;
//     case $menu8[0]: $page_content=$menu8[1];$menu8[4]=$mbold;break;
//     case $menu9[0]: $page_content=$menu9[1];$menu9[4]=$mbold;break;
//     case $menu10[0]: $page_content=$menu10[1];$menu10[4]=$mbold;break;
//     case $menu11[0]: $page_content=$menu11[1];$menu11[4]=$mbold;break;
//     case $menu12[0]: $page_content=$menu12[1];$menu12[4]=$mbold;break;
//     default:        $page_content=$menu0[1];$menu0[4]=$mbold;break;
//   }
// }
?>