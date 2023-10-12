<?php
$judul = "MK SAYA";
$aksi_ok=0;
$div_ok = [];
$div_not_ok = [];
$divs = '';

$s = "SELECT 
a.id as id_jadwal,
a.tanggal_approve_sesi,
a.tanggal_approve_soal_uts,
a.tanggal_approve_soal_uas,
a.tanggal_approve_lengkap_uts,
a.tanggal_approve_lengkap_uas,
a.shift,
a.awal_kuliah,
c.nama as nama_mk,
d.id_prodi,
e.jenjang,
e.angkatan,
f.singkatan as prodi,
(SELECT nomor from tb_semester where id=b.id_semester) as semester

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
$div='';
$i=0;
$eta=null;
while ($d=mysqli_fetch_assoc($q)) {
  $shift=$d['shift'];

  # ========================================================
  # STATUS JADWAL HANDLER DAN AWAL KULIAH
  # ========================================================
  if($d['awal_kuliah']==''){
    $awal_kuliah_show = "<span class=red>Awal Kuliah belum ditentukan</span>";
    $status_jadwal = $unset;
    $kelas_peserta = '';
    $links = '';
    
  }else{ // awal kuliah sudah di set
    $awal_kuliah = $d['awal_kuliah'];
    
    $tawal = strtotime($awal_kuliah);
    $tnow = strtotime('now');
  
    $awal_kuliah_show = $nama_hari[date('w',$tawal)].', '.date('d-M-Y',$tawal);
    $eta = strtotime('now')-strtotime($awal_kuliah);
    $eta_hari = intval($eta/(60*60*24));
    $eta_jam = ($eta/(60*60)) % 24;
    $eta_menit = ($eta/(60)) % 60 +1;
  
    $awal_kuliah_show = "Awal kuliah: $awal_kuliah_show ($eta_hari hari yang lalu)";
    $status_jadwal = $eta ? 'sudah berlangsung' : 'belum berlangsung';
    
    
    # ========================================================
    # GET KELAS PESERTA
    # ========================================================
    $jumlah_peserta_mhs=0;
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
        $list_kelas .= ", $kelas_show"; 
        $jumlah_peserta_mhs += $d2['jumlah_mhs'];
      }
      $list_kelas = str_replace('__,','',$list_kelas);
  
    }else{
      $jumlah_kelas_show = '<span class="miring red">0</span>';
      $list_kelas = '<span class="miring red">Belum ada Peserta kelas</span>';
      $err_presensi=1;
    }
  
    $danger_sesi = $d['tanggal_approve_sesi']==''?'danger':'success';
    $danger_rps = file_exists("../uploads/rps/$d[id_jadwal].pdf") ?'success':'danger';
    $danger_soal_uts = $d['tanggal_approve_soal_uts']!='' ?'success':'danger';
    $danger_soal_uas = $d['tanggal_approve_soal_uas']!='' ?'success':'danger';
    $danger_lengkap_uts = $d['tanggal_approve_lengkap_uts']!='' ?'success':'danger';
    $danger_lengkap_uas = $d['tanggal_approve_lengkap_uas']!='' ?'success':'danger';
    
    $set_judul_sesi_enab = "<a href='?set_judul_sesi&id_jadwal=$d[id_jadwal]' class='btn btn-$danger_sesi mb1 btn-sm btn-block'>Set Judul Sesi</a>";
    $upload_rps_enab = "<a href='?upload_rps&id_jadwal=$d[id_jadwal]' class='btn btn-$danger_rps mb1 btn-sm btn-block'>Upload RPS</a>";
    $input_soal_uts_enab = "<a href='?input_soal&id_jadwal=$d[id_jadwal]&id_tipe_sesi=8' class='btn btn-$danger_soal_uts mb1 btn-sm btn-block'>Input Soal UTS</a>";
    $input_lengkap_uts_enab = "<a href='?input_nilai&id_jadwal=$d[id_jadwal]&id_tipe_sesi=8' class='btn btn-$danger_lengkap_uts mb1 btn-sm btn-block'>Input Nilai UTS</a>";
    $input_soal_uas_enab = "<a href='?input_soal_uas&id_jadwal=$d[id_jadwal]' class='btn btn-$danger_soal_uas mb1 btn-sm btn-block'>Input Soal UAS</a>";
    $input_lengkap_uas_enab = "<a href='?input_soal&id_jadwal=$d[id_jadwal]&id_tipe_sesi=16' class='btn btn-$danger_lengkap_uas mb1 btn-sm btn-block'>Input Nilai UAS</a>";

    $set_judul_sesi_dis = "<button class='btn btn-secondary btn-sm mb1 btn-block' onclick='alert(\"Silahkan penuhi dahulu persyaratan sebelumnya!\")'>Set Judul Sesi</button>";
    $upload_rps_dis = "<button class='btn btn-secondary btn-sm mb1 btn-block' onclick='alert(\"Silahkan penuhi dahulu persyaratan sebelumnya!\")'>Upload RPS</button>";
    $input_soal_uts_dis = "<button class='btn btn-secondary btn-sm mb1 btn-block' onclick='alert(\"Silahkan penuhi dahulu persyaratan sebelumnya!\")'>Input Soal UTS</button>";
    $input_lengkap_uts_dis = "<button class='btn btn-secondary btn-sm mb1 btn-block' onclick='alert(\"Silahkan penuhi dahulu persyaratan sebelumnya!\")'>Input Nilai UTS</button>";
    $input_soal_uas_dis = "<button class='btn btn-secondary btn-sm mb1 btn-block' onclick='alert(\"Silahkan penuhi dahulu persyaratan sebelumnya!\")'>Input Soal UAS</button>";
    $input_lengkap_uas_dis = "<button class='btn btn-secondary btn-sm mb1 btn-block' onclick='alert(\"Silahkan penuhi dahulu persyaratan sebelumnya!\")'>Input Nilai UAS</button>";

    $link_set_judul_sesi = $set_judul_sesi_enab;
    $link_upload_rps = $danger_sesi=='success' ? $upload_rps_enab : $upload_rps_dis;
    $link_input_soal_uts = $danger_rps=='success' ? $input_soal_uts_enab : $input_soal_uts_dis;
    $link_input_lengkap_uts = $danger_soal_uts=='success' ? $input_lengkap_uts_enab : $input_lengkap_uts_dis;
    $link_input_soal_uas = $danger_lengkap_uts=='success' ? $input_soal_uas_enab : $input_soal_uas_dis;
    $link_input_lengkap_uas = $danger_soal_uas=='success' ? $input_lengkap_uas_enab : $input_lengkap_uas_dis;

    $links = "
      $link_set_judul_sesi
      $link_upload_rps
      $link_input_soal_uts
      $link_input_lengkap_uts
      $link_input_soal_uas
      $link_input_lengkap_uas
    ";    
    $kelas_peserta = "<div class='kecil'><a class='tebal' href='?lihat_kelas_peserta&id_jadwal=$d[id_jadwal]'>$jumlah_kelas_ta kelas $shift</a> | $list_kelas</div>"; 
    $aksi_ok=1;

    
    if($jumlah_peserta_mhs==0){ // jika tidak ada peserta mhs
      // $links = '-';
      $kelas_peserta = "
      <div class='red'>
        0 kelas peserta <a class='btn btn-primary m-2' href='?lapor_kesalahan&fitur=manage_kelas&hal=kelas_peserta masih kosong.&id_jadwal=$d[id_jadwal]'>Laporkan</a>
      </div>";    
    } //end// jika tidak ada peserta mhs
  } //end // awal kuliah sudah di set
    




  $i++;
  $mk_sudah_selesai = 0; //zzz

  $eta_sort = $mk_sudah_selesai ? 'z' : $eta;
  # ========================================
  # DIV OUTPUT MOBILE VERSION
  # ========================================
  $div_output = "<span class=debug>$eta_sort</span>
  <div>
    <div class='row mb-3'>
      <div class='col-lg-6'>
        <div class='darkblue tebal'>$d[nama_mk]</div>
        <div class='kecil miring'>Status: $status_jadwal</div>
        <div class='kecil miring'>$awal_kuliah_show</div>
        $kelas_peserta
      </div>
      <div class='col-lg-3 kecil'>
        <div>Kurikulum: $d[jenjang]-$d[prodi]-$d[angkatan]</div>
        <div>Semester: $d[semester]</div>
        <div class=proper>Kelas: $d[shift]</div>
      </div>
      <div class='col-lg-3 mt-2'>
        $links
      </div>
    </div>
  </div>
  ";

  if($aksi_ok){
    $div_ok[$i] = $div_output;
  }else{
    $div_not_ok[$i] = $div_output;
  }

  
  
  
} //end while fetch_assoc

// echo '<pre>';
// var_dump($div_not_ok);
// echo '</pre>';
$i=0;
sort($div_ok); foreach ($div_ok as $div) {$i++;$divs.="<div class='kecil miring pt-2 mt-3' style='border-top: solid 6px #faf'>$i</div>$div";}
sort($div_not_ok); foreach ($div_not_ok as $div) {$i++;$divs.="<div class='kecil miring pt-2 mt-3' style='border-top: solid 6px #faf'>$i</div>$div";}

$divs = $divs=='' ? '<div class="alert alert-danger">Belum ada Jadwal MK untuk Anda.</div>' : "<div class='mobile'>$divs</div>";

echo "<h3>$judul</h3>$divs";