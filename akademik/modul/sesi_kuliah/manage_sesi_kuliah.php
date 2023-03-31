<?php
$judul = '<h1>Manage Sesi Kuliah</h1>';
include 'form_buat_sesi_default_process.php';

$id_jadwal = isset($_GET['id_jadwal']) ? $_GET['id_jadwal'] : '';

if($id_jadwal==''){
  // include 'modul/jadwal_kuliah/list_jadwal.php';
  include 'modul/sesi_kuliah/manage_multiple_sesi.php';
  exit;
}
echo "<span class=debug id=id_jadwal>$id_jadwal</span>";
$s = "SELECT 
concat('JADWAL',c.nama,' / ', h.jenjang,'-', g.nama, ' ', h.angkatan) as jadwal,
b.id as id_kurikulum_mk,
b.id_semester,
b.id_kurikulum,
c.bobot_teori,
c.bobot_praktik,
d.id as id_dosen,
d.nama as dosen_koordinator,  
a.sesi_uts,  
a.sesi_uas,  
a.jumlah_sesi,
a.tanggal_jadwal,   
e.nomor as nomor_semester,   
e.awal_kuliah_uts as awal_perkuliahan,   
e.id_kalender    

FROM tb_jadwal a 
JOIN tb_kurikulum_mk b on b.id=a.id_kurikulum_mk 
JOIN tb_mk c on c.id=b.id_mk 
JOIN tb_dosen d on d.id=a.id_dosen 
JOIN tb_semester e on b.id_semester=e.id 
JOIN tb_kurikulum f on f.id=b.id_kurikulum 
JOIN tb_prodi g on g.id=f.id_prodi 
JOIN tb_kalender h on h.id=f.id_kalender 

WHERE a.id=$id_jadwal";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
if(mysqli_num_rows($q)==0) die('Data Jadwal tidak ditemukan.');
$d = mysqli_fetch_assoc($q);
$id_kurikulum = $d['id_kurikulum'];
$id_kurikulum_mk = $d['id_kurikulum_mk'];
$id_dosen = $d['id_dosen'];
$id_semester = $d['id_semester'];
$id_kalender = $d['id_kalender'];
$nomor_semester = $d['nomor_semester'];
$awal_perkuliahan = $d['awal_perkuliahan'];
$jumlah_sesi = $d['jumlah_sesi'];
$sesi_uts = $d['sesi_uts'];
$sesi_uas = $d['sesi_uas'];
$bobot = $d['bobot_teori']+$d['bobot_praktik'];


$back_to = "Back to: 
<a href='?manage_kalender&id_kalender=$id_kalender' class=proper>manage kalender</a> | 
<a href='?manage_kurikulum&id_kurikulum=$id_kurikulum' class=proper>manage kurikulum</a> | 
<a href='?manage_jadwal&id_kurikulum_mk=$id_kurikulum_mk' class=proper>manage jadwal</a> | 
<a href='?manage_kelas&id_jadwal=$id_jadwal' class=proper>manage kelas peserta</a> | 
<a href='?cek_all_sesi&id_kurikulum=$id_kurikulum' class=proper>cek all sesi kurikulum</a>  
";

$koloms = [];
$i=0;
$tr = '';
foreach ($d as $key => $value) {
  if($key=='nomor_semester' || $key=='awal_perkuliahan') continue;
  $koloms[$i] = str_replace('_',' ',$key);
  $debug = substr($key,0,2)=='id' ? 'debug' : 'upper';
  $tr .= "<tr class=$debug><td>$koloms[$i]</td><td id=$key>$value</td></tr>";
  $i++;
}

echo "<div class=mb2>$back_to</div>$judul<table class=table>$tr</table>";



# ====================================================
# LIST SESI KULIAH
# ====================================================
$s = "SELECT 
a.id as id_sesi_kuliah,
a.pertemuan_ke,
a.nama as nama_sesi,
a.id_dosen, 
a.tanggal_sesi,
a.stop_sesi,
b.nama as nama_dosen,
(SELECT count(1) FROM tb_assign_ruang WHERE id_sesi_kuliah=a.id) as jumlah_ruang 

FROM tb_sesi_kuliah a 
JOIN tb_dosen b on b.id=a.id_dosen 
where a.id_jadwal=$id_jadwal order by a.pertemuan_ke";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
if(mysqli_num_rows($q)==0){
  include 'form_buat_sesi_default.php';
}else{

  $kelas_peserta = '<span class="miring red">--NULL--</span>';
  $s = "SELECT * FROM tb_kelas_peserta a 
  JOIN tb_dosen b on b.id=a.id_dosen 
  WHERE a.id_jadwal=$id_jadwal order by a.pertemuan_ke";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));  

  $thead = "
  <thead>
    <th class='text-left upper'>Pertemuan ke</th>
    <th class='text-left upper'>Nama Sesi</th>
    <th class='text-left upper'>Kelas Peserta</th>
    <th class='text-left upper'>Jam Masuk</th>
    <th class='text-left upper'>Jam Keluar</th>
    <th class='text-left upper'>Ruang</th>
    <th class='text-left upper'>Aksi</th>
  </thead>"; 
  $tr = '';
  while ($d=mysqli_fetch_assoc($q)) {
    // $today = '2023-3-29';// zzz debug
    // $d['tanggal_sesi'] = '2023-3-29';// zzz debug
    $tsesi = strtotime($d['tanggal_sesi']);
    $ttoday = strtotime($today);

    $tanggal_sesi = date('d M Y', $tsesi);
    $jam_masuk = date('H:i', $tsesi);

    $jam_keluar = date('H:i',strtotime($d['stop_sesi']));
    $hari = $nama_hari[date('w',$tsesi)];

    $gradasi = $tsesi<$ttoday ? 'kuning' : '';
    $lampau = $tsesi<$ttoday ? '<span class="kecil miring">(sesi lampau)</span>' : '';
    $gradasi = strtotime(date('Y-m-d',$tsesi))==$ttoday ? 'hijau biru' : $gradasi;
    $gradasi = strtoupper($d['nama_sesi'])=='UTS' ? 'pink' : $gradasi;
    $gradasi = strtoupper($d['nama_sesi'])=='UAS' ? 'pink' : $gradasi;
    
    # ===========================================================
    # PENCARIAN MINGGU AKTIF
    # ===========================================================
    $ttoday = strtotime($today);
    $w = date('w',$ttoday);
    $add_days = $w==0 ? 0 : -$w;
    $ahad_skg = date('Y-m-d',strtotime("$add_days day",$ttoday));
    $ahad_depan = date('Y-m-d',strtotime("7 day",strtotime($ahad_skg)));

    $selisih_detik = strtotime($tanggal_sesi) - $ttoday;
    $selisih_menit = intval($selisih_detik/60);
    $selisih_jam = intval($selisih_menit/60);
    $selisih_hari = intval($selisih_jam/24);

    $x_hari_lagi = $selisih_hari>0 ? "<span class='kecil miring'>($selisih_hari hari lagi)</span>" : '';


    $tr_active = ($tsesi>=strtotime($ahad_skg) and $tsesi<strtotime($ahad_depan)) ? 'tr_active' : '';
    $minggu_aktif = $tr_active=='tr_active' ? '<span class="kecil miring biru">(minggu aktif)</span>':'';
    $sesi_mgg_ini = $tr_active=='tr_active' ? "<span class=red>($selisih_hari hari lagi)</span>":$x_hari_lagi;
    $sesi_hari_ini = strtotime(date('Y-m-d',$tsesi))==$ttoday ? '<span class="miring merah">(sesi hari ini)</span>' : $sesi_mgg_ini;

    $list_ruang = '<span class="red kecil miring">--none--</span>';
    if($d['jumlah_ruang']>0){
      $s2 = "SELECT b.nama as nama_ruang FROM tb_assign_ruang a 
      JOIN tb_ruang b on a.id_ruang=b.id 
      WHERE a.id_sesi_kuliah=$d[id_sesi_kuliah]";
      $q2 = mysqli_query($cn,$s2) or die(mysqli_error($cn));
      $list_ruang = '<ol style="padding-left:15px;">';
      while ($d2=mysqli_fetch_assoc($q2)) {
        $list_ruang.= "<li>$d2[nama_ruang]</li>";
      }
      $list_ruang .= '</ol>';
    }

    $today2 = date('Y-m-d');

    $tr .= "
    <tr class='$tr_active'>
      <td class='upper gradasi-$gradasi'>
        $d[pertemuan_ke] 
        <br>$lampau$sesi_hari_ini 
        <br>$minggu_aktif
      </td>
      <td class='upper gradasi-$gradasi'>
        <a href='?master&p=sesi_kuliah&aksi=update&id=$d[id_sesi_kuliah]' class='tebal' target='_blank'>$d[nama_sesi]</a>
        <br><i>Pengajar</i>: <a href='?master&p=dosen&id=$d[id_dosen]' target=_blank>$d[nama_dosen]</a>
        <br>$bobot SKS x 50 menit
        
      </td>
      <td class='upper gradasi-$gradasi'>
        $kelas_peserta
      </td>
      <td class='upper gradasi-$gradasi'>
        $hari<br>$tanggal_sesi
        <br>$jam_masuk
      </td>
      <td class='upper gradasi-$gradasi'>
        $hari<br>$tanggal_sesi
        <br>$jam_keluar
      </td>
      <td class='upper gradasi-$gradasi'>$list_ruang</td>
      <td class='upper gradasi-$gradasi'>
        <a href='?assign_ruang&id_sesi_kuliah=$d[id_sesi_kuliah]' class='btn btn-info btn-sm'>assign ruang</a>
      </td>
    </tr>"; 
  }

  $hapus_all_sesi = "<div class='wadah gradasi-kuning'>
  <p>Untuk setting ulang tanggal sesi dari P1 s.d P$jumlah_sesi secara terurut per minggu silahkan lakukan <code>Hapus All Sesi</code> lalu Buat Ulang Sesi Default. <span class=red>Perhatian! Proses ini akan mengembalikan Nama-nama Sesi menjadi Default (NEW PXX)</span></p>
  <a href='?hapus_all_sesi&id_jadwal=$id_jadwal' class='btn btn-danger'>Hapus All Sesi</a>
  </div>";

  echo "<table class='table table-striped table-hover'>$thead$tr</table>$hapus_all_sesi$back_to";
}
