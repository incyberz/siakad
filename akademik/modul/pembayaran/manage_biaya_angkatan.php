<?php
$angkatan = isset($_GET['angkatan']) ? $_GET['angkatan'] : die(erid('angkatan'));
$id_prodi = isset($_GET['id_prodi']) ? $_GET['id_prodi'] : '';
echo "<span class=debug>id_prodi: <span id=id_prodi>$id_prodi</span> | angkatan: <span id=angkatan>$angkatan</span></span><h1>Manage Biaya Angkatan</h1>";

if($id_prodi==''){
  echo "<div>Untuk angkatan $angkatan prodi:</div>";
  $s = "SELECT id,nama,jenjang FROM tb_prodi";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  while ($d=mysqli_fetch_assoc($q)) {
    $d['nama'] = strtoupper($d['nama']);
    echo "<a class='btn btn-info mb2 btn-block' href='?manage_biaya_angkatan&angkatan=$angkatan&id_prodi=$d[id]'>$d[jenjang]-$d[nama]</a> ";
  }
  exit;
}else{
  $s = "SELECT nama,jenjang FROM tb_prodi where id=$id_prodi";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  if(mysqli_num_rows($q)==0) die('Data prodi tidak ditemukan.');
  $d = mysqli_fetch_assoc($q);
  $nama_prodi = "$d[jenjang]-$d[nama]";
}



$s = "SELECT a.*,
(SELECT nominal FROM tb_biaya_angkatan WHERE id_biaya=a.id and angkatan=$angkatan and id_prodi=$id_prodi) as nominal, 
(SELECT besar_cicilan FROM tb_biaya_angkatan WHERE id_biaya=a.id and angkatan=$angkatan and id_prodi=$id_prodi) as besar_cicilan 
FROM tb_biaya a ";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));

$tr_biaya="
<thead>
  <th>No</th>
  <th>Komponen Biaya</th>
  <th>Nominal</th>
  <th>Besar Cicilan</th>
</thead>
";
$i=0;
$null = '<code class=miring>null</code>';
$sum_nominal=0;
while ($d=mysqli_fetch_assoc($q)) {
  $i++;
  $nominal = $d['nominal']=='' ? $null : $d['nominal'];
  $sum_nominal += $d['nominal'];
  $besar_cicilan = $d['besar_cicilan']=='' ? $null : $d['besar_cicilan'];
  $id = $d['id'];

  $tr_biaya.="
  <tr>
    <td class= id=no__$id>$d[no]<span class=debug>$d[id]</span></td>
    <td class= id=nama__$id>$d[nama]</td>
    <td class=editable id=nominal__$id>$nominal</td>
    <td class=editable id=besar_cicilan__$id>$besar_cicilan</td>
  </tr>";
}
echo "<span class=debug>sum_nominal: $sum_nominal</span>";
if($sum_nominal==0){
  // set to default
  $autoset = "Semua nominal biaya masih kosong. Anda dapat setting Auto-Set Biaya Default berdasarkan Nominal Default pada Komponen Biaya. <hr><a href='#' class='btn btn-info'>Set Biaya Default</a>";
}else{
  // reset to default
  $autoset = "Anda sudah setting biaya angkatan $angkatan prodi $nama_prodi secara manual. <hr><a href='#' class='btn btn-danger'>Reset Semua Biaya ke Default</a>";
}

echo "<div class=wadah>$autoset</div>";


?>
<p>Berikut adalah Nominal Biaya untuk <b><u>Angkatan <?=$angkatan?></u></b> prodi <b><u><?=$nama_prodi?></u></b>.</p>
<table class="table">
  <?=$tr_biaya?>
</table>
<div class="kecil miring abu">Jika besar cicilan = <code>null</code> maka pembayaran tidak dapat dicicil.</div>