<?php
$s = "SELECT 
a.kelas,
(SELECT count(1) FROM tb_kelas_ta_detail WHERE id_kelas_ta=a.id ) as jumlah_mhs    
FROM tb_kelas_ta a 
JOIN tb_kelas_peserta b on a.id=b.id_kelas_ta  
JOIN tb_kurikulum_mk c on c.id=b.id_kurikulum_mk 
JOIN tb_jadwal d on c.id=d.id_kurikulum_mk 

WHERE d.id=$id_jadwal
";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));

$thead = '';
$tr = '';
$i=0;
while ($d = mysqli_fetch_assoc($q)) {
  $i++;
  $tr .= "<tr>
    <td>$i</td>
    <td>$d[kelas]</td>
    <td>$d[jumlah_mhs] Mhs</td>
    <td>
      <a href='?manage_peserta&kelas=$d[kelas]' class='btn btn-info btn-sm proper'>Manage Peserta Mhs</a> 
      <a href='?dpnu&kelas=$d[kelas]&id_jadwal=$id_jadwal' class='btn btn-primary btn-sm '>DPNU</a> 
    </td>
  </tr>";
}

$tb = $tr=='' ? "<div class='alert alert-danger'>Belum ada Kelas Peserta pada Jadwal ini. | <a href='?manage_kelas&id_jadwal=$id_jadwal' target=_blank>Manage Kelas</a></div>" : "
<div class=wadah>
  <p class=biru>Silahkan Pilih Kelas untuk melihat DPNU</p>
  <table class=table>
    $thead
    $tr
  </table>
</div>
";

echo $tb;
