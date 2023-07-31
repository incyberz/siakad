<?php
include 'session_security.php';
include '../../conn.php';

$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : die(erid('keyword'));

# ===================================================
# LIST JADWAL
# ===================================================

$limit = 10;
$s = "SELECT 
a.id,
a.nim,
-- a.kelas,
a.nama as nama_mhs,
(
  SELECT b.kelas FROM tb_kelas_ta b
  JOIN tb_kelas_ta_detail c ON b.id=c.id_kelas_ta 
  WHERE c.id_mhs=a.id) as kelas  

FROM tb_mhs a 
WHERE (a.nim like '%$keyword%' OR a.nama like '%$keyword%') 

ORDER BY a.nama    
";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
$jumlah_row = mysqli_num_rows($q);

$s .= " LIMIT $limit 
";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));

$thead = '
  <thead>
    <th>No</th>
    <th>NIM</th>
    <th>Nama</th>
    <th>Kelas</th>
    <th>Aksi</th>
  </thead>
';
$tr = '';
$i=0;
while ($d = mysqli_fetch_assoc($q)) {
  $i++;
  $tr .= "<tr id='tr__$d[id]'>
    <td>$i</td>
    <td>$d[nim]</td>
    <td>$d[nama_mhs]</td>
    <td>$d[kelas]</td>
    <td>
      Edit | <a href='?presensi_per_mhs&id_mhs=$d[id]'>Presensi</a> | Pembayaran | KRS | KHS | Transkrip | Delete
    </td>
  </tr>";
}

$jumlah_row_of = "Tampil $i data dari $jumlah_row total";
$sisa_data = $i==$limit ? "$jumlah_row_of <span class=red>(masih ada data sisa)</span> silahkan filter dengan lebih spesifik." : $jumlah_row_of;

$tb = $tr=='' ? '<div class="alert alert-info">Mahasiswa tidak ditemukan. | <a href="?master&p=mhs">Master Mhs</a></div>' : "<table class='table table-hover'>$thead$tr</table>";

$debug = "<div class=debug>$s</div>";
echo "<div class=wadah>$sisa_data</div>$tb$debug";