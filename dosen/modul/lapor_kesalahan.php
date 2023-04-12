<?php
$judul = 'LAPORANKAN KESALAHAN';
$server_name = $online_version ? 'https://siakad.ikmi.ac.id/akademik' : 'http://localhost/siakad/akademik';
$no_wa_petugas = '6287729007318'; //zzz debug

if(isset($_POST['btn_laporkan'])){
  $link = $_POST['link'];
  $hal = $_POST['hal'];
  $isi = str_replace('\'','`',$_POST['isi']);
  $report_by = $_POST['id_dosen'];

  $link_back = "<a href='javascript:history.go(-2)' class='btn btn-primary btn-sm'>Kembali</a>";

  # ==================================================
  # RE-CHECK IF DOUBLE INSERT
  # ==================================================
  $today = date('Y-m-d');
  $s = "SELECT 1 FROM tb_kesalahan WHERE link='$link' and report_by='$report_by' and date>'$today'";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  if(mysqli_num_rows($q)){
    echo "<div class='alert alert-success'>Anda sudah melaporkannya hari ini. Terimakasih. | $link_back</div>";
    exit;
  }

  $s = "INSERT INTO tb_kesalahan 
  (link,hal,isi,report_by) VALUES 
  ('$link','$hal','$isi','$report_by')";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));

  $link_wa = "https://api.whatsapp.com/send?phone=$no_wa_petugas&text=$isi";
  echo "<div class='alert alert-success'>Laporan Kesalahan tersimpan. Terimakasih. | $link_back</div>";
  echo "<script>window.open('$link_wa')</script>";
  exit;

}

$hal = isset($_GET['hal']) ? $_GET['hal'] : die(erid('hal'));
$fitur = isset($_GET['fitur']) ? $_GET['fitur'] : die(erid('fitur'));
$id_jadwal = isset($_GET['id_jadwal']) ? $_GET['id_jadwal'] : '';
$kelas = isset($_GET['kelas']) ? $_GET['kelas'] : '';

switch ($fitur) {
  case 'manage_kelas': $pair_keys="id_jadwal=$id_jadwal"; break;
  case 'manage_peserta': $pair_keys="kelas=$kelas"; break;
  default: die(erid('fitur::pair_keys'));
}

$link = "?$fitur&$pair_keys";

$date_system = date('Y-m-d H:i:s').' ~ SIAKAD System';
$isi = "Yth. Petugas Akademik%0a%0aDengan ini saya beritahukan bahwa terdapat kesalahan perihal: *$hal*. Mohon segera ditindaklanjuti. Terimakasih.%0a%0aDari: $nama_dosen [$date_system]%0a%0aLink: $server_name/$link";

?>
<div class='wadah gradasi-merah'>Lapor perihal: <span class=red><?=$hal?></span></div>
<div class="wadah gradasi-hijau">
  <div class='tebal mb2'>Isi Pesan:</div>
  <form method="post">
    <input class="debuga" name=link value='<?=$link?>'>
    <input class="debuga" name=hal value='<?=$hal?>'>
    <input class="debuga" name=pair_keys value='<?=$pair_keys?>'>
    <input class="debuga" name=id_dosen value='<?=$id_dosen?>'>
    <textarea name="isi" id="isi" rows="5" class="form-control"><?=$isi?></textarea>
    <button class='btn btn-primary btn-block mt-2' name=btn_laporkan><img src="../assets/img/icons/wa.png" height=30px> Laporkan via WhatsApp</button>
  </form>
</div>
