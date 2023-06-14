<h3 class="page-header"><i class="fa fa-laptop"></i>SIAKAD Dashboard</h3>
<?php 

$today = date("D, d M Y h:i");

$jumlah_mhs_aktif = 0;
$jumlah_sudah_bayar = 0;
$jumlah_sudah_krs = 0;

$s = "SELECT * from tb_mhs WHERE status_mhs=1";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
$jumlah_mhs_aktif = mysqli_num_rows($q);
$rid_prodi = [41,42,43,31,32];
$rlabel_prodi = ['TI','RPL','SI','MI','KA'];
for ($i=0; $i < count($rid_prodi); $i++){
	$jumlah_mhs_aktif_prodi[$rid_prodi[$i]] = 0;
	$jumlah_sudah_bayar_prodi[$rid_prodi[$i]] = 0;
	$jumlah_sudah_krs_prodi[$rid_prodi[$i]] = 0;
} 
$jumlah_mhs_aktif_unprodi = 0;
$jumlah_belum_bayar = 0;
$jumlah_belum_krs = 0;


while ($d = mysqli_fetch_assoc($q)) {
	$id_prodi=$d['id_prodi'];
	if($id_prodi!=''){
		$jumlah_mhs_aktif_prodi[$id_prodi]++;
		if($d['status_bayar_manual']){
			$jumlah_sudah_bayar_prodi[$id_prodi]++;
			$jumlah_sudah_bayar++;
		} 
		if($d['status_krs_manual']){
			$jumlah_sudah_krs_prodi[$id_prodi]++;
			$jumlah_sudah_krs++;
		} 
	}
}

$jumlah_mhs_aktif_prodi_show = '';
$jumlah_sudah_bayar_prodi_show = '';
$jumlah_sudah_krs_prodi_show = '';
for ($i=0; $i < count($rid_prodi); $i++){
	$jumlah_mhs_aktif_prodi_show .= "
		<div class='wadah bg-white rounded30'>
			<div><b>$rlabel_prodi[$i]</b>: ".$jumlah_mhs_aktif_prodi[$rid_prodi[$i]]."</div>
		</div>
	";
	$jumlah_sudah_bayar_prodi_show .= "
		<div class='wadah bg-white rounded30'>
			<div><b>$rlabel_prodi[$i]</b>: ".$jumlah_sudah_bayar_prodi[$rid_prodi[$i]]."</div>
		</div>
	";
	$jumlah_sudah_krs_prodi_show .= "
		<div class='wadah bg-white rounded30'>
			<div><b>$rlabel_prodi[$i]</b>: ".$jumlah_sudah_krs_prodi[$rid_prodi[$i]]."</div>
		</div>
	";
}

// $jumlah_mhs_aktif_unprodi = 89; //zzz
$merah = $jumlah_mhs_aktif_unprodi>0 ? 'gradasi-merah' : 'bg-white';

$unprodi_show = $jumlah_mhs_aktif_unprodi==0 ? '' : "
	<div class='wadah rounded30 $merah'>
		<div><b>Unprodi</b>: $jumlah_mhs_aktif_unprodi</div>
	</div>
";

$jumlah_mhs_aktif_prodi_show .= $unprodi_show;


# =======================================================
# SEMESTER AKTIF
# =======================================================
$s = "SELECT a.*, b.* FROM tb_semester a 
JOIN tb_kalender b ON a.id_kalender=b.id 
WHERE '$now' >= a.tanggal_awal AND '$now' < a.tanggal_akhir";
// echo "<span class=debug>$s</span>";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
$li = '';
while ($d=mysqli_fetch_assoc($q)) {
	$tanggal_akhir = date('d M Y',strtotime($d['tanggal_akhir']));
	$selisih_hari = (strtotime($d['tanggal_akhir'])-strtotime('today'))/(24*60*60);
	$id = "<span class=debug>$d[id]</span>";
	$li .= "<li>Semester $d[nomor] $d[jenjang]-$d[angkatan] hingga $tanggal_akhir ($selisih_hari hari lagi) $id</li>";
}

$ul = $li=='' ? "<div class=red>Belum ada semester aktif pada SIAKAD.</div>" 
: "<ul>$li</ul>";

?>



<p style="background-color: #ffa;padding: 10px"><b>Today</b>: <?=$today?> | <b>Petugas</b>: <?=$nama_user?> | <b>Login as</b>: <?=$login_as?>  </p>

<div class="alert alert-info">
	<b>Semester Aktif:</b>
	<?=$ul?>
</div>


<!-- <div class='alert alert-danger'>Perhatian! Masih Data Dummy.</div> -->

<style>
	.count_block{
		text-align:center; 
		/* cursor: pointer; */
		/* transition:.2s */
	}
	/* .count_block:hover{letter-spacing: 1px; background: linear-gradient(#fcf,#fff)} */
	.count_h1{font-size: 40px}
	.count_h2{font-size: 30px; border-radius:40px;}
	.count_h1_info{margin-bottom:20px}
	.rounded50{border-radius:40px}
	.rounded30{border-radius:30px}
</style>
<div class="row">
	<div class="col-lg-4">
		<div class="wadah gradasi-hijau rounded50 count_block">
			<div class="count_h1">
				<a href="?mhs_aktif"><?=$jumlah_mhs_aktif?></a>
			</div>
			<div class="count_h1_info">Mhs Aktif</div>
			<?=$jumlah_mhs_aktif_prodi_show?>
		</div>
	</div>

	<div class="col-lg-4">
		<div class="wadah gradasi-hijau rounded50 count_block">
			<div class="count_h1">
				<?=$jumlah_sudah_bayar?>
			</div>
			<div class="count_h1_info">Sudah Bayar</div>
			<?=$jumlah_sudah_bayar_prodi_show?>
		</div>
	</div>

	<div class="col-lg-4">
		<div class="wadah gradasi-hijau rounded50 count_block">
			<div class="count_h1">
				<?=$jumlah_sudah_krs?>
			</div>
			<div class="count_h1_info">Sudah KRS</div>
			<?=$jumlah_sudah_krs_prodi_show?>
		</div>
	</div>

</div>
<hr>

