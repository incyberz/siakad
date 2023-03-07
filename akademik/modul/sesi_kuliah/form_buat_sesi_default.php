<?php
$today = date('Y-m-d');
$now = date('H:i');
$tb_sesi = "
<div class='alert alert-info'>
  <b>Belum ada sesi untuk jadwal ini</b>. 
  <p>Silahkan perkirakan Tanggal dan Pukul untuk Pertemuan Pertama MK ini. Tanggal dan Pukul yang Anda input akan menjadi acuan terhadap $d[jumlah_sesi] sesi kuliah berikutnya.</p>
  <hr>
  <form method=post>
    <input class=debug name=id_jadwal value='$id_jadwal'>
    <input class=debug name=id_dosen value='$id_dosen'>
    <input class=debug name=jumlah_sesi value='$jumlah_sesi'>
    <input class=debug name=sesi_uts value='$sesi_uts'>
    <input class=debug name=sesi_uas value='$sesi_uas'>
    <div class='mb2'>
      <label for=tanggal_p1>Tanggal Pertemuan Pertama</label>
      <input class='form-control' type=date value='$today' required name=tanggal_p1 id=tanggal_p1>
    </div>
    <div class='mb2'>
      <label for=pukul_p1>Pukul</label>
      <input class='form-control' type=time value='$now' required name=pukul_p1 id=pukul_p1>
    </div>
    <button class='btn btn-primary' name=btn_buat_sesi_default>Buat $d[jumlah_sesi] Sesi Kuliah Default</button>
  </form>
</div>";