<?php 
$today=date('Y-m-d');

# ======================================================
# VARIABEL MAHASISWA
# ======================================================
$angkatan=$undef;
$prodi=$undef;
$semester=$undef;
$kelas=$undef;
$status_mhs=$undef;


# ========================================================
# GET DATA MAHASISWA
# ========================================================
$s = "SELECT a.*,
(
  SELECT singkatan FROM tb_prodi WHERE id=a.id_prodi) as prodi,
(
  SELECT nama FROM tb_prodi WHERE id=a.id_prodi) as nama_prodi,
(
  SELECT jenjang FROM tb_prodi WHERE id=a.id_prodi) as jenjang,
(
  SELECT jumlah_semester FROM tb_jenjang j JOIN tb_prodi p ON p.jenjang=j.jenjang WHERE p.id=a.id_prodi) as jumlah_semester 

FROM tb_mhs a 

WHERE a.nim='$nim'";
$sd = $s;
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
if(mysqli_num_rows($q)==0){
  die("mhs_var :: Mahasiswa dengan NIM: $nim tidak ditemukan pada database.");
}
$d_mhs = mysqli_fetch_assoc($q);

$folder_uploads = $d_mhs['folder_uploads'];
if($folder_uploads==''){
  $a = '_'.strtolower($d_mhs['nama']);
  $a = str_replace(' ','',$a);
  $a = str_replace('.','',$a);
  $a = str_replace(',','',$a);
  $a = str_replace('\'','',$a);
  $a = str_replace('`','',$a);
  $a = substr($a,0,6).date('ymdHis');

  $folder_uploads = $a;
  $ss = "UPDATE tb_mhs set folder_uploads='$a' where nim='$nim'";
  $qq = mysqli_query($cn,$ss)or die("Update folder_uploads error. ".mysqli_error($cn));
}

$img_profile = "uploads/$folder_uploads/img_profile_$nim.jpg";
$img_bg = "uploads/$folder_uploads/img_bg_$nim.jpg";

if(!file_exists($img_profile)) $img_profile = "uploads/profile_na.jpg";
if(!file_exists($img_bg)) $img_bg = "uploads/bg_na.jpg";


# ========================================================
# ID + NAMA MHS
# ========================================================
$id_mhs = $d_mhs['id'];
$nama_mhs = $d_mhs['nama'];
$nama_mhs = ucwords(strtolower($nama_mhs));

# ========================================================
# DATA PRODI + KALENDER AKADEMIK
# ========================================================
$status_mhs = $d_mhs['status_mhs'];
$angkatan = $d_mhs['angkatan'];
$id_prodi = $d_mhs['id_prodi'];
$prodi = $d_mhs['prodi'];
$nama_prodi = $d_mhs['nama_prodi'];
$jenjang = $d_mhs['jenjang'];
$jumlah_semester = $d_mhs['jumlah_semester'];
$shift = $d_mhs['shift'];


$id_kalender = '';
$id_kurikulum = '';
if($angkatan!='' and $jenjang!=''){
  $s = "SELECT a.id as id_kalender, 
  (SELECT id FROM tb_kurikulum WHERE id_kalender=a.id AND id_prodi=$id_prodi) as id_kurikulum  
  FROM tb_kalender a WHERE a.angkatan='$angkatan' AND a.jenjang='$jenjang'";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  if(mysqli_num_rows($q)){
    $d=mysqli_fetch_assoc($q);
    $id_kalender = $d['id_kalender'];
    $id_kurikulum = $d['id_kurikulum'];
  }
}

# ========================================================
# WHATSAPP
# ========================================================
$no_wa = $d_mhs['no_wa']!=''?$d_mhs['no_wa']:'';
$no_wa_show = $no_wa==''?$undef:substr($no_wa,0,4).'***'.substr($no_wa,strlen($no_wa)-3,3);
$link_wa = "https://api.whatsapp.com/send?phone=62$no_wa&text=Halo... saya $nama_mhs";
$is_verified_no_wa = $d_mhs['is_verified_no_wa'];

# ========================================================
# STATUS AKADEMIK SHOW
# ========================================================
$status_mhs_show = $status_mhs ? '<span class=blue>Aktif</span>' : '<span class="red bold">Tidak Aktif</span>';
$angkatan_show = $d_mhs['angkatan']!=''?$d_mhs['angkatan']:$undef;
$nama_prodi_show = $d_mhs['nama_prodi']!=''?$d_mhs['nama_prodi']:$undef;
$jenjang_show = $d_mhs['jenjang']!=''?$d_mhs['jenjang']:$undef;

$is_depas = ($d_mhs['password']=='' || $d_mhs['password']==$nim) ? 1 : 0;

# ========================================================
# GET DATA SEMESTER
# ========================================================
$semester = $d_mhs['semester_manual'];



# ========================================================
# GET DATA KELAS ANGKATAN
# ========================================================
$s = "SELECT b.kelas, b.tahun_ajar  
FROM tb_kelas_ta_detail a 
JOIN tb_kelas_ta b ON a.id_kelas_ta=b.id 
WHERE a.nim=$nim 
ORDER BY b.tahun_ajar DESC 
LIMIT 1 
";
// echo $s;
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
if(mysqli_num_rows($q)>0){
  $d_kelas = mysqli_fetch_assoc($q);
  $kelas = $d_kelas['kelas'];
  $tahun_ajar = $d_kelas['tahun_ajar'];
}
$kelas_show = $kelas==$undef ? $undef : "$kelas pada TA $tahun_ajar";