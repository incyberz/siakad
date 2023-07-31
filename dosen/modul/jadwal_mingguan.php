<?php $semua = isset($_GET['semua']) ? $_GET['semua'] : 0; ?>
<?php $lampau = isset($_GET['lampau']) ? $_GET['lampau'] : 0; ?>
<style>
  .nav_jadwal{
    display:inline-block;
    /* background:linear-gradient(#cfc,#afa); */
    border-radius:10px;
    cursor: pointer;
    padding: 5px 10px;
    margin-right:5px;
    transition:.2s;
    border: solid 1px #ddd;    
  }
  .nav_jadwal:hover{
    letter-spacing: 1px;
    color:blue;
  }
</style>
<!-- =========================================================== -->
<!-- NAVIGATION -->
<!-- =========================================================== -->
<div class='mb2'>
  <?php if($semua){ ?>
    <a class='nav_jadwal gradasi-biru' href="?jadwal_mingguan&semua=1">Mendatang</a>
    <a class='nav_jadwal gradasi-kuning' href="?jadwal_mingguan&semua=1&lampau=1">Lampau</a>
    <a class='nav_jadwal gradasi-hijau' href="?">Sekarang</a>
  <?php }else{ ?>
    <span class='nav_jadwal nav_aksi gradasi-hijau' id='nav__hari_ini'>Hari ini</span>
    <span class='nav_jadwal nav_aksi gradasi-kuning' id='nav__besok'>Besok</span>
    <span class='nav_jadwal nav_aksi' id='nav__minggu_ini'>Minggu ini</span>
    <a class='nav_jadwal' href="?jadwal_mingguan&semua=1" onclick="return confirm('Ingin melihat semua Data Mengajar?')">Semua</a>
  <?php } ?>
</div>

<?php
$judul = "JADWAL DOSEN";

# ===========================================================
# PENCARIAN MINGGU AKTIF
# ===========================================================
$ttoday = strtotime($today);
$w = date('w',$ttoday);
$add_days = $w==0 ? 0 : -$w;
$ahad_skg = date('Y-m-d',strtotime("$add_days day",$ttoday));
$ahad_depan = date('Y-m-d',strtotime("7 day",strtotime($ahad_skg)));

$senin_skg = date('Y-m-d',strtotime("1 day",strtotime($ahad_skg)));
$sabtu_skg = date('Y-m-d',strtotime("6 day",strtotime($ahad_skg)));

$minggu_skg_show = date('d-M-Y',strtotime($senin_skg)).' s.d '.date('d-M-Y',strtotime($sabtu_skg));

$tanggal_besok = date('Y-m-d',strtotime('tomorrow'));
$hari_besok = $nama_hari[date('w',strtotime($tanggal_besok))].', '.date('d-M-Y',strtotime($tanggal_besok));

$and_durasi = $semua ? "AND a.tanggal_sesi >= '$today' " : "AND a.tanggal_sesi >= '$ahad_skg' AND a.tanggal_sesi < '$ahad_depan' ";
$and_durasi = $lampau ? "AND a.tanggal_sesi < '$today' " : $and_durasi;
$order_by = $semua ? 'c.id, a.tanggal_sesi' : 'a.tanggal_sesi';
$order_by = $lampau ? 'c.id, a.tanggal_sesi desc' : $order_by;
# ===========================================================
# SELECT JADWAL PADA MINGGU AKTIF
# ===========================================================
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
(SELECT timestamp_masuk FROM tb_presensi_dosen WHERE id_sesi_kuliah=a.id) as tanggal_presensi, 
(SELECT count(1) FROM tb_presensi WHERE id_sesi_kuliah=a.id) as jumlah_presensi_mhs 


from tb_sesi_kuliah a 
join tb_jadwal b on b.id=a.id_jadwal 
join tb_kurikulum_mk c on c.id=b.id_kurikulum_mk 
join tb_mk d on d.id=c.id_mk 
join tb_dosen e on e.id=a.id_dosen  
where a.id_dosen=$id_dosen 
$and_durasi  
order by $order_by 
";
// echo "<pre>$s</pre>";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));


$jadwal_minggu_ini='';
$jadwal_hari_ini='';
$jadwal_besok='';
$jadwal_lampau='';
$jadwal_next='';
$jumlah_jadwal=0;
while ($d=mysqli_fetch_assoc($q)) {
  $jumlah_jadwal++;
  $err_presensi=0;
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
  $eta_show = $eta_hari<0 ? '<span class="abu">'.(-$eta_hari).' yang lalu</span>' : $eta_show;




  # ========================================================
  # KELAS PESERTA DAN JUMLAH PESERTA MAHASISWA
  # ========================================================
  $jumlah_peserta_mhs=0;

  if($semua){
    $jumlah_kelas_show = '';
    $list_kelas = '';
  }else{
    if($d['jumlah_kelas_peserta']){
      $jumlah_kelas_show = "<span class='tebal'>$d[jumlah_kelas_peserta] kelas</span>";
      $s2 = "SELECT 
      b.kelas,
      (SELECT count(1) from tb_kelas_ta_detail where id_kelas_ta=b.id) as jumlah_mhs  
      from tb_kelas_peserta a 
      JOIN tb_kelas_ta b ON a.id_kelas_ta=b.id  
      where a.id_kurikulum_mk=$d[id_kurikulum_mk]";
      $q2 = mysqli_query($cn,$s2) or die(mysqli_error($cn));
      $list_kelas = '__';
      while ($d2=mysqli_fetch_assoc($q2)) {
        $kelas_show = $d2['jumlah_mhs']==0 ? "<span class=red>$d2[kelas] (0)</span>" : "$d2[kelas] ($d2[jumlah_mhs])";
        $err_presensi=$d2['jumlah_mhs']==0?1:$err_presensi;
        $list_kelas .= ", $kelas_show"; 
        $jumlah_peserta_mhs += $d2['jumlah_mhs'];
      }
      $list_kelas = str_replace('__,','',$list_kelas);

    }else{
      $jumlah_kelas_show = '<span class="miring red">0</span>';
      $list_kelas = '<span class="miring red">Belum ada Peserta kelas</span>';
      $err_presensi=1;
    }
  }
  
  # ========================================================
  # TIPE SESI DAN RUANGANS
  # ========================================================
  if($semua){
    $list_ruang = '';
    $tipe_sesi = '';
  }else{
    if($d['jumlah_ruang']){
      $s2 = "SELECT 
      b.nama as nama_ruang,
      c.nama as tipe_sesi 

      from tb_assign_ruang a 
      join tb_ruang b on b.id=a.id_ruang 
      join tb_mode_sesi c on c.id=a.id_tipe_sesi 
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
      $err_presensi=1;
    }
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
  $btn_presensi_dosen = $eta_hari<=0 ? "<a href='?presensi_dosen&id_sesi_kuliah=$d[id_sesi_kuliah]' class='btn btn-primary btn-block'>Isi Presensi Dosen</a>" : '';
  
  # ========================================================
  # PRESENSI DOSEN DAN MAHASISWA
  # ========================================================
  $jumlah_presensi_mhs = $d['jumlah_presensi_mhs'];

  $info_sudah_presensi = $d['tanggal_presensi']=='' 
  ? div_alert('danger text-center',"Anda Telat Presensi. Segera lapor ke Petugas Akademik!") 
  : div_alert('info text-center',"Anda Sudah Presensi pada $d[tanggal_presensi]");
  
  $blok_presensi = ($d['jumlah_presensi_dosen']==0 and $eta_hari==0) ? $btn_presensi_dosen : $info_sudah_presensi;
  $blok_presensi = $err_presensi ? div_alert('danger','Belum dapat mengisi presensi. Hubungi Petugas Akademik untuk melengkapi administrasi presensi (bertanda merah)') : $blok_presensi;
  $blok_presensi = $eta_hari>0 ? '' : $blok_presensi;
  $blok_presensi_mhs = ($eta_hari>0 || $semua) ? '' : "<div>Presensi Mhs: $jumlah_presensi_mhs of $jumlah_peserta_mhs Mhs</div>";

  $biru = $eta_hari==0 ? 'biru' : 'abu';
  $biru = $eta_hari==1 ? 'darkred' : $biru;

  $blok_jumlah_kelas = $jumlah_kelas_show==''?'':"<div class=mb2>$jumlah_kelas_show | $list_kelas</div>";
  $blok_tipe_sesi = $tipe_sesi==''?'':"<div>$tipe_sesi | $list_ruang</div>";

  $jadwal = "
  <div class='$wadah bg-white'>
    <h4 class='tebal $biru'>$d[nama_mk]</h4>
    <h5 class='miring $biru'>$d[nama_sesi]</h5>
    <div>$pukul_show</div>
    <div>$eta_show</div>
    $blok_tipe_sesi
    $blok_jumlah_kelas
    $blok_presensi
    $blok_presensi_mhs
  </div>
  ";

  $jadwal_minggu_ini.=$jadwal;
  if($eta_hari==0) $jadwal_hari_ini.=$jadwal;
  if($eta_hari==1) $jadwal_besok.=$jadwal;
  if($eta_hari>=0) $jadwal_next.=$jadwal;
  if($eta_hari<0) $jadwal_lampau.=$jadwal;
}

$jadwal_minggu_ini = $jadwal_minggu_ini=='' ? '<div class="alert alert-info">Belum ada jadwal untuk minggu ini.</div>' : $jadwal_minggu_ini;
$jadwal_hari_ini = $jadwal_hari_ini=='' ? '<div class="alert alert-info">Belum ada jadwal untuk hari ini.</div>' : $jadwal_hari_ini;
$jadwal_besok = $jadwal_besok=='' ? '<div class="alert alert-info">Belum ada jadwal untuk besok.</div>' : $jadwal_besok;
$jadwal_next = $jadwal_next=='' ? '<div class="alert alert-info">Belum ada jadwal untuk mendatang.</div>' : $jadwal_next;
$jadwal_lampau = $jadwal_lampau=='' ? '<div class="alert alert-info">Tidak ada jadwal lampau.</div>' : $jadwal_lampau;

$hide_next = $lampau ? 'hideit' : '';
$hide_lampau = $lampau ? '' : 'hideit';

$jadwal_show = $semua ? "
  <div class='jadwal $hide_next' id='jadwal__hari_ini'>
    <div class='wadah gradasi-hijau'>
      <h3>Jadwal Start hari ini</h3>
      <p>$jumlah_jadwal Jadwal</p>
      $jadwal_next
    </div>
  </div>
  
  <div class='jadwal $hide_lampau' id='jadwal__besok'>
    <div class='wadah gradasi-kuning'>
      <h3>Jadwal Lampau</h3>
      <p>$jumlah_jadwal Jadwal</p>
      $jadwal_lampau
    </div>
  </div>
" : "
  <div class='jadwal' id='jadwal__hari_ini'>
    <div class='wadah gradasi-hijau'>
      <h3>Jadwal Hari ini</h3>
      <p>$hari_ini</p>
      $jadwal_hari_ini
    </div>
  </div>
  
  <div class='jadwal hideit' id='jadwal__besok'>
    <div class='wadah gradasi-kuning'>
      <h3>Jadwal Besok</h3>
      <p>$hari_besok</p>
      $jadwal_besok
    </div>
  </div>
  
  <div class='jadwal hideit' id='jadwal__minggu_ini'>
    <div class='wadah '>
      <h3>Jadwal Minggu ini</h3>
      <p>$minggu_skg_show</p>
      $jadwal_minggu_ini
    </div>
  </div>
";

echo $jadwal_show;

?>

<script>
  $(function(){
    $(".nav_aksi").click(function(){
      let tid = $(this).prop('id');
      let rid = tid.split('__');
      let id = rid[1];

      $('.jadwal').hide();
      $('#jadwal__'+id).fadeIn();
    })
  })
</script>