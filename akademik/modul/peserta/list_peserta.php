<?php 
$id_kelas_ta = isset($_GET['id_kelas_ta']) ? $_GET['id_kelas_ta'] : die(erid('id_kelas_ta'));
?>
<div class=subsistem>List Peserta: <?=$id_kelas_ta?></div>
<?php

$s = "SELECT 
b.id as id_mhs,
b.nim,
b.nama as nama_mhs,
c.kelas,
a.id as id_kelas_ta_detail 

FROM tb_kelas_ta_detail a 
JOIN tb_mhs b on b.id=a.id_mhs 
JOIN tb_kelas_ta c on a.id_kelas_ta=c.id  
WHERE c.id='$id_kelas_ta'
ORDER BY b.nama    
";
echo "<span class=debug>$s</span>";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
$jumlah_row = mysqli_num_rows($q);

$thead = "
  <thead>
    <th>No</th>
    <th>NIM</th>
    <th>Nama</th>
    <th>Kelas (TA-$tahun_ajar)</th>
    <th>Aksi</th>
  </thead>
";
$tr = '';
$i=0;
while ($d = mysqli_fetch_assoc($q)) {
  $i++;
  $btn_drop = "<button class='btn btn-danger btn-sm btn_aksi' id='drop__$d[id_mhs]__$d[id_kelas_ta_detail]'>Drop</button>";
  $kelas = $d['kelas']==''?'<span class="abu miring">-- null --</span>':$d['kelas'];
  $tr .= "<tr id=tr2__$d[id_mhs]>
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
