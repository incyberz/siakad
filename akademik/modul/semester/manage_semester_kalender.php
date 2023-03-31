<div class="wadah gradasi-hijau">
  <h3 class='m0 mb2'>Kalender Semester <?=$semester_ke?></h3>
  <?php
  $selisih = (strtotime($batas_akhir)-strtotime($batas_awal)) / (60*60*24);

  $last_month = '';
  $next_day = $batas_awal;
  $first_month = '<div class=this_month>'.date('F Y',strtotime($next_day)).'</div>';
  $item_tgl = "$first_month<div class='wadah flexy'>";
  for ($i=1; $i <= $selisih ; $i++) {

    // styling KRS
    $BYR = (strtotime($next_day) >= strtotime($awal_bayar) && strtotime($next_day) <= strtotime($akhir_bayar)) ? '<div class=BYR>BYR</div>' : '';
    $KRS = (strtotime($next_day) >= strtotime($awal_krs) && strtotime($next_day) <= strtotime($akhir_krs)) ? '<div class=KRS>KRS</div>' : '';
    $KUTS = (strtotime($next_day) >= strtotime($awal_kuliah_uts) && strtotime($next_day) <= strtotime($akhir_kuliah_uts)) ? '<div class=KUTS>KUTS</div>' : '';
    $KUAS = (strtotime($next_day) >= strtotime($awal_kuliah_uas) && strtotime($next_day) <= strtotime($akhir_kuliah_uas)) ? '<div class=KUAS>KUAS</div>' : '';
    $UTS = (strtotime($next_day) >= strtotime($awal_uts) && strtotime($next_day) <= strtotime($akhir_uts)) ? '<div class=UTS>UTS</div>' : '';
    $UAS = (strtotime($next_day) >= strtotime($awal_uas) && strtotime($next_day) <= strtotime($akhir_uas)) ? '<div class=UAS>UAS</div>' : '';


    // Basic ui kalender
    $next_day_show = date('d/m',strtotime($next_day));
    $this_month = date('F Y',strtotime($next_day));
    $ahad = date('w',strtotime($next_day))==0 ? 'ahad' : '';
    $hr = ($last_month!=$this_month && $i>1) ? "</div><div class=this_month>$this_month</div><div class='wadah flexy'>" : '';
    $item_tgl .= "
    $hr
    <div class='item_tgl $ahad'>
      <table width=100%>
        <tr>
          <td class=hari_ke>$i</td>
          <td class='tgl_ke text-right'>$next_day_show</td>
        </tr>
      </table>
      $BYR $KRS $KUTS $UTS $KUAS $UAS
    </div>
    ";
    $last_month = date('F Y',strtotime($next_day));
    $next_day = date('Y-m-d',strtotime("+1 day",strtotime($next_day)));
    $first_month = '';
  }
  $item_tgl.='</div>';
  ?>
  <style>
    .flexy{
      gap: 3px;
      margin-bottom: 15px;
    }
    .item_tgl{
      border: solid 1px #ccc;
      border-radius: 5px;
      background: white;
      width: 70px;
      height: 70px;
      padding: 3px
    }
    .hari_ke{
      font-size: 8px;
    }
    .tgl_ke{
      font-size: 9px;
      color:blue;
    }
    .this_month{
      font-size: 16px;
      margin-bottom: 5px;
      margin-left: 10px;
      color: #33f;
      margin-top: 20px;
    }

    .ahad{
      background:linear-gradient(#faa,#f55);
    }

    .BYR,.KRS,.KUTS,.KUAS,.UTS,.UAS {text-align:center;font-size:10px;}
    .BYR {background: #ff0; color:black}
    .KRS {background: #0f0; color:white}
    .KUTS,.KUAS {background: #00f; color:white}
    .UTS,.UAS {background: #f0f; color:black}
  </style>
  <?=$item_tgl?>
</div>
