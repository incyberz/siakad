<h3 class="page-header"><i class="fa fa-laptop"></i>SIAKAD Dashboard</h3>
<p style="background-color: #ffa;padding: 10px"><b>Today</b>: <?=date('D, d-M-Y H:i', strtotime('now'))?> | <b>Petugas</b>: <?=$nama_user?> | <b>Login as</b>: <?=$login_as?>  </p>
<?php 

# ======================================================
# PROGRES
# ======================================================
$s = "SELECT * FROM tb_unsetting ORDER BY no";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
$div='';
while ($d=mysqli_fetch_assoc($q)) {
	$setinged = $d['total'] - $d['unsetting'];
	$persen = round($setinged/$d['total']*100,2);
	$green_color = intval($persen/100*155);
  $red_color = intval((100-$persen)/100*255);
  $rgb = "rgb($red_color,$green_color,50)";

	$div.="
		<div class=col-lg-4>
			<div class='kecil miring abu'>Manage $d[caption] ~ $persen% | $setinged of $d[total]</div>
			<div class=progress>
				<div class='progress-bar' style='width:$persen%; background:$rgb'></div>
			</div>
		</div>
	";
}
echo "
<div class='wadah gradasi-hijau'>
	<h4 class='darkblue'>Progress Manage:</h4>
	<div class=row>
		$div
	</div>
</div>";

# ======================================================
# MHS AKTIF
# ======================================================
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
$today = date('Y-m-d');
$s = "SELECT *,
(
	SELECT nomor FROM tb_semester s 
	JOIN tb_kalender k ON s.id_kalender=k.id 
	WHERE k.angkatan=a.angkatan 
	AND k.jenjang='S1' 
	AND s.tanggal_awal <= '$today' 
	AND s.tanggal_akhir >= '$today' 
	
	) semester  
FROM tb_angkatan a";
echo "<pre class=debug>$s</pre>";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
$ul = div_alert('info','Belum ada angkatan.');
if(mysqli_num_rows($q)){
	$ul = '';
	while ($d=mysqli_fetch_assoc($q)) {
		if($d['semester']=='') continue;
		$ul.="<br>~ Angkatan $d[angkatan] ~ Semester $d[semester] <span class=debug>~ Last Semester Aktif $d[last_semester_aktif]</span>";
		if($d['semester']!=$d['last_semester_aktif']){
			$s2 = "UPDATE tb_mhs SET semester_manual=$d[semester] WHERE angkatan=$d[angkatan]";
			$q2 = mysqli_query($cn,$s2) or die(mysqli_error($cn));
			$ul.= "<div class='consolas green small ml4'>Updating semester tiap mahasiswa angkatan $d[angkatan] success.</div>";
			$s2 = "UPDATE tb_angkatan SET last_semester_aktif=$d[semester] WHERE angkatan=$d[angkatan]";
			$q2 = mysqli_query($cn,$s2) or die(mysqli_error($cn));
			$ul.= "<div class='consolas green small ml4'>Updating last_semester_aktif angkatan $d[angkatan] success.</div>";
		}
	}
}
?>



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
			<div class="count_h1_info"><a href="?mhs_aktif">Mhs Aktif</a></div>
			<?=$jumlah_mhs_aktif_prodi_show?>
		</div>
	</div>

	<div class="col-lg-4">
		<div class="wadah gradasi-hijau rounded50 count_block">
			<div class="count_h1">
				<a href="?rekap_pembayaran_manual"><?=$jumlah_sudah_bayar?></a>
			</div>
			<div class="count_h1_info"><a href="?rekap_pembayaran_manual">Sudah Bayar</a></div>
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

