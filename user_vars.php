<?php 
if(!isset($username)) die(erid('username at user_vars'));
$s = "SELECT a.nama as nama_user, b.* FROM tb_user a 
JOIN tb_role b ON a.role=b.id where a.username='$username'";
$q = mysqli_query($cn,$s) or die("db_vars: tidak dapat mengakses data prodi");
if(mysqli_num_rows($q)==0) die('Data user tidak ditemukan.');
$d=mysqli_fetch_assoc($q);
$nama_user = $d['nama_user'];
$login_as = $d['login_as'];
$sub_domain = $d['sub_domain'];

$img_pegawai = "img/pegawai/admin.jpg"; //zzz
