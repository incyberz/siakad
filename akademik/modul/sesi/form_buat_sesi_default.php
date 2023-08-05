<?php
$today = date('Y-m-d');
$now = date('H:i');
$minggu_awal_perkuliahan_show = date('d M Y',strtotime($minggu_awal_perkuliahan));


$awal_kuliah_date = date('Y-m-d',strtotime($awal_kuliah));
$pukul = date('H:i',strtotime($awal_kuliah));


$form = "
  <hr>
  <form method=post>
    <h4>Buat Sesi Perkuliahan Default</h4>
    <input class=debug name=id_jadwal value='$id_jadwal'>
    <input class=debug name=id_dosen value='$id_dosen'>
    <input class=debug name=jumlah_sesi value='$jumlah_sesi'>
    <input class=debug name=sesi_uts value='$sesi_uts'>
    <input class=debug name=sesi_uas value='$sesi_uas'>
    <input class=debug name=bobot value='$bobot'>
    <div class='mb2'>
      <label for=awal_perkuliahan>Tanggal Sesi Pertama <b class=darkblue>$nama_mk | $kode_mk</b></label>
      <input class='form-control' type=date value='$awal_kuliah_date' required name=awal_perkuliahan id=awal_perkuliahan>
      <div class='miring'>
        <p>Sesi Pertama harus mengacu pada Minggu Awal Perkuliahan ($minggu_awal_perkuliahan_show) | <a href='?manage_semester&id_semester=$id_semester' target=_blank>Lihat Seting Tanggal Semester</a>.</p>
      </div>
    </div>
    <div class='mb2'>
      <label for=pukul_p1 class='proper tebal darkred'>Pukul (Kelas $shift)</label>
      <input class='form-control' type=time value='$pukul' required name=pukul_p1 id=pukul_p1>
      <div class=' miring'>Silahkan tentukan Jam Perkuliahan <span class='tebal darkred'>kelas $shift</span> !</div>
    </div>

    <div class='mb2'>
      <label>Minggu tenang UTS</label>
      <select class='form-control opsi_batch' id=minggu_tenang_uts>
        <option selected>0</option>
        <option>1</option>
        <option>2</option>
      </select>
    </div>
    <div class='mb2'>
      <label>Durasi UTS (minggu)</label>
      <select class='form-control opsi_batch' id=durasi_uts>
        <option>0</option>
        <option selected>1</option>
        <option>2</option>
      </select>
    </div>
    <div class='mb2'>
      <label>Minggu tenang UAS</label>
      <select class='form-control opsi_batch' id=minggu_tenang_uas>
        <option selected>0</option>
        <option>1</option>
        <option>2</option>
      </select>
    </div>
    
    
    <button class='btn btn-primary' name=btn_buat_sesi_default>Buat $d[jumlah_sesi] Sesi Kuliah Default</button>
  </form>

";

$manage_tanggal_semester = div_alert('danger',"Awal Perkuliahan belum di set pada Semester $nomor_semester <hr><a href='?manage_semester&id_semester=$id_semester' class='btn btn-primary ' target=_blank>Manage Tanggal pada Semester $nomor_semester</a>");
?>
<div class='alert alert-info'>
  <div class='alert alert-danger'><b>Belum ada sesi untuk jadwal ini</b></div> 
  <?php echo $minggu_awal_perkuliahan=='' ? $manage_tanggal_semester : $form; ?>
</div>