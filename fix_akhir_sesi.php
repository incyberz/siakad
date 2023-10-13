<?php
include 'conn.php';

$s = "SELECT a.*,
a.id as id_sesi,
b.id as id_jadwal,
b.awal_kuliah,
b.akhir_kuliah,
(d.bobot_teori+d.bobot_praktik)bobot

FROM tb_sesi a  
JOIN tb_jadwal b ON a.id_jadwal=b.id 
JOIN tb_kurikulum_mk c ON b.id_kurikulum_mk=c.id 
JOIN tb_mk d ON c.id_mk=d.id 
WHERE a.awal_sesi is not null 
and a.akhir_sesi is null 
and b.akhir_kuliah is not null 
and b.awal_kuliah is not null 
and a.pertemuan_ke>0

";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));

echo mysqli_num_rows($q). ' records<hr>';

$a='';
$b='';
while ($d=mysqli_fetch_assoc($q)) {
  // $akhir_kuliah = date('Y-m-d H:i:s',strtotime($d['awal_kuliah']) + $d['bobot']*45*60);
  // $akhir_kuliah = date('Y-m-d H:i:s',strtotime($d['awal_sesi']) + $d['bobot']*45*60);
  $durasi_detik = strtotime($d['akhir_kuliah'])-strtotime($d['awal_kuliah']);

  $akhir_sesi = date('Y-m-d H:i', strtotime($d['awal_sesi']) + $durasi_detik);

  $a.= "<br><br>P$d[pertemuan_ke] <br>sesi:$d[awal_sesi]~$akhir_sesi <br>jad:$d[awal_kuliah] s.d $d[akhir_kuliah] <br>$d[bobot]-SKS | $durasi_detik sec";
  $b.= "<br>UPDATE tb_sesi SET akhir_sesi='$akhir_sesi' WHERE id='$d[id_sesi]';";
}

echo "$a<hr>$b";
