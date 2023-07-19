<?php 
if(!isset($username)) die(erid('username at user_vars'));
$s = "SELECT a.id as id_user,a.nama as nama_user, a.id_role, b.* 
FROM tb_user a 
JOIN tb_role b ON a.id_role=b.id where a.username='$username'";
$q = mysqli_query($cn,$s) or die("db_vars: tidak dapat mengakses data prodi");
if(mysqli_num_rows($q)==0) die('Data user tidak ditemukan.');
$d=mysqli_fetch_assoc($q);
$id_user = $d['id_user'];
$nama_user = $d['nama_user'];
$login_as = $d['login_as'];
$sub_domain = $d['sub_domain'];
$id_role = $d['id_role'];
$admin_level = $id_role;

$img_pegawai = "img/pegawai/admin.jpg"; //zzz
