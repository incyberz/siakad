<h1>Reset Assign Ruang</h1>
<?php
$id_jadwal = $_GET['id_jadwal'] ?? die('<script>alert("ID Jadwal belum terdefinisi. Silahkan Manage Sesi !"); location.replace("?manage_sesi")</script>');

if(isset($_POST['btn_drop_all'])){
  $s = "SELECT a.id FROM tb_sesi_kuliah a WHERE a.id_jadwal=$id_jadwal";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));

  $s_del = "DELETE FROM tb_assign_ruang WHERE 0 ";
  while ($d=mysqli_fetch_assoc($q)) {
    $s_del .= " OR id_sesi_kuliah=$d[id] ";
  }

  $q = mysqli_query($cn,$s_del) or die(mysqli_error($cn));
  die(div_alert('info',"Semua Ruangan berhasil dihapus. | <a href='?manage_sesi_detail&id_jadwal=$id_jadwal'>Kembali</a>"));

}

echo "<span class=debug id=id_jadwal>$id_jadwal</span>";
$s = "SELECT 
a.shift,
c.kode as kode_mk,
c.nama as nama_mk,
(SELECT nama FROM tb_ruang WHERE id=a.id_ruang) nama_ruang 

FROM tb_jadwal a 
JOIN tb_kurikulum_mk b on b.id=a.id_kurikulum_mk 
JOIN tb_mk c on c.id=b.id_mk 

WHERE a.id=$id_jadwal";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
if(mysqli_num_rows($q)==0) die('Data Jadwal tidak ditemukan.');
$d = mysqli_fetch_assoc($q);
$Shift = ucwords($d['shift']);

echo "
<p class=red>Perhatian! Fitur ini digunakan untuk menghapus seluruh <u>Assign Ruang dari P1 s.d P16 pada Jadwal $d[nama_mk]</u> | $d[kode_mk] | <u>Kelas $Shift</u></p>
";

$s = "SELECT a.*,c.nama as nama_ruang, b.pertemuan_ke    
FROM tb_assign_ruang a 
JOIN tb_sesi_kuliah b ON a.id_sesi_kuliah=b.id 
JOIN tb_ruang c ON a.id_ruang=c.id 
WHERE b.id_jadwal=$id_jadwal 
";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
$tr='';
while ($d=mysqli_fetch_assoc($q)) {
  $tr.="<div><span class='btn btn-info btn-sm' onclick='alert(\"Ruang $d[nama_ruang] telah di-assign untuk Sesi Pertemuan-$d[pertemuan_ke].\")'>P$d[pertemuan_ke]-$d[nama_ruang]</span></div>";
}

echo "
<div class=wadah>
  <div class='blue mb2'>Berikut adalah ruang-ruang yang di assign pada jadwal ini.</div>
  <div class=flexy style=gap:5px>$tr</div>
  <form method=post class=mt2>
    <button class='btn btn-danger btn-block' onclick='return confirm(\"Yakin untuk men-drop semua ruangan ini pada Jadwal?\")' name=btn_drop_all>Drop All Ruang</button>
  </form>
</div>";