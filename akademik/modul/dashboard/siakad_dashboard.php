<?php 
// $s = "SELECT 1 from tb_pegawai where status_pegawai=1";
// $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
// $not_teaching_today = mysqli_num_rows($q);


$not_teaching_today = 820;
$jumlah_teaching = 39;
$jumlah_teaching_terlaksana = 12;

$not_presention_sakit = 12; //zzz
$not_presention_izin = 3; //zzz
$not_presention_alfa = 7; //zzz
$not_presention_tsj = 51; //zzz
$not_presention_today = $not_presention_sakit+$not_presention_izin+$not_presention_alfa+$not_presention_tsj; //zzz

$not_teaching_sakit = 1; //zzz
$not_teaching_izin = 2; //zzz
$not_teaching_alfa = 0; //zzz
$not_teaching_tsj = 2; //zzz
$not_teaching_today = $not_teaching_sakit+$not_teaching_izin+$not_teaching_alfa+$not_teaching_tsj; //zzz

$jumlah_presensi = 567;
$jumlah_presensi_terlaksana = 324;

$persen_presensi = round($jumlah_presensi_terlaksana/$jumlah_presensi*100);
$persen_teaching = round($jumlah_teaching_terlaksana/$jumlah_teaching*100);

$today = date("D, d M Y h:i");
$ta = "2021-2022";
?>

<h3 class="page-header"><i class="fa fa-laptop"></i>SIAKAD Dashboard</h3>


<p style="background-color: #ffa;padding: 10px"><strong>Today</strong>: <?=$today?> | <strong>TA</strong>: <?=$ta?> | <strong>Petugas</strong>: <?=$cnama_pegawai?> | <strong>Login as</strong>: <?=$cjenis_user?></p>

<style type="text/css">
.dashboard_wfh{border: solid 1px #aaa; margin: 5px;padding: 15px; border-radius: 10px; background-color: #def}
.wfh_header{font-size: 16px;font-weight: bold;color: darkblue}
.wfh_count{font-size: 50px;font-weight: bold;}
.not_present_count{font-size: 30px;font-weight: bold;}
.wfh_count_of{font-size: 24px;font-weight: bold;}
.wfh_count_satuan{font-size: 14px;font-weight: bold}
</style>

<div class='alert alert-danger'>Perhatian! Masih Data Dummy.</div>

<div class="row">
	<div class="col-lg-2">
		<div class="zzz" style="border: solid 1px #aaa; margin: 5px;padding: 5px; border-radius: 60px; background-color: #def">
			<div class="text-center">
				<div style="font-size: 40px">
					<?=$jumlah_mahasiswa_aktif?>
				</div>
				<div style="margin-bottom: 10px; font-size: 18pxa">
					<a href="?Mhs&tipe=wfo" class="not_ready">
						Mhs Aktif
					</a>
				</div>
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

