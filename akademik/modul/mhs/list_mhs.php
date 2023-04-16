<h3>List Mahasiswa Zzz</h3>
<?php

$s = "SELECT 
a.id,
a.nim,
a.nama as nama_mhs,
a.kelas 

FROM tb_mhs a 
WHERE 1 
ORDER BY a.nama    
";
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
  $btn_presensi = "<button class='btn btn-info btn-sm btn_aksi proper' id='presensi__$d[id]'>presensi</button>";
  $kelas = $d['kelas']==''?'<span class="abu miring">-- null --</span>':$d['kelas'];
  $tr .= "<tr id=tr2__$d[id]>
    <td>$i</td>
    <td>$d[nim]</td>
    <td>$d[nama_mhs]</td>
    <td>$kelas</td>
    <td>
      $btn_presensi
    </td>
  </tr>";
}

$jumlah_row_of = "Tampil $i data dari $jumlah_row total";

$tb = $tr=='' ? '<div class="alert alert-info">Mahasiswa tidak ditemukan. | <a href="?master&p=mhs">Master Mhs</a></div>' : "<table class='table table-hover'>$thead$tr</table>";

echo "<div class=wadah>Jumlah Peserta: $jumlah_row Mhs</div>$tb";




?>
