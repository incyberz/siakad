<?php 
$id_kelas_angkatan = isset($_GET['id_kelas_angkatan']) ? $_GET['id_kelas_angkatan'] : die(erid('id_kelas_angkatan'));
?>
<div class=subsistem>List Peserta: <?=$id_kelas_angkatan?></div>
<?php

$s = "SELECT 
b.id,
b.nim,
b.nama as nama_mhs,
c.kelas,
a.id as id_kelas_angkatan 

FROM tb_kelas_angkatan a 
JOIN tb_mhs b on b.id=a.id_mhs 
JOIN tb_kelas c on a.kelas=a.kelas  
WHERE a.id='$id_kelas_angkatan'
ORDER BY b.nama    
";
echo $s;
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
$jumlah_row = mysqli_num_rows($q);

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
  $btn_drop = "<button class='btn btn-danger btn-sm btn_aksi' id='drop__$d[id]'>Drop</button>";
  $kelas = $d['kelas']==''?'<span class="abu miring">-- null --</span>':$d['kelas'];
  $tr .= "<tr id=tr2__$d[id]>
    <td>$i</td>
    <td>$d[nim]</td>
    <td>$d[nama_mhs]</td>
    <td>$kelas</td>
    <td>
      $btn_drop
    </td>
  </tr>";
}

$jumlah_row_of = "Tampil $i data dari $jumlah_row total";

$tb = $tr=='' ? '<div class="alert alert-info">Mahasiswa tidak ditemukan. | <a href="?master&p=mhs">Master Mhs</a></div>' : "<table class='table table-hover'>$thead$tr</table>";

echo "<div class=wadah>Jumlah Peserta: $jumlah_row Mhs</div>$tb";




?>
