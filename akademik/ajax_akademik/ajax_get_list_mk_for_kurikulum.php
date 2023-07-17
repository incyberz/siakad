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
a.kode 

FROM tb_mk a 

WHERE 1   
AND ( a.nama like '%$keyword%' OR a.kode like '%$keyword%' )
order by a.nama;

";
// die($s);
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$li = '';
while ($d=mysqli_fetch_assoc($q)) {
  if(!in_array($d['id'],$id_terpakai)) $li .= "<li class='pilihan_mk' value=$d[id]>$d[kode] | $d[nama] ~ $d[id]</li>";
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