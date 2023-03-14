<?php
$today = date('Y-m-d');
$now = date('H:i');

$form = "
  <hr>
  <form method=post>
    <input class=debug name=id_jadwal value='$id_jadwal'>
    <input class=debug name=id_dosen value='$id_dosen'>
    <input class=debug name=jumlah_sesi value='$jumlah_sesi'>
    <input class=debug name=sesi_uts value='$sesi_uts'>
    <input class=debug name=sesi_uas value='$sesi_uas'>
    <div class='mb2'>
      <label for=awal_perkuliahan>Awal Perkuliahan</label>
      <input class='form-control' type=date value='$awal_perkuliahan' required name=awal_perkuliahan id=awal_perkuliahan>
      <div class='miring'>
        <p>Awal Perkuliahan mengacu pada <a href='?manage_semester&id_semester=$id_semester' target=_blank>Seting Tanggal Semester</a>.</p>
      </div>
    </div>
    <div class='mb2'>
      <label for=pukul_p1>Pukul</label>
      <input class='form-control' type=time value='08:00' required name=pukul_p1 id=pukul_p1>
      <div class='merah miring'>Silahkan tentukan Jam Perkuliahan!</div>
    </div>
    <button class='btn btn-primary' name=btn_buat_sesi_default>Buat $d[jumlah_sesi] Sesi Kuliah Default</button>
  </form>

";

$manage_tanggal_semester = div_alert('danger',"Awal Perkuliahan belum di set pada Semester $nomor_semester <hr><a href='?manage_semester&id_semester=$id_semester' class='btn btn-primary ' target=_blank>Manage Tanggal pada Semester $nomor_semester</a>");
?>
<div class='alert alert-info'>
  <div class='alert alert-danger'><b>Belum ada sesi untuk jadwal ini</b></div> 
  <?php echo $awal_perkuliahan=='' ? $manage_tanggal_semester : $form; ?>
</div>