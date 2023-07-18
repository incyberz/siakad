<?php
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
  SELECT verif_status FROM tb_bayar WHERE id_biaya=a.id AND id_mhs=$id_mhs ORDER BY tanggal DESC LIMIT 1
  ) as status_bayar, 
(
  SELECT tanggal_penagihan FROM tb_penagihan WHERE id_biaya=a.id AND id_mhs=$id_mhs 
  ) as tanggal_penagihan 
FROM tb_biaya a 
ORDER BY a.no";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
if(mysqli_num_rows($q)==0){
  die(div_alert('danger',"Belum ada data biaya untuk angkatan $angkatan prodi $prodi"));
}

$tr_biaya="
<thead>
  <th>No</th>
  <th>Jenis Biaya</th>
  <th class=text-right>Jumlah & Status Bayar</th>
</thead>
";
$i=0;
while ($d=mysqli_fetch_assoc($q)) {
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


  $lunas_show = $jumlah_bayar==''?'':"<div><a class='tebal red' href='?lihat_trx&id_biaya=$d[id]'>Belum Lunas (sisa ".number_format($sisa_bayar).')</a></div>';
  $lunas_show = $sisa_bayar==0?"<div><a class='tebal darkred' href='?lihat_trx&id_biaya=$d[id]'>Sedang Proses Verifikasi</a> $form_wa_petugas</div>":$lunas_show;
  $lunas_show = $d['status_bayar']==1?"<div><a class='tebal biru' href='?lihat_trx&id_biaya=$d[id]'>Lunas</a></div>":$lunas_show;
  $status_bayar = $jumlah_bayar==''?'':"$jumlah_bayar<div class='kecil miring abu'>$d[last_bayar]</div>$lunas_show";
  
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


  $tr_biaya.="
  <tr>
    <td>$d[no]</td>
    <td>
      $d[nama]<span class=debug>$d[id]</span>
      <div class='kecil miring abu'>$d[jenis]</div>
      Rp $nominal
      <span class=debug>tanggal_penagihan: $d[tanggal_penagihan]</span>
    </td>
    <td class=text-right>$status_bayar$link_bayar</td>
  </tr>";
}
?>

<section id="pembayaran" class="" data-aos="fade-left">
  <div class="container">

    <div class="section-title">
      <h2>Pembayaran</h2>
      <p>Berikut adalah Data Tagihan untuk angkatan <b><u id=angkatan><?=$angkatan?></u></b> prodi <b><u><?=$prodi?></u></b> <span class=debug id=id_prodi><?=$id_prodi?></span>:</p>
    </div>

    <table class="table table-striped table-hover">
      <?=$tr_biaya?>
    </table>


  </div>
</section>