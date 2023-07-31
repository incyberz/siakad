<?php
$s = "SELECT 
a.id as id_mhs,
a.nim,
a.nama as nama_mhs
FROM tb_mhs a 
JOIN tb_kelas_ta_detail b ON a.id=b.id_mhs 
JOIN tb_kelas_ta c ON b.id_kelas_ta=c.id 
WHERE c.kelas='$kelas' 
ORDER BY a.nama
";
// echo "<pre>$s</pre>";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
if(mysqli_num_rows($q)==0) die(div_alert('danger',"Jadwal ini belum punya Sesi Kuliah. | <a href='?manage_sesi&id_jadwal=$id_jadwal' target=_blank>Manage Sesi</a>"));

$thead = "
  <thead>
    <th class='text-left'>No</th>
    <th class='text-left'>NIM</th>
    <th class='text-left'>Nama</th>
    <th class='text-left'>Kehadiran</th>
    <th class='text-left'>Tugas</th>
    <th class='text-left'>UTS</th>
    <th class='text-left'>UAS</th>
    <th class='text-left'>Nilai Akhir</th>
    <th class='text-left'>Huruf Mutu</th>
  </thead>
";
$tr = '';
$jumlah_mhs=0;
while ($d=mysqli_fetch_assoc($q)) {
  $jumlah_mhs++;
  $d['status_presensi']=''; //zzz

  $tr .= "
  <tr>
    <td>$jumlah_mhs</td>
    <td>$d[nim]</td>
    <td>$d[nama_mhs]</td>
    <td>0 %</td>
    <td>0</td>
    <td>0</td>
    <td>0</td>
    <td>0</td>
    <td>E</td>
  </tr>
  ";
}
$opsi = "<div class=wadah>
  <span class='btn btn-primary btn-sm not_ready'>Cetak PDF</span> 
  <span class='btn btn-success btn-sm not_ready'>Export Excel</span> 
</div>
";
echo "<div class=wadah><table class=table>$thead$tr</table> $opsi</div>";
