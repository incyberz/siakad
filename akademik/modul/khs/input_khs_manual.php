<h1>Input KHS Manual</h1>
<style>th{text-align:left}</style>
<?php
$kode_mk = '';
$nama_mk = '';
$bobot = '';
$semester = '';

$nim = '';
$nama_mhs = '';
$gender = '';
$kelas_manual = '';

$nilai = '';
$nilai2 = '';
$hm = '';

$disabled_mk = '';
$disabled_mhs = '';
$jumlah_peserta_show = '';


if(isset($_POST['btn_simpan_nilai'])){
  echo '<pre>';
  var_dump($_POST);
  echo '</pre>';

  $mode = $_POST['mode'];
  $id_mk_manual = isset($_POST['id_mk_manual']) ? $_POST['id_mk_manual'] : $_POST['id_mk_manual2'];

  $nim = isset($_POST['nim']) ? $_POST['nim'] : $_POST['nim2'];
  $nama_mhs = isset($_POST['nama_mhs']) ? $_POST['nama_mhs'] : '';
  $gender = isset($_POST['gender']) ? $_POST['gender'] : '';
  $kelas_manual = isset($_POST['kelas_manual']) ? $_POST['kelas_manual'] : '';

  $nilai = $_POST['nilai'];
  $hm = $_POST['hm'];

  $kelas_or_null = $kelas_manual==''?'NULL':"'$kelas_manual'";

  if($mode=='insert'){
    if($id_mk_manual!=''){
      // cek if exists nilai
      $s = "SELECT 1 FROM tb_nilai_manual WHERE nim='$nim' AND id_mk_manual='$id_mk_manual'";
      $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
      if(mysqli_num_rows($q)==1){
        die(div_alert('danger',"Nilai sudah ada.<hr>
        <a href='?input_khs_manual' class='btn btn-info'>Input pada MK lain.</a> 
        <a href='?input_khs_manual&id_mk_manual=$id_mk_manual' class='btn btn-info'>Input Mhs lain pada MK ini.</a> 
        <a href='?input_khs_manual&id_mk_manual=$id_mk_manual&nim=$nim' class='btn btn-info'>Ubah nilai Mhs ini.</a> 
        "));
      }

      // insert biasa dg POST id_mk_manual
      $s = "INSERT INTO tb_nilai_manual 
      (nim,id_mk_manual,kelas,nilai,hm) VALUES 
      ('$nim','$id_mk_manual',$kelas_or_null,'$nilai','$hm')";
      $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
      
      echo div_alert('success', 'Insert Nilai baru berhasil.');
      echo "<script>location.replace('?input_khs_manual')</script>";
      exit;


    }else{
      # ================================================
      # GET AUTO_INCREMENT id_mk_manual
      # ================================================
      $s = "SELECT auto_increment from information_schema.tables 
      where table_schema = '$db_name' 
      and table_name = 'tb_mk_manual'";
      $q = mysqli_query($cn,$s) or die('Error get auto_increment.');
      $d = mysqli_fetch_array($q);
      $new_id_mk_manual = $d['auto_increment'];

      echo(div_alert('success','Get auto_increment mata kuliah success.'));

      // batalkan jika nim tidak terdaftar


      // insert new mk manual zzz here
      $s = "INSERT INTO tb_nilai_manual 
      (nim,id_mk_manual,kelas,nilai,hm) VALUES 
      ('$nim','$id_mk_manual',$kelas_or_null,'$nilai','$hm')";
      $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
      
      echo div_alert('success', 'Insert Nilai baru berhasil.');
      echo "<script>location.replace('?input_khs_manual')</script>";
      exit;

    }

  }else{
    // mode update
    echo 'mode update';
  }

  exit;

}



$id_mk_manual = isset($_GET['id_mk_manual']) ? $_GET['id_mk_manual'] : '';
$nim = isset($_GET['nim']) ? $_GET['nim'] : '';

if($id_mk_manual!=''){
  $disabled_mk = 'disabled';
  $s = "SELECT * FROM tb_mk_manual WHERE id='$id_mk_manual'";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  if(mysqli_num_rows($q)==0) die('Data MK tidak ditemukan.');
  $d = mysqli_fetch_assoc($q);
  $kode_mk = $d['kode'];
  $nama_mk = $d['nama'];
  $bobot = $d['bobot'];
  $semester = $d['semester'];

  $s = "SELECT 
  b.nim,
  (SELECT nama FROM tb_mhs WHERE nim=b.nim) as nama_mhs 

  FROM tb_mk_manual a 
  JOIN tb_nilai_manual b ON a.id=b.id_mk_manual 
  WHERE a.id='$id_mk_manual'";

  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  $peserta = '<div class="miring kecil abu">Belum ada mhs pada MK ini.</div>';
  $jumlah_peserta = mysqli_num_rows($q);
  if($jumlah_peserta>0){
    $peserta = 'Para peserta mhs:';
    $i=0;
    while ($d=mysqli_fetch_assoc($q)) {
      $i++;
      $peserta.="<div>$i. $d[nim] - $d[nama_mhs]</div>";
    }

    $jumlah_peserta_show = "
    <tr>
      <td colspan=4>
        <div id=jumlah_peserta_show class='btn btn-success btn-sm mb2'>Terdapat $jumlah_peserta mahasiswa pada Mata Kuliah ini.</div>
        <div class='wadah hideit' id=peserta>
          $peserta
        </div>
      </td>
    </tr>
    ";
  }else{
    // $jumlah_peserta_show = "Belum ada peserta pada MK ini.";
  }

}

if($nim!=''){
  $disabled_mhs = 'disabled';
  $s = "SELECT * FROM tb_mhs WHERE nim='$nim'";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  if(mysqli_num_rows($q)==0) die('Data Mhs tidak ditemukan.');
  $d = mysqli_fetch_assoc($q);
  $nama_mhs = $d['nama'];
  $gender = $d['gender'];
  $kelas_manual = $d['kelas_manual'];
}


$tombol_caption = 'Simpan Nilai';
$insert = 'insert';
$nilai_sudah_ada = 'Nilai';
$info_history = '';
$konfirmasi_update = '<input name=konfirmasi_update class=debug>';
$nilai_history = '<div class="kecil miring abu">Belum ada history nilai.</div>';
if($id_mk_manual!='' and $nim!=''){
  // mode update nilai
  $insert = 'update';
  $tombol_caption = 'Update Nilai';

  $s = "SELECT * FROM tb_nilai_manual WHERE nim='$nim' AND id_mk_manual=$id_mk_manual";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  if(mysqli_num_rows($q)==1){
    $d = mysqli_fetch_assoc($q);
    $nilai = $d['nilai'];
    $hm = $d['hm'];
    $nilai_sudah_ada = '<span class="biru tebal">Nilai sudah ada.</span>';
    $konfirmasi_update = "Silahkan ketik kata `UPDATE`:<input name=konfirmasi_update class='form-control' minlength=6 maxlength=6 required >";
    
    
    $s = "SELECT * FROM tb_nilai_manual_history WHERE nim='$nim' AND id_mk_manual=$id_mk_manual";
    // $s = "SELECT * FROM tb_nilai_manual WHERE nim='$nim' ";
    $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
    if(mysqli_num_rows($q)>0){
      $nilai_history = '';
      $i = 0;
      while ($d = mysqli_fetch_assoc($q)) {
        $i++;
        $nilai_history .= "<div class='miring abu biru'>$i. $d[date_created] | Nilai: $d[nilai] | HM: $d[hm]</div>";
      }
    }

    $info_history = "<div class='wadah kecil miring mb2'><span class='biru tebal'>Informasi penting:</span> Nilai lama akan tersimpan pada history nilai. Nilai terupdate akan muncul di KHS.<hr>$nilai_history</div>";
  } 
}

$rprodi = ['TI','RPL','SI','MI','KA'];
$rgender = ['-','L','P'];

$select_gender = '<select name=gender class=form-control>';
for ($i=0; $i < count($rgender); $i++) $select_gender.="<option>$rgender[$i]</option>";
$select_gender .= '</select>';



?>


<form method=post>
  <input class="debug" name=mode value=<?=$insert?>>
  <input class="debug" name=id_mk_manual2 value=<?=$id_mk_manual?>>
  <input class="debug" name=nim2 value=<?=$nim?>>
  
  <table class="table">
    <tr>
      <td>Kode: <?=$bm?><input class="form-control" value='<?=$kode_mk?>' name=kode_mk  minlength=3 maxlength=20 required <?=$disabled_mk?>></td>
      <td>MK: <?=$bm?><input class="form-control" value='<?=$nama_mk?>' name=nama_mk  minlength=3 maxlength=100 required <?=$disabled_mk?>></td>
      <td>SKS: <?=$bm?><input class="form-control" value='<?=$bobot?>' name=bobot type=number min=1 max=6 required <?=$disabled_mk?>></td>
      <td>Semester: <?=$bm?><input class="form-control" value='<?=$semester?>' name=semester type=number min=1 max=8 required <?=$disabled_mk?>></td>
    </tr>
    <?=$jumlah_peserta_show ?>
  </table>  
  

  <table class="table">
    <tr>
      <td>NIM: <?=$bm?><input class="form-control" value='<?=$nim?>' name=nim maxlength=8 minlength=8 required <?=$disabled_mhs?>></td>
      <td>Nama: <?=$bm?><input class="form-control" value='<?=$nama_mhs?>' name=nama_mhs maxlength=30 minlength=3 required <?=$disabled_mhs?>></td>
      <td>L/P:<input class="form-control" value='<?=$gender?>' name=gender type=text minlength=1 maxlength=1 <?=$disabled_mhs?>></td>
      <td>Kelas:<input class="form-control" value='<?=$kelas_manual?>' name=kelas_manual minlength=5 <?=$disabled_mhs?>></td>
    </tr>
  </table>  

  <table class="table">
    <tr>
      <td><?=$nilai_sudah_ada?> <?=$bm?><input class="form-control" type=number min=0 max=100 value='<?=$nilai?>' name=nilai  required></td>
      <td>Huruf: <?=$bm?><input class="form-control" value='<?=$hm?>' name=hm minlength=1 maxlength=1 required></td>
    </tr>
  </table>  

  <div style="max-width:200px; margin-bottom: 10px">
    <?=$konfirmasi_update?>
  </div>

  <div style="margin-bottom: 10px">
    <button class="btn btn-primary btn-block" name=btn_simpan_nilai><?=$tombol_caption ?></button>
  </div>
  <?=$info_history?>
</form>


<script>
  $(function(){
    $('#jumlah_peserta_show').click(function(){
      $('#peserta').fadeToggle();
    })
  })
</script>