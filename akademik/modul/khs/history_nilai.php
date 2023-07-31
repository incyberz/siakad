<h1>History Nilai KHS</h1>
<?php
$id_kurikulum_mk = $_GET['id_kurikulum_mk'] ?? '';
$nim = $_GET['nim'] ?? '';

# =============================================
# GET DATA MK
# =============================================
$disabled_mk = 'disabled';
$s = "SELECT b.kode,b.nama,(b.bobot_teori+b.bobot_praktik) bobot, c.nomor as semester  
FROM tb_kurikulum_mk a 
JOIN tb_mk b ON a.id_mk=b.id 
JOIN tb_semester c ON a.id_semester=c.id 
WHERE a.id='$id_kurikulum_mk'";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
if(mysqli_num_rows($q)==0) die('Data MK tidak ditemukan.');
$d = mysqli_fetch_assoc($q);
$kode_mk = $d['kode'];
$nama_mk = $d['nama'];
$bobot = $d['bobot'];
$semester = $d['semester'];


# =============================================
# GET DATA MHS
# =============================================
$disabled_mhs = 'disabled';
$s = "SELECT * FROM tb_mhs WHERE nim='$nim'";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
if(mysqli_num_rows($q)==0) die('Data Mhs tidak ditemukan.');
$d = mysqli_fetch_assoc($q);
$nama_mhs = $d['nama'];
$gender = $d['gender'];
$kelas_manual = $d['kelas_manual'];


# =============================================
# FORM UPDATE
# =============================================
$nilai_history = '<div class="kecil miring abu">Belum ada history nilai.</div>';

$s = "SELECT * FROM tb_nilai WHERE nim='$nim' AND id_kurikulum_mk=$id_kurikulum_mk";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
if(mysqli_num_rows($q)==0) die(div_alert('danger','Data nilai tidak ditemukan.'));
if(mysqli_num_rows($q)==1){
  $d = mysqli_fetch_assoc($q);
  $id_kurikulum_mk = $d['id_kurikulum_mk'];
  $na = $d['na'];
  $hm = $d['hm'];
  $nilai_sudah_ada = "";
  $konfirmasi_update = "Silahkan ketik kata `UPDATE`:<input name=konfirmasi_update class='form-control' minlength=6 maxlength=6 required >";
  $input_alasan_update = "
  
  ";
  
  $s = "SELECT a.*, b.nama as pengubah  
  FROM tb_nilai_history a 
  JOIN tb_user b ON a.change_by=b.id 
  WHERE a.nim='$nim' AND a.id_kurikulum_mk=$id_kurikulum_mk 
  ORDER BY change_date DESC";
  // $s = "SELECT * FROM tb_nilai WHERE nim='$nim' ";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  if(mysqli_num_rows($q)>0){
    $nilai_history = 'History Nilai :<ol>';
    $i = 0;
    while ($d = mysqli_fetch_assoc($q)) {
      $i++;
      $biru = $i==1 ? 'darkblue' : 'abu';
      $terbaru = $i==1 ? ' ~ (history terbaru)' : '';
      $tgl = date('M d, Y, H:i', strtotime($d['change_date']));
      $nilai_history .= "<li class='miring $biru'>Nilai asal: $d[na] ($d[hm]) ~ menjadi $d[na_baru] ($d[hm_baru]) | by $d[pengubah] at $tgl | Alasan: $d[alasan_update]$terbaru</li>";
    }
    $nilai_history.='</ol>';
  }
} 
?>


<div class="row">
  <div class="col-lg-6">
    <div class="wadah bg-white">
      <?=$nama_mk?> | <?=$kode_mk?> | <?=$bobot?>-SKS | SM-<?=$semester?>
    </div>
  </div>
  <div class="col-lg-6">
    <div class="wadah bg-white">
      <?=$nama_mhs?> | <?=$nim?> | <?=$kelas_manual?>
    </div>
  </div>
</div>

<span class='biru tebal'>Nilai terupdate: <span style='font-size:200%'><?=$na?> (<?=$hm?>)</span> </span>
<div class='wadah kecil miring mb2'>
  <?=$nilai_history?>
</div>