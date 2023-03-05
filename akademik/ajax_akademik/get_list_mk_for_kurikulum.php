<?php
include '../../conn.php';
include 'session_security.php';

$id_kurikulum = isset($_GET['id_kurikulum']) ? $_GET['id_kurikulum'] : die(erid('id_kurikulum'));
$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : die(erid('keyword'));

# ===================================================
# MK DI KURIKULUM INI
# ===================================================
$s = "SELECT a.id 
from tb_mk a 
join tb_kurikulum_mk b on a.id=b.id_mk 
join tb_semester c on c.id = b.id_semester 
WHERE c.id_kurikulum = $id_kurikulum  
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
c.id_kurikulum 

from tb_mk a 
join tb_kurikulum_mk b on a.id=b.id_mk 
join tb_semester c on c.id = b.id_semester 

WHERE c.id_kurikulum != $id_kurikulum  
AND a.nama like '%$keyword%'
order by a.nama;

";
// die($s);
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$li = '';
while ($d=mysqli_fetch_assoc($q)) {
  $n = $d['id_kurikulum']==$id_kurikulum ? '' : "<li class='pilihan_mk' value=$d[id]>$d[nama] ~ $d[id]</li>";
  if(!in_array($d['id'],$id_terpakai)) $li .= $n;
}

# ===================================================
# TAMBAH DENGAN MK UN-ASSIGN
# ===================================================
$s = "SELECT 
a.nama, 
a.id 
from tb_mk a 
left join tb_kurikulum_mk b on a.id=b.id_mk 

WHERE b.id is null 
AND a.nama like '%$keyword%'
order by a.nama;


";
// die($s);
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
while ($d=mysqli_fetch_assoc($q)) {
  $li .= "<li class='pilihan_mk' value=$d[id]>$d[nama] ~ $d[id]</li>";
}





echo $li==''? "<ul><li class='abu miring'>-- not found --</li></ul>" : "<ul>$li</ul>";
?>