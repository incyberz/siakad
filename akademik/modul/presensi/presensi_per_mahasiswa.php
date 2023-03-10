<h1>Presensi per Mahasiswa</h1>
<style>th{text-align:left}</style>
<?php
$id_mhs = isset($_GET['id_mhs']) ? $_GET['id_mhs'] : '';

if($id_mhs==''){
  include 'modul/mhs/list_mhs.php';
  exit;
}


# ==========================================================
# SAAT INI
# ==========================================================
$s = "SELECT 
a.nama as nama_mhs,
a.nim,
a.kelas,
b.angkatan,
c.id as id_prodi,
c.nama as prodi,
c.jenjang,
d.nama as nama_jenjang,
(SELECT id from tb_kalender where angkatan=b.angkatan and jenjang=c.jenjang) as id_kalender

FROM tb_mhs a 
JOIN tb_kelas b on a.kelas=b.kelas 
JOIN tb_prodi c on c.id=b.id_prodi 
JOIN tb_jenjang d on d.jenjang=c.jenjang  
WHERE a.id=$id_mhs 
";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
if(mysqli_num_rows($q)==0) die('Data mahasiswa tidak ditemukan.');
if(mysqli_num_rows($q)>1) die('Data mahasiswa harus unik.');
$d = mysqli_fetch_assoc($q);
$id_kalender = $d['id_kalender'];
$id_prodi = $d['id_prodi'];

if($id_kalender==''){
  die("<div class='alert alert-danger'>Belum ada kalender akademik untuk angkatan $d[angkatan] jenjang $d[jenjang]<hr><a href='?manage_kalender'>Manage Kalender</a></div>");
}
?>
<div class="wadah">
  <ul>
    <li>Nama: <?=$d['nama_mhs']?></li>
    <li>NIM: <?=$d['nim']?></li>
    <li>Kelas: <h3><?=$d['kelas']?></h3></li>
  </ul>
</div>
<div class="wadah">
  <h3>Posisi Mahasiswa:</h3>
  <div class="wadah">
    <ul>
      <li>Sekarang Tanggal: <?=$today?></li>
      <li>Angkatan: <?=$d['angkatan']?></li>
      <li>Prodi: <?=$d['prodi']?></li>
      <li>Jenjang: <?=$d['nama_jenjang']?></li>
      <li>ID-Kalender: <?=$d['id_kalender']?></li>
    </ul>
  </div>
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
// die($s_kalender);

$q = mysqli_query($cn,$s_kalender) or die(mysqli_error($cn));
if(mysqli_num_rows($q)==0) die(div_alert('danger',"Kalender Akademik untuk mahasiswa ini belum ada. | <a href='?manage_kalender&id=$id_kalender' >Manage Kalender</a>"));

# ==========================================================
# GET KURIKULUM
# ==========================================================
$s = "SELECT id as id_kurikulum,nama as nama_kurikulum from tb_kurikulum where id_kalender = $id_kalender and id_prodi = $id_prodi ";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
if(mysqli_num_rows($q)>1) die(div_alert('danger','Jumlah kurikulum harus unik.'));
if(mysqli_num_rows($q)==0) die(div_alert('danger',"Kurikulum untuk kalender dan prodi ini belum ada. | <a href='?manage_kurikulum'>Manage Kurikulum</a>"));
$d = mysqli_fetch_assoc($q);
$id_kurikulum = $d['id_kurikulum'];
$manage_kurikulum = "<a href='?manage_kurikulum&id=$id_kurikulum'>Manage Kurikulum</a>";
$nama_kurikulum = $d['nama_kurikulum'];
echo "<div class=wadah>Kurikulum: <span id=nama_kurikulum>$nama_kurikulum</span> <span class=debug id=id_kurikulum>$id_kurikulum</span> </div>";

# ==========================================================
# CEK SEMESTER FOR MHS DAN KURIKULUM-MK
# ==========================================================
$manage_kalender = "<a href='?manage_kalender&id=$id_kalender' >Manage Kalender</a>";
$s_semester = $s_kalender." AND a.tanggal_awal <= '$today'";
// echo "<pre>$s_semester</pre>";
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
  (SELECT id from tb_jadwal where id_kurikulum_mk=a.id) as id_jadwal,
  (
    SELECT d.nama  
    from tb_dosen d 
    join tb_jadwal e on d.id=e.id_dosen 
    join tb_kurikulum_mk f on f.id=e.id_kurikulum_mk 
    where e.id_kurikulum_mk=a.id) as nama_dosen 


  from tb_kurikulum_mk a 
  join tb_mk b on b.id=a.id_mk 
  where id_semester=$id_semester and id_kurikulum=$id_kurikulum  ";
  $q2 = mysqli_query($cn,$s2) or die(mysqli_error($cn));
  $jumlah_mk = mysqli_num_rows($q2);
  $thead_mk='<thead>
    <th>No</th>
    <th>Mata Kuliah</th>
    <th>Dosen Pengampu</th>
    <th>Sesi Kuliah dan Presensi</th>
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

    # ==========================================================
    # CEK SEMESTER FOR MHS DAN KURIKULUM-MK
    # ==========================================================
    $s3 = "SELECT 
    a.id as id_sesi_kuliah,
    a.pertemuan_ke,
    a.tanggal_sesi,
    (
      SELECT timestamp_masuk from tb_presensi where id_mhs=$id_mhs and id_sesi_kuliah=a.id) as timestamp_masuk, 
    (
      SELECT status from tb_presensi where id_mhs=$id_mhs and id_sesi_kuliah=a.id) as status_presensi 
    from tb_sesi_kuliah a 
    where a.id_jadwal=$id_jadwal
    order by a.pertemuan_ke";
    $q3 = mysqli_query($cn,$s3) or die(mysqli_error($cn));

    $td_sesi = '';
    $td_tgl = '';
    $td_presensi = '';
    $no_presensi = 0;
    while ($d3=mysqli_fetch_assoc($q3)) {
      $no_presensi++;
      $total_presensi++;
      if($no_presensi!=$d3['pertemuan_ke']) die(div_alert('danger',"Nomor Presensi tidak berurutan (tidak sama dengan Sesi Pertemuan). | $manage_sesi<hr><small><i>no_presensi: $no_presensi !== pertemuan_ke: $d3[pertemuan_ke]</i></small>"));

      $id_sesi_kuliah = $d3['id_sesi_kuliah'];
      $manage_presensi = "<a href='?manage_presensi_per_mhs&id_jadwal=$id_jadwal&id_mhs=$id_mhs'>Manage Presensi per Mhs</a>";

      $tanggal_sesi_show = date('d/m',strtotime($d3['tanggal_sesi']));
      $td_sesi .= "<td>$no_presensi</td>";
      $td_tgl .= "<td class='small'>$tanggal_sesi_show</td>";

      $status_presensi = $d3['status_presensi'];
      switch ($d3['status_presensi']) {
        case 'h': $gradasi_presensi = 'hijau';$total_hadir++;break;
        case 's': $gradasi_presensi = 'kuning';$total_sakit++;break;
        case 'i': $gradasi_presensi = 'kuning';$total_izin++;break;
        default:  $gradasi_presensi = 'merah';$total_alfa++;break;
      }
      $td_presensi .= "<td class='gradasi-$gradasi_presensi upper'>$status_presensi</td>";
    }

    $tb_sesi = $td_sesi=='' ? div_alert('warning',"Belum ada sesi untuk MK ini. | $manage_sesi") 
    : "
    <table class='table-bordered text-center'>
      <tr>$td_sesi</tr>
      <tr>$td_tgl</tr>
      <tr>$td_presensi</tr>
    </table>
    <div class='kecil m1'>Presensi: 0% | $manage_presensi</div>
    ";



    $tr_mk .= "
    <tr>
      <td>$no_mk</td>
      <td>$d2[nama_mk]</td>
      <td>$d2[nama_dosen]</td>
      <td>$tb_sesi</td>
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
echo $tb;








?>
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
    <h1 class='tebal'>Persentase Presensi: <?=round($total_hadir/$total_presensi*100,2)?>%</h1>
  </div>
</div>