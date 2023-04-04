<?php
$judul = "MK SAYA";

$s = "SELECT 
a.id as id_jadwal,
a.tanggal_approve_sesi,
c.nama as nama_mk,
e.jenjang,
e.angkatan,
f.nama as nama_prodi,
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
  $danger_soal_uts = file_exists("../uploads/soal_uts/$d[id_jadwal].docx") ?'success':'danger';
  $danger_soal_uas = file_exists("../uploads/soal_uas/$d[id_jadwal].docx") ?'success':'danger';

  $links = "
  <a href='?set_judul_sesi&id_jadwal=$d[id_jadwal]' class='btn btn-$danger_sesi mb1 btn-sm btn-block'>Set Judul Sesi</a>
  <a href='?upload_rps&id_jadwal=$d[id_jadwal]' class='btn btn-$danger_rps mb1 btn-sm btn-block'>Upload RPS</a>
  <a href='?upload_soal_uts&id_jadwal=$d[id_jadwal]' class='btn btn-$danger_soal_uts mb1 btn-sm btn-block'>Upload Soal UTS</a>
  <a href='?upload_soal_uts&id_jadwal=$d[id_jadwal]' class='btn btn-$danger_soal_uas mb1 btn-sm btn-block'>Upload Soal UAS</a>  ";

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
