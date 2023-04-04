<?php
# ============================================================
# CONFIG PHP v.3
# ============================================================


# ============================================================
# DATABASE CONNECTION
# ============================================================
include 'conn.php';

# ============================================================
# DATE AND TIMEZONE
# ============================================================
// $tanggal_skg = date("Y-m-d");
// $saat_ini = date("Y-m-d H:i:sa");
// $jam_skg = date("H:i:sa");
// $tahun_skg = date("Y");
// $thn_skg = date("y");
$waktu = "Pagi";
if(date("H")>=9) $waktu = "Siang";
if(date("H")>=15) $waktu = "Sore";
if(date("H")>=18) $waktu = "Malam";

$nama_hari = ['Ahad','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
$nama_bulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
$hari_ini = $nama_hari[date('w')].', '.date('d').' '.$nama_bulan[intval(date('m'))-1].' '.date('Y');

$now = date('Y-m-d H:i:s');
$today = date('Y-m-d');
$null = '<span class="miring small">--null--</span>';


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

function go($a){
  $b = strtolower(str_replace(' ','_',$a));
  $c = ucwords(strtolower(str_replace('_',' ',$a)));
  return " | <a href='?$b'>$c</a>";
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

function div_alert($a,$b){
  return "<div class='alert alert-$a'>$b</div>";
}

function nr(){
  return '<div class="alert alert-info">Maaf, fitur ini masih dalam tahap pengembangan. | <a href="javascript:history.go(-1)">Kembali</a></div>';
}
?>