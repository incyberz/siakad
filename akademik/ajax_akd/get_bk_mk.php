<?php 
$msg = "Error @AJAX. Missing index field";
if (!isset($_GET['id_mk']))die("$msg #1");

$id_mk = $_GET['id_mk'];

include "../../config.php";

$s = "SELECT id_bk FROM tb_mk WHERE id_mk=$id_mk";

$q = mysqli_query($cn,$s) or die("Error @AJAX. Tidak dapat mengakses data mata kuliah. ".mysqli_error($cn));
if(mysqli_num_rows($q)!=1) die("Error @AJAX. ID MK tidak ada dalam list.");
$d=mysqli_fetch_assoc($q);

$id_bk = $d['id_bk'];
$nama_bk='';

if($id_bk!=""){
	$s = "SELECT nama_bk FROM tb_bk WHERE id_bk=$id_bk";
	$q = mysqli_query($cn,$s) or die("Error @AJAX. Tidak dapat mengakses data bidang keahlian. ".mysqli_error($cn));
	$d=mysqli_fetch_assoc($q);
	$nama_bk=$d['nama_bk'];
}

$hasil="None | <a href='#' class='not_ready merah'>Set BK untuk MK ini</a>";
if($nama_bk!="")$hasil="
<a href='?datamk&aksi=data_bk&id_bk=$id_bk' class='not_ready badge badge-primary' style='margin:0px 5px'>
	$nama_bk
</a>
";

echo "1__$hasil"."__$id_bk";
?>