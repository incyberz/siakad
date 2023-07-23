<?php
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
a.akhir_uas 

FROM tb_semester a  
WHERE a.id=$id_semester";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
if(mysqli_num_rows($q)==0) die('<span class=red>Data SEMESTER tidak ditemukan.');
$d = mysqli_fetch_assoc($q);
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
  // if($key=='last_update') continue;
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

?>
<div class="wadah gradasi-hijau">
  <form method=post>
    <input class=debug name='id_semester' value='<?=$id_semester?>'>
    <h3 class='m0 mb2'>Custom Aturan Tanggal</h3>
    <?=$blok_tgl ?>
    <button class='btn btn-primary btn-block' name=btn_simpan_aturan_tanggal>Simpan Aturan Tanggal</button>
  </form>
</div>