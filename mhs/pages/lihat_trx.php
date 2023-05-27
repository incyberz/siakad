<?php
if(isset($_POST['btn_hapus'])){
  $id_bayar = $_POST['id_bayar'];
  $s = "DELETE FROM tb_bayar WHERE id=$id_bayar";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  die('<script>location.replace("?pembayaran")</script>');
}

$id_biaya = isset($_GET['id_biaya']) ? $_GET['id_biaya'] : die('<script>location.replace("?pembayaran")</script>');
$s = "SELECT a.*, (SELECT nama FROM tb_user WHERE id=a.verified_by) as nama_petugas 
FROM tb_bayar a 
WHERE a.id_biaya = $id_biaya";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));

$trx = '';
if(mysqli_num_rows($q)>0){
  while ($d=mysqli_fetch_assoc($q)) {
    $jumlah = number_format($d['jumlah']);
    $status = $d['status']==1 ? "<span class='biru tebal'>Verified by $d[nama_petugas]</span>" : '<span class=darkred>Sedang diverifikasi ...</span>';
    $status = $d['status']==-1 ? "<span class='merah tebal'>Rejected by $d[nama_petugas]: $d[alasan_reject]</span>" : $status;
    $src = "../uploads/bukti_bayar/$d[id].jpg";
    $img = "<a href='$src' target=_blank><img class='img_bukti_bayar' src='$src' /></a>";

    $link_batalkan = $d['status']==1 ? '<span class="kecil miring abu">Bukti sudah diverifikasi.</span>' 
    : "<form method=post><input class=debug name=id_bayar value=$d[id]><button class='btn btn-danger' onclick='return confirm(\"Yakin mau hapus Bukti Bayar ini?\")' name=btn_hapus>Batalkan / Hapus Bukti</button></form>";
    $trx.= "<li class='kecil miring abu li_trx mb-4'>$d[tanggal_bayar], Rp $jumlah, $status<div class=mb-2>$img</div>$link_batalkan</li>";
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