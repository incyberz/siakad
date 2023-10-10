<h1>Lihat Dosen</h1>
<p class="red consolas miring">Page ini sedang proses coding...</p>
<style>
  .img-profil{
    width: 100px;
    height: 100px;
    border-radius: 50%;
    margin-bottom: 5px;
    transition:.2s;
  }
  .img-profil:hover{transform:scale(1.2)}
</style>


<?php
$nidn = $_GET['nidn'] ?? '';
$id_dosen = $_GET['id_dosen'] ?? '';

$s = "SELECT * FROM tb_dosen WHERE id='$id_dosen' OR nidn='$nidn'";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
if(mysqli_num_rows($q)==0){
  die(div_alert('danger','Data dosen tidak ditemukan.'));
}else{
  $d = mysqli_fetch_assoc($q);
  $nama_dosen = $d['nama'];
}


?>
<div class="row">
  <div class="col-lg-3 text-center">
    <img src="#" alt="foto profil dosen" class='img-profil'>
  </div>
  <div class="col-lg-9">
    <table class="table table-hover table-striped">
      <tr>
        <td>Nama Dosen</td>
        <td><?=$nama_dosen?></td>
        <td><?=$d['folder_uploads']?></td>
      </tr>
    </table>

  </div>
</div>