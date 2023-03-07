<h1>Manage Peserta Kelas</h1>
<?php
if(isset($_POST['btn_buat_sesi_default'])){
  $id_dosen = $_POST['id_dosen'];
  $id_jadwal = $_POST['id_jadwal'];
  $jumlah_sesi = $_POST['jumlah_sesi'];
  $sesi_uts = $_POST['sesi_uts'];
  $sesi_uas = $_POST['sesi_uas'];
  
  $values = '__';
  for ($i=1; $i <= $jumlah_sesi ; $i++) {

    $nama_sesi = "NEW P$i";
    $nama_sesi = $i==$sesi_uts ? 'UTS' : $nama_sesi;
    $nama_sesi = $i==$sesi_uas ? 'UAS' : $nama_sesi;
    
    $values .= ",(
    $id_jadwal,
    $i,
    $id_dosen,
    '$nama_sesi'
    )";
  }
  $values = str_replace('__,','',$values);

  $s = "INSERT INTO tb_sesi_kuliah (
    id_jadwal,
    pertemuan_ke,
    id_dosen,
    nama
    ) VALUES $values";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));

  echo div_alert('success',"Membuat $jumlah_sesi Sesi Kuliah Default berhasil.<hr><a href='?manage_sesi&id_jadwal=$id_jadwal'>Lanjutkan Proses</a>");
  exit;
  
}


$id_jadwal = isset($_GET['id_jadwal']) ? $_GET['id_jadwal'] : '';

if($id_jadwal==''){
  include 'modul/jadwal_kuliah/list_jadwal.php';
  exit;
}
echo "<span class=debug id=id_jadwal>$id_jadwal</span>";
$s = "SELECT 
a.keterangan,
b.id as id_kurikulum_mk,
d.id as id_dosen,
d.nama as dosen_koordinator,  
a.sesi_uts,  
a.sesi_uas,  
a.jumlah_sesi  

FROM tb_jadwal a 
JOIN tb_kurikulum_mk b on b.id=a.id_kurikulum_mk 
JOIN tb_mk c on c.id=b.id_mk 
JOIN tb_dosen d on d.id=a.id_dosen  
WHERE a.id=$id_jadwal";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
if(mysqli_num_rows($q)==0) die('Data Jadwal tidak ditemukan.');
$d = mysqli_fetch_assoc($q);
$id_kurikulum_mk = $d['id_kurikulum_mk'];
$id_dosen = $d['id_dosen'];
$jumlah_sesi = $d['jumlah_sesi'];
$sesi_uts = $d['sesi_uts'];
$sesi_uas = $d['sesi_uas'];

$koloms = [];
$i=0;
$tr = '';
foreach ($d as $key => $value) {
  if($key=='nama_dosen') continue;
  $koloms[$i] = str_replace('_',' ',$key);
  $debug = substr($key,0,2)=='id' ? 'debug' : 'upper';
  // echo substr($key,0,2)."<hr>";
  $tr .= "<tr class=$debug><td>$koloms[$i]</td><td id=$key>$value</td></tr>";
  $i++;
}

$tb_jadwal_info = "<table class=table>$tr</table>";

# ====================================================
# LIST SESI KULIAH
# ====================================================
$s = "SELECT 
a.id,
a.pertemuan_ke,
a.nama as nama_sesi,
a.id_dosen, 
a.tanggal_sesi,
b.nama as nama_dosen,
(SELECT r.nama from tb_ruang r where r.id=a.id_ruang) as nama_ruang  

from tb_sesi_kuliah a 
join tb_dosen b on b.id=a.id_dosen 
where a.id_jadwal=$id_jadwal order by a.pertemuan_ke";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
if(mysqli_num_rows($q)==0){
  $tb_sesi = "
  <div class='alert alert-info'>
    Belum ada sesi untuk jadwal ini.<hr>
    <form method=post>
      <input class=debug name=id_jadwal value='$id_jadwal'>
      <input class=debug name=id_dosen value='$id_dosen'>
      <input class=debug name=jumlah_sesi value='$jumlah_sesi'>
      <input class=debug name=sesi_uts value='$sesi_uts'>
      <input class=debug name=sesi_uas value='$sesi_uas'>
      <button class='btn btn-primary' name=btn_buat_sesi_default>Buat $d[jumlah_sesi] Sesi Kuliah Default</button>
    </form>
  </div>";
}else{

  $thead = "
  <thead>
    <th class='text-left upper'>Pertemuan ke</th>
    <th class='text-left upper'>Nama Sesi</th>
    <th class='text-left upper'>Pengajar</th>
    <th class='text-left upper'>Tanggal Sesi</th>
    <th class='text-left upper'>Ruang</th>
    <th class='text-left upper'>Aksi</th>
  </thead>"; 
  $tr = '';
  while ($d=mysqli_fetch_assoc($q)) {
    $tr .= "
    <tr>
      <td class='upper'>$d[pertemuan_ke]</td>
      <td class='upper'>$d[nama_sesi]</td>
      <td class='upper'>$d[nama_dosen]</td>
      <td class='upper'>$d[tanggal_sesi]</td>
      <td class='upper'>$d[nama_ruang]</td>
      <td>
        <a href='?master&p=sesi_kuliah&aksi=update&id=$d[id]' class='btn btn-info btn-sm' target='_blank'>edit</a>
        <a href='?master&p=sesi_kuliah&aksi=hapus&id=$d[id]' class='btn btn-danger btn-sm' target='_blank'>hapus</a>
      </td>
    </tr>"; 
  }

  $batch = "<div class=wadah>
  <p>Untuk setting tanggal sesi dari P1 s.d P$jumlah_sesi secara terurut per minggu silahkan lakukan Batch Tanggal Sesi</p>
  <a href='?batch_tanggal_sesi&id_jadwal=$id_jadwal' class='btn btn-info'>Batch Tanggal Sesi</a>
  </div>";

  $tb_sesi = "$batch<table class='table table-striped table-hover'>$thead$tr</table>";
}










?>
<?=$tb_jadwal_info ?>
<?=$tb_sesi ?>











<script>
  $(function(){
   
  })
</script>