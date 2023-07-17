<?php
include '../../conn.php';
include 'session_security.php';

$angkatan = isset($_GET['angkatan']) ? $_GET['angkatan'] : die(erid('angkatan'));
$id_prodi = isset($_GET['id_prodi']) ? $_GET['id_prodi'] : die(erid('id_prodi'));
// $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : die(erid('keyword'));


# ===================================================
# LIST KALENDER
# ===================================================
$s = "SELECT 
a.angkatan,
a.jenjang,
a.id as id_kalender,
d.last_update,
b.id as id_kurikulum,
c.nama as nama_prodi,
c.id as id_prodi, 
d.id as id_semester,  
d.nomor as nomor_semester   

FROM tb_kalender a 
JOIN tb_kurikulum b on a.id=b.id_kalender
JOIN tb_prodi c on c.id=b.id_prodi 
JOIN tb_semester d on a.id=d.id_kalender 
WHERE c.id = $id_prodi 
AND a.angkatan = $angkatan 
";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));

$thead = '
  <thead>
    <th>Angkatan</th>
    <th>Kurikulum</th>
    <th>Semester</th>
    <th>Last Update</th>
    <th>Aksi</th>
  </thead>
';
$tr = '';
while ($d = mysqli_fetch_assoc($q)) {
  $is_manage = $d['last_update']=='' ? '--Belum--' : "$d[last_update]";
  $merah = $d['last_update']=='' ? 'merah' : 'hijau';
  $tr .= "<tr>
    <td>$d[angkatan]</td>
    <td>$d[jenjang] ~ $d[angkatan] ~ $d[nama_prodi]</td>
    <td>$d[nomor_semester]</td>
    <td class='text-center gradasi-$merah kecil miring'>$is_manage</td>
    <td>
      <a class='btn btn-primary' href='?manage_semester&id_semester=$d[id_semester]'>Manage Semester<span class=debug>id:$d[id_semester]idk:$d[id_kurikulum]</span></a>
      <a class='btn btn-primary' href='?manage_kurikulum&id_kurikulum=$d[id_kurikulum]'>Manage Kurikulum MK</a>
    </td>
  </tr>";
}

$debug = '';
$debug = "<pre>$s</pre>";

$tb = $tr=='' ? "<div class='alert alert-info'>Kalender tidak ditemukan. | <a href='?manage_kalender'>Manage Kalender</a>$debug</div>" : "<table class=table>$thead$tr</table>";

echo $tb;


