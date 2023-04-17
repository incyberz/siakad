<?php
include '../../conn.php';
include 'session_security.php';

$angkatan = isset($_GET['angkatan']) ? $_GET['angkatan'] : die(erid('angkatan'));
$id_prodi = isset($_GET['id_prodi']) ? $_GET['id_prodi'] : die(erid('id_prodi'));
$id_jadwal = isset($_GET['id_jadwal']) ? $_GET['id_jadwal'] : die(erid('id_jadwal'));

# ===================================================
# GET KELAS IN THIS JADWAL
# ===================================================
$sql_where_jadwal = $id_jadwal=='' ? '1' : "d.id = $id_jadwal";
$s = "SELECT a.id as id_kelas_angkatan FROM tb_kelas_angkatan a 
JOIN tb_kelas_peserta b on a.id=b.id_kelas_angkatan  
JOIN tb_kurikulum_mk c on c.id=b.id_kurikulum_mk 
JOIN tb_jadwal d on d.id_kurikulum_mk=c.id 
JOIN tb_kelas e on e.kelas=a.kelas 
where a.tahun_ajar=$angkatan and e.id_prodi=$id_prodi and $sql_where_jadwal";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$assigned_kelas_angkatan = [];
$i=0;
while ($d=mysqli_fetch_assoc($q)) {
  $assigned_kelas_angkatan[$i] = $d['id_kelas_angkatan'];
  $i++;
}

// die(var_dump($assigned_kelas_angkatan));

# ===================================================
# GET KELAS IN THIS JADWAL
# ===================================================
$s = "SELECT a.id as id_kelas_angkatan,b.kelas 
FROM tb_kelas_angkatan a 
JOIN tb_kelas b ON a.kelas=b.kelas 
where a.tahun_ajar=$angkatan and b.id_prodi=$id_prodi";
echo "<span class=debug>$s</span>";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$ceks = '';
while ($d=mysqli_fetch_assoc($q)) {
  if(in_array($d['id_kelas_angkatan'],$assigned_kelas_angkatan)) continue;
  $ceks .= "<div><label><input type=checkbox id='$d[id_kelas_angkatan]' name='$d[id_kelas_angkatan]'> $d[kelas]</label></div>";
}

echo $ceks==''? "<div class='abu miring alert alert-danger'>-- kelas tidak ditemukan --</div>" : "<div class='wadah bg-white'><div class='wadah gradasi-hijau'>$ceks</div><div class='btn-link'><button class='btn btn-primary' name='btn_assign_kelas_peserta'>Assign Kelas Peserta</button></div></div>";
?>