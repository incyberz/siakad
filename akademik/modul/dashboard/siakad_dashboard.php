<h3 class="page-header"><i class="fa fa-laptop"></i>SIAKAD Dashboard</h3>
<p style="background-color: #ffa;padding: 10px"><b>Today</b>: <?=date('D, d-M-Y H:i', strtotime('now'))?> | <b>Petugas</b>: <?=$nama_user?> | <b>Login as</b>: <?=$login_as?>  </p>
<style>.kanan{text-align:right !important}</style>

<script type="text/javascript" src="../assets/js/echarts.min.js"></script>


<?php 
$tahun_skg = date('Y');
$ta_baru = strtotime('today')>=date('Y-m-d',strtotime("$tahun_skg-7-1"));
$tahun_ajar_skg = $ta_baru ? $tahun_skg : $tahun_skg-1;
$ganjil_genap = $ta_baru ? 'Ganjil' : 'Genap';
include '../include/include_rid_prodi.php';
include '../include/include_rid_jalur.php';

# ======================================================
# PROGRESS MANAGE
# ======================================================
include 'siakad_dashboard_progress_manage.php';


# ======================================================
# SEMESTER AKTIF
# ======================================================
include 'siakad_dashboard_semester_aktif.php';


# ======================================================
# GRAFIK STUDENT BODY
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
	// $jumlah_sudah_bayar_prodi[$rid_prodi[$i]] = 0;
	// $jumlah_sudah_krs_prodi[$rid_prodi[$i]] = 0;
} 
for ($i=0; $i < count($rid_jalur); $i++){
	$jumlah_mhs_aktif_jalur[$rid_jalur[$i]] = 0;
} 
$jumlah_mhs_aktif_unprodi = 0;
$jumlah_mhs_aktif_unjalur = 0;
$jumlah_belum_bayar = 0;
$jumlah_belum_krs = 0;


while ($d = mysqli_fetch_assoc($q)) {
	$id_prodi=$d['id_prodi'];
	if($id_prodi!=''){
		$jumlah_mhs_aktif_prodi[$id_prodi]++;
		// if($d['status_bayar_manual']){
		// 	$jumlah_sudah_bayar_prodi[$id_prodi]++;
		// 	$jumlah_sudah_bayar++;
		// } 
		// if($d['status_krs_manual']){
		// 	$jumlah_sudah_krs_prodi[$id_prodi]++;
		// 	$jumlah_sudah_krs++;
		// } 
    $id_jalur=$d['id_jalur'];
    if($id_jalur!=''){
      $jumlah_mhs_aktif_jalur[$id_jalur]++;
    }else{
      $jumlah_mhs_aktif_unjalur++;
    }

  }else{
    $jumlah_mhs_aktif_unprodi++;
  }

}






















$gf='';
$ta = 'TA. 2022 Genap';

# ======================================================
$chart_no = 1;
# ======================================================
$judul_item[$chart_no] = 'Program Studi';
$judul_chart[$chart_no] = 'Student Body';
$tb_header[$chart_no] = ['Program Studi','Persen','Jumlah'];
$satuan[$chart_no] = 'Mhs';
$sub_judul_chart[$chart_no] = "$jumlah_mhs_aktif Mhs Aktif";
$rlabel[$chart_no] = [];
$rlink[$chart_no] = [];
$rsum[$chart_no] = [];
$rwarna[$chart_no] = '';
foreach ($rid_prodi as $id_prodi){
	array_push($rsum[$chart_no],$jumlah_mhs_aktif_prodi[$id_prodi]);
	array_push($rlabel[$chart_no],$rjenjang_prodi[$id_prodi].'-'.$rprodi[$id_prodi]);
	array_push($rlink[$chart_no],'?master_mhs&status_mhs=1&id_prodi='.$id_prodi);
	$rwarna[$chart_no] .= '"'. $rwarna_prodi[$id_prodi].'",';
}
$chart_jumlah[$chart_no] = array_sum($rsum[$chart_no]);


# ======================================================
$chart_no = 2;
# ======================================================
// $judul_item[$chart_no] = 'Jalur Daftar';
// $judul_chart[$chart_no] = 'Jalur Daftar';
// $tb_header[$chart_no] = ['Jalur Daftar','Persen','Jumlah'];
// $satuan[$chart_no] = 'Mhs';
// $sub_judul_chart[$chart_no] = "$jumlah_mhs_aktif Mhs Aktif";
// $rlabel[$chart_no] = [];
// $rsum[$chart_no] = [];
// $rlink[$chart_no] = [];
// $rwarna[$chart_no] = '';
// foreach ($rid_jalur as $id_jalur){
// 	array_push($rsum[$chart_no],$jumlah_mhs_aktif_jalur[$id_jalur]);
// 	array_push($rlabel[$chart_no],$rjalur[$id_jalur]);
// 	array_push($rlink[$chart_no],'?master_mhs&status_mhs=1&id_jalur='.$id_jalur);
// 	$rwarna[$chart_no] .= '"'. $rwarna_jalur[$id_jalur].'",';
// }
// if($jumlah_mhs_aktif_unjalur){
//   array_push($rsum[$chart_no],$jumlah_mhs_aktif_unjalur);
//   array_push($rlabel[$chart_no],'NULL');
// 	array_push($rlink[$chart_no],'?master_mhs&status_mhs=1&id_jalur=null');
//   $rwarna[$chart_no] .= '"#ff0000",';
// }
// $chart_jumlah[$chart_no] = array_sum($rsum[$chart_no]);



$gf = '';
for ($h=1; $h <= count($judul_item); $h++) { 
  $data_chart = '';
  $tr = '';
  for ($i=0; $i < count($rlabel[$h]); $i++) { 
    $data_chart.="{
      value: ".$rsum[$h][$i].",
      name: '".$rlabel[$h][$i]."',
    },";

    $chart_persen = number_format($rsum[$h][$i]/$chart_jumlah[$h]*100,2);

    $a = "<a href='".$rlink[$h][$i]."' target=_blank>";
    $tr.="
    <tr>
      <td class=''>$a".$rlabel[$h][$i]."</a></td>
      <td class='text-right'>$chart_persen%</td>
      <td class='text-right'>$a".$rsum[$h][$i]." $satuan[$h]</a></td>
    </tr>
    ";
  }

  $gf .= "
	<div class='col-lg-4 mb-4'>
		<div class='wadah gradasi-hijau'>
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

$gf3 = "
  <div class='col-lg-4'>
    <div id='gf3' style='height: 100%'></div>
  </div>

  <script type='text/javascript'>
    var dom = document.getElementById('gf3');
    var myChart = echarts.init(dom, null, {
      renderer: 'canvas',
      useDirtyRect: false,
    });
    var app = {};

    var option;

    option = {
      tooltip: {
        trigger: 'axis',
        axisPointer: {
          type: 'shadow',
        },
      },
      legend: {},
      grid: {
        left: '3%',
        right: '4%',
        bottom: '3%',
        containLabel: true,
      },
      xAxis: [
        {
          type: 'category',
          data: [2020, 2021, 2022],
        },
      ],
      yAxis: [
        {
          type: 'value',
        },
      ],
      series: [
        {
          name: 'Reguler',
          type: 'bar',
          stack: 'Jalur',
          emphasis: {
            focus: 'series',
          },
          data: [120, 132, 101, 134, 90, 230, 210],
        },
        {
          name: 'KIP',
          type: 'bar',
          stack: 'Jalur',
          emphasis: {
            focus: 'series',
          },
          data: [220, 182, 191, 234, 290, 330, 310],
        },
        {
          name: 'KIP-C',
          type: 'bar',
          stack: 'Jalur',
          emphasis: {
            focus: 'series',
          },
          data: [150, 232, 201, 154, 190, 330, 410],
        },
        {
          name: 'MBKM',
          type: 'bar',
          stack: 'Jalur',
          emphasis: {
            focus: 'series',
          },
          data: [150, 232, 201, 154, 190, 330, 410],
        },
      ],
    };

    if (option && typeof option === 'object') {
      myChart.setOption(option);
    }

    window.addEventListener('resize', myChart.resize);
  </script>
";

echo "<div class='row'>
  $gf
</div>";

?>





<style>
	.rounded10{border-radius:10px}
  .tb-grafik th, .td-jumlah{ font-size:12px; padding:5px;}
  .tb-grafik td{ color: #333333; font-size:11px; padding:5px;}
  .tb-grafik thead{color:white;background:gray;}
  .td-jumlah {font-weight:bold; color: white !important; background: #999; font-family:consolas }
</style>











   