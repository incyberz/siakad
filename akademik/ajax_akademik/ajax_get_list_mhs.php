<?php
include '../../conn.php';
include 'session_security.php';

$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : die(erid('keyword'));

# ===================================================
# LIST JADWAL
# ===================================================
$limit = 10;
$s = "SELECT 
a.id,
a.nim,
a.nama as nama_mhs,
a.kelas 

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
  $bg_merah = $d['kelas']=='' ? '' : 'bg-merah'; 
  $tr_sty = $d['kelas']=='' ? '' : 'style="background:linear-gradient(#fee,#fcc)"'; 
  $btn_assign = $d['kelas']=='' 
  ? "<button class='btn btn-primary btn-sm btn_aksi' id='assign__$d[id]'>Assign</button>"
  : "<button class='btn btn-danger btn-sm btn_aksi' id='move__$d[id]'>Move</button>";
  $kelas = $d['kelas']==''?'<span class="abu miring">-- null --</span>':$d['kelas'];
  $tr .= "<tr $tr_sty class='$bg_merah' id='tr__$d[id]'>
    <td>$i</td>
    <td>$d[nim]</td>
    <td>$d[nama_mhs]</td>
    <td>$kelas</td>
    <td>
      $btn_assign
    </td>
  </tr>";
}

$jumlah_row_of = "Tampil $i data dari $jumlah_row total";
$sisa_data = $i==$limit ? "$jumlah_row_of <span class=red>(masih ada data sisa)</span> silahkan filter dengan lebih spesifik." : $jumlah_row_of;

$tb = $tr=='' ? '<div class="alert alert-info">Mahasiswa tidak ditemukan. | <a href="?master&p=mhs">Master Mhs</a></div>' : "<table class='table table-hover'>$thead$tr</table>";

$debug = "<div class=debug>$s</div>";
echo "<div class=wadah>$sisa_data</div>$tb$debug";