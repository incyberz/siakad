<?php
include '../../conn.php';
include 'session_security.php';

$angkatan = isset($_GET['angkatan']) ? $_GET['angkatan'] : die(erid('angkatan'));
$id_prodi = isset($_GET['id_prodi']) ? $_GET['id_prodi'] : die(erid('id_prodi'));
$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : die(erid('keyword'));

# ===================================================
# LIST JADWAL
# ===================================================
$s = "SELECT 
a.id,
g.nama as nama_mk   
FROM tb_jadwal a 
JOIN tb_kurikulum_mk b on b.id=a.id_kurikulum_mk 
JOIN tb_kurikulum d on d.id=b.id_kurikulum 
JOIN tb_prodi e on e.id=d.id_prodi 
JOIN tb_kalender f on f.id=d.id_kalender 
JOIN tb_mk g on g.id=b.id_mk 
WHERE e.id = $id_prodi 
AND f.angkatan = $angkatan 
";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));

$thead = '';
$tr = '';
$i=0;
while ($d = mysqli_fetch_assoc($q)) {
  $i++;
  $tr .= "<tr>
    <td>$i</td>
    <td>$d[nama_mk]</td>
    <td>
      <a href='?manage_kelas&id_jadwal=$d[id]' class='btn btn-info btn-sm proper'>Manage Kelas</a>
      <a href='?manage_sesi&id_jadwal=$d[id]' class='btn btn-info btn-sm proper'>Manage sesi</a>
    </td>
  </tr>";
}

$tb = $tr=='' ? '<div class="alert alert-info">Jadwal tidak ditemukan. | <a href="?manage_jadwal">Manage Jadwal</a></div>' : "<table class=table>$thead$tr</table>";

echo $tb;