<?php 

$today = date("D, d M Y h:i");

$jumlah_mhs_aktif = 0;
$jumlah_sudah_bayar = 2300;
$jumlah_sudah_krs = 2286;

$s = "SELECT id_prodi from tb_mhs where status_mhs=1";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
$jumlah_mhs_aktif = mysqli_num_rows($q);
$rid_prodi = [41,42,43,31,32];
$rlabel_prodi = ['TI','RPL','SI','MI','KA'];
for ($i=0; $i < count($rid_prodi); $i++) $jumlah_mhs_aktif_prodi[$rid_prodi[$i]] = 0;
$jumlah_mhs_aktif_unprodi = 0;


while ($d = mysqli_fetch_assoc($q)) {
	$id_prodi=$d['id_prodi'];
	if($id_prodi==41){ $jumlah_mhs_aktif_prodi[41]++;		
	}elseif($id_prodi==42){ $jumlah_mhs_aktif_prodi[42]++;
	}elseif($id_prodi==43){ $jumlah_mhs_aktif_prodi[43]++;
	}elseif($id_prodi==31){ $jumlah_mhs_aktif_prodi[31]++;
	}elseif($id_prodi==32){ $jumlah_mhs_aktif_prodi[32]++;
	}else{$jumlah_mhs_aktif_unprodi++;}
}

$jumlah_mhs_aktif_prodi_show = '';
for ($i=0; $i < count($rid_prodi); $i++){
	$jumlah_mhs_aktif_prodi_show .= "
		<div class='col-md-2'>
			<div class='wadah bg-white rounded30'>
				<div><b>$rlabel_prodi[$i]</b></div>
				<div class='count_h2'>".$jumlah_mhs_aktif_prodi[$rid_prodi[$i]]."</div>
			</div>
		</div>
	";
}

$jumlah_mhs_aktif_unprodi = 89; //zzz
$merah = $jumlah_mhs_aktif_unprodi>0 ? 'gradasi-merah' : 'bg-white';

$jumlah_mhs_aktif_prodi_show .= "
	<div class='col-md-2'>
		<div class='wadah rounded30 $merah'>
			<div><b>Unprodi</b></div>
			<div class='count_h2'>$jumlah_mhs_aktif_unprodi</div>
		</div>
	</div>
";

?>

<h3 class="page-header"><i class="fa fa-laptop"></i>SIAKAD Dashboard</h3>


<p style="background-color: #ffa;padding: 10px"><b>Today</b>: <?=$today?> | <b>Petugas</b>: <?=$nama_user?> | <b>Login as</b>: <?=$login_as?>  </p>

<div class="alert alert-info">
	<b>Semester Aktif:</b>
	<ul>
		<li>Semester 2 hingga 2 Jun 2023</li>
		<li>Semester 4 hingga 12 Mei 2023</li>
		<li>Semester 6 hingga 26 Apr 2023</li>
		<li>Semester 8 hingga 20 Feb 2023</li>
	</ul>

</div>


<!-- <div class='alert alert-danger'>Perhatian! Masih Data Dummy.</div> -->

<style>
	.count_block{text-align:center; cursor: pointer;transition:.2s}
	.count_block:hover{letter-spacing: 1px; background: linear-gradient(#fcf,#fff)}
	.count_h1{font-size: 40px}
	.count_h2{font-size: 30px; border-radius:40px;}
	.count_h1_info{margin-bottom:20px}
	.rounded50{border-radius:40px}
	.rounded30{border-radius:30px}
</style>
<div class="row">
	<div class="col-lg-2">
		<div class="wadah gradasi-hijau rounded50 count_block">
			<div class="count_h1">
				<?=$jumlah_mhs_aktif?>
			</div>
			<div class="count_h1_info">Mhs Aktif</div>
			<div class="row">
				<?=$jumlah_mhs_aktif_prodi_show?>
			</div>
		</div>
	</div>
	<div class="col-lg-2">
		<div class="zzz" style="border: solid 1px #aaa; margin: 5px;padding: 5px; border-radius: 60px; background-color: #def">
			<div class="text-center">
				<div style="font-size: 40px">
					<?=$jumlah_sudah_bayar?>
				</div>
				<div style="margin-bottom: 10px; font-size: 18pxa">
					<a href="?Mhs&tipe=wfo" class="not_ready">
						Sudah Bayar
					</a>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-2">
		<div class="zzz" style="border: solid 1px #aaa; margin: 5px;padding: 5px; border-radius: 60px; background-color: #def">
			<div class="text-center">
				<div style="font-size: 40px">
					<?=$jumlah_sudah_krs?>
				</div>
				<div style="margin-bottom: 10px; font-size: 18pxa">
					<a href="?Mhs&tipe=wfo" class="not_ready">
						Sudah KRS
					</a>
				</div>
			</div>
		</div>
	</div>
</div>
<hr>

<!-- <div class="row">
	<div class="col-lg-3 bordered">
		<div class="dashboard_wfh" style="background-color: #dfd">
			<div class="wfh_header"><a href="?Mhs&tipe=wfo" class="not_ready">Has Presention Today</a></div>
			<div><span class="wfh_count biru tebal"><?=$jumlah_presensi_terlaksana ?></span> of <span class="wfh_count_of"><?=$jumlah_presensi ?></span> Students</div>
			<div class="wfh_count_satuan">
				<div class='progress' style="border: solid 1px #aaa;margin-bottom: 0">
				  <div class='progress-bar' role='progressbar' aria-valuenow='<?=$persen_presensi?>' aria-valuemin='0' aria-valuemax='100' style='width: <?=$persen_presensi?>%;'>
				    <?=$persen_presensi?>%
				  </div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-lg-3 bordered">
		<div class="dashboard_wfh">
			<div class="wfh_header"><a href="?Mhs&tipe=wfh" class="not_ready">Has Teaching Today</a></div>
			<div><span class="wfh_count biru tebal"><?=$jumlah_teaching_terlaksana ?></span> of <span class="wfh_count_of"><?=$jumlah_teaching ?></span> Lecturers</div>
			<div class="wfh_count_satuan">
				<div class='progress' style="border: solid 1px #aaa;margin-bottom: 0">
				  <div class='progress-bar' role='progressbar' aria-valuenow='<?=$persen_teaching?>' aria-valuemin='0' aria-valuemax='100' style='width: <?=$persen_teaching?>%;'>
				    <?=$persen_teaching?>%
				  </div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-lg-3 bordered">
		<div class="dashboard_wfh" style="background-color: #ffc">
			<div class="wfh_header">Not Presention Today</div>
			<table width="100%">
				<tr>
					<td width="25%"><div class="not_present_count ungu"><?=$not_presention_sakit ?></div>Sakit</td>
					<td width="25%"><div class="not_present_count merah"><?=$not_presention_izin ?></div>Izin</td>
					<td width="25%"><div class="not_present_count merah"><?=$not_presention_alfa ?></div>Alfa</td>
					<td width="25%"><div class="not_present_count ungu"><?=$not_presention_tsj ?></div>TSJ</td>
				</tr>
			</table>
		</div>
	</div>

	<div class="col-lg-3 bordered">
		<div class="dashboard_wfh" style="background-color: #ffc">
			<div class="wfh_header">Not Teaching Today</div>
			<table width="100%">
				<tr>
					<td width="25%"><div class="not_present_count ungu"><?=$not_teaching_sakit ?></div>Sakit</td>
					<td width="25%"><div class="not_present_count merah"><?=$not_teaching_izin ?></div>Izin</td>
					<td width="25%"><div class="not_present_count merah"><?=$not_teaching_alfa ?></div>Alfa</td>
					<td width="25%"><div class="not_present_count ungu"><?=$not_teaching_tsj ?></div>TSJ</td>
				</tr>
			</table>
		</div>
	</div>



	
</div> -->

