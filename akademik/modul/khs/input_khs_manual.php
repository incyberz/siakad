<h1>Input KHS Manual</h1>
<style>th{text-align:left}</style>
<?php
$nim = '';
$nama_mhs = '';
$gender = '';
$kelas = '';
$nm = '';
$hm = '';

if(isset($_POST['btn_simpan_nilai'])){
  echo '<pre>';
  var_dump($_POST);
  echo '</pre>';

  $nim = $_POST['nim'];
  $nama_mhs = $_POST['nama_mhs'];
  $gender = $_POST['gender'];
  $kelas = $_POST['kelas'];
  $nm = $_POST['nm'];
  $hm = $_POST['hm'];

}

$rprodi = ['TI','RPL','SI','MI','KA'];
$rgender = ['-','L','P'];

$select_gender = '<select name=gender class=form-control>';
for ($i=0; $i < count($rgender); $i++) $select_gender.="<option>$rgender[$i]</option>";
$select_gender .= '</select>';



?>

<form method=post>
  <table class="table">
    <thead>
      <th>NIM</th>
      <th>Nama Mahasiswa</th>
      <th>JK</th>
      <th>Kelas</th>
      <th>NM</th>
      <th>Huruf</th>
    </thead>
    <tr>
      <td><input class="form-control" value='<?=$nim?>' name=nim maxlength=8 minlength=8  required></td>
      <td><input class="form-control" value='<?=$nama_mhs?>' name=nama_mhs maxlength=30 minlength=3 required></td>
      <td><input class="form-control" value='<?=$gender?>' name=gender type=text minlength=1 maxlength=1 required></td>
      <td><input class="form-control" value='<?=$kelas?>' name=kelas></td>
      <td><input class="form-control" value='<?=$nm?>' name=nm type=number min=0 max=100 required></td>
      <td><input class="form-control" value='<?=$hm?>' name=hm type=text minlength=1 maxlength=1 required></td>
    </tr>
  </table>  
  <button class="btn btn-primary btn-block" name=btn_simpan_nilai>Simpan Nilai</button>
</form>
