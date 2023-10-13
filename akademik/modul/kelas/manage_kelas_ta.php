<style>th{text-align:left}.tb_semester{background:#ffffff77}</style>
<?php
$kelas = $_GET['kelas'] ?? '';
$id_kurikulum = $_GET['id_kurikulum'] ?? '';
if(!$kelas || $kelas<1) die('<script>location.replace("?manage_kelas")</script>');
echo "<h1>Manage Kelas TA</h1><p>Proses assign Grup Kelas <a href='?manage_grup_kelas&id_kurikulum=$id_kurikulum'>$kelas</a> dengan Tahun Ajar tertentu.</p>";


$s = "SELECT a.*, b.jenjang 
FROM tb_kelas a 
JOIN tb_prodi b ON a.id_prodi=b.id 
WHERE a.kelas='$kelas'";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
if(mysqli_num_rows($q)==0) die(div_alert('danger','Data kelas tidak ada.'));
$d=mysqli_fetch_assoc($q);
$angkatan = $d['angkatan'];
$jenjang = $d['jenjang'];



# ==============================================================
# KELAS TA YANG SUDAH ADA
# ==============================================================
$s = "SELECT * FROM tb_kelas_ta WHERE kelas='$kelas'";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
$arr_kelas_ta = [];
while ($d=mysqli_fetch_assoc($q)) {
  array_push($arr_kelas_ta,$d['tahun_ajar']);
}


# ==============================================================
# GET OPTION ANGKATAN / TAHUN_AJAR
# ==============================================================
$tahun_jenjang['D3'] = 3;
$tahun_jenjang['S1'] = 4;
$arr_unsigned_ta = [];
$arr_smt_ta = [];
$s = "SELECT * FROM tb_tahun_ajar";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
$i=0;
while ($d=mysqli_fetch_assoc($q)) {
  // hanya untuk angkatan ini dan sesuai dg jenjang
  if($d['angkatan'] >= $angkatan AND $d['angkatan'] < ($angkatan+$tahun_jenjang[$jenjang])){
    $i++;
    if(!in_array($d['tahun_ajar'],$arr_kelas_ta)){
      array_push($arr_unsigned_ta,"$d[tahun_ajar]");
    }
    $arr_smt_ta[$d['tahun_ajar']] = $i;
  }
}


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
      echo div_alert('success', "Tambah Kelas-TA berhasil. | <a href='?manage_kelas_ta&kelas=$_GET[kelas]&id_kurikulum=$_GET[id_kurikulum]'>Back</a>");
      exit;
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
$s = "SELECT a.id as id_kelas_ta, a.*, b.gg, b.angkatan,
(SELECT count(1) FROM tb_kelas_ta_detail 
WHERE id_kelas_ta=a.id) jumlah_peserta_mhs
FROM tb_kelas_ta a 
JOIN tb_tahun_ajar b ON a.tahun_ajar=b.tahun_ajar 
WHERE a.kelas='$kelas' 
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
        <td>$kelas ~ TA-$d[angkatan]-$d[gg]</td>
        <td>$d[jumlah_peserta_mhs] | <a href='?manage_peserta&id_kelas_ta=$id_kelas_ta&id_kurikulum=$id_kurikulum'>manage peserta mhs</td>
        <td>$btn_hapus</td>
      </tr>
    ";
  }


}

$opt_ta = '';
foreach ($arr_unsigned_ta as $value){
  if($value<$angkatan) continue;
  $opt_ta.= "<option>$value (smt-$arr_smt_ta[$value])</option>";
}


$tr_tambah = count($arr_unsigned_ta)==0 ? "<tr><td colspan=4><div class='kecil miring abu'>Semua tahun ajar jenjang $jenjang sudah ditambahkan.</div></td></tr>" : "
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
<p>Setelah menambah Kelas-TA, silahkan tentukan Peserta Mhs untuk setiap semesternya.</p>
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
