<?php
if(0){
$gf='';
$ta = 'TA. 2022 Genap';

$chart_no = 1;
$judul_item[$chart_no] = 'Program Studi';
$warna_item[$chart_no] = "'#5470C6','#6BAC4C','#F1B327','#C544C3','#73C0DE'";
$judul_chart[$chart_no] = 'Student Body';
$sub_judul_chart[$chart_no] = 'By Study Program';
$rlabel[$chart_no] = ['S1-TI','S1-RPL','S1-SI','D3-MI','D3-KA'];
$rsum[$chart_no] = [1567,54,97,654,448];
$chart_jumlah[$chart_no] = array_sum($rsum[$chart_no]);

$chart_no = 2;
$judul_item[$chart_no] = 'Program Studi';
$warna_item[$chart_no] = "'#5470C6','#ff8000','#ff0000','#6BAC4C','#ff00ff'";
$judul_chart[$chart_no] = 'Student Body';
$sub_judul_chart[$chart_no] = 'By Student Status';
$rlabel[$chart_no] = ['Aktif','Cuti','Non-Aktif','Lulus','D.D.'];
$rsum[$chart_no] = [2416,51,139,206,8];
$chart_jumlah[$chart_no] = array_sum($rsum[$chart_no]);

$chart_no = 3;
$judul_item[$chart_no] = 'Kehadiran';
$warna_item[$chart_no] = "'#6BAC4C','#ff8000','#ff00ff','#ff0000','#5470C6'";
$judul_chart[$chart_no] = 'Presensi Mahasiswa';
$sub_judul_chart[$chart_no] = 'Minggu Sekarang';
$rlabel[$chart_no] = ['Hadir','Sakit','Izin','Tanpa Ket.','Pindah Jadwal'];
$rsum[$chart_no] = [2261,19,35,76,429];
$chart_jumlah[$chart_no] = array_sum($rsum[$chart_no]);

$chart_no = 4;
$judul_item[$chart_no] = 'Kehadiran';
$warna_item[$chart_no] = "'#6BCC4C','#ff8000','#ff00ff','#ff0000','#6BAC4C'";
$judul_chart[$chart_no] = 'Presensi Mengajar Dosen';
$sub_judul_chart[$chart_no] = 'Minggu Sekarang';
$rlabel[$chart_no] = ['Mengajar','Sakit','Pindah Jadwal','Tanpa Ket.'];
$rsum[$chart_no] = [36,2,5,7];
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
      <td class='text-right'>".$rsum[$h][$i]."</td>
    </tr>
    ";
  }

  $gf .= "
    <div class='col-lg-6 mb-4'>
      <div class='card'>
        <div class='card-body'>
          <h5 class='card-title'>$ta</h5>

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
                    color: [$warna_item[$h]],
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
              <th>Labels</th>
              <th class='text-right'>Percent</th>
              <th class='text-right'>Count</th>
            </thead>
            $tr
            <tr>
              <td class='td-jumlah' colspan='2'>Jumlah</td>
              <td class='td-jumlah text-right'>$chart_jumlah[$h]</td>
            </tr>

          </table>

        </div><!-- End card-body -->
      </div><!-- End card -->
    </div>

  ";
}


?>
<style>
  .tb-grafik th, .td-jumlah{ font-size:12px; padding:5px;}
  .tb-grafik td{ color: #333333; font-size:11px; padding:5px;}
  .tb-grafik thead{
    color:white;
    background:gray;
  }
  .td-jumlah {font-weight:bold; color: white !important; background: #999; font-family:consolas }
</style>
<section id="info_siakad" class="portfolio">
  <div class="container">

    <div class="section-title" data-aos="fade-up">
      <h2>Informasi</h2>
      <p>Berikut adalah informasi publik secara realtime dari Sistem SIAKAD</p>
    </div>


    <div class="row" data-aos="fade-up">
      <?=$gf?>
    </div>    
 
    
  </div>
</section>

<script src="assets/vendor/echarts/echarts.min.js"></script>

<?php } ?>