
<?php
$msg = "Error @Uplod. Missing index field";
if (!isset($_POST['folder_uploads'])) die("$msg #1");
if (!isset($_POST['id_mhs'])) die("$msg #2");

include "../config.php";

$folder_uploads = $_POST['folder_uploads'];
$id_mhs = $_POST['id_mhs'];

$nama_file='';

if (isset($_POST['btn_upload_img_profile'])) $nama_file = "img_profile";
if (isset($_POST['btn_upload_img_bg'])) $nama_file = "img_bg";

if (!file_exists("uploads/".$folder_uploads)) mkdir("uploads/".$folder_uploads);

$target_dir = "uploads/$folder_uploads/";
$target_file = $target_dir.$nama_file."_$id_mhs.jpg";
$up_error = 0;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

# ================================================================
# SWITCH FILES
# ================================================================
switch ($nama_file) {
  case 'img_profile':
    $file_size = $_FILES["file_img_profile"]["size"];
    $file_name = $_FILES["file_img_profile"]["name"];
    $tmp_name  = $_FILES["file_img_profile"]["tmp_name"];
    $nama_file_cap = "Foto Profile";
    break;
  
  case 'img_bg':
    $file_size = $_FILES["file_img_bg"]["size"];
    $file_name = $_FILES["file_img_bg"]["name"];
    $tmp_name  = $_FILES["file_img_bg"]["tmp_name"];
    $nama_file_cap = "Background Image";
    break;
  
  default: die("Error #upload Unknown nama_file. $link_back");
      
}

$file_size =intval($file_size/1000);

if (empty($tmp_name)) {
  $pesan = "<hr>Error upload. File belum ada atau karakter nama file tidak terdeteksi";
  $up_error=1;
}else{
  $cek_dim = getimagesize($tmp_name);
  $file_type = strtolower(pathinfo(basename($file_name),PATHINFO_EXTENSION));
  $panjang = $cek_dim[0];
  $lebar = $cek_dim[1];

  if($panjang<100 or $lebar<100){
    $pesan = "<hr>Error upload. Dimensi gambar terlalu kecil, <100px, atau file terdeteksi bukan gambar.";
    $up_error=1;
  }elseif($file_size>2048000){
    $pesan = "<hr>Error upload. Ukuran gambar melebihi 200kB, silahkan diperkecil dahulu.";
    $up_error=1;
  }elseif ($file_type!="jpg") {
    $pesan = "<hr>Error upload. Tipe gambar bukan .JPG, silahkan dikonversi dahulu.";
    $up_error=1;
  }
}


if (!$up_error) {
  if (move_uploaded_file($tmp_name, $target_file)) {
    $pesan = "<hr>Upload $nama_file_cap berhasil.";
  }else{
    $pesan = "<hr>Move upload file gagal.";
  }
  $pesan.="    
    <hr> - Ukuran Gambar: $panjang x $lebar pixel
    <br> - Tipe: $file_type
    <br> - Size: $file_size kB
    ";
}

$style_div = "color:green";
if($up_error) $style_div = "color:red";
?>

<div style="margin: 15px;padding: 15px;border: solid 1px #bbb; border-radius: 10px; background-color: #dff">
  <h1>Upload Result</h1>
  <div style="<?=$style_div?>">
    <?=$pesan?>
  </div>
  <hr>
  <?=$link_back?>
</div>


