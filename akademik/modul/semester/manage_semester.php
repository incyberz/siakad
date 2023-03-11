<?php
$id_semester = isset($_GET['id_semester']) ? $_GET['id_semester'] : '';
$link_home = $id_semester=='' ? '' : "<a href='?manage_semester'><i class='icon_house_alt'></i></a>";
echo "<h1>$link_home MANAGE KURIKULUM SEMESTER</h1>
";

include 'manage_semester_settings_apply.php';

if($id_semester==''){
  include 'modul/kurikulum/list_kurikulum_semester.php';
  exit;
}else{
  # ==========================================================
  # IDENTITAS SEMESTER
  # ==========================================================
  $s = "SELECT 
  concat('Kurikulum ',b.jenjang,' Angkatan ',b.angkatan,' Prodi ', d.nama) as kurikulum,
  a.nomor as semester_ke, 
  a.tanggal_awal as batas_awal, 
  a.tanggal_akhir as batas_akhir, 
  a.tanggal_akhir as batas_akhir  
  FROM tb_semester a 
  JOIN tb_kalender b on a.id_kalender=b.id 
  JOIN tb_kurikulum c on a.id_kalender=c.id 
  JOIN tb_prodi d on c.id_prodi=d.id 
  where a.id=$id_semester";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  if(mysqli_num_rows($q)==0) die('<span class=red>Data SEMESTER tidak ditemukan.');
  $d = mysqli_fetch_assoc($q);
  $batas_awal = $d['batas_awal'];
  $batas_akhir = $d['batas_akhir'];
  $semester_ke = $d['semester_ke'];
  $kurikulum = $d['kurikulum'];
  $koloms_smt = [];
  $i=0;
  $tr_smt = '';
  foreach ($d as $key => $value) {
    if($key=='nama_dosen') continue;
    $koloms_smt[$i] = str_replace('_',' ',$key);
    $debug = substr($key,0,2)=='id' ? 'debug' : 'upper';
    // echo substr($key,0,2)."<hr>";
    $tr_smt .= "<tr class=$debug><td>$koloms_smt[$i]</td><td>$value</td></tr>";
    $i++;
  }

  # ==========================================================
  # OUTPUT BLOK SEMESTER
  # ==========================================================
  $blok_smt = "<table class=table>$tr_smt</table>";



  # ==========================================================
  # MANAGE SEMESTER
  # ==========================================================
  $s = "SELECT 
  a.awal_bayar, 
  a.akhir_bayar, 
  a.awal_krs, 
  a.akhir_krs, 
  a.awal_kuliah_uts, 
  a.akhir_kuliah_uts, 

  a.awal_uts, 
  a.akhir_uts, 

  a.awal_kuliah_uas, 
  a.akhir_kuliah_uas, 

  a.awal_uas,
  a.akhir_uas,

  a.last_update  
  FROM tb_semester a  
  where a.id=$id_semester";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  if(mysqli_num_rows($q)==0) die('<span class=red>Data SEMESTER tidak ditemukan.');
  $d = mysqli_fetch_assoc($q);
  $last_update = $d['last_update'];
  
  $awal_bayar = $d['awal_bayar'];
  $akhir_bayar = $d['akhir_bayar'];
  $awal_krs = $d['awal_krs'];
  $akhir_krs = $d['akhir_krs'];

  $awal_kuliah_uts = $d['awal_kuliah_uts'];
  $awal_kuliah_uas = $d['awal_kuliah_uas'];
  $awal_uts = $d['awal_uts'];
  $awal_uas = $d['awal_uas'];

  $akhir_kuliah_uts = $d['akhir_kuliah_uts'];
  $akhir_kuliah_uas = $d['akhir_kuliah_uas'];
  $akhir_uts = $d['akhir_uts'];
  $akhir_uas = $d['akhir_uas'];

  $koloms_smt = [];
  $i=0;
  $tr_smt = '';
  foreach ($d as $key => $value) {
    if($key=='last_update') continue;
    $koloms_smt[$i] = str_replace('_',' ',$key);
    if($key=='awal_bayar' || $key=='akhir_bayar') $gradasi = 'kuning';
    if($key=='awal_krs' || $key=='akhir_krs') $gradasi = 'hijau';
    if($key=='awal_kuliah_uts' || $key=='akhir_kuliah_uts') $gradasi = 'biru';
    if($key=='awal_uts' || $key=='akhir_uts') $gradasi = 'pink';
    if($key=='awal_kuliah_uas' || $key=='akhir_kuliah_uas') $gradasi = 'biru';
    if($key=='awal_uas' || $key=='akhir_uas') $gradasi = 'pink';
    $tr_smt .= "
      <div class='col-lg-6 '>
        <div class='upper mb1'>$koloms_smt[$i]</div>
        <input class='form-control mb3 gradasi-$gradasi' type=date name=$key id=$key value='$value' required>
      </div>
    ";
    $i++;
  }

  # ==========================================================
  # OUTPUT BLOK MANAGE SEMESTER
  # ==========================================================
  $blok_tgl = "<div class='row'>$tr_smt</div>";

}

$w = date('w',strtotime($batas_awal));
$add_days = $w<=1 ? (1-$w) : (8-$w);

$tanggal_senin_pertama = date('Y-m-d',strtotime("+$add_days day",strtotime($batas_awal)));
$batas_awal_show = date('D, d M Y',strtotime($batas_awal));
// die("
// batas_awal: $batas_awal<br>
// w: $w<br>
// add_days: $add_days<br>
// tanggal_senin_pertama: $tanggal_senin_pertama<br>
// batas_awal_show: $batas_awal_show<br>
// ")




?>
<!-- ===================================================== -->
<!-- IDENTITAS SEMESTER -->
<!-- ===================================================== -->
<div class="wadah">
  <h3 class='m0 mb2'>Identitas Semester</h3>
  <?=$blok_smt ?>
</div>



<!-- ===================================================== -->
<!-- SETINGS -->
<!-- ===================================================== -->
<?php include 'manage_semester_settings.php'; ?>

<!-- ===================================================== -->
<!-- ATURAN TANGGAL -->
<!-- ===================================================== -->
<div class="wadah gradasi-hijau">
  <form method=post>
    <input class=debug name='id_semester' value='<?=$id_semester?>'>
    <h3 class='m0 mb2'>Aturan Tanggal pada Semester</h3>
    <?=$blok_tgl ?>
    <button class='btn btn-primary btn-block'>Simpan Aturan Tanggal</button>
  </form>
</div>



<!-- ===================================================== -->
<!-- KALENDER -->
<!-- ===================================================== -->
<?php if($last_update!='') include 'manage_semester_kalender.php'; ?>
