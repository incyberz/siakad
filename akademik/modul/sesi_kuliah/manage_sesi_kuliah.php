<?php
$judul = '<h1>Manage Sesi Kuliah</h1>';
include 'form_buat_sesi_default_process.php';
include 'form_hapus_all_sesi_process.php';

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
  if($key=='nomor_semester' 
  || $key=='awal_perkuliahan'
  || $key=='bobot_teori'
  || $key=='bobot_praktik'
  || $key=='sesi_uts'
  || $key=='sesi_uas'
  || $key=='jumlah_sesi'
  || $key=='tanggal_jadwal'
  ) continue;
  $koloms[$i] = str_replace('_',' ',$key);
  $debug = substr($key,0,2)=='id' ? 'debug' : 'upper';
  $tr .= "<tr class=$debug><td>$koloms[$i]</td><td id=$key>$value</td></tr>";
  $i++;
}




# ====================================================
# KELAS PESERTA
# ====================================================
$s2 = "SELECT d.kelas  
FROM tb_kelas_peserta a 
JOIN tb_kurikulum_mk b on b.id=a.id_kurikulum_mk  
JOIN tb_jadwal c on b.id=c.id_kurikulum_mk  
JOIN tb_kelas_ta d on d.id=a.id_kelas_ta   
WHERE c.id=$id_jadwal ";
$q2 = mysqli_query($cn,$s2) or die(mysqli_error($cn));
if(mysqli_num_rows($q2)==0){
  $kelas_peserta = '<span class="miring red">--NULL--</span>';
}else{
  $kelas_peserta = '<ol style="padding-left:15px">';
  while ($d2=mysqli_fetch_assoc($q2)) {
    $kelas_peserta.= "<li>$d2[kelas]</li>";
  }
  $kelas_peserta .= '</ol>';
}


echo "
<div class=mb2>$back_to</div>
$judul
<table class=table>
  $tr
  <tr>
    <td>KELAS PESERTA</td>
    <td>$kelas_peserta</td>
  </tr>
</table>";


# ====================================================
# LIST SESI KULIAH
# ====================================================
$s = "SELECT 
a.id as id_sesi,
a.pertemuan_ke,
a.nama as nama_sesi,
a.id_dosen, 
a.awal_sesi,
b.nama as nama_dosen,
(SELECT count(1) FROM tb_assign_ruang WHERE id_sesi=a.id) as jumlah_ruang, 
(SELECT count(1) FROM tb_presensi_dosen WHERE id_sesi=a.id) as jumlah_presensi_dosen, 
(SELECT count(1) FROM tb_presensi WHERE id_sesi=a.id) as jumlah_presensi_mhs 

FROM tb_sesi a 
JOIN tb_dosen b on b.id=a.id_dosen 
where a.id_jadwal=$id_jadwal order by a.pertemuan_ke";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
if(mysqli_num_rows($q)==0){
  include 'form_buat_sesi_default.php';
}else{


  $thead = "
  <thead>
    <th class='text-left upper'>Pertemuan ke</th>
    <th class='text-left upper'>Nama Sesi</th>
    <th class='text-left upper'>Jam Masuk</th>
    <th class='text-left upper'>Jam Keluar</th>
    <th class='text-left upper'>Presensi</th>
    <th class='text-left upper'>Ruang</th>
    <th class='text-left upper'>Aksi</th>
  </thead>"; 
  $tr = '';
  $total_presensi_dosen =0;
  $total_presensi_mhs =0;
  while ($d=mysqli_fetch_assoc($q)) {
    $tsesi = strtotime($d['awal_sesi']);
    $ttoday = strtotime($today);

    $awal_sesi = date('d M Y', $tsesi);
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

    $selisih_detik = strtotime($awal_sesi) - $ttoday;
    $selisih_menit = intval($selisih_detik/60);
    $selisih_jam = intval($selisih_menit/60);
    $selisih_hari = intval($selisih_jam/24);

    $x_hari_lagi = $selisih_hari>0 ? "<span class='kecil miring'>($selisih_hari hari lagi)</span>" : '';


    $tr_active = ($tsesi>=strtotime($ahad_skg) and $tsesi<strtotime($ahad_depan)) ? 'tr_active' : '';
    $minggu_aktif = $tr_active=='tr_active' ? '<span class="kecil miring biru">(minggu aktif)</span>':'';
    $sesi_mgg_ini = $tr_active=='tr_active' ? "<span class=red>($selisih_hari hari lagi)</span>":$x_hari_lagi;
    $sesi_mingguan = strtotime(date('Y-m-d',$tsesi))==$ttoday ? '<span class="miring merah">(sesi hari ini)</span>' : $sesi_mgg_ini;

    $list_ruang = '<span class="red kecil miring">--none--</span>';
    if($d['jumlah_ruang']>0){
      $s2 = "SELECT b.nama as nama_ruang FROM tb_assign_ruang a 
      JOIN tb_ruang b on a.id_ruang=b.id 
      WHERE a.id_sesi=$d[id_sesi]";
      $q2 = mysqli_query($cn,$s2) or die(mysqli_error($cn));
      $list_ruang = '<ol style="padding-left:15px;">';
      while ($d2=mysqli_fetch_assoc($q2)) {
        $list_ruang.= "<li>$d2[nama_ruang]</li>";
      }
      $list_ruang .= '</ol>';
    }

    $today2 = date('Y-m-d');

    # ========================================================
    # PRESENSI DOSEN DAN MAHASISWA
    # ========================================================
    $presensi_dosen_show = $d['jumlah_presensi_dosen'] ? 'Sudah' : '<span class="red miring">Belum</span>';
    $jumlah_presensi_mhs = $d['jumlah_presensi_mhs'];
    $total_presensi_dosen += $d['jumlah_presensi_dosen'];
    $total_presensi_mhs += $d['jumlah_presensi_mhs'];

    # ========================================================
    # FINAL TR OUTPUT
    # ========================================================
    $tr .= "
    <tr class='$tr_active'>
      <td class='upper gradasi-$gradasi'>
        $d[pertemuan_ke] 
        <br>$lampau$sesi_mingguan 
        <br>$minggu_aktif
      </td>
      <td class='upper gradasi-$gradasi'>
        <a href='?master&p=sesi&aksi=update&id=$d[id_sesi]' class='tebal' target='_blank'>$d[nama_sesi]</a>
        <br><i>Pengajar</i>: <a href='?master&p=dosen&id=$d[id_dosen]' target=_blank>$d[nama_dosen]</a>
        <br>$bobot SKS x 50 menit
        
      </td>
      <td class='upper gradasi-$gradasi'>
        $hari<br>$awal_sesi
        <br>$jam_masuk
      </td>
      <td class='upper gradasi-$gradasi'>
        $hari<br>$awal_sesi
        <br>$jam_keluar
      </td>
      <td class='upper gradasi-$gradasi kecil'>
        Dosen: $presensi_dosen_show
        <br>Mhs: $jumlah_presensi_mhs mhs
      </td>
      <td class='upper gradasi-$gradasi'>$list_ruang</td>
      <td class='upper gradasi-$gradasi'>
        <a href='?assign_ruang&id_sesi=$d[id_sesi]' class='btn btn-info btn-sm'>assign ruang</a>
      </td>
    </tr>"; 
  }

  // $total_presensi_dosen = 1; //debug
  $total_presensi = $total_presensi_dosen+$total_presensi_mhs;

  $hapus_all_sesi = $total_presensi ? "
  <div class=wadah>
    <div class='alert alert-info tebal'>Sudah ada presensi. Anda tidak dapat lagi menghapus sesi kuliah pada MK ini.</div>
    <ul>
      <li>Presensi dosen: $total_presensi_dosen</li>
      <li>Presensi mhs: $total_presensi_mhs</li>
    </ul>
    <p class=miring><code>Untuk menghapus semua sesi Anda hapus menghapus semua presensi terlebih dahulu.</code></p>
  </div>
  " : "
  <form method=post>
    <input type=debug name=id_jadwal value=$id_jadwal>
    <div class='wadah gradasi-kuning'>
      <p>
        <div class='alert alert-info tebal'>Presensi masih kosong. Anda masih dapat menghapus semua sesi.</div>
        Untuk setting ulang tanggal sesi dari P1 s.d P$jumlah_sesi secara terurut per minggu silahkan lakukan <code>Hapus All Sesi</code> lalu Buat Ulang Sesi Default. <span class=red>Perhatian! Proses ini akan mengembalikan Nama-nama Sesi menjadi Default (NEW PXX)</span>
      </p>
      <div class='alert alert-danger'>
        <div class='mb2'>
          <input type=checkbox id=check_hapus_all_sesi> 
          <label for=check_hapus_all_sesi> Saya yakin untuk menghapus semua sesi pada MK ini.</label>
        </div>
        <button href='?hapus_all_sesi&id_jadwal=$id_jadwal' class='btn btn-danger' id=btn_hapus_all_sesi name=btn_hapus_all_sesi disabled>Hapus All Sesi</button>
      </div>
    </div>
  </form>
  ";

  echo "<table class='table table-striped table-hover'>$thead$tr</table>$hapus_all_sesi$back_to";
}

?>
<script>
  $(function(){
    $('#check_hapus_all_sesi').click(function(){
      let checked = $(this).prop('checked');
      $('#btn_hapus_all_sesi').prop('disabled',!checked);
    })
  })
</script>