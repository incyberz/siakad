<style>th{text-align:left}.tb_semester{background:#ffffff77}</style>
<?php
$kelas = $_GET['kelas'] ?? '';
$id_kurikulum = $_GET['id_kurikulum'] ?? '';
if(!$kelas || $kelas<1) die('<script>location.replace("?manage_kelas")</script>');
echo "<h1>Manage Kelas TA</h1><p>Proses assign Grup Kelas <a href='?manage_grup_kelas&id_kurikulum=$id_kurikulum'>$kelas</a> dengan Tahun Ajar tertentu.</p>";

# ==============================================================
# GET OPTION ANGKATAN / TAHUN_AJAR
# ==============================================================
$rta = [2020,2021,2022,2023,2024]; // zzz default | tidak sesuai db


# ==============================================================
# FORM PROCESSING
# ==============================================================
if(isset($_POST['btn_tambah']) || isset($_POST['btn_hapus'])){
  // echo '<pre>';
  // var_dump($_POST);
  // echo '</pre>';
  $kelas = $_POST['kelas'] ?? die(erid('kelas'));
  $tahun_ajar = $_POST['tahun_ajar'] ?? die(erid('tahun_ajar'));
  
  if(isset($_POST['btn_tambah'])){
    $s = "SELECT 1 FROM tb_kelas_ta WHERE kelas='$kelas' AND tahun_ajar='$tahun_ajar'";
    $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
    if(mysqli_num_rows($q)==1){
      echo div_alert('danger',"Kelas-TA: <b>$kelas ~ TA$tahun_ajar</b> sudah ada. Silahkan pilih TA lain!");
    }else{
      $s = "INSERT INTO tb_kelas_ta 
      (kelas,tahun_ajar) VALUES 
      ('$kelas','$tahun_ajar')";
      $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
      echo div_alert('success', "Tambah Kelas-TA berhasil.");
    }
  }else{ // btn_hapus 
    $aksi = 'Hapus';
    $rid = explode('__',$_POST['btn_hapus']);
    $id_kelas_ta = $rid[1];
    $s = "DELETE FROM tb_kelas_ta WHERE id='$id_kelas_ta'";
    $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
    echo div_alert('success', "Hapus kelas-TA berhasil.");
  }
}

# ==============================================================
# GET DATA KELAS
# ==============================================================
$s = "SELECT * FROM tb_kelas WHERE kelas='$kelas'";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
if(mysqli_num_rows($q)==0) die('Data kelas tidak ditemukan.');
$d = mysqli_fetch_assoc($q);
$angkatan = $d['angkatan'];
$id_prodi = $d['id_prodi'];
$id_jalur = $d['id_jalur'];
$shift = $d['shift'];


# ==============================================================
# GET DATA KELAS TA
# ==============================================================
$tr = '';
$s = "SELECT a.id as id_kelas_ta, a.*, 
(SELECT count(1) FROM tb_kelas_ta_detail WHERE id_kelas_ta=a.id) jumlah_peserta_mhs
FROM tb_kelas_ta a WHERE a.kelas='$kelas' 
ORDER BY tahun_ajar 
";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
if(mysqli_num_rows($q)==0){
  $tb = div_alert('danger','Belum ada kelas TA pada Grup Kelas ini.');
}else{
  $i=0;
  while ($d=mysqli_fetch_assoc($q)) {
    $i++;
    $id_kelas_ta = $d['id_kelas_ta'];
    $jumlah_peserta_mhs = $d['jumlah_peserta_mhs'];
    $btn_hapus = $jumlah_peserta_mhs ? '-' : "<button class='btn btn-danger btn-sm' onclick='return confirm(\"Yakin mau hapus kelas TA ini?\")' name=btn_hapus value=hapus__$id_kelas_ta>Hapus</button>";

    $tr.="
      <tr>
        <td>$i</td>
        <td>$kelas ~ TA$d[tahun_ajar]</td>
        <td>$d[jumlah_peserta_mhs] | <a href='?manage_peserta&id_kelas_ta=$id_kelas_ta&id_kurikulum=$id_kurikulum'>manage peserta mhs</td>
        <td>$btn_hapus</td>
      </tr>
    ";
  }


}

$opt_ta = '';
foreach ($rta as $value){
  if($value<$angkatan) continue;
  $opt_ta.= "<option>$value</option>";
}


$tr_tambah = "
<tr>
  <td>#</td>
  <td colspan=3>
    $kelas ~ TA
    <select name=tahun_ajar>$opt_ta</select>-
    <button class='btn btn-info btn-sm' name=btn_tambah>Tambah</button>
  </td>
</tr>
";

$tb = "
<p>Misal: kelas <u class=darkblue>$kelas ~ TA2020</u> mungkin saja tidak sama dengan kelas <u class=darkblue>$kelas ~ TA2021</u>. Anda harus menentukan sendiri Jumlah Peserta Mhs tiap tahun ajarnya.</p>
<form method=post>
  <table class=table>
    <thead>
      <th>No</th>
      <th>Kelas TA</th>
      <th>Jumlah Peserta Mhs</th>
      <th>Aksi</th>
    </thead>
    $tr
    $tr_tambah
  </table>
  <input class=debug name=kelas value='$kelas'>
</form>";  
echo $tb;