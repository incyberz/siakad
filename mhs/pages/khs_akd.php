
<style>.mobile{display:none}@media(max-width:575px){.mobile{display:inline}}</style>
<style>.desktop{display:inline}@media(max-width:575px){.desktop{display:none}}</style>
<?php
function hm2angka($a){
  switch (strtolower($a)) {
    case 'a': return 4; break;
    case 'b': return 3; break;
    case 'c': return 2; break;
    case 'd': return 1; break;
    case 'e': return 0; break;
  }
  return false;
}

# ==========================================================
# SAAT INI
# ==========================================================
$s = "SELECT 
a.*, 
b.kode as kode_mk,
b.nama as nama_mk,
b.semester,
b.dosen,
b.bobot 
FROM tb_nilai_tmp a 
JOIN tb_mk_tmp b ON a.id_mk_tmp=b.id 
WHERE a.nim='$nim'";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));

$tb_tmp=div_alert('Danger','Data Nilai tidak ditemukan (Anda belum KRS atau Petugas Akademik belum input nilai).');
if(mysqli_num_rows($q)>0){
  for ($i=1; $i <=8 ; $i++) {
    $div[$i]=''; 
    $total_bobot_smt[$i]=0;
    $total_nilai_smt[$i]=0;
    $dmk[$i]='';
  }
  $i=0;
  $total_bobot=0;
  $total_nilai=0;
  $max_smt=0;
  while ($d=mysqli_fetch_assoc($q)) {
    $i++;
    $max_smt = $d['semester']>$max_smt?$d['semester']:$max_smt;
    $nilai = $d['nilai']==''?'<span style="color:#f55"><i>null</i></span>':$d['nilai'];
    $d['hm'] = $d['hm']==''?'E':$d['hm'];
    $total_bobot+=$d['bobot'];
    $total_nilai+=hm2angka($d['hm'])*$d['bobot'];
    $total_bobot_smt[$d['semester']]+=$d['bobot'];
    $total_nilai_smt[$d['semester']]+=hm2angka($d['hm'])*$d['bobot'];
    $kode_mk = $d['kode_mk']==''?'':$d['kode_mk'].' :: ';
    $div[$d['semester']].="
      <div class='wadah bg-white'>
        <div class=row>
          <div class='col-sm-1 kecil desktop'>$i</div>
          <div class='col-sm-5'>$kode_mk$d[nama_mk]</div>
          <div class='col-sm-2 kecil'><span class='mobile'>Nilai:</span> $nilai</div>
          <div class='col-sm-2 kecil'><span class='mobile'>SKS:</span> $d[bobot]</div>
          <div class='col-sm-2 kecil'><span class='mobile'>HM:</span> $d[hm]</div>
        </div>
      </div>
    ";

    $kode_mk = $d['kode_mk']==''?'-':$d['kode_mk'];
    $dmk[$d['semester']] .= $i . ';'
             . $kode_mk . ';'
             . $d['nama_mk'] . ';'
             . $d['nilai'] . ';'
             . $d['bobot'] . ';'
             . $d['hm'] . ';'
             . '<br>'
             ;


  }


  
  $divs='';
  $dmks='';
  for ($i=1; $i <= $max_smt ; $i++) {
    $ip[$i] = $total_bobot_smt[$i]==0?0:round($total_nilai_smt[$i]/$total_bobot_smt[$i],2);
    $ip_show[$i] = "<div class='wadah gradasi-kuning text-center'>IP-Semester-$i : $ip[$i]</div>";
    $div[$i]=$div[$i]==''?"<div class='wadah gradasi-merah'>Semester $i ~ No Data.</div>"
    :"<div class='wadah gradasi-hijau'><p>Semester $i</p>$div[$i]$ip_show[$i]</div>";
    $dmk[$i]=$dmk[$i]==''?"Semester $i ~ No Data.":"$dmk[$i]|$ip[$i]";
    $divs.=$div[$i];
    $dmks.=$dmk[$i].'<hr>';
  }

  $ipk = $total_bobot==0?0:round($total_nilai/$total_bobot,2);
  $ipk_show = "<div class='wadah gradasi-biru text-center'>IP Kumulatif : $ipk</div>";

  $divs.= $ipk_show;
  $dmks.= "||$ipk";
  $info = '<div class="mt-3"><small><i>)* jika terdapat nilai <code>null</code> maka dikarenakan Anda belum KRS.</i></small></div>';
  $divs.=$info;
}else{
  $divs = div_alert('danger','Belum ada data nilai dari akademik.');
}
?>


<section id="khs_akd" class="section-bg"  data-aos="fade-left">
  <div class="container">

  <div class=wadah>
    <?=$dmks?>
  </div>

    <div class="section-title">
      <h2>KHS dari Akademik</h2>
      <p>Berikut adalah Kartu Hasil Studi (KHS) langsung dari Data Akademik (Non-SIAKAD).</p>
    </div>

    <?=$divs?>
    <hr>
    <form method=post target=_blank action='?khs_pdf'>
      <input type="hiddena" value="<?=$dmks?>" name=dmks>
      <input type="hidden" value="<?=$nim?>" name=nim>
      <button class="btn btn-primary">Donload KHS PDF</button>
    </form>

  </div>
</section>

