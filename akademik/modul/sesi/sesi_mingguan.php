<h1>Sesi Hari Ini</h1>
<?php
$hari_ini = date('Y-m-d');
$besok = date('Y-m-d H:i',strtotime('+1 day', strtotime('today')));
$hari_ini_show = $nama_hari[date('w',strtotime('today'))].', '.date('d M Y H:i',strtotime('now'));
echo "<div class='mb2'>Hari ini : $hari_ini_show</div>";

$s = "SELECT a.*,
a.nama as nama_sesi 
FROM tb_sesi a 
WHERE a.tanggal_sesi >= '$hari_ini' AND a.tanggal_sesi < '$besok' ";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));

$tr = '';
if(mysqli_num_rows($q)){
  $i=0;
  while ($d=mysqli_fetch_assoc($q)) {
    $i++;
    $tr.= "
    <tr>
      <td>$i</td>
      <td>$d[nama_sesi]</td>
      <td>$d[tanggal_sesi]</td>
      <td>$d[tanggal_sesi]</td>
    </tr>";
  }
}

echo $tr=='' ? div_alert('danger', 'Tidak ada sesi perkuliahan hari ini. ') : "<table class=table>$tr</table>";

