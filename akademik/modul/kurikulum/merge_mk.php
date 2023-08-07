<h1>Merge MK</h1>
<?php
$id_kurikulum_mk = $_GET['id_kurikulum_mk'] ?? die(erid('id_kurikulum_mk'));


# ==============================================================
# GET KURIKULUM DATA
# ==============================================================
$s = "SELECT 
a.id as id_kurikulum, 
b.nama as nama_prodi, 
b.id as id_prodi, 
d.jenjang,
d.angkatan,
d.id as id_kalender, 
e.singkatan as prodi  

FROM tb_kurikulum a 
JOIN tb_prodi b ON b.id=a.id_prodi 
JOIN tb_kurikulum_mk c ON c.id_kurikulum=a.id 
JOIN tb_kalender d ON a.id_kalender=d.id 
JOIN tb_prodi e ON a.id_prodi=e.id 
WHERE c.id='$id_kurikulum_mk'";
// echo "<pre>$s</pre>";
$q = mysqli_query($cn, $s)or die(mysqli_error($cn));
if(mysqli_num_rows($q)==0) die('Data Kurikulum tidak ditemukan.');
if(mysqli_num_rows($q)>1) die('Duplikat Kurikulum detected.');
$d=mysqli_fetch_assoc($q);
echo "<p>
  Fitur ini digunakan untuk memindahkan semua sub-trx MK terhadap MK lain agar tidak terjadi duplikat MK pada Kurikulum <a href='?manage_kurikulum&id_kurikulum=$d[id_kurikulum]'>$d[jenjang]-$d[prodi]-$d[angkatan]</a>
</p>";

# ==============================================================
# SUB-TRX-NILAI
# ==============================================================
$rsub = ['nilai','nilai_history','jadwal','kelas_peserta'];
foreach ($rsub as $sub) {
  $s = "SELECT * FROM tb_$sub WHERE id_kurikulum_mk=$id_kurikulum_mk";
  $q = mysqli_query($cn, $s)or die(mysqli_error($cn));
  echo "<div>Jumlah sub-trx-$sub: ". mysqli_num_rows($q). ' trx</div>';
}


# ==============================================================
# SUB-TRX-NILAI-HISTORY
# ==============================================================



# ==============================================================
# SUB-TRX-JADWAL
# ==============================================================

# ==============================================================
# SUB-TRX-KELAS-PESERTA
# ==============================================================
