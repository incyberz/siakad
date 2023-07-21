<h1>Hapus Kurikulum MK</h1>
<?php
include '../include/include_rid_prodi.php';
$id_kurikulum_mk = $_GET['id_kurikulum_mk'] ?? die(erid('id_kurikulum_mk'));

$s = "SELECT a.*, 
b.nama as nama_mhs, 
b.angkatan, 
b.id_prodi  
FROM tb_nilai a 
JOIN tb_mhs b ON a.nim=b.nim  
WHERE a.id_kurikulum_mk=$id_kurikulum_mk";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
if(mysqli_num_rows($q)==0){
  $s = "DELETE FROM tb_kurikulum_mk WHERE id=$id_kurikulum_mk";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  echo div_alert('success','Drop Kurikulum MK berhasil');
  exit;
}else{
  $ol = '';
  while ($d=mysqli_fetch_assoc($q)) {
    $prodi = $rprodi[$d['id_prodi']];
    $ol .= "<li>$d[nim] | <a href='?manage_khs&angkatan=$d[angkatan]&id_prodi=$d[id_prodi]&nim=$d[nim]'>$d[nama_mhs]</a> | angkatan $d[angkatan] prodi $prodi</li>";
  }
  $ol = "<ol>$ol</ol>";
  echo div_alert('info',"Tidak bisa melepas (drop) MK ini karena dipakai oleh data nilai atas nama $ol.");

}
