<?php
$s = "SELECT 
a.id as id_sesi_kuliah,
a.pertemuan_ke,
a.nama as nama_sesi,
a.tanggal_sesi,
a.status as status_presensi,
b.nama as nama_dosen,
(SELECT nama from tb_ruang where id=a.id_ruang) as nama_ruang 

FROM tb_sesi_kuliah a 
JOIN tb_dosen b on b.id=a.id_dosen 

WHERE a.id_jadwal=$id_jadwal";
// echo "<pre>$s</pre>";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
if(mysqli_num_rows($q)==0) die(div_alert('danger',"Jadwal ini belum punya Sesi Kuliah. | <a href='?manage_sesi&id_jadwal=$id_jadwal' target=_blank>Manage Sesi</a>"));

$thead = "
  <thead>
    <th class='proper text-left'>p-ke</th>
    <th class='proper text-left'>nama sesi</th>
    <th class='proper text-left'>tanggal sesi</th>
    <th class='proper text-left'>dosen pengajar</th>
    <th class='proper text-left'>nama ruang</th>
    <th class='proper text-left'>Aksi</th>
  </thead>
";
$tr = '';
while ($d=mysqli_fetch_assoc($q)) {
  $tanggal_sesi = date('d-M-y ~ H:i',strtotime($d['tanggal_sesi']));
  $nama_ruang = $d['nama_ruang']=='' ? $null : $d['nama_ruang'];

  $tr .= "
  <tr>
    <td>$d[pertemuan_ke]</td>
    <td>$d[nama_sesi]</td>
    <td>$tanggal_sesi</td>
    <td>$d[nama_dosen]</td>
    <td>$nama_ruang</td>
    <td>
      <a href='?dpnu&kelas=$kelas&id_jadwal=$id_jadwal&id_sesi_kuliah=$d[id_sesi_kuliah]' class='btn btn-info btn-sm'>Daftar Hadir Sesi</a>
    </td>
  </tr>
  ";
}
echo "<div class=wadah>
  <h3>Detail Daftar Hadir Sesi</h3>
  <table class=table>$thead$tr</table>
</div>";
