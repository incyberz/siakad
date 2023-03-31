<?php 
$msg = "Error @AJAX. Missing index field";
if (!isset($_GET['id_dosen']))die("$msg #1");
if (!isset($_GET['id_bk']))die("$msg #2");

$id_dosen = $_GET['id_dosen'];
$get_id_bk = $_GET['id_bk'];

include "../../config.php";


# =================================================


$s = "SELECT a.id_bk,b.nama_bk FROM tb_bk_dosen a 
JOIN tb_bk b ON a.id_bk=b.id_bk 
where a.id_dosen=$id_dosen";
$q = mysqli_query($cn,$s) or die("Error @AJAX. Tidak dapat mengakses data keahlian dosen. ".mysqli_error($cn));

$list_bk_dosen="None | <a href='?datadosen&aksi=edit&id_dosen=$id_dosen' class='not_ready merah'>Set BK untuk Dosen ini</a>";

if(mysqli_num_rows($q)>0){

	$list_bk_dosen='';

	while ($d=mysqli_fetch_assoc($q)) {
		$id_bk = $d['id_bk'];
		$nama_bk = $d['nama_bk'];
		if($get_id_bk==$id_bk){
			$list_bk_dosen.="<a href='?datamk&aksi=data_bk&id_bk=$id_bk' class='not_ready badge badge-primary' >".$d['nama_bk']."</a>"." | ";
		}else{
			$list_bk_dosen.="<a href='?datamk&aksi=data_bk&id_bk=$id_bk' class='not_ready' >".$d['nama_bk']."</a>"." | ";
		}
	}

	$list_bk_dosen.= "<a href='?datadosen&id_dosen=$id_dosen' class='ungu'>Add</a>";
}



// $hasil="None | <a href='#' class='not_ready merah'>Set BK untuk Dosen ini</a>";
// if($list_bk_dosen!="")$hasil="
// <a href='?datadosen&aksi=editdosen&id_dosen=$id_dosen' class='not_ready'>
// 	$list_bk_dosen ~ $id_bk
// </a>
// ";

echo "1__$list_bk_dosen";
?>