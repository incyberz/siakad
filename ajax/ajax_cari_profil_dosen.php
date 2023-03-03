<style type="text/css">.baris{background: linear-gradient(#ffd,#dfd);margin: 15px; padding: 15px; border: solid 1px #ccc}</style>
<style type="text/css">.bingkai{aborder: solid 1px #ccc; }</style>
<?php 
$keyword = $_GET['keyword'];

include "../config.php";
$s = "SELECT * FROM tb_dosen where nama_dosen like '%$keyword%' or nidn like '%$keyword%' order by nama_dosen";
$q = mysqli_query($cn, $s) or die("Tidak bisa mengakses data dosen. ".mysqli_error($cn));
if(mysqli_num_rows($q)==0){
  echo "<div class='col-lg-3 col-md-6 d-flex align-items-stretch'>Data tidak ditemukan.</div>";
}else{
  while($d=mysqli_fetch_assoc($q)){
    $id_dosen = $d['id_dosen'];
    $nama_dosen = ucwords(strtolower(trim($d['nama_dosen'])));
    $nidn = $d['nidn'];
    $gelar_depan = ucwords(strtolower(trim($d['gelar_depan'])));
    $gelar_belakang = $d['gelar_belakang'];
    $zid_prodi = $d['id_prodi'];
    $jabatan_akademik = $d['jabatan_akademik'];
    $email_dosen = $d['email_dosen'];

    $nama_dosen_show = $nama_dosen;
    if($gelar_depan!="") $nama_dosen_show = $gelar_depan." $nama_dosen_show";
    if($gelar_belakang!="") $nama_dosen_show = "$nama_dosen_show, ".trim($gelar_belakang);

    $nama_prodi = ["","S1-Teknik Informatika", "S1-Rekayasa Perangkat Lunak","S1-Sistem Informasi","D3-Manajemen Informatika","D3-Komputerisasi Akuntansi"];

    if($jabatan_akademik=='') $jabatan_akademik="-";
    if($email_dosen=='') $email_dosen="-";

    $img = "assets/img/dosen/$id_dosen.jpg";
    if(!file_exists("../".$img)) $img = "assets/img/dosen/dosen_na.jpg";

    echo "
    <div class='row baris'>
      <div class='col-lg-1'>&nbsp;</div>
      <div class='col-lg-3 text-left'>
        <a href='dosen/?id_dosen=$id_dosen' target='_blank'>
          <img src='$img' class='rounded-circle' height='150px' style='border:solid 5px #ddd; margin:5px 0 10px 0'>
        </a>
      </div>
      <div class='col-lg-7 text-left'>
        <h5><a href='dosen/?id_dosen=$id_dosen' target='_blank'>$nama_dosen_show</a></h5>
        ~ NIDN: $nidn
        <br>~ Homebase Prodi: $nama_prodi[$zid_prodi]
        <br>~ Jabatan Akademik: $jabatan_akademik
        <br>~ Email: $email_dosen

      </div>
    </div>
    ";
  }
}

?>

