<h1>Manage Sesi Detail</h1>
<p>Silahkan re-check kembali tanggal, jam, dan ruangan tiap sesi!</p>
<?php
$judul = '<h1>Manage Sesi Detail</h1>';
include 'form_buat_sesi_default_process.php';
include 'form_hapus_all_sesi_process.php';

$id_jadwal = $_GET['id_jadwal'] ?? die('<script>alert("ID Jadwal belum terdefinisi. Silahkan Manage Sesi !"); location.replace("?manage_sesi")</script>');

echo "<span class=debug id=id_jadwal>$id_jadwal</span>";
$s = "SELECT 
a.*,
b.id as id_kurikulum_mk,
b.id_semester,
b.id_kurikulum,
c.kode as kode_mk,
c.nama as nama_mk,
(c.bobot_teori+c.bobot_praktik) bobot,
d.id as id_dosen,
d.nama as dosen_koordinator,  
d.nidn,  
e.nomor as nomor_semester,   
e.awal_kuliah_uts as minggu_awal_perkuliahan,
f.id as id_kurikulum,
g.id as id_prodi,
g.singkatan as prodi,
h.angkatan,
h.jenjang,
(SELECT nama FROM tb_ruang WHERE id=a.id_ruang) nama_ruang 

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
$id_prodi = $d['id_prodi'];
$id_dosen = $d['id_dosen'];
$id_semester = $d['id_semester'];
$id_kurikulum = $d['id_kurikulum'];
$nomor_semester = $d['nomor_semester'];
$minggu_awal_perkuliahan = $d['minggu_awal_perkuliahan'];
$jumlah_sesi = $d['jumlah_sesi'];
$sesi_uts = $d['sesi_uts'];
$sesi_uas = $d['sesi_uas'];
$bobot = $d['bobot'];
$angkatan = $d['angkatan'];
$shift = $d['shift'];
$nama_mk = $d['nama_mk'];
$kode_mk = $d['kode_mk'];
$awal_kuliah = $d['awal_kuliah'];

$nidn_show = $d['nidn'] ?? '-';

$tahun_ajar = $angkatan + intval(($nomor_semester-1)/2);

$unset = '<span class="red consolas miring">unset</span>';
// $ruang_show = $d['nama_ruang'] ?? $unset;
$hari_show = $d['awal_kuliah'] ?? $unset;

# ====================================================
# KELAS PESERTA
# ====================================================
$s2 = "SELECT a.kelas, a.id as id_kelas_ta, 
(SELECT count(1) FROM tb_kelas_ta_detail WHERE id_kelas_ta=a.id) jumlah_mhs 
FROM tb_kelas_ta a 
JOIN tb_kelas b ON a.kelas=b.kelas 
WHERE a.tahun_ajar='$tahun_ajar' 
AND b.angkatan='$angkatan'
AND b.id_prodi='$id_prodi'
AND b.shift='$shift'
";
echo "<pre class=debug>$s2</pre>";
$q2 = mysqli_query($cn,$s2) or die(mysqli_error($cn));
if(mysqli_num_rows($q2)==0){
  $kelas_peserta = div_alert('danger',"Belum ada Kelas untuk tahun ajar $tahun_ajar | <a href='?manage_grup_kelas&id_kurikulum=$id_kurikulum' target=_blank onclick='return confirm(\"Menuju Manage Grup Kelas?\")'>Manage Grup Kelas</a>");
}else{
  $div = '';
  $j=0;
  while ($d2=mysqli_fetch_assoc($q2)) {
    $j++;
    $id_kelas_ta = $d2['id_kelas_ta'];
    $jumlah_mhs = $d2['jumlah_mhs'] ? "$d2[jumlah_mhs] Mhs" : '<span class=red>0 Mhs</span>';
    $div.= "<div><a href='?manage_peserta&id_kelas_ta=$id_kelas_ta&id_kurikulum=$id_kurikulum' target=_blank onclick='return confirm(\"Lihat List Peserta pada Kelas ini?\")'>$j. $d2[kelas] ~ $jumlah_mhs</a></div>";
  }
  $kelas_peserta = "<div>$div</div>";
}

echo "
<div class=wadah>
  <h4 class='proper bold darkblue'>Kurikulum $d[jenjang]-$d[prodi]-$angkatan ~ Semester $d[nomor_semester] (TA.$tahun_ajar) ~ Kelas $shift</h4>
  <table class=table>
    <tr>
      <td>MK</td>
      <td>$d[nama_mk] | $d[kode_mk] | $d[bobot] SKS</td>
    </tr>
    <tr>
      <td>Dosen Koordinator</td>
      <td>$d[dosen_koordinator] | NIDN. $nidn_show</td>
    </tr>
    <tr>
      <td>Awal Perkuliahan</td>
      <td>$hari_show</td>
    </tr>
    <tr>
      <td class=proper>Kelas Peserta TA. $tahun_ajar $shift</td>
      <td>$kelas_peserta</td>
    </tr>
  </table>
</div>
";


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
(SELECT count(1) FROM tb_assign_ruang WHERE id_sesi_kuliah=a.id) as jumlah_ruang, 
(SELECT count(1) FROM tb_presensi_dosen WHERE id_sesi_kuliah=a.id) as jumlah_presensi_dosen, 
(SELECT count(1) FROM tb_presensi WHERE id_sesi_kuliah=a.id) as jumlah_presensi_mhs 

FROM tb_sesi_kuliah a 
JOIN tb_dosen b on b.id=a.id_dosen 
where a.id_jadwal=$id_jadwal 
order by a.pertemuan_ke";

$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
if(mysqli_num_rows($q)==0){
  $syarat_jadwal_ok = 1; //zzz here
  if($syarat_jadwal_ok){
    include 'form_buat_sesi_default.php';
  }else{
    die(div_alert('danger','Penjadwalan diatas belum selesai. Silahkan tentukan ruang kelas (untuk dosen), jadwal hari, dan kelas peserta-nya.'));
  }
}else{


  $thead = "
  <thead>
    <th class='text-left upper'>Pertemuan ke</th>
    <th class='text-left upper'>Nama Sesi</th>
    <th class='text-left upper'>Jam Kuliah</th>
    <th class='text-left upper hideit'>Presensi</th>
    <th class='text-left upper'>List Ruang</th>
    <th class='text-left upper'>Aksi</th>
  </thead>"; 
  $tr = '';
  $total_presensi_dosen =0;
  $total_presensi_mhs =0;
  $total_assign_ruang =0;
  while ($d=mysqli_fetch_assoc($q)) {
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
      $total_assign_ruang+=$d['jumlah_ruang'];
      $s2 = "SELECT b.nama as nama_ruang FROM tb_assign_ruang a 
      JOIN tb_ruang b on a.id_ruang=b.id 
      WHERE a.id_sesi_kuliah=$d[id_sesi_kuliah]";
      $q2 = mysqli_query($cn,$s2) or die(mysqli_error($cn));
      $list_ruang = '<ol style="padding-left:15px;">';
      while ($d2=mysqli_fetch_assoc($q2)) {
        $list_ruang.= "<li>$d2[nama_ruang]</li>";
      }
      $list_ruang .= '</ol>';
      $btn_assign_multi_ruang = "<a href='?assign_ruang&id_sesi_kuliah=$d[id_sesi_kuliah]' class='btn btn-danger btn-sm'>drop ruang</a>";
    }else{
      $btn_assign_multi_ruang = "<a href='?assign_ruang&id_sesi_kuliah=$d[id_sesi_kuliah]' class='btn btn-info btn-sm'>assign multi ruang</a>";
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
        <br>$lampau$sesi_hari_ini 
        <br>$minggu_aktif
      </td>
      <td class='upper gradasi-$gradasi'>
        <a href='?master&p=sesi_kuliah&aksi=update&id=$d[id_sesi_kuliah]' class='tebal' target='_blank'>$d[nama_sesi]</a>
        <br><i>Pengajar</i>: <a href='?master&p=dosen&id=$d[id_dosen]' target=_blank>$d[nama_dosen]</a>
        <br>$bobot SKS x 50 menit
        
      </td>
      <td class='upper gradasi-$gradasi'>
        $hari<br>$tanggal_sesi
        <br>$jam_masuk - $jam_keluar
      </td>
      <td class='upper gradasi-$gradasi kecil hideit'>
        Dosen: $presensi_dosen_show
        <br>Mhs: $jumlah_presensi_mhs mhs
      </td>
      <td class='upper gradasi-$gradasi'>$list_ruang</td>
      <td class='upper gradasi-$gradasi'>$btn_assign_multi_ruang</td>
    </tr>"; 
  }

  // $total_presensi_dosen = 1; //debug
  $total_trx = $total_presensi_dosen + $total_presensi_mhs + $total_assign_ruang;

  $reset_assign_ruang = $total_assign_ruang>0 ? "<a href='?reset_assign_ruang&id_jadwal=$id_jadwal' target=_blank onclick='return confirm(\"Menuju laman reset?\")'><span class=red>Reset Assign Ruang</span></a>" : '-';
  $reset_presensi_dosen = $total_presensi_dosen>0 ? "<a href='?reset_presensi_dosen&id_jadwal=$id_jadwal' target=_blank onclick='return confirm(\"Menuju laman reset?\")'><span class=red>Reset Presensi Dosen</span></a>" : '-';
  $reset_presensi_mhs = $total_presensi_mhs>0 ? "<a href='?reset_presensi_mhs&id_jadwal=$id_jadwal' target=_blank onclick='return confirm(\"Menuju laman reset?\")'><span class=red>Reset Presensi Mhs</span></a>" : '-';
  
  $hapus_all_sesi = $total_trx ? "
    <div class='alert alert-info'><span class=darkblue>Sudah ada $total_trx sub-trx pada Jadwal Kuliah ini.</span> <div class='kecil miring darkred'>Saat ini Anda tidak dapat Reset Sesi. Agar dapat Reset Sesi Anda harus menghapus semua sub-trx sebagai berikut:</div>
      <ul>
        <li>Presensi dosen: $total_presensi_dosen | $reset_presensi_dosen</li>
        <li>Presensi mhs: $total_presensi_mhs | $reset_presensi_mhs</li>
        <li>Assign Ruang: $total_assign_ruang | $reset_assign_ruang</li>
      </ul>
    </div>
  " : "
  <form method=post>
    <input class=debug name=id_jadwal value=$id_jadwal>
    <div class='wadah gradasi-kuning'>
      <p>
        <div class='alert alert-info tebal'>Sub-trx masih kosong, sehingga Anda masih dapat menghapus (reset) semua sesi.</div>
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

  echo "<table class='table table-striped table-hover'>$thead$tr</table>$hapus_all_sesi";
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