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
  a.tanggal_akhir as batas_akhir,
  a.last_update   
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
  $last_update = $d['last_update'];
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
}

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
<!-- CUSTOM ATURAN TANGGAL -->
<!-- ===================================================== -->
<?php if($last_update!='') include 'manage_semester_custom_tanggal.php'; ?>

<!-- ===================================================== -->
<!-- KALENDER -->
<!-- ===================================================== -->
<?php if($last_update!='') include 'manage_semester_kalender.php'; ?>
