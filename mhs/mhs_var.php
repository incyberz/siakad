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
  SELECT nama FROM tb_prodi WHERE id=a.id_prodi) as nama_prodi,
(
  SELECT jenjang FROM tb_prodi WHERE id=a.id_prodi) as jenjang,
(
  SELECT p.id 
  FROM tb_semester p 
  JOIN tb_kalender q ON p.id_kalender=q.id 
  WHERE p.tanggal_awal<='$today' AND p.tanggal_akhir>'$today' 
  AND q.angkatan=a.angkatan) as id_semester 

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


$id_mhs = $d_mhs['id'];
$nama_mhs = $d_mhs['nama'];
$nama_mhs = ucwords(strtolower($nama_mhs));
$no_wa = $d_mhs['no_wa']!=''?$d_mhs['no_wa']:'';
$no_wa_show = $no_wa==''?$undef:substr($no_wa,0,4).'***'.substr($no_wa,strlen($no_wa)-3,3);
$link_wa = "https://api.whatsapp.com/send?phone=62$no_wa&text=Halo... saya $nama_mhs";
$is_verified_no_wa = $d_mhs['is_verified_no_wa'];

# ========================================================
# STATUS AKADEMIK
# ========================================================
$status_mhs = $d_mhs['status_mhs']==1 ? 'Aktif' : 'Tidak Aktif';
$angkatan = $d_mhs['angkatan']!=''?$d_mhs['angkatan']:$undef;
$nama_prodi = $d_mhs['nama_prodi']!=''?$d_mhs['nama_prodi']:$undef;
$jenjang = $d_mhs['jenjang']!=''?$d_mhs['jenjang']:$undef;

$is_depas = ($d_mhs['password']=='' || $d_mhs['password']==$nim) ? 1 : 0;

# ========================================================
# GET DATA SEMESTER
# ========================================================
$id_semester = $d_mhs['id_semester']!=''?$d_mhs['id_semester']:'';
if($id_semester!=''){
  $s = "SELECT * FROM tb_semester WHERE id=$id_semester";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  $d_semester = mysqli_fetch_assoc($q);
  $semester = $d_semester['nomor']; //nomor semester
}



# ========================================================
# GET DATA KELAS ANGKATAN
# ========================================================
$s = "SELECT b.kelas, b.tahun_ajar  
FROM tb_kelas_angkatan_detail a 
JOIN tb_kelas_angkatan b ON a.id_kelas_angkatan=b.id 
WHERE a.id_mhs=$id_mhs 
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
$kelas_show = $kelas=$undef ? $undef : "$kelas pada TA $tahun_ajar";









# ========================================================
# OLD CODING
# ========================================================
$link_edit = "<a href='?edit' style='color:red;font-weight:bold'>edit</a>";

// $id_kec = 1; //zzz debug
// $nama_kec = "Kec: none | $link_edit";
// $nama_kab = "Kab ?";



//zzz
$alamat_mhs = "none";
$tempat_lahir_mhs = "none";
$tanggal_lahir_mhs = "none";
$status_pernikahan = "none";
$jumlah_anak = "none";
$pendidikan_mhs = "none";
$lulusan_mhs = "none";
$jabatan_mhs = "none";
$divisi_mhs = "none";







$saya_sebagai = "none... | $link_edit";
$about_intro = "none... | $link_edit";
$about_header = "none... | $link_edit";
$about_subheader = "none... | $link_edit";
$about_details = "none... | $link_edit";

// if(1){ //zzz
//   $saya_sebagai = "Informatics Students, Software Engineering, Junior Programmer";
//   $about_intro = "Saya adalah mahasiswa STMIK IKMI Cirebon angkatan 2017. Saya memilih prodi Teknik Informatika.";
//   $about_header = "Junior Web Programmer";
//   $about_subheader = "Programmer pemula dalam pembuatan web memakai React JS, Laravel, dan Javascript.";
//   $about_details = "Saya pilih prodi Teknik Informatika karena ingin jadi Software Engineering.";

//   $youtube_channel = "Ngampus Online";
//   $link_twitter = "https://twitter.com/incyberz";
//   $link_facebook = "https://facebook.com/incyberz";
//   $link_instagam = "https://instagram.com/asdasd";
//   $link_linkedin = "https://linkedin.com/asdasd";
//   $medsos_lainnya = '';


// }







?>