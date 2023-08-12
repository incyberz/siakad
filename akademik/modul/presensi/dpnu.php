<style>.btn_active{border:solid 3px blue}</style>
<?php
$id_jadwal = isset($_GET['id_jadwal']) ? $_GET['id_jadwal'] : '';
$id_sesi = isset($_GET['id_sesi']) ? $_GET['id_sesi'] : '';
$judul = $id_sesi=='' ? 'Daftar Presensi dan Nilai Ujian (DPNU)' : 'Daftar Hadir Sesi Kuliah';
$judul = "<h1 class='m0 mb2'>$judul</h1>";

if($id_jadwal==''){
  die(div_alert('info',"Silahkan tentukan dahulu Mata Kuliahnya di Menu | $manage_jadwal <hr>Perhatian! Hanya MK yang sudah dijadwalkan yang mempunyai Aksi Manage DPNU."));
}

# ===============================================
# DATA KURIKULUM MK
# ===============================================
$s = "SELECT 
concat(c.nama,' / ',c.kode) as mata_kuliah,
d.nama as dosen_koordinator, 
b.id as id_kurikulum_mk, 
a.id as id_jadwal  
FROM tb_jadwal a 
JOIN tb_kurikulum_mk b on b.id=a.id_kurikulum_mk 
JOIN tb_mk c on c.id=b.id_mk 
JOIN tb_dosen d on d.id=a.id_dosen 

WHERE a.id=$id_jadwal";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
$d = mysqli_fetch_assoc($q);
$id_kurikulum_mk = $d['id_kurikulum_mk'];
$koloms = [];
$tr = '';
foreach ($d as $key => $value) {
  $koloms[$i] = str_replace('_',' ',$key);
  $debug = (substr($key,0,2)=='id') ? 'debug' : 'upper';
  $tr .= "<tr class=$debug><td>$koloms[$i]</td><td>$value</td></tr>";
  $i++;
}
$back_to = "<div class=mb2>Back to : 
<a href='?manage_jadwal&id_kurikulum_mk=$id_kurikulum_mk'>Manage Jadwal</a> | 
<a href='?manage_kelas&id_jadwal=$id_jadwal'>Manage Kelas</a> | 
<a href='?manage_sesi&id_jadwal=$id_jadwal'>Manage Sesi</a> | 
<a href='?manage_mhs'>Presensi per Mahasiswa</a> 
</div>
";
// union tabel, <tr> lanjut ke tabel berikutnya 


# ===============================================
# DATA SESI KULIAH
# ===============================================
if($id_sesi>0){
  $s = "SELECT 
  concat(a.pertemuan_ke, ' / ',a.nama) as pertemuan_ke,
  a.tanggal_sesi 

  FROM tb_sesi a 


  WHERE a.id=$id_sesi";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  $d = mysqli_fetch_assoc($q);
  $koloms = [];
  foreach ($d as $key => $value) {
    $koloms[$i] = str_replace('_',' ',$key);
    $debug = (substr($key,0,2)=='id') ? 'debug' : 'upper';
    $tr .= "<tr class=$debug><td>$koloms[$i]</td><td>$value</td></tr>";
    $i++;
  }
}
echo "$back_to$judul<div class=wadah><table class='table'>$tr</table></div>";



# ===============================================
# LIST KELAS PESERTA
# ===============================================
$kelas = isset($_GET['kelas']) ? $_GET['kelas'] : '';
if($kelas==''){
  include 'dpnu_pilih_dahulu_kelas_peserta.php';
  die($back_to);
}


# ===============================================
# LIST SESI KULIAH
# ===============================================
if($id_sesi==''){
  # ===============================================
  # LIST MAHASISWA PADA DPNU
  # ===============================================
  include 'dpnu_list_mahasiswa.php';  
  echo $back_to;

  # ===============================================
  # LINK TO DETAIL DAFTAR HADIR SESI
  # ===============================================
  include 'dpnu_list_daftar_hadir_sesi.php';
  die($back_to);
}



# ===============================================
# DAFTAR HADIR DETAIL SESI KULIAH
# ===============================================
include 'dpnu_sesi_kuliah.php';
?>
<hr>



<script>
  $(function(){
    $('.btn_status_presensi').click(function(){
      $('.btn_status_presensi').removeClass('btn_active');
      $(this).addClass('btn_active');
      alert("Tombol Set Status Presensi masih dalam tahap pengembangan. Terimakasih. zzz here")
    })
  })
</script>