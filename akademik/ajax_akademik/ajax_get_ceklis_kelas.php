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
$s = "SELECT a.kelas from tb_kelas a 
join tb_peserta_kelas b on a.kelas=b.kelas 
join tb_kurikulum_mk c on c.id=b.id_kurikulum_mk 
join tb_jadwal d on d.id_kurikulum_mk=c.id 
where a.angkatan=$angkatan and a.id_prodi=$id_prodi and $sql_where_jadwal";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$assigned_classes = [];
$i=0;
while ($d=mysqli_fetch_assoc($q)) {
  $assigned_classes[$i] = $d['kelas'];
  $i++;
}

// die(var_dump($assigned_classes));

# ===================================================
# GET KELAS IN THIS JADWAL
# ===================================================
$s = "SELECT a.kelas from tb_kelas a 
where a.angkatan=$angkatan and a.id_prodi=$id_prodi";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$ceks = '';
while ($d=mysqli_fetch_assoc($q)) {
  if(in_array($d['kelas'],$assigned_classes)) continue;
  $ceks .= "<div><label><input type=checkbox id='$d[kelas]' name='$d[kelas]'> $d[kelas]</label></div>";
}

echo $ceks==''? "<div class='abu miring alert alert-danger'>-- kelas not found --</div>" : "<div class='wadah bg-white'><div class='wadah gradasi-hijau'>$ceks</div><div class='btn-link'><button class='btn btn-primary' name='btn_assign_peserta_kelas'>Assign Peserta Kelas</button></div></div>";
?>