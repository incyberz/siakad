<?php
include 'khs_tmp.php';

# ==========================================================
# SAAT INI
# ==========================================================
$s = "SELECT 
a.id as id_mhs,
a.nama as nama_mhs,
a.nim,
(
  SELECT 
  CONCAT(b.kelas,';',e.angkatan,';',f.id,';',f.nama,';',f.jenjang) 
  FROM tb_kelas_angkatan b 
  JOIN tb_kelas_angkatan_detail c on c.id_kelas_angkatan=b.id 
  JOIN tb_mhs d on d.id=c.id_mhs 
  JOIN tb_kelas e on b.kelas=e.kelas 
  JOIN tb_prodi f ON e.id_prodi=f.id  
  WHERE c.id_mhs=a.id 
  ORDER BY b.tahun_ajar DESC 
  LIMIT 1  
) as data_kelas,

(
  SELECT CONCAT(e.id,';',e.nama,';',e.id_kalender) FROM tb_kurikulum_mk z 
  JOIN tb_kelas_peserta b on z.id=b.id_kurikulum_mk 
  JOIN tb_kelas_angkatan c on b.id_kelas_angkatan=c.id 
  JOIN tb_kelas_angkatan_detail d on d.id_kelas_angkatan=c.id 
  JOIN tb_kurikulum e on z.id_kurikulum=e.id 
  WHERE d.id_mhs= $id_mhs
  LIMIT 1
) as data_kurikulum

FROM tb_mhs a 
WHERE a.id=$id_mhs 
";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
if(mysqli_num_rows($q)==0) die('Data mahasiswa tidak ditemukan.');
if(mysqli_num_rows($q)>1) die('Data mahasiswa harus unik.');
$d = mysqli_fetch_assoc($q);
echo "<span class=debug>id_mhs: <span id=id_mhs>$d[id_mhs]</span></span>";

$nama_mhs=$d['nama_mhs'];
$nim=$d['nim'];

# ==========================================================
# GET DATA KELAS
# ==========================================================
if($d['data_kelas']!=''){
  $data_kelas = explode(';',$d['data_kelas']);
  $kelas=$data_kelas[0];
  $angkatan=$data_kelas[1];
  $id_prodi=$data_kelas[2];
  $nama_prodi=$data_kelas[3];
  $jenjang=$data_kelas[4];
}else{
  die(div_alert('danger m-4','Maaf, Anda belum dimasukan ke Kelas-Angkatan manapun.'));
}

# ==========================================================
# GET KURIKULUM DATA
# ==========================================================
if($d['data_kurikulum']!=''){
  $data_kurikulum = explode(';',$d['data_kurikulum']);
  $id_kurikulum=$data_kurikulum[0];
  $nama_kurikulum=$data_kurikulum[1];
  $id_kalender=$data_kurikulum[2];

  $manage_kurikulum = "<a href='?manage_kurikulum&id_kurikulum=$id_kurikulum'>Manage Kurikulum</a>";
  $manage_kalender = "<a href='?manage_kalender&id_kalender=$id_kalender' >Manage Kalender</a>";
}else{
  die(div_alert('danger m-4','Maaf, belum ada data kurikulum untuk Anda.'));
}

echo "<span class=debug>ID-Kalender: <span id=id_kalender>$id_kalender</span></span>";

?>
<div class="wadah">
  <ul>
    <li>Nama: <?=$nama_mhs?></li>
    <li>NIM: <?=$nim?></li>
    <li>Kelas: <?=$kelas?></li>
    <!-- <li>Sekarang Tanggal: <?=$today?></li> -->
    <li>Prodi: <?=$jenjang?>-<?=$nama_prodi?> Angkatan <?=$angkatan?></li>
    <li>Tercatat pada Kurikulum: <?=$nama_kurikulum?><span class=debug id=id_kurikulum><?=$id_kurikulum?></span></li>
  </ul>
</div>
<?php





# ==========================================================
# CEK KALENDER FOR MHS
# ==========================================================
$s_kalender = "SELECT 
a.id as id_semester, 
a.nomor, 
a.tanggal_awal, 
a.tanggal_akhir 

FROM tb_semester a 
JOIN tb_kalender b on b.id=a.id_kalender 
WHERE a.id_kalender=$id_kalender 
";

# ==========================================================
# CEK SEMESTER FOR MHS DAN KURIKULUM-MK
# ==========================================================
$s_semester = $s_kalender." AND a.tanggal_awal <= '$today'";
echo "<pre class=debug>$s_semester</pre>";
$q = mysqli_query($cn,$s_semester) or die(mysqli_error($cn));
if(mysqli_num_rows($q)==0) die("<div class='alert alert-danger'>Tidak ada Semester yang cocok pada Kalender Akademik. | $manage_kalender</div>");

$thead = '<thead>
  <th>Semester</th>
  <th>Mata Kuliah, Sesi, dan Presensi</th>
</thead>';
$thead = ''; //zzz
$tr = '';
$no_mk=0;
$total_presensi = 0;
$total_hadir = 0;
$total_sakit = 0;
$total_izin = 0;
$total_alfa = 0;
while ($d=mysqli_fetch_assoc($q)) {
  $id_semester = $d['id_semester'];
  $s2 = "SELECT 
  a.id as id_kurikulum_mk,
  a.id_mk,
  b.nama as nama_mk,
  (SELECT id FROM tb_jadwal WHERE id_kurikulum_mk=a.id) as id_jadwal,
  (
    SELECT d.nama  
    FROM tb_dosen d 
    JOIN tb_jadwal e on d.id=e.id_dosen 
    JOIN tb_kurikulum_mk f on f.id=e.id_kurikulum_mk 
    WHERE e.id_kurikulum_mk=a.id) as nama_dosen 


  FROM tb_kurikulum_mk a 
  JOIN tb_mk b on b.id=a.id_mk 
  WHERE id_semester=$id_semester and id_kurikulum=$id_kurikulum  ";
  $q2 = mysqli_query($cn,$s2) or die(mysqli_error($cn));
  $jumlah_mk = mysqli_num_rows($q2);
  $thead_mk='<thead>
    <th>No</th>
    <th>Mata Kuliah</th>
    <th>Dosen Pengampu</th>
    <th>Nilai Mata Kuliah</th>
  </thead>';
  $tr_mk='';
  while ($d2=mysqli_fetch_assoc($q2)) {
    $no_mk++;
    $id_kurikulum_mk = $d2['id_kurikulum_mk'];
    $manage_jadwal = "<a href='?manage_jadwal&id_kurikulum_mk=$id_kurikulum_mk'>Manage Jadwal</a>";

    # ==========================================================
    # CEK JADWAL FOR THIS KURIKULUM-MK
    # ==========================================================
    $id_jadwal = $d2['id_jadwal'];
    $nama_dosen = $d2['nama_dosen'];
    $manage_sesi = "<a href='?manage_sesi&id_jadwal=$id_jadwal'>Manage Sesi</a>";
    if($id_jadwal=='') die(div_alert('danger',"Terdapat MK Kurikulum yang belum dijadwalkan [<code>$d2[nama_mk]</code>]. | $manage_jadwal"));



    $tr_mk .= "
    <tr>
      <td>$no_mk</td>
      <td>$d2[nama_mk]</td>
      <td>$d2[nama_dosen]</td>
      <td>$tb_nilai</td>
    </tr>";
  }
  $tb_mk = $tr_mk==''?div_alert('danger',"MK pada semester ini belum ada. | $manage_kurikulum"):"<table class=table>$thead_mk$tr_mk</table>";
  
  $tr .= "
  <tr>
    <td><h3 class=' biru'>Semester $d[nomor]</h3>$d[tanggal_awal] s.d $d[tanggal_akhir]</td>
    <td>$tb_mk</td>
  </tr>";
}

$tb = $tr=='' ? div_alert('danger',"Belum ada semester yang dilalui. | $manage_kalender") : "<h3>Semester yang dilalui:</h3><table class=table>$thead$tr</tr></table>";
// echo $tb;

$persen_presensi = $total_presensi==0?0:round($total_hadir/$total_presensi*100,2);






?>


<section id="khs" class="section-bg"  data-aos="fade-left">
  <div class="container">

    <div class="section-title">
      <h2>KHS</h2>
      <p>Berikut adalah Kartu Hasil Studi (KHS) pada Mata Kuliah yang Anda ikuti.</p>
    </div>

    <?=$tb?>

    <div class="wadah">
      <h3>Rekap Presensi per Mahasiswa</h3>
      <table class="table">
        <tr class='gradasi-biru tebal'><td>Total Presensi</td><td><?=$total_presensi?></td></tr>
        <tr class='gradasi-hijau'><td>Total Hadir</td><td><?=$total_hadir?></td></tr>
        <tr class='gradasi-kuning'><td>Total Sakit</td><td><?=$total_sakit?></td></tr>
        <tr class='gradasi-kuning'><td>Total Izin</td><td><?=$total_izin?></td></tr>
        <tr class='gradasi-merah'><td>Total Alfa</td><td><?=$total_alfa?></td></tr>
      </table>
      <div class="wadah bg-white blue text-center">
        <h1 class='tebal'>Persentase Presensi: <?=$persen_presensi?>%</h1>
      </div>
    </div>    


  </div>
</section>