<h1>Penagihan Biaya</h1>

<?php
if(isset($_POST['btn_tagihkan'])){
  echo '<pre>';
  var_dump($_POST);
  echo '</pre>';

  $id_biaya = $_POST['id_biaya'];
  $id_prodi = $_POST['id_prodi'];
  $angkatan = $_POST['angkatan'];
  

  $s = "INSERT INTO tb_penagihan (id_mhs,id_biaya) VALUES ";
  foreach ($_POST as $key => $value) {
    if(strpos("salt$key",'cek_tagih')){
      $z = explode('__',$key);
      $id_mhs = $z[1];
      // echo "id_mhs : $id_mhs<br>";
      $s .= "($id_mhs,$id_biaya),";
    }
  }

  $s .= '__';
  $s = str_replace(',__','',$s);
  // echo $s;
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));

  echo div_alert('success','Penagihan sukses.');
  echo "<script>location.replace('?penagihan_biaya&angkatan=$angkatan&id_prodi=$id_prodi&id_biaya=$id_biaya')</script>";
  exit;

}



$id_prodi = isset($_GET['id_prodi']) ? $_GET['id_prodi'] : '';
$id_biaya = isset($_GET['id_biaya']) ? $_GET['id_biaya'] : '';
$angkatan = isset($_GET['angkatan']) ? $_GET['angkatan'] : '';
include '../include/include_rprodi.php';
$nama_prodi = $id_prodi=='' ? '' : $rnama_prodi[$id_prodi];
$prodi = $id_prodi=='' ? '' : $rprodi[$id_prodi];


if($angkatan==''){
  $s = "SELECT angkatan FROM tb_angkatan";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  $link='';
  while ($d=mysqli_fetch_assoc($q)) {
    $link .= "<a class='btn btn-info btn-sm' href='?penagihan_biaya&angkatan=$d[angkatan]'>$d[angkatan]</a> ";
  }
  echo "<h4>Untuk Mahasiswa Angkatan:</h4><div class=wadah>$link</div>";
  exit;
}


if($id_prodi==''){
  $s = "SELECT id as id_prodi,nama as nama_prodi,jenjang FROM tb_prodi";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  $link='';
  while ($d=mysqli_fetch_assoc($q)) {
    $primary = $d['jenjang']=='D3' ? 'success' : 'primary';
    $link .= "<a class='btn btn-$primary btn-sm mt2 mb2' href='?penagihan_biaya&angkatan=$angkatan&id_prodi=$d[id_prodi]'>$d[nama_prodi]</a><br> ";
  }
  echo "<h4>Untuk Mahasiswa Angkatan <a href='?penagihan_biaya'>$angkatan</a> Prodi :</h4><div class=wadah>$link</div>";
  exit;
}



$id_biaya = isset($_GET['id_biaya']) ? $_GET['id_biaya'] : '';
if($id_biaya==''){
  $s = "SELECT * FROM tb_biaya ORDER BY no";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  $link='';
  while ($d=mysqli_fetch_assoc($q)) {
    $a = $d['untuk_semester']=='' 
    ? "<a class='btn btn-primary btn-sm mt2 mb2' href='?penagihan_biaya&angkatan=$angkatan&id_prodi=$id_prodi&id_biaya=$d[id]'>$d[no]. $d[nama]</a><br> "
    : "<a class='btn btn-success btn-sm mt2 mb2' href='?penagihan_semester&angkatan=$angkatan&id_prodi=$id_prodi&id_biaya=$d[id]&untuk_semester=$d[untuk_semester]'>$d[no]. $d[nama]</a><br> ";

    $link .= $a;
  }
  echo "<h4>Untuk Mahasiswa Angkatan <a href='?penagihan_biaya'>$angkatan</a> prodi <a href='?penagihan_biaya&angkatan=$angkatan'>$nama_prodi</a> :</h4><div class=wadah>$link</div>";
  exit;
  $opt='';
  $info='';
  if(mysqli_num_rows($q)==0){
    die("Belum ada Data Biaya untuk angkatan $angkatan | ");
  }else{
    while ($d=mysqli_fetch_assoc($q)) {
      $selected = $d['id'] == $id_biaya ? 'selected' : '';
      $opt .= "<option value=$d[id] $selected>$d[nama]</option>";
      $info .= "<div class=wadah>$d[nama]</div>";
    }
  }
  $select_biaya = "<select class=form-control name=id_biaya id=id_biaya>$opt</select>$info";

  echo "<h4>Untuk Mahasiswa Angkatan <a href='?penagihan_biaya'>$angkatan</a> Prodi :</h4><div class=wadah>$select_biaya</div>";
  exit;
}

$s = "SELECT a.*,
(SELECT nominal FROM tb_biaya_angkatan WHERE id_biaya=a.id AND angkatan=$angkatan AND id_prodi=$id_prodi) as nominal, 
(SELECT besar_cicilan FROM tb_biaya_angkatan WHERE id_biaya=a.id AND angkatan=$angkatan AND id_prodi=$id_prodi) as besar_cicilan  
FROM tb_biaya a WHERE a.id=$id_biaya";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
$d = mysqli_fetch_assoc($q);
$nominal = $d['nominal'];
$nominal_default = $d['nominal_default'];
$nominal = $nominal=='' ? $nominal_default : $nominal;
$nominal_show = 'Rp'.number_format($nominal,0).',-';
$besar_cicilan = $d['besar_cicilan'];
$besar_cicilan_show = ($besar_cicilan==''||$besar_cicilan==0||$besar_cicilan==$nominal) ? '<div class="kecil miring abu">Tidak dapat dicicil.</div>' : 'Rp'.number_format($besar_cicilan,0).',-';
$selected_biaya = "<div class='tebal biru'><a href='?penagihan_biaya&angkatan=$angkatan&id_prodi=$id_prodi'>$d[nama]</a></div> 
<div>$nominal_show</div> $besar_cicilan_show";

?>
<div class="row">
  <div class="col-lg-4">
    <h4>Jenis Biaya</h4>
    <div class="wadah">
      <?=$selected_biaya?>
    </div>
  </div>
  <div class="col-lg-8">
    <div>
      Tagihkan ke <b><u>Mhs Aktif</u></b> 
      angkatan <b><a href='?penagihan_biaya'><?=$angkatan?></a></b> 
      prodi <b><a href='?penagihan_biaya&angkatan=<?=$angkatan?>'><?=$nama_prodi?></a></b>:
      <span class="debug">include list_target_penagihan.php</span>
    </div>
    <?php include 'list_target_penagihan.php'; ?>
      
  </div>
</div>
