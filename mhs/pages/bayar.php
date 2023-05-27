<style>.li_trx{border:solid 1px #ccc; border-radius:5px; padding:5px; margin-bottom:5px; margin-left:-15px}</style>
<?php
if(isset($_POST['btn_upload'])){ 

  if($_POST['jumlah_bayar']%10000!=0){
    die(div_alert('danger','Nominal uang harus kelipatan 10.000. Silahkan coba kembali.<hr><a href=?pembayaran>Kembali</a>'));
  }
  $s = "SELECT auto_increment FROM information_schema.tables 
  WHERE table_schema = '$db_name' 
  AND table_name = 'tb_bayar'
  ";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  $d=mysqli_fetch_assoc($q);
  $new_id_bayar = $d['auto_increment'];

  $path_upload = '../uploads/bukti_bayar';
  if(move_uploaded_file($_FILES['bukti_bayar']['tmp_name'],"$path_upload/$new_id_bayar.jpg")){
    $s = "INSERT INTO tb_bayar 
    (id,id_biaya,id_mhs,jumlah) VALUES 
    ($new_id_bayar,$_POST[id_biaya],$id_mhs,$_POST[jumlah_bayar])";
    $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
    
    die('<script>location.replace("?pembayaran")</script>');

  }else{
    div_alert('danger','Gagal move-upload bukti bayar.');
  }
}

$id_biaya = isset($_POST['id_biaya'])?$_POST['id_biaya']:die(erid('id_biaya'));
$nama_biaya = isset($_POST['nama_biaya'])?$_POST['nama_biaya']:die(erid('nama_biaya'));
$sisa_bayar = isset($_POST['sisa_bayar'])?$_POST['sisa_bayar']:die(erid('sisa_bayar'));
$dapat_dicicil = isset($_POST['dapat_dicicil'])?$_POST['dapat_dicicil']:die(erid('dapat_dicicil'));

$info_cicilan = $dapat_dicicil ? "<span class='kecil miring hijau'>)* Biaya ini dapat Anda cicil.</span>":"<span class='kecil miring darkred'>)* Biaya ini tidak dapat dicicil.</span>";
$input_bayar = $dapat_dicicil 
? "<input id=jumlah_bayar name=jumlah_bayar type='number' class='form-control' min=50000 max=$sisa_bayar value=$sisa_bayar required>" 
: "<input class='form-control' value=$sisa_bayar disabled><input class=debug name=jumlah_bayar value=$sisa_bayar>";

# ==============================================
# CICILAN PEMBAYARAN SEBELUMNYA
# ==============================================
$s = "SELECT a.*,
(SELECT nama FROM tb_user WHERE id=a.verified_by) as nama_petugas 
FROM tb_bayar a 
WHERE a.id_biaya=$id_biaya AND a.id_mhs=$id_mhs";
echo "<div class=debug>$s</div>";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));

$trx_sebelumnya = '';
if(mysqli_num_rows($q)>0){
  while ($d=mysqli_fetch_assoc($q)) {
    $jumlah = number_format($d['jumlah']);
    $status = $d['status']==1 ? "<span class='biru tebal'>Verified by $d[nama_petugas]</span>" : '<span class=red>Sedang diverifikasi ...</span>';
    $status = $d['status']==-1 ? "<span class='merah tebal'>Rejected by $d[nama_petugas]: $d[alasan_reject]</span>" : $status;
    $trx_sebelumnya.= "<li class='kecil miring abu li_trx'>$d[tanggal_bayar], Rp $jumlah, $status</li>";
  }
  $trx_sebelumnya = "<div class=wadah><label><a href=?lihat_trx&id_biaya=$id_biaya>Transaksi Sebelumnya:</a></label> <ol>$trx_sebelumnya</ol></div>";
}
?>
<section id="krs" class="" data-aos="fade-left">
  <div class="container">

    <div class="section-title">
      <h2>Transaksi Pembayaran</h2>
      <p><a href='?pembayaran'>Kembali</a> | Silahkan Anda upload Bukti Bayar <u><?=$nama_biaya?></u>!</p>
    </div>
    
    <?=$trx_sebelumnya?>
    
    
    <div class="wadah ">
      <form method="post" enctype='multipart/form-data'>
        <input class="debug" name=id_biaya value=<?=$id_biaya?>>
        <label for="jumlah_bayar">Jumlah yang harus Anda bayar:</label> 
        <?=$input_bayar?>
        <?=$info_cicilan?>
        <div class="form-group">
          <label for="bukti_bayar">Bukti Bayar</label>
          <input class=form-control type="file" name="bukti_bayar" id="bukti_bayar" accept="image/jpeg" required>
          <div class='kecil miring abu mt-1'>Silahkan upload bukti bayar dengan format JPG. Jika dari mesin ATM, silahkan foto terlebih dahulu, jika menggunakan online payment cukup dengan screenshoot.</div>
        </div>
        <button class='btn btn-primary btn-block' name=btn_upload>Upload</button>
      </form>
    </div>


  </div>
</section>