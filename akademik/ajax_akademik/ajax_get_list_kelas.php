<?php
include 'session_security.php';
include '../../conn.php';

$angkatan = isset($_GET['angkatan']) ? $_GET['angkatan'] : die(erid('angkatan'));
$id_prodi = isset($_GET['id_prodi']) ? $_GET['id_prodi'] : die(erid('id_prodi'));
$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : die(erid('keyword'));

# ===================================================
# LIST JADWAL
# ===================================================
$s = "SELECT 
a.kelas,
b.nama as nama_jalur,
(SELECT count(1) FROM tb_kelas_angkatan WHERE kelas=a.kelas ) as jumlah_mhs    
FROM tb_kelas a 
JOIN tb_jalur b on a.id_jalur = b.id 
WHERE a.id_prodi = $id_prodi 
AND a.angkatan = $angkatan 
AND a.kelas like '%$keyword%'  
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
    </td>
  </tr>";
}

$tb = $tr=='' ? '<div class="alert alert-info">Kelas tidak ditemukan. | <a href="?manage_kelas">Manage Kelas</a></div>' : "<table class=table>$thead$tr</table>";

echo $tb;