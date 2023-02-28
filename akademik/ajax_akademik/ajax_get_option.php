<?php
include '../../conn.php';
include 'session_security.php';

$id = isset($_GET['id']) ? $_GET['id'] : die(erid('id'));
$val = isset($_GET['val']) ? $_GET['val'] : die(erid('val'));
$tabel = isset($_GET['tabel']) ? $_GET['tabel'] : die(erid('tabel'));
$defid = isset($_GET['defid']) ? $_GET['defid'] : die(erid('defid'));

$s = "SELECT $id, $val from tb_$tabel order by $val desc";
// die($s);
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$opt = '';
while ($d=mysqli_fetch_assoc($q)) {
  $selected = $d[$id]==$defid ? 'selected' : '';
  // die("$d[id]==$defid");
  $isi = $d[$val]!=$d[$id] ? "$d[$val] ~ $d[$id]" : $d[$id];
  $opt .= "<option value=$d[$id] $selected>$isi</option>";
}
echo $opt;
?>