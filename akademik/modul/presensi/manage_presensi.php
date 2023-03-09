<h1 class='m0 mb2'>Manage Presensi</h1>
<style>.btn_active{border:solid 3px blue}</style>
<?php
$id_jadwal = isset($_GET['id_jadwal']) ? $_GET['id_jadwal'] : '';
$id_mhs = isset($_GET['id_mhs']) ? $_GET['id_mhs'] : '';

if($id_jadwal=='' || $id_mhs==''){
  die(div_alert('info',"Silahkan tentukan dahulu Mahasiswa dan Mata Kuliahnya di Menu <a class='btn btn-primary btn-sm' href='?manage_mhs'>Manage Mahasiswa</a>, kemudian pilih aksi <code>Presensi</code>, dan klik salah satu Link <code>Manage Presensi</code> "));
}


# ===============================================
# DATA MAHASISWA
# ===============================================
$s = "SELECT 
a.nama,
a.nim,
a.kelas,
a.no_wa,
a.status_mhs,
a.folder_uploads
FROM tb_mhs a 
WHERE a.id=$id_mhs";
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


# ===============================================
# DATA KURIKULUM MK
# ===============================================
$s = "SELECT 
concat(c.nama,' / ',c.kode) as mata_kuliah,
d.nama as dosen_koordinator, 
b.id as id_kurikulum_mk 
FROM tb_jadwal a 
JOIN tb_kurikulum_mk b on b.id=a.id_kurikulum_mk 
JOIN tb_mk c on c.id=b.id_mk 
JOIN tb_dosen d on d.id=a.id_dosen 

WHERE a.id=$id_jadwal";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
$d = mysqli_fetch_assoc($q);
$koloms = [];
foreach ($d as $key => $value) {
  $koloms[$i] = str_replace('_',' ',$key);
  $debug = (substr($key,0,2)=='id' || $key=='no_wa' || $key=='status_mhs' || $key=='folder_uploads') ? 'debug' : 'upper';
  // echo substr($key,0,2)."<hr>";
  $tr .= "<tr class=$debug><td>$koloms[$i]</td><td>$value</td></tr>";
  $i++;
}
$fitur_tambahan = "
Go to: 
<a href='?manage_jadwal&id_kurikulum_mk=$d[id_kurikulum_mk]'>Manage Jadwal</a> | 
<a href='?manage_kelas&id_jadwal=$id_jadwal'>Manage Kelas</a> | 
<a href='?manage_sesi&id_jadwal=$id_jadwal'>Manage Sesi</a> | 
<a href='?dpnu&id_jadwal=$id_jadwal'>DPNU</a> 
";
echo "<div class=wadah><table class='table'>$tr</table>$fitur_tambahan</div>";


// echo "<span class=debug>id_jadwal:<span id=id_jadwal>$id_jadwal</span> </span>";
// echo "<span class=debug>id_mhs:<span id=id_mhs>$id_mhs</span> </span>";



# ===============================================
# LIST SESI KULIAH
# ===============================================
$s = "SELECT 
a.pertemuan_ke,
a.nama as nama_sesi,
a.tanggal_sesi,
a.status as status_presensi,
b.nama as nama_dosen,
(SELECT nama from tb_ruang where id=a.id_ruang) as nama_ruang, 
(SELECT timestamp_masuk from tb_presensi where id_mhs=$id_mhs and id_sesi_kuliah=a.id) as tanggal_presensi 

FROM tb_sesi_kuliah a 
JOIN tb_dosen b on b.id=a.id_dosen 

WHERE a.id_jadwal=$id_jadwal";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
if(mysqli_num_rows($q)==0) die('Data Jadwal tidak ditemukan.');

$thead = "
  <thead>
    <th class='proper text-left'>p-ke</th>
    <th class='proper text-left'>nama sesi</th>
    <th class='proper text-left'>tanggal sesi</th>
    <th class='proper text-left'>tanggal presensi</th>
    <th class='proper text-left'>dosen pengajar</th>
    <th class='proper text-left'>nama ruang</th>
    <th class='proper text-left'>Status Presensi</th>
  </thead>
";
$tr = '';
while ($d=mysqli_fetch_assoc($q)) {
  $tanggal_sesi = date('d-M-y ~ H:i',strtotime($d['tanggal_sesi']));
  $tanggal_presensi = $d['tanggal_presensi']=='' ? $null : date('d-M-y ~ H:i',strtotime($d['tanggal_presensi']));
  $nama_ruang = $d['nama_ruang']=='' ? $null : $d['nama_ruang'];

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
    <td>$d[pertemuan_ke]</td>
    <td>$d[nama_sesi]</td>
    <td>$tanggal_sesi</td>
    <td>$tanggal_presensi</td>
    <td>$d[nama_dosen]</td>
    <td>$nama_ruang</td>
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