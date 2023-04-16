<?php
$judul = "MK SAYA";

$s = "SELECT 
a.id as id_jadwal,
a.tanggal_approve_sesi,
a.tanggal_approve_soal_uts,
a.tanggal_approve_soal_uas,
a.tanggal_approve_nilai_uts,
a.tanggal_approve_nilai_uas,
c.nama as nama_mk,
e.jenjang,
e.angkatan,
f.nama as nama_prodi,
(SELECT count(1) from tb_kelas_peserta where id_kurikulum_mk=b.id) as jumlah_kelas_peserta,
(SELECT nama from tb_status_jadwal WHERE id=a.id_status_jadwal) as status_jadwal   

FROM tb_jadwal a 
JOIN tb_kurikulum_mk b ON b.id=a.id_kurikulum_mk 
JOIN tb_mk c ON c.id=b.id_mk 
JOIN tb_kurikulum d ON d.id=b.id_kurikulum 
JOIN tb_kalender e ON e.id=d.id_kalender 
JOIN tb_prodi f ON f.id=d.id_prodi 

WHERE a.id_dosen=$id_dosen";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
$thead='
  <thead>
    <th>No</th>
    <th>MATA KULIAH</th>
    <th>KURIKULUM</th>
    <th>KELENGKAPAN</th>
  </thead>
';
$trm='';
$i=0;
while ($d=mysqli_fetch_assoc($q)) {
  # ========================================================
  $status_jadwal = $d['status_jadwal']=='' ? '<span class=miring>Belum dilaksanakan</span>' : $d['status_jadwal'];
  $danger_sesi = $d['tanggal_approve_sesi']==''?'danger':'success';
  $danger_rps = file_exists("../uploads/rps/$d[id_jadwal].pdf") ?'success':'danger';
  $danger_soal_uts = $d['tanggal_approve_soal_uts']!='' ?'success':'danger';
  $danger_soal_uas = $d['tanggal_approve_soal_uas']!='' ?'success':'danger';
  $danger_nilai_uts = $d['tanggal_approve_nilai_uts']!='' ?'success':'danger';
  $danger_nilai_uas = $d['tanggal_approve_nilai_uas']!='' ?'success':'danger';

  $set_judul_sesi_enab = "<a href='?set_judul_sesi&id_jadwal=$d[id_jadwal]' class='btn btn-$danger_sesi mb1 btn-sm btn-block'>Set Judul Sesi</a>";
  $upload_rps_enab = "<a href='?upload_rps&id_jadwal=$d[id_jadwal]' class='btn btn-$danger_rps mb1 btn-sm btn-block'>Upload RPS</a>";
  $input_soal_uts_enab = "<a href='?input_soal&id_jadwal=$d[id_jadwal]&id_tipe_sesi=8' class='btn btn-$danger_soal_uts mb1 btn-sm btn-block'>Input Soal UTS</a>";
  $input_nilai_uts_enab = "<a href='?input_nilai&id_jadwal=$d[id_jadwal]&id_tipe_sesi=8' class='btn btn-$danger_nilai_uts mb1 btn-sm btn-block'>Input Nilai UTS</a>";
  $input_soal_uas_enab = "<a href='?input_soal_uas&id_jadwal=$d[id_jadwal]' class='btn btn-$danger_soal_uas mb1 btn-sm btn-block'>Input Soal UAS</a>";
  $input_nilai_uas_enab = "<a href='?input_soal&id_jadwal=$d[id_jadwal]&id_tipe_sesi=16' class='btn btn-$danger_nilai_uas mb1 btn-sm btn-block'>Input Nilai UAS</a>";

  $set_judul_sesi_dis = "<button class='btn btn-secondary btn-sm mb1 btn-block' onclick='alert(\"Silahkan penuhi dahulu persyaratan sebelumnya!\")'>Set Judul Sesi</button>";
  $upload_rps_dis = "<button class='btn btn-secondary btn-sm mb1 btn-block' onclick='alert(\"Silahkan penuhi dahulu persyaratan sebelumnya!\")'>Upload RPS</button>";
  $input_soal_uts_dis = "<button class='btn btn-secondary btn-sm mb1 btn-block' onclick='alert(\"Silahkan penuhi dahulu persyaratan sebelumnya!\")'>Input Soal UTS</button>";
  $input_nilai_uts_dis = "<button class='btn btn-secondary btn-sm mb1 btn-block' onclick='alert(\"Silahkan penuhi dahulu persyaratan sebelumnya!\")'>Input Nilai UTS</button>";
  $input_soal_uas_dis = "<button class='btn btn-secondary btn-sm mb1 btn-block' onclick='alert(\"Silahkan penuhi dahulu persyaratan sebelumnya!\")'>Input Soal UAS</button>";
  $input_nilai_uas_dis = "<button class='btn btn-secondary btn-sm mb1 btn-block' onclick='alert(\"Silahkan penuhi dahulu persyaratan sebelumnya!\")'>Input Nilai UAS</button>";

  $link_set_judul_sesi = $set_judul_sesi_enab;
  $link_upload_rps = $danger_sesi=='success' ? $upload_rps_enab : $upload_rps_dis;
  $link_input_soal_uts = $danger_rps=='success' ? $input_soal_uts_enab : $input_soal_uts_dis;
  $link_input_nilai_uts = $danger_soal_uts=='success' ? $input_nilai_uts_enab : $input_nilai_uts_dis;
  $link_input_soal_uas = $danger_nilai_uts=='success' ? $input_soal_uas_enab : $input_soal_uas_dis;
  $link_input_nilai_uas = $danger_soal_uas=='success' ? $input_nilai_uas_enab : $input_nilai_uas_dis;


  $links = $d['jumlah_kelas_peserta'] ? "
    $link_set_judul_sesi
    $link_upload_rps
    $link_input_soal_uts
    $link_input_nilai_uts
    $link_input_soal_uas
    $link_input_nilai_uas
  " : '-';

  $kelas_peserta = $d['jumlah_kelas_peserta'] 
  ? "<div class='kecil'><a class='tebal' href='?lihat_kelas_peserta&id_jadwal=$d[id_jadwal]'>$d[jumlah_kelas_peserta] kelas peserta</a></div>" 
  : "
  <div class='red'>
    0 kelas peserta <a class='btn btn-primary m-2' href='?lapor_kesalahan&fitur=manage_kelas&hal=kelas_peserta masih kosong.&id_jadwal=$d[id_jadwal]'>Laporkan</a>
  </div>";

  $i++;
  # ========================================
  # MOBILE VERSION
  # ========================================
  $trm .= "
  <div style='border-top: solid 1px #eee' class='pt-2 mt-2'>
  <div class='row mb-3'>
    <div class='col-lg-1'>$i</div>
    <div class='col-lg-4'>
      <div class='darkblue tebal'>$d[nama_mk]</div>
      <div class='kecil miring'>Status: $status_jadwal</div>
      $kelas_peserta
    </div>
    <div class='col-lg-4'>
      $d[jenjang]-$d[nama_prodi] $d[angkatan]
    </div>
    <div class='col-lg-3 mt-2'>
      $links
    </div>
  </div>
  </div>
  ";

  

}

$tbm = $trm=='' ? '<div class="alert alert-danger">Belum ada Jadwal MK untuk Anda.</div>' : "<div class='mobile'>$trm</div>";



?>
<h3><?=$judul?></h3>
<?=$tbm?>
