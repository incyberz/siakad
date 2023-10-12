<style>.blok_filter{
  display: flex; gap:5px
}.show_records{
  font-size:small;
  border:solid 1px #ccc; 
  display:flex; 
  gap:15px; 
  background:white; 
  border-radius:5px; 
  padding:5px 10px; 
  margin-bottom:5px
}.bg_green{ background: #2a2; color:white
}.bg_red{ background: #fcc;
}.sesi-next{ background: #cff;
}.sesi-now{ background: #cfc; border: solid 2px blue;
}.sesi-prev{ background: #ffa;
}.sedang-berlangsung{ background: #8f8; border: solid 4px blue;
}
</style>
<?php
include '../akademik/include/akademik_icons.php';
if(isset($_POST['btn_check_in'])||isset($_POST['btn_check_out'])) include 'presensi_process.php';


# =======================================================
# STATUS PRESENSI
# =======================================================
# 0 = BELUM SAATNYA PRESENSI | NEXT-JADWAL
# 1 = BERSIAPLAH UNTUK PRESENSI | JADWAL HARI INI
# 2 = DOSEN BELUM PRESENSI | JADWAL HARI INI
# 3 = ANDA BELUM PRESENSI | JADWAL HARI INI
# 4 = ANDA SUDAH PRESENSI | JADWAL HARI INI AND PAST
# -1 = ANDA TIDAK PRESENSI | JADWAL HARI INI AND PAST
$arr_status_presensi = array(
  -1 => 'Anda tidak presensi',
  0 => 'Belum saatnya presensi',
  1 => 'Bersiaplah untuk presensi',
  2 => 'Dosen belum presensi',
  3 => 'Silahkan Anda klik presensi',
  4 => 'Anda sudah presensi'
);
$arr_alert_presensi = array(
  -1 => 'Anda tidak presensi untuk sesi ini dan sesi telah berakhir. Poin presensi Anda 0. Mohon tidak diulangi kembali!',
  0 => 'Jadwal sesi ini untuk besok-besok. Harap bersabar ya!',
  1 => 'Hari ini kamu ada jadwal kuliah. Siap-siap presensi ya, jangan telat!',
  2 => 'Sekarang sudah masuk jam sesi, tetapi dosen kamu belum melakukan presensi. Silahkan tanya dosen atau lapor ke Petugas Akademik!',
  3 => 'Dosen sudah presensi, saatnya kamu sekarang yang melakukan presensi. Jangan tunggu sesi berakhir!',
  4 => 'Terimakasih kamu sudah presensi. Poin presensimu sudah dicatat oleh sistem.'
);
$arr_warna_presensi = array(
  -1 => 'red',
  0 => 'gray',
  1 => 'darkblue',
  2 => 'purple',
  3 => 'blue',
  4 => 'white'
);
$arr_latar_presensi = array(
  -1 => '#fcc',
  0 => '#ddd',
  1 => '#ccf',
  2 => '#ffa',
  3 => '#fcc',
  4 => '#3a3'
);

# =======================================================
$kode_presensi = 0;
$tombol_presensi = '';
# =======================================================


# =======================================================
# NOTIF KELAS JIKA KELAS-TA IS NULL
# =======================================================
$notif_kelas = '';
if($kelas_ta==''||$kelas_ta==$unset) $notif_kelas = div_alert('danger',"Perhatian! Anda belum dimasukan kedalam Grup Kelas untuk Tahun Ajar $tahun_ajar, sehingga Ruang Kelas untuk Anda belajar belum ditentukan. Segera Laporkan ke Petugas!");


# =======================================================
# SUB JUDUL INFO
# =======================================================
$sub_judul = "<div class='mt3 pt1' style='border-top: solid 3px #fcf'>Berikut adalah Jadwal Kuliah untuk Semester $semester tahun ajar $tahun_ajar prodi $prodi-$angkatan kelas $shift</div>
";

# =============================================================
# DATE MANAGEMENTS
# =============================================================
include '../include/date_managements.php';
$date_info = "<div class=flexy>
  <div class='mb2'>Hari ini : <b>$hari_ini_show</b></div>
  <div>|</div><div>Minggu ini :   s.d $sabtu_skg_show</div>
</div>";

$date_info = "
<div>
  <div class='mb2'>Hari ini : <b>$hari_ini_show</b></div>
  <div class='row'>
    <div class='col-lg-2 kecil miring'>
      Minggu ini: 
    </div>
    <div class='col-lg-8'>
      <div class='kecil miring' style='display:grid; grid-template-columns:auto 30px auto; border: solid 1px #ccc; border-radius: 7px; padding: 5px;'>
        <div class='kanan'>$senin_skg_show</div> 
        <div class='tengah'>s.d</div> 
        <div>$sabtu_skg_show</div>
      </div>
    </div>
  </div>
</div>
";


# =======================================================
# CEK JADWAL BELUM BERES
# =======================================================
$notif_sesi = '';
$s = "SELECT 1 FROM tb_kurikulum_mk a
JOIN tb_kurikulum b ON a.id_kurikulum=b.id 
JOIN tb_kalender c ON b.id_kalender=c.id 
WHERE b.id_prodi = '$id_prodi '
AND c.angkatan = '$angkatan' 
AND a.id_semester = '$id_semester'
";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
$jumlah_kurikulum_mk = mysqli_num_rows($q);



# =======================================================
# BLOK JADWAL PROCESSING
# =======================================================
$blok_jadwal = div_alert('danger', 'Belum ada MK pada Semester ini. Segera Lapor Petugas!');



$s = "SELECT 
a.id as id_jadwal,
a.awal_kuliah,
a.akhir_kuliah,
c.id as id_kurikulum,
d.singkatan as prodi,
e.angkatan,
(f.bobot_praktik+f.bobot_teori)bobot,
f.nama as nama_mk,
g.id as id_dosen_koordinator,  
g.nama as dosen_koordinator  
FROM tb_jadwal a 
JOIN tb_kurikulum_mk b ON a.id_kurikulum_mk=b.id 
JOIN tb_kurikulum c ON b.id_kurikulum=c.id 
JOIN tb_prodi d ON c.id_prodi=d.id 
JOIN tb_kalender e ON c.id_kalender=e.id 
JOIN tb_mk f ON b.id_mk=f.id 
JOIN tb_dosen g ON a.id_dosen=g.id 
WHERE d.id = '$id_prodi '
AND e.angkatan = '$angkatan '
AND a.shift='$shift' 
ORDER BY a.awal_kuliah 
";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
$jumlah_jadwal = mysqli_num_rows($q);
if($jumlah_jadwal>0){
  $blok_jadwal = '';
  $i=0;
  while ($d=mysqli_fetch_assoc($q)) {
    $i++;
    $id_dosen = $d['id_dosen_pengajar'] ?? $d['id_dosen_koordinator'];


    # ==============================================
    # GET SESI KULIAH DATA IF EXISTS
    # ==============================================
    $id_jadwal = $d['id_jadwal'];

    $s2 = "SELECT 
    a.id as id_sesi,
    a.nama as nama_sesi,
    a.tanggal_sesi,
    a.pertemuan_ke,
    b.id as id_dosen_pengajar,
    b.nama as dosen_pengajar,
    (SELECT count(1) FROM tb_assign_ruang WHERE id_sesi=a.id) jumlah_assign_ruang,   
    (SELECT 1 FROM tb_presensi WHERE id_sesi=a.id and id_mhs='$id_mhs') sudah_presensi,
    (SELECT 1 FROM tb_presensi_dosen WHERE id_sesi=a.id and id_dosen='$id_dosen') dosen_sudah_presensi   
    FROM tb_sesi a 
    JOIN tb_dosen b ON a.id_dosen=b.id 
    WHERE a.id_jadwal='$id_jadwal' 
    AND a.tanggal_sesi >= '$senin_skg' AND a.tanggal_sesi < '$ahad_depan' 
    "; 
    $q2 = mysqli_query($cn,$s2) or die(mysqli_error($cn));
    if(mysqli_num_rows($q2)>1) die(div_alert('danger','Tidak boleh ada 2 jadwal sesi dalam satu minggu.'));

    if(mysqli_num_rows($q2)==1){
      $d2=mysqli_fetch_assoc($q2);
      $id_sesi = $d2['id_sesi'];
      $nama_sesi = $d2['nama_sesi'];
      $tanggal_sesi = $d2['tanggal_sesi'];
      $dosen_pengajar = $d2['dosen_pengajar'];
      $id_dosen_pengajar = $d2['id_dosen_pengajar'];
      $jumlah_assign_ruang = $d2['jumlah_assign_ruang'];
      $pertemuan_ke = $d2['pertemuan_ke'];


      $hari_show = $nama_hari[date('w',strtotime($tanggal_sesi))];
      $tanggal_show = date('d M Y',strtotime($tanggal_sesi));
      $pukul_show = date('H:i',strtotime($tanggal_sesi));

      $durasi = ($d['awal_kuliah']=='' || $d['akhir_kuliah']=='') ? $d['bobot']*45*60 : (strtotime($d['akhir_kuliah'])-strtotime($d['awal_kuliah']))/60;

      $jam_akhir = date('H:i', strtotime($d['awal_kuliah'])+$durasi*60);
      $pukul_show.= " s.d $jam_akhir";

      # =======================================================
      # ETA CALCULATIONS
      # =======================================================
      $eta_menit = intval((strtotime($tanggal_sesi) - strtotime('now'))/60);
      $tanggal_only = date('Y-m-d',strtotime($tanggal_sesi));
      $eta_day = intval((strtotime($tanggal_only) - strtotime('today'))/(60*60*24));
      $eta_day_abs = abs($eta_day);

      if($eta_day<0){
        $sesi_sty = 'sesi-prev';
        $info_hari = "<div class='kecil miring abu'>$eta_day_abs hari yang lalu</div>";
        $info_mulai = '';
        $mulai_sty = '';
      }else{
        if($eta_day == 0){
          $sesi_sty = 'sesi-now';
          $info_hari = "<div class='kecil miring tebal biru'>hari ini</div>";
          if($eta_menit < -$durasi){
            $info_mulai = "<div class='kecil miring darkred'>sudah selesai</div>";
            $mulai_sty = '';
          }else{
            if($eta_menit<=0 and $eta_menit > -$durasi){
              $info_mulai = "<div class='kecil miring tebal biru'>sedang berlangsung</div>";
              $mulai_sty = 'sedang-berlangsung';
            }else{
              $info_mulai = "<div class='kecil miring abu'>belum dimulai</div>";
              $mulai_sty = '';
            }
          }
        }else{
          $info_mulai = '';
          $sesi_sty = 'sesi-next';
          $info_hari = "<div class='kecil miring abu'>$eta_day hari lagi</div>";
        }
      }


      # =======================================================
      # CEK PRESENSI
      # =======================================================
      if($d2['sudah_presensi']){
        $kode_presensi = 4;
        $tombol_presensi = '';
      }else{ //belum presensi dan
        if($eta_day<0){ //udah lewat 
          $kode_presensi = -1;
          $tombol_presensi = '';
        }elseif($eta_day>0){ //besok-besok
          $kode_presensi = 0;
          $tombol_presensi = '';
        }else{ //hari ini ada jadwal
          if($d2['dosen_sudah_presensi']){
            $kode_presensi = 3;
            $tombol_presensi = "<button class='btn btn-primary btn-block btn-sm' >Presensi</button>";
          }else{ //dosen belum presensi
            if($eta_menit>0){
              $kode_presensi = 1; // sesi belum dimulai
              $tombol_presensi = "<button class='btn btn-info btn-block btn-sm' disabled>Presensi dalam $eta_menit menit lagi</button>";
            }else{
              $kode_presensi = 2; //sesi sudah dimulai tapi dosen belum presensi
              $tombol_presensi = "<button class='btn btn-primary btn-block btn-sm' disabled>Menunggu dosen presensi...</button>";
            }

          }

        }
      }

      # =======================================================
      # APPLY KODE PRESENSI
      # =======================================================
      $status_presensi_show = $arr_status_presensi[$kode_presensi];
      $warna_presensi = $arr_warna_presensi[$kode_presensi];
      $latar_presensi = $arr_latar_presensi[$kode_presensi];
      $alert_presensi = $arr_alert_presensi[$kode_presensi];


    }else{
      $id_sesi = '';
      $nama_sesi = $unset;
      $tanggal_sesi = '';
      $dosen_pengajar = '';
      $id_dosen_pengajar = '';
      $jumlah_assign_ruang = 0;
      $pertemuan_ke = '';
    }
   








    # =======================================================
    # GET RUANGAN
    # =======================================================
    if($jumlah_assign_ruang){
      $ruang_show = "$jumlah_assign_ruang ruangan";

      $s2 = "SELECT a.*,
      b.nama as mode_sesi,
      c.nama as nama_ruang  
      FROM tb_assign_ruang a 
      JOIN tb_mode_sesi b ON a.id_tipe_sesi=b.id 
      JOIN tb_ruang c ON a.id_ruang=c.id 
      WHERE a.id_sesi='$id_sesi'";
      $q2 = mysqli_query($cn,$s2) or die(mysqli_error($cn));
      while ($d2=mysqli_fetch_assoc($q2)) {
        $ruang_show.= " | $d2[nama_ruang]";
      }

    }else{
      $ruang_show = "$unset | <span class='kecil red consolas'>belum ditentukan</span>";
    }



    # =======================================================
    # FINAL OUTPUT
    # =======================================================
    $blok_jadwal.= "
    <div class='$sesi_sty $mulai_sty' style='margin: 30px -15px; padding: 15px; border-radius: 7px; border: solid 1px #ddd'>
      <div class='row '>
        <div class='col-lg-2'>
          $i
          $info_hari
          $info_mulai
          <div class='debug kecil' style=background:yellow>
            eta_menit:$eta_menit
            <br>id_sesi:$id_sesi
          </div>
        </div>
        <div class='col-lg-4'>
          <div class='tebal darkblue mt1'>$d[nama_mk]</div>
          <div class='kecil miring abu mb1'>P$pertemuan_ke | $nama_sesi</div>
          Pengajar: 
          <a href='?lihat_dosen&id_dosen=$id_dosen' target=_blank onclick='return confirm(\"Lihat Profil Dosen di TAB baru?\")'>$dosen_pengajar</a> 
          
          <br>$d[bobot] SKS
        </div>
        
        <div class='col-lg-3'>
          <div class='tebal darkblue mt1'>$hari_show, $tanggal_show</div>
          <div class=''>Pukul: $pukul_show</div>
          <div>Ruang: $ruang_show</div>
        </div>
        <div class='col-lg-3'>
          <div class='kecil miring mt1 abu'>Status Presensi:</div>
          <div style='color: $warna_presensi; background: $latar_presensi; padding: 5px; border-radius: 9px; font-size: 12px; text-align:center; margin-bottom: 5px; cursor:pointer;' onclick='alert(\"$alert_presensi\")'>$status_presensi_show</div>
          $tombol_presensi
        </div>
      </div>
    </div>
    ";
  }
}


if($jumlah_kurikulum_mk==$jumlah_jadwal){
  $notif_sesi = '';
}else{
  $jumlah_unjadwal = $jumlah_kurikulum_mk - $jumlah_jadwal;
  $notif_sesi = div_alert('danger',"Perhatian! Masih terdapat $jumlah_unjadwal Mata Kuliah yang belum dijadwalkan oleh Petugas. Segera lapor ke Bagian Akademik!");
}


































?>
<style>.bingkai{border-top: solid 1px #ccc; padding: 5px}.berlangsung{border:solid 5px blue;padding:10px; background: linear-gradient(#cfc,#afa);text-align:center}</style>
<section id="jadwal_kuliah" class="" data-aos="fade-left">
  <div class="container">
    <div class="section-title">
      <h2>Jadwal Kuliah</h2>
      <?=$notif_kelas?>
      <?=$date_info?>
    </div>
    <?=$sub_judul?>
    <?=$blok_jadwal?>
    <?=$notif_sesi?>
  </div>
</section>