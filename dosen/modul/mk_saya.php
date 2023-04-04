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
$tr='';
$i=0;
while ($d=mysqli_fetch_assoc($q)) {
  # ========================================================
  $status_jadwal = $d['status_jadwal']=='' ? '<span class=miring>Belum dilaksanakan</span>' : $d['status_jadwal'];
  $danger_sesi = $d['tanggal_approve_sesi']==''?'danger':'success';
  $danger_rps = 1 ?'danger':'success';
  $danger_soal_uts = 1 ?'danger':'success';
  $danger_soal_uas = 1 ?'danger':'success';
  $i++;
  $tr .= "
  <tr>
    <td>$i</td>
    <td>
      $d[nama_mk]
      <div class='kecil miring'>Status: $status_jadwal</a>
    </td>
    <td>$d[jenjang]-$d[nama_prodi] $d[angkatan]</td>
    <td width=160px>
      <a href='?set_judul_sesi&id_jadwal=$d[id_jadwal]' class='btn btn-$danger_sesi mb1 btn-sm btn-block'>Set Judul Sesi</a>
      <a href='?upload_rps&id_jadwal=$d[id_jadwal]' class='btn btn-$danger_rps mb1 btn-sm btn-block'>Upload RPS</a>
      <a href='?upload_soal_uts&id_jadwal=$d[id_jadwal]' class='btn btn-$danger_soal_uts mb1 btn-sm btn-block'>Upload Soal UTS</a>
      <a href='?upload_soal_uts&id_jadwal=$d[id_jadwal]' class='btn btn-$danger_soal_uas mb1 btn-sm btn-block'>Upload Soal UAS</a>
    </td>
  </tr>
  ";
}

$tb = $tr=='' ? "<div class='alert alert-danger'>Belum ada Jadwal MK untuk Anda.</div>" : "<table class=table>$thead$tr</table>";



?>
<h3><?=$judul?></h3>
<?=$tb?>
