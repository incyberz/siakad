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
a.id as id_sesi, 
a.nama as nama_sesi, 
a.id_status_sesi, 
a.tanggal_sesi,
b.id as id_jadwal,
b.shift,
d.id as id_mk,
d.nama as nama_mk,
(d.bobot_teori+d.bobot_praktik) bobot,
f.id_prodi,
g.angkatan,
c.id as id_kurikulum_mk,
(SELECT nomor from tb_semester where id=c.id_semester) as semester,
(SELECT count(1) from tb_assign_ruang where id_sesi=a.id) as jumlah_ruang,


(SELECT nama from tb_status_sesi where id=a.id) as status_sesi,
(SELECT count(1) FROM tb_presensi_dosen WHERE id_sesi=a.id) as jumlah_presensi_dosen, 
(SELECT timestamp_masuk FROM tb_presensi_dosen WHERE id_sesi=a.id) as tanggal_presensi, 
(SELECT count(1) FROM tb_presensi WHERE id_sesi=a.id) as jumlah_presensi_mhs 


from tb_sesi a 
join tb_jadwal b on b.id=a.id_jadwal 
join tb_kurikulum_mk c on c.id=b.id_kurikulum_mk 
join tb_mk d on d.id=c.id_mk 
join tb_dosen e on e.id=a.id_dosen  
join tb_kurikulum f on c.id_kurikulum=f.id
join tb_kalender g on f.id_kalender=g.id
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
  // $status_sesi = ($d['status_sesi']=='' || $d['status_sesi']==0) ? "<div class='miring kecil red'>--Belum-Terlaksana-</div>" : $d['status_sesi'];

  $awal_sesi = $d['tanggal_sesi'];
  $bobot = $d['bobot'];
  $akhir_sesi = date('Y-m-d H:i',strtotime($awal_sesi)+$bobot*45*60);
  $bobot_show = "$bobot SKS";

  $tawal = strtotime($awal_sesi);
  $takhir = strtotime($akhir_sesi);
  $tnow = strtotime('now');

  $tanggal_only = date('Y-m-d', $tawal);
  $jam_only = date('H:i', $tawal);
  $eta = $tawal-strtotime('now');
  $eta_date = strtotime($tanggal_only)-strtotime('today');
  $eta_hari = intval($eta_date/(60*60*24));
  $eta_hari_abs = abs($eta_hari);
  $eta_jam = ($eta/(60*60)) % 24;
  $eta_menit = ($eta/(60)) % 60 +1;
  $eta_show = "$eta_hari hari
    <br>$eta_jam jam
    <br>$eta_menit menit
  ";


  if($tnow<$tawal){
    $sedang_berlangsung = -1; //belum berlangsung
    $info_berlangsung = '<span class=green>belum berlangsung</span>';
  }elseif($tnow>$takhir){
    $sedang_berlangsung = 0; // sudah berakhir
    $info_berlangsung = '<span class=abu>sudah berakhir</span>';
  }else{
    $sedang_berlangsung = 1; // sedang berlangsung
    $info_berlangsung = '<span class="biru tebal">sedang berlangsung</span>';
  }

  if($eta_hari==0){$eta_hari_ini = '<span class="biru tebal">hari ini</span>';}
  elseif($eta_hari<0){$eta_hari_ini = "<span class='abu'>$eta_hari_abs hari yang lalu</span>";}
  elseif($eta_hari>0){$eta_hari_ini = "<span class='green'>$eta_hari hari lagi</span>";}

  $eta_show = "$eta_hari_ini :: $info_berlangsung";





  # ========================================================
  # KELAS PESERTA DAN JUMLAH PESERTA MAHASISWA
  # ========================================================
  $jumlah_peserta_mhs=0;

  if($semua){
    $jumlah_kelas_show = '';
    $list_kelas = '';
  }else{ // hanya jadwal hari ini

    # ========================================================
    # GET KELAS ANGKATAN
    # ========================================================
    $tahun_ajar = $d['angkatan'] + intval(($d['semester']-1)/2);
    $s2 = "SELECT *,
    (SELECT count(1) FROM tb_kelas_ta_detail WHERE id_kelas_ta=a.id) jumlah_mhs 
    FROM tb_kelas_ta a 
    JOIN tb_kelas b ON a.kelas=b.kelas 
    WHERE a.tahun_ajar='$tahun_ajar' 
    AND b.angkatan='$d[angkatan]' 
    AND b.id_prodi='$d[id_prodi]' 
    AND b.shift='$d[shift]' 
    ";

    $q2 = mysqli_query($cn,$s2) or die(mysqli_error($cn));
    $jumlah_kelas_ta = mysqli_num_rows($q2);
    if($jumlah_kelas_ta){
      $jumlah_kelas_show = "<span class='tebal'>$jumlah_kelas_ta kelas $d[shift]</span>";

      $list_kelas = '__';
      while ($d2=mysqli_fetch_assoc($q2)) {
        $kelas_show = $d2['jumlah_mhs']==0 ? "<span class=red>$d2[kelas] (0)</span>" : "$d2[kelas] ($d2[jumlah_mhs] mhs)";
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
      where a.id_sesi=$d[id_sesi]";
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
  $tanggal_sesi_show = $nama_hari[date('w',$tawal)].', '.date('d-M-Y', $tawal);
  $waktu_show = $nama_hari[date('w', $tawal)].', '.date('H:i', $tawal).' s.d '.date('H:i', $takhir);

  # ========================================================
  # DESAIN-UI JADWAL-HARI-INI DAN BESOK
  # ========================================================
  $wadah = $sedang_berlangsung==1 ? 'wadah_active' : 'wadah';
  $btn_presensi_dosen = $eta_hari==0 ? "<a href='?presensi_dosen&id_sesi=$d[id_sesi]' class='btn btn-primary btn-block'>Isi Presensi Dosen</a>" : '';
  
  # ========================================================
  # PRESENSI DOSEN DAN MAHASISWA
  # ========================================================
  $jumlah_presensi_mhs = $d['jumlah_presensi_mhs'];

  $masih_berlangsung = strtotime('now')<$takhir ? 1 : 0;
  $eta_menit_abs = intval(($takhir-strtotime('now'))/60)+1;
  $form_stop_sesi = !$masih_berlangsung ? '' : "
  <form method=post>
    <button name=btn_stop_sesi value='$d[id_sesi]' class='btn btn-danger btn-block' onclick='return confirm(\"Yakin untuk Stop Sesi secara manual?\")'>Stop Sesi Perkuliahan (manual)</button>
    <div class='kecil miring abu'>Stop Sesi dapat secara manual atau otomatis (saat waktu perkuliahan habis, $eta_menit_abs menit lagi). Stop Sesi dipergunakan agar Mhs dapat Check-Out pada Sesi ini.</div>
  </form>
  ";

  $info_sudah_presensi = $d['tanggal_presensi']=='' 
  ? div_alert('danger text-center',"Anda Telat Presensi. Segera lapor ke Petugas Akademik!") 
  : div_alert('info text-center',"Anda Sudah Presensi pada $d[tanggal_presensi]").$form_stop_sesi;

  
  $blok_presensi = ($d['jumlah_presensi_dosen']==0 and $eta_hari==0) ? $btn_presensi_dosen : $info_sudah_presensi;
  $blok_presensi = $err_presensi ? div_alert('danger','Belum dapat mengisi presensi. Hubungi Petugas Akademik untuk melengkapi administrasi presensi (bertanda merah)') : $blok_presensi;
  $blok_presensi = $eta_hari>0 ? '' : $blok_presensi;
  $blok_presensi_mhs = ($eta_hari>0 || $semua) ? '' : "<div>Presensi Check-In Mhs: $jumlah_presensi_mhs of $jumlah_peserta_mhs Mhs</div>";

  $biru = $eta_hari==0 ? 'biru' : 'abu';
  $biru = $eta_hari==1 ? 'darkred' : $biru;

  $blok_jumlah_kelas = $jumlah_kelas_show==''?'':"<div class=mb2>$jumlah_kelas_show | $list_kelas</div>";
  $blok_tipe_sesi = $tipe_sesi==''?'':"<div>$tipe_sesi | $list_ruang</div>";

  $jadwal = "
  <div class='$wadah bg-white'>
    <h4 class='tebal $biru'>$d[nama_mk] <span class=debug>idmk:$d[id_mk]|idj:$d[id_jadwal]|idkmk:$d[id_kurikulum_mk]|smt:$d[semester]</span></h4>
    <h5 class='miring $biru'>$d[nama_sesi] <span class=debug>id:$d[id_sesi]</span></h5>
    <div>$waktu_show | $bobot_show</div>
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
" 
: 
"
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