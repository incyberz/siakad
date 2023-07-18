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
a.id,
d.nama as nama_kurikulum,
b.nomor as semester_ke,
f.nama as nama_mk,
d.id as id_kurikulum,
(SELECT id FROM tb_jadwal WHERE id_kurikulum_mk=a.id) as id_jadwal   

FROM tb_kurikulum_mk a  
JOIN tb_semester b on b.id=a.id_semester 
JOIN tb_kalender c on c.id=b.id_kalender 
JOIN tb_kurikulum d on d.id=a.id_kurikulum 
JOIN tb_prodi e on e.id=d.id_prodi 
JOIN tb_mk f on a.id_mk=f.id 

WHERE e.id = $id_prodi 
AND c.angkatan = $angkatan 
AND f.nama like '%$keyword%' 
ORDER BY b.nomor, f.nama
";

// die("<pre>$s</pre>");

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
  $goto_dpnu = $d['id_jadwal']=='' ? '<span class="btn btn-warning btn-sm dpnu_not_ready">DPNU</span>' : "<a href='?dpnu&id_jadwal=$d[id_jadwal]' class='btn btn-primary btn-sm'>DPNU</a>";
  $tr .= "<tr>
    <td>$i</td>
    <td>$d[nama_mk]</td>
    <td>$d[semester_ke]</td>
    <td>$d[nama_kurikulum]</td>
    <td>
      <a href='?manage_jadwal&id_kurikulum_mk=$d[id]' class='btn btn-info btn-sm proper'>Manage jadwal</a>
      $goto_dpnu
    </td>
  </tr>";
}

$tb = $tr=='' ? "<div class='alert alert-info'>MK tidak ditemukan. | <a href='?master&p=kurikulum'>Master Kurikulum</a></div>" : "<table class=table>$thead$tr</table>";

echo $tb;