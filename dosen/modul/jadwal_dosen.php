<?php

$s = "SELECT 
d.nama as nama_mk,
a.id as id_sesi_kuliah, 
a.nama as nama_sesi, 
a.id_status_sesi, 
a.tanggal_sesi,
a.stop_sesi,
c.id as id_kurikulum_mk,
(SELECT count(1) from tb_assign_ruang where id_sesi_kuliah=a.id) as jumlah_ruang,
(SELECT count(1) from tb_kelas_peserta where id_kurikulum_mk=c.id) as jumlah_kelas_peserta,
(SELECT nama from tb_status_sesi where id=a.id) as status_sesi,
(SELECT count(1) FROM tb_presensi_dosen WHERE id_sesi_kuliah=a.id) as jumlah_presensi_dosen, 
(SELECT count(1) FROM tb_presensi WHERE id_sesi_kuliah=a.id) as jumlah_presensi_mhs 


from tb_sesi_kuliah a 
join tb_jadwal b on b.id=a.id_jadwal 
join tb_kurikulum_mk c on c.id=b.id_kurikulum_mk 
join tb_mk d on d.id=c.id_mk 
join tb_dosen e on e.id=a.id_dosen  
where a.id_dosen=$id_dosen 
order by a.tanggal_sesi 
limit 20
";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
$thead='
  <thead>
    <th>No</th>
    <th>TANGGAL</th>
    <th>MATA KULIAH / SESI</th>
    <th>STATUS</th>
    <th>PRESENSI</th>
  </thead>
';
$tr='';
$jadwal_hari_ini='';
$jadwal_besok='';
$i=0;
while ($d=mysqli_fetch_assoc($q)) {
  $status_sesi = ($d['status_sesi']=='' || $d['status_sesi']==0) ? "<div class='miring kecil red'>--Belum-Terlaksana-</div>" : $d['status_sesi'];
  $tanggal_sesi = date('Y-m-d', strtotime($d['tanggal_sesi']));
  $eta = strtotime($d['tanggal_sesi'])-strtotime('now');
  $eta_day = strtotime($tanggal_sesi)-strtotime('today');
  $eta_hari = intval($eta_day/(60*60*24));
  $eta_jam = ($eta/(60*60)) % 24;
  $eta_menit = ($eta/(60)) % 60 +1;
  $eta_show = "$eta_hari hari
  <br>$eta_jam jam
  <br>$eta_menit menit
  ";

  $sedang_berlangsung = ((strtotime($d['stop_sesi'])-strtotime('now'))>0 and $eta<0) ? 1 : 0;

  $eta_jam_show = $eta_jam==0 ? "$eta_menit menit lagi" : "$eta_jam jam $eta_menit menit lagi";
  $eta_jam_show = $sedang_berlangsung ? "<span class='biru tebal'>Sedang berlangsung</span>" : $eta_jam_show;
  $eta_jam_show = ($sedang_berlangsung==0 and $eta<0) ? "<span class='abu miring'>telah berlalu</span>" : $eta_jam_show;
  $warna_eta_jam = ($eta>0) ? 'red' : 'abu';

  $gradasi = $eta_hari==0 ? 'hijau' : '';
  $eta_show = $eta_hari==0 ? '<span class="biru tebal">hari ini</span> :: '."<span class='$warna_eta_jam tebal'>$eta_jam_show</span>" : "$eta_hari hari lagi";

  # ========================================================
  # KELAS PESERTA
  # ========================================================
  if($d['jumlah_kelas_peserta']){
    $jumlah_kelas_show = "<span class='tebal'>$d[jumlah_kelas_peserta] kelas</span>";
    $s2 = "SELECT kelas from tb_kelas_peserta 
    where id_kurikulum_mk=$d[id_kurikulum_mk]";
    $q2 = mysqli_query($cn,$s2) or die(mysqli_error($cn));
    $list_kelas = '__';
    while ($d2=mysqli_fetch_assoc($q2)) {
      $list_kelas .= ", $d2[kelas]"; 
    }
    $list_kelas = str_replace('__,','',$list_kelas);

  }else{
    $jumlah_kelas_show = '<span class="miring red">0</span>';
    $list_kelas = '<span class="miring red">Belum ada Peserta kelas</span>';
  }
  
  # ========================================================
  # TIPE SESI DAN RUANGANS
  # ========================================================
  if($d['jumlah_ruang']){
    $s2 = "SELECT 
    b.nama as nama_ruang,
    c.nama as tipe_sesi 

    from tb_assign_ruang a 
    join tb_ruang b on b.id=a.id_ruang 
    join tb_tipe_sesi c on c.id=a.id_tipe_sesi 
    where a.id_sesi_kuliah=$d[id_sesi_kuliah]";
    $q2 = mysqli_query($cn,$s2) or die(mysqli_error($cn));
    $list_ruang = '__';
    while ($d2=mysqli_fetch_assoc($q2)) {
      $list_ruang .= ", $d2[nama_ruang]"; 
      $tipe_sesi = "<span class='tebal'>$d2[tipe_sesi]</span>";
    }
    $list_ruang = str_replace('__,','',$list_ruang);

  }else{
    $list_ruang = '<span class="miring red">List ruang belum ditentukan</span>';
    $tipe_sesi = '<span class="miring red">Tipe sesi belum ditentukan</span>';
  }

  # ========================================================
  # TANGGAL DAN PUKUL
  # ========================================================
  $tanggal_sesi_show = $nama_hari[date('w',strtotime($d['tanggal_sesi']))].', '.date('d-M-Y', strtotime($d['tanggal_sesi']));
  $pukul_show = date('H:i', strtotime($d['tanggal_sesi'])).' s.d '.date('H:i', strtotime($d['stop_sesi']));

  # ========================================================
  # DESAIN-UI JADWAL-HARI-INI DAN BESOK
  # ========================================================
  $wadah = $sedang_berlangsung ? 'wadah_active' : 'wadah';
  $tanggal_besok = date('Y-m-d',strtotime('tomorrow'));
  $hari_besok = $nama_hari[date('w',strtotime($tanggal_besok))].', '.date('d-M-Y',strtotime($tanggal_besok));
  if($eta_hari==0 || $eta_hari==1){

    $btn_presensi_dosen = $eta_hari==0 ? "<a href='?presensi_dosen&id_sesi_kuliah=$d[id_sesi_kuliah]' class='btn btn-primary btn-block'>Isi Presensi Dosen</a>" : '';

    $jadwal = "
    <div class='$wadah bg-white'>
      <h4 class='tebal biru'>$d[nama_mk]</h4>
      <h5 class='miring biru'>$d[nama_sesi]</h5>
      <div>$pukul_show</div>
      <div>$eta_show</div>
      <div>$tipe_sesi | $list_ruang</div>
      <div class=mb2>$jumlah_kelas_show | $list_kelas</div>
      $btn_presensi_dosen
    </div>
    ";

    if($eta_hari==0) $jadwal_hari_ini.=$jadwal;
    if($eta_hari==1) $jadwal_besok.=$jadwal;

  }


  # ========================================================
  # PRESENSI DOSEN DAN MAHASISWA
  # ========================================================
  $presensi_dosen_show = $d['jumlah_presensi_dosen'] ? 'Sudah' : '<span class="red miring">Belum</span>';
  $jumlah_presensi_mhs = $d['jumlah_presensi_mhs'];


  $i++;
  $tr .= "
  <tr class='gradasi-$gradasi'>
    <td>
      $i<br>

    </td>
    <td>
      <div>$tanggal_sesi_show</div>
      <div>$pukul_show</div>
      <div class='kecil miring'>
        $eta_show
      </div>
    </td>
    <td>
      $d[nama_mk]
      <div class='miring kecil'>$d[nama_sesi]</div>
    </td>
    <td>$status_sesi</td>
    <td class='kecil'>
      Dosen: $presensi_dosen_show
      <br>Mhs: $jumlah_presensi_mhs mhs
    </td>
  </tr>
  ";
}

$tb = $tr=='' ? "<div class='alert alert-danger'>Belum ada Data Kegiatan Mengajar.</div>" : "<table class=table>$thead$tr</table>";
$jadwal_besok = $jadwal_besok=='' ? '<div class="alert alert-info">Belum ada jadwal untuk besok.</div>' : $jadwal_besok;





?>
<h1>Jadwal Dosen</h1>
<p>Selamat Datang <?=$nama_dosen?></p>
<div class="row">
  <div class="col-lg-6">
    <div class="wadah gradasi-hijau">
      <h3>Jadwal Hari ini</h3>
      <p><?=$hari_ini?></p>
      <?=$jadwal_hari_ini?>
    </div>
  </div>
  
  <div class="col-lg-6">
    <div class="wadah gradasi-">
      <h3>Jadwal Besok</h3>
      <p><?=$hari_besok?></p>
      <?=$jadwal_besok?>
    </div>
  </div>
  
</div>

<hr>
<h4>Seluruh Jadwal</h4>
<?=$tb?>

<?php
echo '<pre>';
var_dump($d_dosen);
echo '</pre>';
