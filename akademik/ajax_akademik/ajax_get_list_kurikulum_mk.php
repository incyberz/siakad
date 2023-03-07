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
b.id,
d.nama as nama_kurikulum,
c.nomor as semester_ke,
g.nama as nama_mk,
d.id as id_kurikulum   
FROM tb_kurikulum_mk b  
JOIN tb_semester c on c.id=b.id_semester 
JOIN tb_kurikulum d on d.id=c.id_kurikulum 
JOIN tb_prodi e on e.id=d.id_prodi 
JOIN tb_kalender f on f.id=d.id_kalender 
JOIN tb_mk g on b.id_mk=g.id 

WHERE e.id = $id_prodi 
AND f.angkatan = $angkatan 
AND d.nama like '%$keyword%' 
ORDER BY c.nomor, g.nama
";

$q = mysqli_query($cn,$s) or die(mysqli_error($cn));

$thead = '
<thead>
  <th>No</th>
  <th>Mata Kuliah</th>
  <th>Semester</th>
  <th>Kurikulum</th>
  <th>Aksi</th>
</thead>
';
$tr = '';
$i=0;
while ($d = mysqli_fetch_assoc($q)) {
  $i++;
  $tr .= "<tr>
    <td>$i</td>
    <td>$d[nama_mk]</td>
    <td>$d[semester_ke]</td>
    <td>$d[nama_kurikulum] | <a href='?manage_kurikulum&id=$d[id_kurikulum]'>Manage</a></td>
    <td>
      <a href='?manage_jadwal&id_kurikulum_mk=$d[id]' class='btn btn-info btn-sm proper'>Manage jadwal</a>
    </td>
  </tr>";
}

$tb = $tr=='' ? "<div class='alert alert-info'>MK tidak ditemukan. | <a href='?master&p=kurikulum'>Master Kurikulum</a></div>" : "<table class=table>$thead$tr</table>";

echo $tb;