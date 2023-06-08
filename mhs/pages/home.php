<section id="home" class="home">
  <div class="container">

    <div class="section-title">
      <h2>Home</h2>
      <p>Selamat Datang <?=$nama_mhs?>! Anda sedang login sebagai Mahasiswa.</p>
      <hr>
      <span class=debug>id_mhs <span><?=$id_mhs?></span></span>
      <span class=debug>id_last_semester <span><?=$id_last_semester?></span></span>
      <ul>
        <li>Status: <?=$status_mhs?></li>
        <li>Angtakan: <?=$angkatan?></li>
        <li>Prodi: <?=$nama_prodi?></li>
        <li>Semester: <?=$semester?></li>
        <li>Kelas: <?=$kelas_show?></li>
        <li>Whatsapp: <?=$no_wa_show?></li>
      </ul>
    </div>

    <a href='?khs' class="btn btn-success btn-block">Cek Nilai / KHS</a>
  </div>
</section>