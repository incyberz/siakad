<?php
include '../../../conn.php';
include '../../../include/include_rid_prodi.php';
include '../../../include/include_rid_jalur.php';

$null = '<span class="red kecil miring consolas">null</span>';

$keyword = $_GET['keyword'] ?? die(erid('keyword'));
$angkatan = $_GET['angkatan'] ?? die(erid('angkatan'));
$status_mhs = $_GET['status_mhs'] ?? die(erid('status_mhs'));
$id_prodi = $_GET['id_prodi'] ?? die(erid('id_prodi'));
$id_jalur = $_GET['id_jalur'] ?? die(erid('id_jalur'));
$shift = $_GET['shift'] ?? die(erid('shift'));
$limit = $_GET['limit'] ?? die(erid('limit'));
$order_by = $_GET['order_by'] ?? die(erid('order_by'));

$filter_keyword = $keyword=='' ? '1' : "(a.nama like '%$keyword%' OR a.nim like '%$keyword%')";
$filter_angkatan = $angkatan=='all' ? '1' : "a.angkatan='$angkatan'";
$filter_shift = $shift=='all' ? '1' : "a.shift='$shift'";
$filter_jalur = $id_jalur=='all' ? '1' : "a.id_jalur='$id_jalur'";
$filter_prodi = $id_prodi=='all' ? '1' : "a.id_prodi='$id_prodi'";

$tr='';

# ================================================
$sql_columns = "
a.angkatan,
a.id_jalur,
a.id_prodi,
a.shift,
a.nama as nama_mhs,
a.nim
";

# ================================================
$sql_join = "
JOIN tb_prodi b ON a.id_prodi=b.id
";

# ================================================
$sql_where = "
status_mhs = $status_mhs  
AND $filter_angkatan 
AND $filter_prodi 
AND $filter_jalur 
AND $filter_shift 
AND $filter_keyword 
";

# ================================================
$s = "SELECT 1 FROM tb_mhs a $sql_join WHERE $sql_where";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
$jumlah_records = mysqli_num_rows($q);

# ================================================
$s = "SELECT $sql_columns FROM tb_mhs a $sql_join WHERE $sql_where
ORDER BY $order_by LIMIT $limit
";
// die($s);
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));

while ($d=mysqli_fetch_assoc($q)) {
  $jalur = $d['id_jalur']=='' ? $null : $rjalur[$d['id_jalur']];
  $prodi = $rprodi[$d['id_prodi']];
  $nama = $order_by=='a.nama' ? "$d[nama_mhs] | $d[nim]" : "$d[nim] | $d[nama_mhs]";
  $tr.="
  <tr>
    <td>$prodi</td>
    <td>$d[angkatan]</td>
    <td>$jalur</td>
    <td>$d[shift]</td>
    <td>$nama</td>
    <td>Edit | Delete</td>
  </tr>
  ";
}

$limit = $limit>$jumlah_records ? $jumlah_records : $limit;

echo "
<div class='show_records'>
  <div>Show $limit of <span style='color:blue; font-size:20px; display:inline-block; margin-left:5px'>$jumlah_records records</span></div>
  <div><button class='btn btn-primary btn-sm'>Download CSV</button></div>
</div>
<table class=table>
  <thead>
    <th>Prodi</th>
    <th>Angkatan</th>
    <th>Jalur</th>
    <th>Shift</th>
    <th>Mahasiswa</th>
    <th>Aksi</th>
  </thead>
  $tr
</table>
";

?>
