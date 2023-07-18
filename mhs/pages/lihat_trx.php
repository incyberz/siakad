<?php
if(isset($_POST['btn_hapus'])){
  $id_bayar = $_POST['id_bayar'];
  $s = "DELETE FROM tb_bayar WHERE id=$id_bayar";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  die('<script>location.replace("?pembayaran")</script>');
}

$id_biaya = isset($_GET['id_biaya']) ? $_GET['id_biaya'] : die('<script>location.replace("?pembayaran")</script>');
$s = "SELECT a.*, (SELECT nama FROM tb_user WHERE id=a.verif_by) as nama_petugas 
FROM tb_bayar a 
WHERE a.id_biaya = $id_biaya 
AND id_mhs=$id_mhs"; 
echo "<pre class=debug>$s</pre>";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));

$trx = '';
if(mysqli_num_rows($q)>0){
  while ($d=mysqli_fetch_assoc($q)) {
    $nominal = number_format($d['nominal']);
    $verif_status = $d['verif_status']==1 ? "<span class='biru tebal'>Verified by $d[nama_petugas]</span>" : '<span class=darkred>Sedang diverifikasi ...</span>';
    $verif_status = $d['verif_status']==-1 ? "<span class='merah tebal'>Rejected by $d[nama_petugas]: $d[alasan_reject]</span>" : $verif_status;
    $src = "../uploads/bukti_bayar/$d[id].jpg";
    $img = "<a href='$src' target=_blank><img class='img_bukti_bayar' src='$src' /></a>";

    $link_batalkan = $d['verif_status']==1 ? '<span class="kecil miring abu">Bukti sudah diverifikasi.</span>' 
    : "<form method=post><input class=debug name=id_bayar value=$d[id]><button class='btn btn-danger' onclick='return confirm(\"Yakin mau hapus Bukti Bayar ini?\")' name=btn_hapus>Batalkan / Hapus Bukti</button></form>";
    $trx.= "<li class='kecil miring abu li_trx mb-4'>$d[tanggal], Rp $nominal, $verif_status<div class=mb-2>$img</div>$link_batalkan</li>";
  }
  $trx = "<div class=wadah><ol>$trx</ol></div>";
}
?>

<style>
  .img_bukti_bayar{
    max-width: 400px;
    max-height: 400px;
  }
</style>
<section id="" class="" data-aos="fade-left">
  <div class="container">

    <div class="section-title">
      <h2>Lihat Transaksi</h2>
      <p>Berikut adalah Bukti Bayar yang pernah Anda upload.</p>
      <!-- <div class="alert alert-info">
        Maaf, fitur ini belum bisa Anda gunakan.
      </div> -->
    </div>

    <?=$trx?>


  </div>
</section>