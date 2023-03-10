<style>.btn_active{border:solid 3px blue}</style>
<?php
$id_jadwal = isset($_GET['id_jadwal']) ? $_GET['id_jadwal'] : '';
$id_sesi_kuliah = isset($_GET['id_sesi_kuliah']) ? $_GET['id_sesi_kuliah'] : '';
$judul = $id_sesi_kuliah=='' ? 'Daftar Presensi dan Nilai Ujian (DPNU)' : 'Daftar Hadir Sesi Kuliah';
echo "<h1 class='m0 mb2'>$judul</h1>";

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
$koloms = [];
$tr = '';
foreach ($d as $key => $value) {
  $koloms[$i] = str_replace('_',' ',$key);
  $debug = (substr($key,0,2)=='id' || $key=='no_wa' || $key=='status_mhs' || $key=='folder_uploads') ? 'debug' : 'upper';
  // echo substr($key,0,2)."<hr>";
  $tr .= "<tr class=$debug><td>$koloms[$i]</td><td>$value</td></tr>";
  $i++;
}
$fitur_tambahan = "
Opsi : 
<a href='?manage_jadwal&id_kurikulum_mk=$d[id_kurikulum_mk]'>Manage Jadwal</a> | 
<a href='?manage_kelas&id_jadwal=$id_jadwal'>Manage Kelas</a> | 
<a href='?manage_sesi&id_jadwal=$id_jadwal'>Manage Sesi</a> 
";
// echo "<div class=wadah><table class='table'>$tr</table>$fitur_tambahan</div>";



# ===============================================
# DATA SESI KULIAH
# ===============================================
if($id_sesi_kuliah>0){
  $s = "SELECT 
  concat(a.pertemuan_ke, ' / ',a.nama) as pertemuan_ke,
  a.tanggal_sesi 

  FROM tb_sesi_kuliah a 


  WHERE a.id=$id_sesi_kuliah";
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
echo "<div class=wadah><table class='table'>$tr</table>$fitur_tambahan</div>";



# ===============================================
# LIST KELAS PESERTA
# ===============================================
$kelas = isset($_GET['kelas']) ? $_GET['kelas'] : '';
if($kelas==''){
  $s = "SELECT 
  a.kelas,
  (SELECT count(1) from tb_mhs WHERE kelas=a.kelas ) as jumlah_mhs    
  FROM tb_kelas a 
  JOIN tb_kelas_peserta b on b.kelas=a.kelas 
  JOIN tb_kurikulum_mk c on c.id=b.id_kurikulum_mk 
  JOIN tb_jadwal d on c.id=d.id_kurikulum_mk 

  WHERE d.id=$id_jadwal
  ";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));

  $thead = '';
  $tr = '';
  $i=0;
  while ($d = mysqli_fetch_assoc($q)) {
    $i++;
    $tr .= "<tr>
      <td>$i</td>
      <td>$d[kelas]</td>
      <td>$d[jumlah_mhs] Mhs</td>
      <td>
        <a href='?manage_peserta&kelas=$d[kelas]' class='btn btn-info btn-sm proper'>Manage Peserta Mhs</a> 
        <a href='?dpnu&kelas=$d[kelas]&id_jadwal=$id_jadwal' class='btn btn-primary btn-sm '>DPNU</a> 
      </td>
    </tr>";
  }

  $tb = $tr=='' ? "<div class='alert alert-danger'>Belum ada Kelas Peserta pada Jadwal ini. | <a href='?manage_kelas&id_jadwal=$id_jadwal' target=_blank>Manage Kelas</a></div>" : "
  <div class=wadah>
    <p class=biru>Silahkan Pilih Kelas untuk melihat DPNU</p>
    <table class=table>
      $thead
      $tr
    </table>
  </div>
  ";

  echo $tb;
  exit;
}


# ===============================================
# LIST SESI KULIAH
# ===============================================
if($id_sesi_kuliah==''){

  # ===============================================
  # LIST MAHASISWA PADA KELAS
  # ===============================================
  $s = "SELECT 
  a.id as id_mhs,
  a.nim,
  a.nama as nama_mhs
  FROM tb_mhs a 
  WHERE a.kelas='$kelas'";
  // echo "<pre>$s</pre>";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  if(mysqli_num_rows($q)==0) die(div_alert('danger',"Jadwal ini belum punya Sesi Kuliah. | <a href='?manage_sesi&id_jadwal=$id_jadwal' target=_blank>Manage Sesi</a>"));

  $thead = "
    <thead>
      <th class='text-left'>No</th>
      <th class='text-left'>NIM</th>
      <th class='text-left'>Nama</th>
      <th class='text-left'>Kehadiran</th>
      <th class='text-left'>Tugas</th>
      <th class='text-left'>UTS</th>
      <th class='text-left'>UAS</th>
      <th class='text-left'>Nilai Akhir</th>
      <th class='text-left'>Huruf Mutu</th>
    </thead>
  ";
  $tr = '';
  $jumlah_mhs=0;
  while ($d=mysqli_fetch_assoc($q)) {
    $jumlah_mhs++;
    $d['status_presensi']=''; //zzz

    $tr .= "
    <tr>
      <td>$jumlah_mhs</td>
      <td>$d[nim]</td>
      <td>$d[nama_mhs]</td>
      <td>0 %</td>
      <td>0</td>
      <td>0</td>
      <td>0</td>
      <td>0</td>
      <td>E</td>
    </tr>
    ";
  }
  $opsi = "<div class=wadah>
    <span class='btn btn-primary btn-sm not_ready'>Cetak PDF</span> 
    <span class='btn btn-success btn-sm not_ready'>Export Excel</span> 
  </div>
  ";
  echo "<div class=wadah><table class=table>$thead$tr</table> $opsi</div>";
  


  # ===============================================
  # LINK TO DETAIL DAFTAR HADIR SESI
  # ===============================================
  $s = "SELECT 
  a.id as id_sesi_kuliah,
  a.pertemuan_ke,
  a.nama as nama_sesi,
  a.tanggal_sesi,
  a.status as status_presensi,
  b.nama as nama_dosen,
  (SELECT nama from tb_ruang where id=a.id_ruang) as nama_ruang 

  FROM tb_sesi_kuliah a 
  JOIN tb_dosen b on b.id=a.id_dosen 

  WHERE a.id_jadwal=$id_jadwal";
  // echo "<pre>$s</pre>";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  if(mysqli_num_rows($q)==0) die(div_alert('danger',"Jadwal ini belum punya Sesi Kuliah. | <a href='?manage_sesi&id_jadwal=$id_jadwal' target=_blank>Manage Sesi</a>"));

  $thead = "
    <thead>
      <th class='proper text-left'>p-ke</th>
      <th class='proper text-left'>nama sesi</th>
      <th class='proper text-left'>tanggal sesi</th>
      <th class='proper text-left'>dosen pengajar</th>
      <th class='proper text-left'>nama ruang</th>
      <th class='proper text-left'>Aksi</th>
    </thead>
  ";
  $tr = '';
  while ($d=mysqli_fetch_assoc($q)) {
    $tanggal_sesi = date('d-M-y ~ H:i',strtotime($d['tanggal_sesi']));
    $nama_ruang = $d['nama_ruang']=='' ? $null : $d['nama_ruang'];

    $tr .= "
    <tr>
      <td>$d[pertemuan_ke]</td>
      <td>$d[nama_sesi]</td>
      <td>$tanggal_sesi</td>
      <td>$d[nama_dosen]</td>
      <td>$nama_ruang</td>
      <td>
        <a href='?dpnu&kelas=$kelas&id_jadwal=$id_jadwal&id_sesi_kuliah=$d[id_sesi_kuliah]' class='btn btn-info btn-sm'>Daftar Hadir Sesi</a>
      </td>
    </tr>
    ";
  }
  echo "<div class=wadah>
    <h3>Detail Daftar Hadir Sesi</h3>
    <table class=table>$thead$tr</table>
  </div>";
  exit;
}



# ===============================================
# DAFTAR HADIR DETAIL SESI KULIAH
# ===============================================
$s = "SELECT 
a.id as id_mhs,
a.nim,
a.nama as nama_mhs
FROM tb_mhs a 
WHERE a.kelas='$kelas'";
// echo "<pre>$s</pre>";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
if(mysqli_num_rows($q)==0) die(div_alert('danger',"Jadwal ini belum punya Sesi Kuliah. | <a href='?manage_sesi&id_jadwal=$id_jadwal' target=_blank>Manage Sesi</a>"));

$thead = "
  <thead>
    <th class='text-left'>No</th>
    <th class='text-left'>NIM</th>
    <th class='text-left'>Nama</th>
    <th class='text-left'>Kehadiran</th>
  </thead>
";
$tr = '';
$jumlah_mhs=0;
while ($d=mysqli_fetch_assoc($q)) {
  $jumlah_mhs++;
  $d['status_presensi']=''; //zzz

  $btn_active_hadir = $d['status_presensi']=='h' ? 'btn_active' : '';
  $btn_active_s = $d['status_presensi']=='s' ? 'btn_active' : '';
  $btn_active_i = $d['status_presensi']=='i' ? 'btn_active' : '';
  $btn_active_a = $d['status_presensi']=='a' ? 'btn_active' : '';
  $btn_active_null = $d['status_presensi']=='' ? 'btn_active' : '';

  $btn_set_hadir = "<button class='btn btn-info btn-sm btn_status_presensi $btn_active_hadir' id=status__h>Hadir</button>";
  $btn_s = "<button class='btn btn-warning btn-sm btn_status_presensi $btn_active_s' id=status__s>S</button>";
  $btn_i = "<button class='btn btn-warning btn-sm btn_status_presensi $btn_active_i' id=status__i>I</button>";
  $btn_a = "<button class='btn btn-danger btn-sm btn_status_presensi $btn_active_a' id=status__a>A</button>";
  $btn_null = "<button class='btn btn-danger btn-sm btn_status_presensi $btn_active_null' id=status__null>Null</button>";


  $tr .= "
  <tr>
    <td>$jumlah_mhs</td>
    <td>$d[nim]</td>
    <td>$d[nama_mhs]</td>
    <td>
      $btn_set_hadir
      $btn_s
      $btn_i
      $btn_a
      $btn_null
    </td>
  </tr>
  ";
}
echo "<div class=wadah><table class=table>$thead$tr</table></div>";
?>
<hr>



<script>
  $(function(){
    $('.btn_status_presensi').click(function(){
      $('.btn_status_presensi').removeClass('btn_active');
      $(this).addClass('btn_active');
      alert("Tombol Set Status Presensi masih dalam tahap pengembangan. Terimakasih.")
    })
  })
</script>