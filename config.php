<?php
# ============================================================
# CONFIG PHP v.3
# ============================================================


# ============================================================
# DATABASE CONNECTION
# ============================================================
$online_version = 1;
if ($_SERVER['SERVER_NAME'] == "localhost") $online_version = 0;

if ($online_version) {
  $db_server = "localhost";
  $db_user = "siakadikmiac_admsiakad";
  $db_pass = "SiakadIKMICirebon2022";
  $db_name = "siakadikmiac_siakad";
}else{
  $db_server = "localhost";
  $db_user = "root";
  $db_pass = '';

  $db_name = "db_siakad";
}

$cn = new mysqli($db_server, $db_user, $db_pass, $db_name);
if ($cn -> connect_errno) {
  echo "Error Konfigurasi# Tidak dapat terhubung ke MySQL Server :: $db_name";
  exit();
}


# ============================================================
# DATE AND TIMEZONE
# ============================================================
date_default_timezone_set("Asia/Jakarta");
$tanggal_skg = date("Y-m-d");
$saat_ini = date("Y-m-d H:i:sa");
$jam_skg = date("H:i:sa");
$tahun_skg = date("Y");
$thn_skg = date("y");
$waktu = "Pagi";
if(date("H")>=9) $waktu = "Siang";
if(date("H")>=15) $waktu = "Sore";
if(date("H")>=18) $waktu = "Malam";

$nama_hari = ['Ahad','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
$nama_bulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
$hari_ini = $nama_hari[date('w')].', '.date('d').' '.$nama_bulan[intval(date('m'))-1].' '.date('Y');



# ============================================================
# IS IDENTITY
# ============================================================
$nama_si 	= "SIAKAD STMIK IKMI"; 
$judul_menu = "SIAKAD IKMI"; 
$lembaga 	= "STMIK IKMI";
$title 		= "$judul_menu :: $lembaga"; // muncul di title
$nama_author = "Iin Sholihin";
$tahun_release = 2021; 
$dev_kontak = '';
 
$dev_name = "Iin Sholihin, M.Kom"; 




# ============================================================
# DEFAULT UI
# ============================================================
$link_back = "<a href='javascript:history.go(-1)'>Kembali</a>";
$btn_back = "<a href='javascript:history.go(-1)'><button class='btn btn-primary' style='margin-top:5px;margin-bottom:5px'>Kembali</button></a>";

$bm = '<span style="color: red;font-weight: bold">*</span>';
$img_wa = "<img src='assets/img/icons/wa.png' width=20px class='img_zoom' />";



# ============================================================
# PUBLIC FUNCTIONS
# ============================================================
function artikan_kode($nama_kode,$nilai){
  switch (strtolower($nama_kode)) {
    case 'status_menikah':
      if($nilai==1) return "Belum Menikah";
      if($nilai==2) return "Menikah";
      if($nilai==3) return "Janda/Duda";
      break;
    case 'jk':
      if(strtoupper($nilai)=="L") return "Laki-laki";
      if(strtoupper($nilai)=="P") return "Perempuan";
      break;
    case 'agama':
      if($nilai==1) return "Islam";
      if($nilai==2) return "Katolik";
      if($nilai==3) return "Protestan";
      if($nilai==4) return "Hindu";
      if($nilai==5) return "Budha";
      if($nilai==6) return "Konghucu";
      if($nilai==7) return "Lainnya";
      break;
    case 'warga_negara':
      if($nilai==1) return "WNI";
      if($nilai==2) return "WNA";
  }
}

function go($a){
  $b = strtolower(str_replace(' ','_',$a));
  $c = ucwords(strtolower(str_replace('_',' ',$a)));
  return " | <a href='?$b'>$c</a>";
}

function erid($a)
{
    return "Error, index $a belum terdefinisi.";
}

function durasi_hari($a,$b){
  if (intval($a) == 0 || intval($b) == 0) {
    return "-";
    
  } 
  $dStart = new DateTime($a);
  $dEnd  = new DateTime($b);
  $dDiff = $dStart->diff($dEnd);
  return $dDiff->format('%r%a'); 

}


function frp($x){
  return "Rp ".fnum($x).",-";
}

function fnum($x){
  switch (strlen($x)) {
    case 1: 
    case 2: 
    case 3: $y = $x; break;

    case 4: $y = substr($x,0,1).".".substr($x,1,3); break;
    case 5: $y = substr($x,0,2).".".substr($x,2,3); break;
    case 6: $y = substr($x,0,3).".".substr($x,3,3); break;

    case 7: $y = substr($x,0,1).".".substr($x,1,3).".".substr($x,4,3); break;
    case 8: $y = substr($x,0,2).".".substr($x,2,3).".".substr($x,5,3); break;
    case 9: $y = substr($x,0,3).".".substr($x,3,3).".".substr($x,6,3); break;
    
    default: $y = "Out of length digit.";break;
  }
  
  if ($y == 0) {
    return "-";
  }else{return "$y";}
  
}




function penyebut($nilai) {
  $nilai = abs($nilai);
  $huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
  $temp = '';

  if ($nilai < 12) {
    $temp = " ". $huruf[$nilai];
  } else if ($nilai <20) {
    $temp = penyebut($nilai - 10). " belas";
  } else if ($nilai < 100) {
    $temp = penyebut($nilai/10)." puluh". penyebut($nilai % 10);
  } else if ($nilai < 200) {
    $temp = " seratus" . penyebut($nilai - 100);
  } else if ($nilai < 1000) {
    $temp = penyebut($nilai/100) . " ratus" . penyebut($nilai % 100);
  } else if ($nilai < 2000) {
    $temp = " seribu" . penyebut($nilai - 1000);
  } else if ($nilai < 1000000) {
    $temp = penyebut($nilai/1000) . " ribu" . penyebut($nilai % 1000);
  } else if ($nilai < 1000000000) {
    $temp = penyebut($nilai/1000000) . " juta" . penyebut($nilai % 1000000);
  } else if ($nilai < 1000000000000) {
    $temp = penyebut($nilai/1000000000) . " milyar" . penyebut(fmod($nilai,1000000000));
  } else if ($nilai < 1000000000000000) {
    $temp = penyebut($nilai/1000000000000) . " trilyun" . penyebut(fmod($nilai,1000000000000));
  }     
  return $temp;
}

function terbilang($nilai) {
  if($nilai<0) {
    $hasil = "minus ". trim(penyebut($nilai));
  } else {
    $hasil = trim(penyebut($nilai));
  }         
  return $hasil;
}


?>