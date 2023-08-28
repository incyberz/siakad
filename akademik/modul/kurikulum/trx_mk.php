<h1>Transaksi Kurikulum-MK</h1>
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
e.singkatan as prodi,
f.kode as kode_mk,
f.nama as nama_mk,
f.id as id_mk 


FROM tb_kurikulum a 
JOIN tb_prodi b ON b.id=a.id_prodi 
JOIN tb_kurikulum_mk c ON c.id_kurikulum=a.id 
JOIN tb_kalender d ON a.id_kalender=d.id 
JOIN tb_prodi e ON a.id_prodi=e.id 
JOIN tb_mk f ON c.id_mk=f.id 
WHERE c.id='$id_kurikulum_mk'";
// echo "<pre>$s</pre>";
$q = mysqli_query($cn, $s)or die(mysqli_error($cn));
if(mysqli_num_rows($q)==0) die('Data Kurikulum tidak ditemukan.');
if(mysqli_num_rows($q)>1) die('Duplikat Kurikulum detected.');
$d=mysqli_fetch_assoc($q);
echo "<p>
  Fitur ini digunakan untuk memindahkan semua sub-trx MK terhadap MK lain agar tidak terjadi duplikat MK pada Kurikulum <a href='?manage_kurikulum&id_kurikulum=$d[id_kurikulum]'>$d[jenjang]-$d[prodi]-$d[angkatan]</a>
</p>
<h3>$d[nama_mk] | $d[kode_mk]</h3>
";

$sub = $_GET['sub'] ?? '';

if($sub==''){
  # ==============================================================
  # SUB-TRX
  # ==============================================================
  $rsub = ['nilai','nilai_history','jadwal'];
  $li='';
  $total_sub_trx=0;
  foreach ($rsub as $sub) {
    $s = "SELECT 1 FROM tb_$sub WHERE id_kurikulum_mk=$id_kurikulum_mk";
    $q = mysqli_query($cn, $s)or die(mysqli_error($cn));
    $rows = mysqli_num_rows($q);
    $li.= $rows ? "<li><a href='?trx_mk&id_kurikulum_mk=$id_kurikulum_mk&sub=$sub'>$rows sub-trx-$sub</a></li>" : "<li>$rows sub-trx-$sub</li>";
    $total_sub_trx+= $rows;
  
  }
  echo  "<ul>$li</ul>";
  
  $deletable = $total_sub_trx ? 1 : 0;

}else{
  include 'include/akademik_icons.php';
  if($sub=='nilai'){
    echo "<h4><a href='?trx_mk&id_kurikulum_mk=$id_kurikulum_mk'>Back</a> | Sub Trx-<span class=proper>$sub</span> untuk mhs:</h4>";
    $s = "SELECT 
    b.angkatan,
    b.id_prodi,
    b.nim,
    b.nama as nama_mhs 

    FROM tb_$sub a  
    JOIN tb_mhs b ON a.nim=b.nim 
    WHERE a.id_kurikulum_mk=$id_kurikulum_mk";
    $q = mysqli_query($cn, $s)or die(mysqli_error($cn));
    $li='';
    while ($d=mysqli_fetch_assoc($q)) {
      $manage_khs = " | <a href='?manage_khs&angkatan=$d[angkatan]&id_prodi=$d[id_prodi]&nim=$d[nim]'>Manage KHS</a>";
      $li.= "<li>$d[nama_mhs] | $d[nim] | <a href='?login_as&nim=$d[nim]'>$img_aksi[login_as]</a> $manage_khs</li>";
    }
    echo "<ol>$li</ol>";

  }
}



