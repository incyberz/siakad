<h3 class="page-header"><i class="fa fa-laptop"></i>SIAKAD Dashboard</h3>
<p style="background-color: #ffa;padding: 10px"><b>Today</b>: <?=date('D, d-M-Y H:i', strtotime('now'))?> | <b>Petugas</b>: <?=$nama_user?> | <b>Login as</b>: <?=$login_as?>  </p>
<style>.kanan{text-align:right !important}</style>
<?php 
$tahun_skg = date('Y');
$ta_baru = strtotime('today')>=date('Y-m-d',strtotime("$tahun_skg-7-1"));
$tahun_ajar_skg = $ta_baru ? $tahun_skg : $tahun_skg-1;
$ganjil_genap = $ta_baru ? 'Ganjil' : 'Genap';
include '../include/include_rid_prodi.php';

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
// $rid_prodi = [41,42,43,31,32]; //zzz
// $rprodi = ['TI','RPL','SI','MI','KA']; //zzz
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
	$jml = $jumlah_mhs_aktif_prodi[$rid_prodi[$i]];
	$persen = number_format($jml/$jumlah_mhs_aktif*100,2);
	$jumlah_mhs_aktif_prodi_show .= "
		<div>$jml Mhs ($persen%)</div>
		<div class=progress>
			<div class='progress-bar' style='width:$persen%'></div>
		</div>
	";
	$jumlah_sudah_bayar_prodi_show .= "
		<div class='wadah bg-white rounded30'>
			<div><b>".$rprodi[$rid_prodi[$i]]."</b>: ".$jumlah_sudah_bayar_prodi[$rid_prodi[$i]]."</div>
		</div>
	";
	$jumlah_sudah_krs_prodi_show .= "
		<div class='wadah bg-white rounded30'>
			<div><b>".$rprodi[$rid_prodi[$i]]."</b>: ".$jumlah_sudah_krs_prodi[$rid_prodi[$i]]."</div>
		</div>
	";
}

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




















$gf='';
$ta = 'TA. 2022 Genap';

$chart_no = 1;
$judul_item[$chart_no] = 'Program Studi';
// $rwarna[$chart_no] = "'#5470C6','#6BAC4C','#F1B327','#C544C3','#73C0DE'";
$judul_chart[$chart_no] = 'Student Body';
$tb_header[$chart_no] = ['Program Studi','Persen','Jumlah'];
$satuan[$chart_no] = 'Mhs';
$sub_judul_chart[$chart_no] = "$jumlah_mhs_aktif Mhs Aktif";
$rlabel[$chart_no] = [];
$rsum[$chart_no] = [];
$rwarna[$chart_no] = '';
foreach ($rid_prodi as $id_prodi){
	array_push($rsum[$chart_no],$jumlah_mhs_aktif_prodi[$id_prodi]);
	array_push($rlabel[$chart_no],$rjenjang_prodi[$id_prodi].'-'.$rprodi[$id_prodi]);
	$rwarna[$chart_no] .= '"'. $rwarna_prodi[$id_prodi].'",';
}
$chart_jumlah[$chart_no] = array_sum($rsum[$chart_no]);



$gf = '';
for ($h=1; $h <= count($judul_item); $h++) { 
  $data_chart = '';
  $tr = '';
  for ($i=0; $i < count($rlabel[$h]); $i++) { 
    $data_chart.="{
      value: ".$rsum[$h][$i].",
      name: '".$rlabel[$h][$i]."',
    },";

    $chart_persen = round($rsum[$h][$i]/$chart_jumlah[$h]*100,1);

    $tr.="
    <tr>
      <td class=''>".$rlabel[$h][$i]."</td>
      <td class='text-right'>$chart_persen%</td>
      <td class='text-right'>".$rsum[$h][$i]." $satuan[$h]</td>
    </tr>
    ";
  }

  $gf .= "
	<div class='col-lg-4 mb-4'>
		<div class='wadah gradasi-hijau rounded10 count_block'>
      <div class='card'>
        <div class='card-body'>
          <h5 class='card-title'>TA. $tahun_ajar_skg ($ganjil_genap)</h5>

          <div id='chart_$h' style='min-height: 400px' class='echart'></div>

          <script>
            document.addEventListener('DOMContentLoaded', () => {
              echarts.init(document.querySelector('#chart_$h')).setOption({
                title: {
                  text: '$judul_chart[$h]',
                  subtext: '$sub_judul_chart[$h]',
                  left: 'center',
                },
                tooltip: {
                  trigger: 'item',
                },
                legend: {
                  orient: 'vertical',
                  left: 'left',
                },
                series: [
                  {
                    name: '$judul_item[$h]',
                    type: 'pie',
                    radius: '50%',
                    color: [$rwarna[$h]],
                    data: [$data_chart],
                    emphasis: {
                      itemStyle: {
                        shadowBlur: 10,
                        shadowOffsetX: 0,
                        shadowColor: 'rgba(0, 0, 0, 0.5)',
                      },
                    },
                  },
                ],
              });
            });
          </script>

          <table class='table table-striped table-hover tb-grafik'>
            <thead>
              <th>".$tb_header[$h][0]."</th>
              <th class='kanan'>".$tb_header[$h][1]."</th>
              <th class='kanan'>".$tb_header[$h][2]."</th>
            </thead>
            $tr
            <tr>
              <td class='td-jumlah' colspan='2'>Jumlah</td>
              <td class='td-jumlah kanan'>$chart_jumlah[$h] $satuan[$h]</td>
            </tr>

          </table>

        </div><!-- End card-body -->
      </div><!-- End card -->
    </div>
	</div>

  ";
}


?>



<div class="alert alert-info">
	<b>Semester Aktif:</b>
	<?=$ul?>
</div>



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
	.rounded10{border-radius:10px}

  .tb-grafik th, .td-jumlah{ font-size:12px; padding:5px;}
  .tb-grafik td{ color: #333333; font-size:11px; padding:5px;}
  .tb-grafik thead{
    color:white;
    background:gray;
  }
  .td-jumlah {font-weight:bold; color: white !important; background: #999; font-family:consolas }

</style>
<div class="row">
	<?=$gf?>

	<!-- <div class="col-lg-4">
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
	</div> -->

</div>

<script src="../assets/vendor/echarts/echarts.min.js"></script>
