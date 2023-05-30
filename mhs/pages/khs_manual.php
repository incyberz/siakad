
<style>.mobile{display:none}@media(max-width:575px){.mobile{display:inline}}</style>
<style>.desktop{display:inline}@media(max-width:575px){.desktop{display:none}}</style>
<?php
$disabled_pdf = '';
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
b.dosen_manual,
b.bobot,
(SELECT 1 FROM tb_komplain_nilai WHERE id_nilai=a.id) as sedang_komplain 

FROM tb_nilai_manual a 
JOIN tb_mk_manual b ON a.id_mk_manual=b.id 
WHERE a.nim='$nim' 
ORDER BY b.semester";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));

$tb_tmp=div_alert('Danger','Data Nilai tidak ditemukan (Anda belum KRS atau Petugas Akademik belum input nilai).');
if(mysqli_num_rows($q)>0){
  for ($i=1; $i <=8 ; $i++) {
    $div[$i]=''; 
    $total_sks_smt[$i]=0;
    $total_nm_smt[$i]=0;
    $dmk[$i]='';
  }
  $i=0;
  $total_sks_all=0;
  $total_nm_all=0;
  $max_smt=0;
  $last_smt=0;
  $count_nilai=0;
  $count_nilai_verified=0;
  while ($d=mysqli_fetch_assoc($q)) {
    $count_nilai++;
    if($d['tanggal_disetujui_mhs']!='')$count_nilai_verified++;
    // $i++;
    if($last_smt!=$d['semester']){
      $last_smt=$d['semester'];
      $i=1; // reset nomor MK
    }else{
      $i++;
    }

    $max_smt = $d['semester']>$max_smt?$d['semester']:$max_smt;
    $nm = $d['nilai']==''?'<span style="color:#f55"><i>null</i></span>':$d['nilai'];
    $d['hm'] = $d['hm']==''?'E':$d['hm'];

    $am = hm2angka($d['hm']);
    $nm = $am*$d['bobot'];

    $total_sks_all+=$d['bobot'];
    $total_nm_all+=$nm;
    $total_sks_smt[$d['semester']]+=$d['bobot'];
    $total_nm_smt[$d['semester']]+=$am*$d['bobot'];

    $ipks[$d['semester']] = $total_nm_smt[$d['semester']]/$total_sks_smt[$d['semester']];
    $kode_mk = $d['kode_mk']==''?'':$d['kode_mk'].' :: ';

    $img_wa_complain = '<img src="../assets/img/icons/wa_complain.png" height=25px />';
    $img_sedang_complain = '<img src="../assets/img/icons/load.png" height=25px />';
    $img_agree = '<img src="../assets/img/icons/agree.png" height=25px />';
    $img_check = '<img src="../assets/img/icons/check.png" height=25px />';
    $link_complain = "<a href='?komplain_nilai&id_nilai=$d[id]' onclick='return confirm(\"Apakah kamu ingin komplain nilai ini ke dosen?\")'>$img_wa_complain</a>";
    $link_sedang_complain = "<a href='?komplain_nilai&id_nilai=$d[id]' onclick='return confirm(\"Menuju laman komplain nilai?\")'>$img_sedang_complain</a>";
    $link_agree = "<a href='?agree_nilai&id_nilai=$d[id]' onclick='return confirm(\"Apakah kamu setuju dengan nilai tersebut?\")'>$img_agree</a>";

    $aksi_nilai = $d['tanggal_disetujui_mhs']=='' ? "$link_complain $link_agree" : $img_check;
    $aksi_nilai = $d['sedang_komplain']=='' ? $aksi_nilai : $link_sedang_complain;

    $div[$d['semester']].="
      <div class='wadah bg-white'>
        <div class=row>
          <div class='col-sm-1 kecil desktop'>$i</div>
          <div class='col-sm-5'>
            $kode_mk$d[nama_mk]
            <span class=debug>id_nilai: $d[id]</span>
          </div>
          <div class='col-sm-6 kecil'>
            <div class=row>
              <div class='col-sm-3'><span class='mobile'>HM:</span> $d[hm]</div>
              <div class='col-sm-3'><span class='mobile'>SKS:</span> $d[bobot]</div>
              <div class='col-sm-3'><span class='mobile'>NM:</span> $nm</div>
              <div class='col-sm-3'>$aksi_nilai</div>
            </div>
          </div>
        </div>
      </div>
    ";

    $kode_mk = $d['kode_mk']==''?'-':$d['kode_mk'];


    $dmk[$d['semester']] .= $i . ';'
             . $kode_mk . ';'
             . $d['nama_mk'] . ';'
             . $d['bobot'] . ';'
             . $d['hm'] . ';'
             . $am . ';'
             . $nm . ';'
             . '<br>'
             ;


  } //end while loop db


  # =============================================
  # UI DATA HINGGA MAX SEMESTER
  # =============================================
  $divs='';
  $dmks='';
  $ipks=0;
  $total_sks=0;
  $total_nm=0;
  for ($i=1; $i <= $max_smt ; $i++) {
    $ip[$i] = $total_sks_smt[$i]==0?0:round($total_nm_smt[$i]/$total_sks_smt[$i],2);
    $total_sks+=$total_sks_smt[$i];
    $total_nm+=$total_nm_smt[$i];
    $ipks = $total_sks==0?0:round($total_nm/$total_sks,2);
    $ip_show[$i] = "
    <div class='wadah gradasi-kuning text-centera'>
      <div class=row>
        <div class='col-sm-2 offset-sm-1'>
          IP: $ip[$i]
        </div>
        <div class=col-sm-3>
          IPKS: $ipks
        </div>
        <div class='col-sm-2 text-left'>
          -
        </div>
        <div class='col-sm-2 text-left'>
          SKS: $total_sks
        </div>
        <div class='col-sm-2 text-left'>
          NM: $total_nm
        </div>
      </div>
    </div>";
    
    $div[$i]=$div[$i]==''?"<div class='wadah gradasi-merah'>Semester $i ~ No Data.</div>"
    :"<div class='wadah gradasi-hijau'><p>Semester $i</p>$div[$i]$ip_show[$i]</div>";
    $dmk[$i]=$dmk[$i]==''?"Semester $i ~ No Data.":"$dmk[$i]|$ip[$i]|$ipks|$total_sks|$total_nm";
    $divs.=$div[$i];
    $dmks.=$dmk[$i].'<hr>';
  } // END FOR MAX SEMESTER

  $ipk = $total_sks_all==0?0:round($total_nm_all/$total_sks_all,2);
  $ipk_show = "<div class='wadah gradasi-biru text-center'>IP Kumulatif : $ipk</div>";

  $divs.= $ipk_show;
  $dmks.= "||$ipk";
  $info = '<div class="mt-3"><small><i>)* jika terdapat nilai <code>null</code> maka dikarenakan Anda belum KRS.</i></small></div>';
  $divs.=$info;
}else{
  $divs = div_alert('danger','Belum ada data nilai dari akademik.');
  $disabled_pdf = 'disabled';
}

$disabled_pdf = $count_nilai==$count_nilai_verified ? $disabled_pdf : 'disabled';
$info_disabled = $count_nilai==$count_nilai_verified ? '' : "<div class='kecil red miring'>Semua nilai harus terverifikasi (Anda setujui) agar dapat cetak KHS.";
?>


<section id="khs_akd" class="section-bg"  data-aos="fade-left">
  <div class="container">

    <div class="section-title">
      <h2>KHS dari Akademik</h2>
      <p>Berikut adalah Kartu Hasil Studi (KHS) langsung dari Data Akademik (Non-SIAKAD).</p>
    </div>

    <?=$divs?>
    <hr>
    <form method=post target=_blank action='pages/khs_pdf.php'>
      <input type="hidden" value="<?=$dmks?>" name=dmks>
      <input type="hidden" value="<?=$nim?>" name=nim>
      <button class="btn btn-primary" <?=$disabled_pdf?>>Donload KHS PDF</button>
      <?=$info_disabled?>
    </form>

  </div>
</section>

<script>
  $(function(){
    $('.wa_not_ready').click(function(){
      alert('Maaf, Fitur Whatsapp Gateway sedang dalam tahap pengembangan. Terimakasih sudah mencoba.\n\n(by SIAKAD Programmer)');
    })
  })
</script>