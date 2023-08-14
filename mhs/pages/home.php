<?php
$pesan_keaktifan = $status_mhs ? '' : div_alert('danger','Maaf, status Anda saat ini tidak aktif. Silahkan hubungi Petugas Akademik jika ada kesalahan.');
$li_status = "
  <li>Status: $status_mhs_show</li>
  <li>Angkatan: $angkatan</li>
  <li>Prodi: $nama_prodi</li>
  <li>Semester: $semester (TA. $tahun_ajar)</li>
  <li>Kelas-TA.$tahun_ajar: $kelas_ta</li>
  <li>Jadwal Kelas: <span class=proper>$shift</span></li>
  <li>Whatsapp: $no_wa_show</li>
";
?>
<section id="home" class="home">
  <div class="container">

    <div class="section-title">
      <h2>Home</h2>
      <p>Selamat Datang <?=$nama_mhs?>! Berikut adalah Data Kemahasiswaan Anda:</p>
      <?=$pesan_keaktifan?>
      <hr>
      <ul><?=$li_status?></ul>
    </div>

    <a href='?khs' class="btn btn-success btn-block">Cek Nilai / KHS</a>
  </div>
</section>