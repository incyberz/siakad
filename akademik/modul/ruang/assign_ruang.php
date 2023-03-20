<?php
if(isset($_POST['btn_assign'])){
  // die(var_dump($_POST));
  $s = "INSERT INTO tb_kurikulum_mk (id_semester,id_mk,id_kurikulum) VALUES ($_POST[id_semester],$_POST[id_mk],$_POST[id_kurikulum])";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  echo "<div class='alert alert-success'>Assign MK Sukses<hr><a class='btn btn-success' href='?manage_kurikulum&id_kurikulum=$_POST[id_kurikulum]'>kembali ke Kurikulum</a></div>";
  exit;
}

$id_sesi_kuliah = isset($_GET['id_sesi_kuliah']) ? $_GET['id_sesi_kuliah'] : die(div_alert('danger',"Tidak bisa diakses secara langsung<hr>$btn_back"));

$s = "SELECT 
a.id as id_sesi_kuliah,
a.id_jadwal,
a.pertemuan_ke,
a.nama as nama_sesi,
a.id_dosen, 
a.tanggal_sesi,
c.id_kurikulum_mk,
d.id_semester,
d.id_kurikulum,
e.nomor as no_semester,
b.nama as nama_dosen,
f.nama as nama_kurikulum,
g.nama as nama_mk,
(g.bobot_teori + g.bobot_praktik) as bobot,
(SELECT count(1) from tb_assign_ruang where id_sesi_kuliah=a.id) as jumlah_ruang 

from tb_sesi_kuliah a 
join tb_dosen b on b.id=a.id_dosen 
join tb_jadwal c on c.id=a.id_jadwal 
join tb_kurikulum_mk d on d.id=c.id_kurikulum_mk 
join tb_semester e on e.id=d.id_semester 
join tb_kurikulum f on f.id=d.id_kurikulum 
join tb_mk g on g.id=d.id_mk 
where a.id=$id_sesi_kuliah";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
$d=mysqli_fetch_assoc($q);

$sesi = "$d[pertemuan_ke]  / $d[nama_sesi] / $d[nama_mk]";

$tsesi = strtotime($d['tanggal_sesi']);
$jam_keluar = date('H:i',($tsesi + $d['bobot']*50*60));
$tanggal_sesi = $nama_hari[date('w',$tsesi)].', '.date('d M Y / H:i',$tsesi).' s.d '.$jam_keluar;
$id_jadwal = $d['id_jadwal'];
$id_semester = $d['id_semester'];
$id_kurikulum = $d['id_kurikulum'];

$semester = "$d[no_semester] / $d[nama_kurikulum]";

$opt_sesi_ruang='';
$s = "SELECT * from tb_sesi_ruang";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
while ($r=mysqli_fetch_assoc($q)) {
  $opt_sesi_ruang.="<option value=$r[id]>$r[nama]</option>";
}

$kotaks='';
$s = "SELECT * from tb_ruang";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
while ($r=mysqli_fetch_assoc($q)) {
  $kotaks.="
          <div class='kotak'>
            <input type='checkbox' name='$r[id]' id='$r[id]'>
            <label for='$r[id]'>$r[nama]</label>
          </div>
  ";
}


?>
<h1>Assign Ruangan pada Sesi Kuliah</h1>
<form method=post>
  <table class='table table-hover table-dark'>
    <tr>
      <td>Sesi</td>
      <td>
        <?=$sesi?>
        <input class=debug name=id_semester value=<?=$id_semester?>>
        <input class=debug name=id_kurikulum  id=id_kurikulum value=<?=$id_kurikulum?>>
      </td>
    </tr>

    <tr>
      <td>Semester</td>
      <td><?=$semester?></td>
    </tr>

    <tr>
      <td>Pengajar</td>
      <td><?=$d['nama_dosen'] ?> / <?=$d['bobot'] ?> SKS</td>
    </tr>

    <tr>
      <td>Tanggal Sesi</td>
      <td><?=$tanggal_sesi ?></td>
    </tr>

    <tr>
      <td>Sesi Ruang</td>
      <td>
        <select class="form-control" name="id_sesi_ruang" id="id_sesi_ruang"><?=$opt_sesi_ruang?></select>
      </td>
    </tr>

    <tr class=debug>
      <td>ID MK</td>
      <td>
        <input name='id_mk' id='id_mk'>
      </td>
    </tr>

    <tr>
      <td>Ruangan</td>
      <td>
        <style>
          .kotak{
            border: solid 1px #ccc;
            border-radius: 5px;
            width: 200px;
            padding: 5px 15px;
          }
        </style>
        <div class=flexy>
          <?=$kotaks?>
          <div class='kotak'>
            <input type="checkbox" name="cek1" id="cek1">
            <label for="cek1">R.101</label>
          </div>
        </div>
      </td>
    </tr>

    <tr>
      <td>&nbsp;</td>
      <td>
        <button class='btn btn-primary btn-block' id=btn_assign name=btn_assign>Assign</button>
      </td>
    </tr>

  </table>
</form>




<script>
  $(function(){

    

  })
</script>