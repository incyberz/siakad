<?php
// auto penagihan biaya semester
$tnow = strtotime('now');

$s = "SELECT a.* 
FROM tb_semester a 
JOIN tb_kalender b ON a.id_kalender=b.id 
WHERE a.tanggal_awal<'$now' AND a.tanggal_akhir>'$now' 
AND b.jenjang='$jenjang' AND b.angkatan=$angkatan";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
if(mysqli_num_rows($q)==0) echo div_alert('warning', 'Tidak ada semester aktif untuk Anda.');
if(mysqli_num_rows($q)>1) echo div_alert('danger', 'Duplikat semester aktif ditemukan. Segera lapor Petugas!');
if(mysqli_num_rows($q)==1){
  $d=mysqli_fetch_assoc($q);
  $awal_smt = date('d-M-Y',strtotime($d['tanggal_awal']));
  $akhir_smt = date('d-M-Y',strtotime($d['tanggal_akhir']));

  $awal_bayar = date('d-M-Y',strtotime($d['awal_bayar']));
  $awal_krs = date('d-M-Y',strtotime($d['awal_krs']));
  $awal_kuliah_uts = date('d-M-Y',strtotime($d['awal_kuliah_uts']));
  $awal_kuliah_uas = date('d-M-Y',strtotime($d['awal_kuliah_uas']));
  $awal_uts = date('d-M-Y',strtotime($d['awal_uts']));
  $awal_uas = date('d-M-Y',strtotime($d['awal_uas']));

  $akhir_bayar = date('d-M-Y',strtotime($d['akhir_bayar']));
  $akhir_krs = date('d-M-Y',strtotime($d['akhir_krs']));
  $akhir_kuliah_uts = date('d-M-Y',strtotime($d['akhir_kuliah_uts']));
  $akhir_kuliah_uas = date('d-M-Y',strtotime($d['akhir_kuliah_uas']));
  $akhir_uts = date('d-M-Y',strtotime($d['akhir_uts']));
  $akhir_uas = date('d-M-Y',strtotime($d['akhir_uas']));

  $smt_aktif = $d['nomor'];

  $smt_aktif_info = "
  <div class='wadah gradasi-hijau'>
    <div>
      Semester Aktif untuk Anda: <span class='tebal darkblue'>Semester $smt_aktif</span> 
      <div class='kecil mb2 consolas purple'>$awal_smt ~ $akhir_smt</div>
    </div>
    <div class='wadah kecil bg-white'>
      Tanggal Pembayaran:
      <div class='consolas purple'>$awal_bayar ~ $akhir_bayar</div>
    </div>
  </div>
  ";  
}


// regular code
$untuk_semester = $_GET['untuk_semester'] ?? '';
$sql_untuk_semester = $untuk_semester=='' ? '1' : "a.untuk_semester=$untuk_semester";

$s = "SELECT a.*,

(
  SELECT nominal FROM tb_biaya_angkatan WHERE id_biaya=a.id and angkatan=$angkatan and id_prodi=$id_prodi 
  ) as nominal, 
(
  SELECT SUM(nominal) FROM tb_bayar WHERE id_biaya=a.id and id_mhs=$id_mhs 
  ) as jumlah_bayar, 
(
  SELECT tanggal FROM tb_bayar WHERE id_biaya=a.id AND id_mhs=$id_mhs ORDER BY tanggal DESC LIMIT 1
  ) as last_bayar, 
(
  SELECT alasan_reject FROM tb_bayar WHERE id_biaya=a.id AND id_mhs=$id_mhs ORDER BY tanggal DESC LIMIT 1
  ) as alasan_reject, 
(
  SELECT verif_status FROM tb_bayar WHERE id_biaya=a.id AND id_mhs=$id_mhs ORDER BY tanggal DESC LIMIT 1
  ) as verif_status, 
(
  SELECT tanggal_penagihan FROM tb_penagihan WHERE id_biaya=a.id AND id_mhs=$id_mhs 
  ) as tanggal_penagihan 
FROM tb_biaya a 
WHERE $sql_untuk_semester 
ORDER BY a.no";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
if(mysqli_num_rows($q)==0){
  die(div_alert('danger',"Belum ada data biaya untuk angkatan $angkatan prodi $prodi"));
}

$tr_biaya="
<thead>
  <th class=hideatm>No</th>
  <th>Jenis Biaya</th>
  <th class=text-right>Status Bayar</th>
</thead>
";
$i=0;
while ($d=mysqli_fetch_assoc($q)) {

  # ====================================================
  # AUTO-INSERT PENAGIHAN SEMESTER
  # ====================================================
  if($d['untuk_semester']==$smt_aktif AND $d['tanggal_penagihan']==''){
    $s2 = "SELECT id as id_biaya FROM tb_biaya 
    WHERE untuk_semester >= $smt_aktif AND untuk_semester <= $jumlah_semester";
    $q2 = mysqli_query($cn,$s2) or die(mysqli_error($cn));
    while ($d2=mysqli_fetch_assoc($q2)) {
      $id_biaya = $d2['id_biaya'];
      $id = "$id_mhs-$id_biaya";
      $s3 = "INSERT INTO tb_penagihan (id,id_biaya,id_mhs) VALUES ('$id',$id_biaya,$id_mhs) ON DUPLICATE KEY UPDATE id_biaya=$id_biaya,id_mhs=$id_mhs";
      $q3 = mysqli_query($cn,$s3) or die(mysqli_error($cn));
    }
    echo div_alert('success',"AUTO-INSERT penagihan sukses.");
    echo '<script>location.replace("?pembayaran")</script>';
    exit;
  }
  
  $i++;
  $nominal = $d['nominal']=='' ? $d['nominal_default'] : $d['nominal'];
  $nominal_show = number_format($nominal,0);
  $jumlah_bayar = $d['jumlah_bayar']==''?'':number_format($d['jumlah_bayar'],0);
  $sisa_bayar = $nominal-$d['jumlah_bayar'];

  $form_wa_petugas = "
    <form method=post action='?wa'>
      <input class=debug name=perihal value='Verifikasi Pembayaran $d[nama]'>
      <input class=debug name=kepada value='Petugas Keuangan'>
      <input class=debug name=no_tujuan value='$no_bau'>
      <input class=debug name=tanggal_pengajuan value='$d[last_bayar]'>
      <input class=debug name=info value='Nominal: $jumlah_bayar'>
      <input class=debug name=link_akses value='https://siakad.ikmi.ac.id/keuangan/?verifikasi_bukti_bayar&nim=$nim&id_biaya=$d[id]'>
      <button class='btn btn-success btn-sm'><img height=25px class=img_zoom src='../assets/img/icons/wa.png'> Hubungi Petugas</button>
    </form>
  ";

  
  
  if($jumlah_bayar==''){
    $lunas_show = '';
    $verif_status = '<span class="miring abu">belum melakukan pembayaran</span>';
  }else{
    if($d['verif_status']==''){
      $lunas_show = "<div><a class='tebal darkred' href='?lihat_trx&id_biaya=$d[id]'>Sedang Proses Verifikasi</a> $form_wa_petugas</div>";
    }else if($d['verif_status']==1){
      // sudah verifikasi OK
      if($sisa_bayar==0){
        $lunas_show = "<div><a class='tebal biru' href='?lihat_trx&id_biaya=$d[id]'>Lunas</a></div>";
      }else{
        $lunas_show = "<div><a class='tebal red' href='?lihat_trx&id_biaya=$d[id]'>Belum Lunas (sisa ".number_format($sisa_bayar).')</a></div>';
      }
    }else if($d['verif_status']==-1){
      $lunas_show = "<div><a class='tebal merah' href='?lihat_trx&id_biaya=$d[id]'>Rejected :: $d[alasan_reject]</a></div>";
    }
    $verif_status = "$jumlah_bayar<div class='kecil miring abu'>$d[last_bayar]</div>$lunas_show";
  }

  $bisa_dicicil = 0; //zzz debug
  $form_bayar = "
  <form method=post action='?bayar_tagihan'>
    <input class=debug name=bisa_dicicil value='$bisa_dicicil'>
    <input class=debug name=nama_biaya value='$d[nama]'>
    <input class=debug name=id_biaya value='$d[id]'>
    <input class=debug name=sisa_bayar value='$sisa_bayar'>
    <button class='btn btn-primary'>Bayar</button>
  </form>
  ";
  $link_bayar = $sisa_bayar==0?'': $form_bayar;
  $link_bayar = $d['tanggal_penagihan']==''?'<span class="kecil miring abu">Belum ada tagihan.</span>': $link_bayar;




  # ====================================================
  # TR OUTPUT
  # ====================================================
  $tr_biaya.="
  <tr>
    <td class=hideatm>$d[no]</td>
    <td>
      $d[nama]<span class=debug>$d[id]</span>
      <div class='kecil miring abu'>$d[jenis]</div>
      Rp $nominal
      <span class=debug>tanggal_penagihan: $d[tanggal_penagihan]</span>
    </td>
    <td class=text-right>$verif_status$link_bayar</td>
  </tr>";
}

$smt = $untuk_semester==''?'': "<span class='biru tebal'>Semester $untuk_semester</span> | <a href='?pembayaran'>Lihat Semua Biaya</a>";

$berikut = "<p>Berikut adalah Data Tagihan untuk <b><u>$prodi-$angkatan</u></b> $smt :</p>";
?>
<style>
  @media (max) {
    
  }
</style>
<style>.hideatm{display:block}@media(max-width:575px){.hideatm{display:none}}</style>

<section id="pembayaran" class="" data-aos="fade-left">
  <div class="container">

    
    <div class="section-title">
      <h2>Pembayaran</h2>
      <?=$smt_aktif_info ?>
      <?=$berikut ?>
    </div>

    <table class="table table-striped table-hover">
      <?=$tr_biaya?>
    </table>


  </div>
</section>