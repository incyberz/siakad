<?php
$s = "SELECT a.*,
(
  SELECT SUM(jumlah) FROM tb_bayar WHERE id_biaya=a.id
  ) as jumlah_bayar, 
(
  SELECT tanggal_bayar FROM tb_bayar WHERE id_biaya=a.id AND id_mhs=$id_mhs ORDER BY tanggal_bayar DESC LIMIT 1
  ) as last_bayar, 
(
  SELECT status FROM tb_bayar WHERE id_biaya=a.id AND id_mhs=$id_mhs ORDER BY tanggal_bayar DESC LIMIT 1
  ) as status_bayar, 
(
  SELECT tanggal_penagihan FROM tb_penagihan WHERE id_biaya=a.id AND id_mhs=$id_mhs 
  ) as tanggal_penagihan 
FROM tb_biaya a 
WHERE a.no is not null ORDER BY a.no";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));

$tr_biaya='
<thead>
  <th>Jenis Biaya</th>
  <th class=text-right>Jumlah & Status Bayar</th>
</thead>
';
$i=0;
while ($d=mysqli_fetch_assoc($q)) {
  $i++;
  $nominal = number_format($d['nominal'],0);
  $jumlah_bayar = $d['jumlah_bayar']==''?'':number_format($d['jumlah_bayar'],0);
  $sisa_bayar = $d['nominal']-$d['jumlah_bayar'];

  $wa_petugas = "<a href='?wa'><img height=25px class=img_zoom src='../assets/img/icons/wa.png'></a>";


  $lunas_show = $jumlah_bayar==''?'':"<div><a class='tebal red' href='?lihat_trx&id_biaya=$d[id]'>Belum Lunas (sisa ".number_format($sisa_bayar).')</a></div>';
  $lunas_show = $sisa_bayar==0?"<div><a class='tebal darkred' href='?lihat_trx&id_biaya=$d[id]'>Sedang Proses Verifikasi</a> $wa_petugas</div>":$lunas_show;
  $lunas_show = $d['status_bayar']==1?"<div><a class='tebal biru' href='?lihat_trx&id_biaya=$d[id]'>Lunas</a></div>":$lunas_show;
  $status_bayar = $jumlah_bayar==''?'':"$jumlah_bayar<div class='kecil miring abu'>$d[last_bayar]</div>$lunas_show";
  
  $form_bayar = "
  <form method=post action='?bayar'>
    <input class=debug name=dapat_dicicil value='$d[dapat_dicicil]'>
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
      <p>Berikut adalah Data Pembayaran yang pernah Anda bayarkan:</p>
      <!-- <div class="alert alert-info">
        Maaf, fitur ini belum bisa Anda gunakan.
      </div> -->
    </div>

    <table class="table table-striped table-hover">
      <?=$tr_biaya?>
    </table>


  </div>
</section>