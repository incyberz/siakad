<?php 
include 'session_security.php';
include '../../conn.php';
include 'akademik_only.php';

# ================================================
# GET VARIABLES
# ================================================
$id_jadwal = $_GET['id_jadwal'] ?? die(erid("id_jadwal"));
$awal_kuliah = $_GET['awal_kuliah'] ?? die(erid("awal_kuliah"));
$akhir_kuliah = $_GET['akhir_kuliah'] ?? die(erid("akhir_kuliah"));
$bobot = $_GET['bobot'] ?? die(erid("bobot"));
$confirm = $_GET['confirm'] ?? 0;

// $akhir_kuliah = date('Y-m-d H:i',strtotime($awal_kuliah)+$bobot*45*60); // bukan lagi auto
// $jam_akhir_kuliah = date('H:i',strtotime($akhir_kuliah));

// $s = "UPDATE tb_jadwal SET awal_kuliah='$awal_kuliah',akhir_kuliah='$akhir_kuliah' WHERE id='$id_jadwal'";

// get id_semester from data jadwal untuk cek bentrok
$s = "SELECT a.id,a.nomor as no, c.shift, b.id_kurikulum  
FROM tb_semester a 
JOIN tb_kurikulum_mk b ON a.id=b.id_semester
JOIN tb_jadwal c ON b.id=c.id_kurikulum_mk 
WHERE c.id=$id_jadwal
";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
if(mysqli_num_rows($q)!=1) die('AJAX. Data semester tidak ditemukan.');
$d=mysqli_fetch_assoc($q);
$id_semester = $d['id'];
$id_kurikulum = $d['id_kurikulum'];
$shift = $d['shift'];
$no_semester = $d['no'];

// get list jadwal pada id_semester
$s = "SELECT a.*,
d.nama as nama_mk,
d.kode as kode_mk,
(d.bobot_teori + d.bobot_praktik) bobot  
FROM tb_jadwal a 
JOIN tb_kurikulum_mk b ON a.id_kurikulum_mk=b.id 
JOIN tb_mk d ON b.id_mk=d.id 
WHERE b.id_kurikulum=$id_kurikulum  
AND a.awal_kuliah is not null 
AND a.shift = '$shift' 
";
// die($s);
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
$tr = '';
$taw = strtotime($awal_kuliah);
$tak = strtotime($akhir_kuliah);
$bentrok_awal = 0;
$bentrok_akhir = 0;
$awal_kuliah_show = date('H:i',strtotime($awal_kuliah));
$akhir_kuliah_show = date('H:i',strtotime($akhir_kuliah));

while ($d=mysqli_fetch_assoc($q)) {
  if($d['id']==$id_jadwal) continue;
  $d_taw = strtotime($d['awal_kuliah']);
  $d_tak = $d['akhir_kuliah']!='' ? strtotime($d['akhir_kuliah']) : $d_taw+($d['bobot']*45*60); //tidak lagi auto
  $d_ak = date('Y-m-d H:i',$d_tak);

  $d_aw_show = date('H:i',$d_taw);
  $d_ak_show = date('H:i',$d_tak);

  if($taw <= $d_taw AND $tak >= $d_tak){
    $bentrok_awal = 1;
    $bentrok_akhir = 1;

    $awal_kuliah_show = "<span class=red>$awal_kuliah_show</span>";
    $akhir_kuliah_show = "<span class=red>$akhir_kuliah_show</span>";
  }else{
    if($taw >= $d_taw AND $taw <$d_tak){
      $bentrok_awal = 1;
      $awal_kuliah_show = "<span class=red>$awal_kuliah_show</span>";
    }
    if($tak > $d_taw AND $tak <=$d_tak){
      $bentrok_akhir = 1;
      $akhir_kuliah_show = "<span class=red>$akhir_kuliah_show</span>";
    }
  }

  if($bentrok_awal || $bentrok_akhir){
    $tr.="<div class=red>Bentrok dg $d[nama_mk] | $d[kode_mk] <div class=consolas>old: $d_aw_show - $d_ak_show</div></div>";
    die("
    <div class='wadah gradasi-merah mt1'>
      $tr
      <div class=consolas>new: $awal_kuliah_show - $akhir_kuliah_show</div>
    </div>");
  }
}

if($confirm){
  $s = "UPDATE tb_jadwal SET awal_kuliah='$awal_kuliah',akhir_kuliah='$akhir_kuliah' WHERE id='$id_jadwal'";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
}
die('sukses');