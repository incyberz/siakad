<?php
include '../../conn.php';
include 'session_security.php';

$id_kurikulum = isset($_GET['id_kurikulum']) ? $_GET['id_kurikulum'] : die(erid('id_kurikulum'));
$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : die(erid('keyword'));

# ===================================================
# MK DI KURIKULUM INI
# ===================================================
$s = "SELECT a.id 
FROM tb_mk a 
JOIN tb_kurikulum_mk b on a.id=b.id_mk 
WHERE b.id_kurikulum = $id_kurikulum  
";
// die($s);
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$id_terpakai = [];
$i=0;
while ($d=mysqli_fetch_assoc($q)) {
  $id_terpakai[$i] = $d['id'];
  $i++;
}

// die(var_dump($id_terpakai));

# ===================================================
# MK BUKAN DI KURIKULUM INI
# ===================================================
$s = "SELECT 
a.nama, 
a.id,
a.kode,
b.id_kurikulum,
d.jenjang,
e.nama as nama_prodi  

FROM tb_mk a 
JOIN tb_kurikulum_mk b on a.id=b.id_mk 
JOIN tb_kurikulum c on c.id=b.id_kurikulum 
JOIN tb_kalender d on d.id=c.id_kalender 
JOIN tb_prodi e on e.id=c.id_prodi  

WHERE b.id_kurikulum != $id_kurikulum  
AND ( a.nama like '%$keyword%' OR a.kode like '%$keyword%' )
order by a.nama;

";
// die($s);
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$li = '';
while ($d=mysqli_fetch_assoc($q)) {
  $n = $d['id_kurikulum']==$id_kurikulum ? '' : "<li class='pilihan_mk' value=$d[id]>$d[nama] ~ $d[id] ~ $d[kode] ~ $d[jenjang] ~ $d[nama_prodi]</li>";
  if(!in_array($d['id'],$id_terpakai)) $li .= $n;
}

# ===================================================
# TAMBAH DENGAN MK UN-ASSIGN
# ===================================================
$s = "SELECT 
a.nama, 
a.kode, 
a.id 
FROM tb_mk a 

left JOIN tb_kurikulum_mk b on a.id=b.id_mk 

WHERE b.id is null 
AND ( a.nama like '%$keyword%' OR a.kode like '%$keyword%' )
order by a.nama;


";
// die($s);
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
while ($d=mysqli_fetch_assoc($q)) {
  $li .= "<li class='pilihan_mk' value=$d[id]>$d[nama] ~ $d[id] ~ $d[kode]</li>";
}





echo $li==''? "<ul><li class='abu miring'>-- not found --</li></ul>" : "<ul>$li</ul>";
?>