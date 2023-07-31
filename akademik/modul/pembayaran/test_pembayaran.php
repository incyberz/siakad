<?php
$id_kurikulum = $_GET['id_kurikulum'] ?? die(erid('id_kurikulum'));
$shift = $_GET['shift'] ?? die(erid('shift'));
$s = "SELECT a.id_prodi, b.angkatan, c.singkatan as prodi   
FROM tb_kurikulum a 
JOIN tb_kalender b ON a.id_kalender=b.id 
JOIN tb_prodi c ON a.id_prodi=c.id 
WHERE a.id=$id_kurikulum";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
if(mysqli_num_rows($q)==0) die('Data kurikulum tidak ditemukan.');
$d=mysqli_fetch_assoc($q);

$s = "SELECT a.nim FROM tb_mhs a 
WHERE id_prodi=$d[id_prodi] 
AND angkatan=$d[angkatan] 
AND a.status_mhs=1 
AND shift='$shift' 
ORDER BY rand() 
LIMIT 1";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
if(mysqli_num_rows($q)==0){
  die(div_alert('danger', "Tidak ada mhs angkatan $d[angkatan]  prodi $d[prodi]  shift $shift "));
}
$d=mysqli_fetch_assoc($q);
die("<script>location.replace('?login_as&nim=$d[nim]')</script>");
