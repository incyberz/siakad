<?php
$judul = "PRESENSI DOSEN";
$minlength = 50; //zzz minimal 5 karakter
$menit_start_presensi = 30; //zzz 15 menit sebelum perkuliahan dimulai


if(isset($_POST['btn_submit_presensi'])){
  $s = "UPDATE tb_sesi set materi='$_POST[materi]' WHERE id=$_POST[id_sesi]"; 
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));

  $s = "INSERT INTO tb_presensi_dosen 
  (id_sesi,id_dosen) VALUES 
  ($_POST[id_sesi],$_POST[id_dosen])";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  echo div_alert('success','Presensi Dosen sukses.');
  echo '<script>location.replace("?jadwal_dosen")</script>';
  exit;
}

if(isset($_POST['btn_save_as_draft'])){
  $s = "UPDATE tb_sesi set materi='$_POST[materi]' WHERE id=$_POST[id_sesi]"; 
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  echo div_alert('success',"Draft Saved.<hr><a class='btn btn-primary' href='?jadwal_dosen'>Kembali ke Jadwal</a>");
  exit;
}



$id_sesi = isset($_GET['id_sesi']) ? $_GET['id_sesi'] : die(erid('id_sesi'));
if($id_sesi=='') die(erid('id_sesi::empty'));

# =========================================
# VALIDASI DOUBLE PRESENSI
# =========================================
$s = "SELECT 1 from tb_presensi_dosen where id_sesi=$id_sesi and id_dosen=$id_dosen";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
if(mysqli_num_rows($q)>0){
  echo '<script>location.replace("?jadwal_dosen")</script>';
  exit;
}

$back_to = "<div class='mb-2 mt-2' style='position:sticky;top:29px;z-index:998;padding:5px;border:solid 1px #ccc;background:white;font-size:small'>Back to: 
  <a href='?jadwal_dosen'>Jadwal Dosen</a>
</div>";



$s = "SELECT 
a.id as id_sesi, 
a.nama as nama_sesi, 
a.id_status_sesi, 
a.awal_sesi,
a.materi,
c.id as id_kurikulum_mk,
d.nama as nama_mk,
(d.bobot_teori+d.bobot_praktik) bobot,

(SELECT count(1) from tb_assign_ruang where id_sesi=a.id) as jumlah_ruang,
(SELECT nama from tb_status_sesi where id=a.id) as status_sesi,
(SELECT count(1) FROM tb_presensi_dosen WHERE id_sesi=a.id) as jumlah_presensi_dosen, 
(SELECT count(1) FROM tb_presensi WHERE id_sesi=a.id) as jumlah_presensi_mhs 


from tb_sesi a 
join tb_jadwal b on b.id=a.id_jadwal 
join tb_kurikulum_mk c on c.id=b.id_kurikulum_mk 
join tb_mk d on d.id=c.id_mk 
join tb_dosen e on e.id=a.id_dosen  
where a.id_dosen=$id_dosen 
and a.id=$id_sesi 
order by a.awal_sesi 
limit 20
";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));

$jadwal_hari_ini='';
$d=mysqli_fetch_assoc($q);

$nama_sesi = $d['nama_sesi'];
$materi = $d['materi'];

$status_sesi = ($d['status_sesi']=='' || $d['status_sesi']==0) ? "<div class='miring kecil red'>--Belum-Terlaksana-</div>" : $d['status_sesi'];
$awal_sesi = date('Y-m-d', strtotime($d['awal_sesi']));
$eta = strtotime($d['awal_sesi'])-strtotime('now')-$menit_start_presensi*60; //$menit_start_presensi menit sebelum kuliah dimulai
$eta_date = strtotime($awal_sesi)-strtotime('today');
$eta_hari
 = intval($eta_date/(60*60*24));
$eta_jam = ($eta/(60*60)) % 
24;
$eta_menit = ($eta/(60)) % 60 +1;
$eta_show = "$eta_hari hari
<br>$eta_jam jam
<br>$eta_menit menit
";
$akhir_sesi = date('Y-m-d H:i',strtotime($d['awal_sesi'])+$d['bobot']*45*60);
$sedang_berlangsung = ((strtotime($akhir_sesi)-strtotime('now'))>0 and $eta<0) ? 1 : 0;

$eta_jam_show = $eta_jam==0 ? "$eta_menit menit lagi" : "$eta_jam jam $eta_menit menit lagi";
$eta_jam_show = $sedang_berlangsung ? "<span class='biru tebal'>Sesi Sedang berlangsung</span>" : $eta_jam_show;
$eta_jam_show = ($sedang_berlangsung==0 and $eta<0) ? "<span class='abu miring'>sesi telah berlalu</span>" : $eta_jam_show;
$warna_eta_jam = ($eta>0) ? 'red' : 'abu';

$eta_show = "<span class='$warna_eta_jam tebal'>$eta_jam_show</span>";



# ========================================================
# TIPE SESI DAN RUANGANS
# ========================================================
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
}

# ========================================================
# TANGGAL DAN PUKUL
# ========================================================
$tanggal_sesi_show = $nama_hari[date('w',strtotime($d['awal_sesi']))].', '.date('d-M-Y', strtotime($d['awal_sesi']));
$pukul_show = date('H:i', strtotime($d['awal_sesi'])).' s.d '.date('H:i', strtotime($akhir_sesi));

# ========================================================
# DESAIN-UI JADWAL-HARI-INI DAN BESOK
# ========================================================
$wadah = $sedang_berlangsung ? 'wadah_active' : 'wadah';
$tanggal_besok = date('Y-m-d',strtotime('tomorrow'));
$hari_besok = $nama_hari[date('w',strtotime($tanggal_besok))].', '.date('d-M-Y',strtotime($tanggal_besok));
if($eta_hari==0){
  $jadwal_hari_ini .= "
  <div class='$wadah bg-white'>
    <h4 class='tebal biru'>$d[nama_mk]</h4>
    <h5>$pukul_show</h5>
    <div>$tipe_sesi | $list_ruang</div>
  </div>
  ";
}else{
  die("ETA days invalid.");
}

$disabled = $eta>0 ? 'disabled' : '';
$start_presensi = $eta>0 ? 'Start Presensi dalam: '.$eta_show : $eta_show;
$petunjuk = $eta>0 ? "Silahkan isi isian diatas minimal $minlength karakter sebagai Draft. Anda dapat melakukan submit presensi $menit_start_presensi menit sebelum perkuliahan dimulai." : "Silahkan isi isian diatas minimal $minlength karakter.";


$btn_save_as_draft = $eta>0 ? "<button class='btn btn-primary btn-block' name=btn_save_as_draft>Save as Draft</button>" : '';

echo "
<h3>$judul</h3>
<div class='wadah gradasi-hijau'>
  <p>Hari ini $hari_ini</p>
  $jadwal_hari_ini
  
  <form method=post>
    <input class=debug name=id_sesi value=$id_sesi>
    <input class=debug name=id_dosen value=$id_dosen>
    <div class='form-group'>
      <label for='materi'>Materi apa yang akan diajarkan pada sesi: <span class='tebal biru upper'>$nama_sesi</span>?</label>
      <textarea name='materi' id='materi' minlength=$minlength required class='form-control' rows=5>$materi</textarea>
      <small><i>$petunjuk</i></small>
      <div class=mt-3>$start_presensi</div>
    </div>
    <div class='form-group'>
      $btn_save_as_draft
      <button class='btn btn-primary btn-block' $disabled name=btn_submit_presensi>Submit Presensi</button>
    </div>
  </form>
</div>
";


