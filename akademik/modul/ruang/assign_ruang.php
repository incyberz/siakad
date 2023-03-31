<?php
if(isset($_POST['btn_assign'])){
  // echo '<pre>';
  // var_dump($_POST);
  // echo '</pre>';

  $values = '__';
  $jumlah_assigned_ruang=0;
  foreach ($_POST as $key => $value) {
    if(strpos("a$key",'cb__')){
      $rkey = explode('__',$key);
      $values .= ",($rkey[1],$_POST[id_sesi_kuliah],$_POST[id_sesi_ruang])";
      $jumlah_assigned_ruang++;
    }
  }
  $values = str_replace('__,','',$values);

  $s = "INSERT INTO tb_assign_ruang 
  (id_ruang,id_sesi_kuliah,id_sesi_ruang) VALUES 
  $values";
  // die($s);
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  echo "<div class='alert alert-success'>Assign sebanyak $jumlah_assigned_ruang Ruang Kelas Sukses.<hr><a class='btn btn-primary' href='?manage_sesi&id_jadwal=$_POST[id_jadwal]'>Kembali ke Manage Sesi</a> | <a class='btn btn-info' href='?assign_ruang&id_sesi_kuliah=$_POST[id_sesi_kuliah]'>Lihat hasil</a></div>";
  exit;
}

if(isset($_POST['btn_drop'])){
  $s = "DELETE FROM tb_assign_ruang where id_sesi_kuliah=$_POST[id_sesi_kuliah]";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  echo "<div class='alert alert-info'>Assign Ruang berhasil di drop. Silahkan pilih kembali ruangan!</div>";
}












$id_sesi_kuliah = isset($_GET['id_sesi_kuliah']) ? $_GET['id_sesi_kuliah'] : die(div_alert('danger',"Tidak bisa diakses secara langsung<hr>$btn_back"));



$s = "SELECT * from tb_assign_ruang a 
where a.id=$id_sesi_kuliah";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
$d=mysqli_fetch_assoc($q);


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
g.id as id_mk,
g.nama as nama_mk,
(g.bobot_teori + g.bobot_praktik) as bobot,
(SELECT count(1) from tb_assign_ruang where id_sesi_kuliah=a.id) as jumlah_ruang, 
(
  SELECT i.nama from tb_assign_ruang h 
  join tb_sesi_ruang i on h.id_sesi_ruang=i.id where id_sesi_kuliah=a.id limit 1
  ) as sesi_ruang 

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
$id_mk = $d['id_mk'];

$semester = "$d[no_semester] / $d[nama_kurikulum]";
$back_to = "Back to: <a href='?manage_sesi&id_jadwal=$id_jadwal'>Manage Sesi</a>";


$kotaks = '';
$s = "SELECT b.*  
from tb_assign_ruang a 
join tb_ruang b on a.id_ruang=b.id 
where id_sesi_kuliah=$id_sesi_kuliah";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
if(mysqli_num_rows($q)){
  while ($r=mysqli_fetch_assoc($q)) {
    $kotaks.="
      <div class='kotak gradasi-hijau text-center'>
          $r[nama]
      </div>
    ";
  }

  $tr_manage = "
    <tr>
      <td>Sesi Ruang</td>
      <td>
        $d[sesi_ruang]
      </td>
    </tr>

    <tr>
      <td>Ruangan</td>
      <td>
        <div class=flexy>
          $kotaks
        </div>
      </td>
    </tr>

    <tr>
      <td>&nbsp;</td>
      <td>
        <button class='btn btn-danger btn-block' id=btn_drop name=btn_drop onclick=\"return confirm('Yakin untuk Drop Ruangan dan memilih lagi?')\">Drop Ruangan dan Re-Assign</button>
      </td>
    </tr>

  ";

}else{

  // belum ada assign ruang
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
            <div class='kotak' style='padding:0'>
              <label style='display:block;padding:5px 5px 5px 15px; margin:0;cursor:pointer;'>
                <input type='checkbox' name='cb__$r[id]' id='cb__$r[id]' class=cb_ruang disabled>
                $r[nama]
              </label>
            </div>
    ";
  }

  $tr_manage = "
    <tr>
      <td>Sesi Ruang</td>
      <td>
        <select class='form-control gradasi-merah' name='id_sesi_ruang' id='id_sesi_ruang'>
          <option value='0'>-- Pilih --</option>
          $opt_sesi_ruang
        </select>
      </td>
    </tr>

    <tr>
      <td>Ruangan</td>
      <td>
        <div class=flexy>
          $kotaks
        </div>
      </td>
    </tr>

    <tr>
      <td>&nbsp;</td>
      <td>
        <button class='btn btn-primary btn-block' id=btn_assign name=btn_assign disabled>Assign</button>
      </td>
    </tr>

  ";

}




?>

<style>
  .kotak{
    border: solid 1px #ccc;
    border-radius: 5px;
    width: 200px;
    padding: 5px 15px;
  }
</style>

<?=$back_to?>
<h1>Assign Ruangan pada Sesi Kuliah</h1>
<form method=post>
  <table class='table table-hover table-dark'>
    <tr>
      <td>Sesi</td>
      <td>
        <?=$sesi?>
        <div class="debug">
          id_sesi_kuliah:<input name=id_sesi_kuliah value=<?=$id_sesi_kuliah?>>
          id_jadwal:<input name=id_jadwal value=<?=$id_jadwal?>>
        </div>
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

    <?=$tr_manage?>

  </table>
</form>




<script>
  $(function(){
    $('#id_sesi_ruang').click(function(){
      if($(this).val()=='0'){
        $('.cb_ruang').prop('checked',false);
        $('.cb_ruang').prop('disabled',true);
        $('#btn_assign').prop('disabled',true);
        $(this).addClass('gradasi-merah');
        $(this).removeClass('gradasi-hijau');
      }else{
        $('.cb_ruang').prop('disabled',false);
        $(this).removeClass('gradasi-merah');
        $(this).addClass('gradasi-hijau');
      }
    })
    
    $('.cb_ruang').click(function(){
      let r = document.getElementsByClassName('cb_ruang');
      let jumlah_cek = 0;
      for (let i = 0; i < r.length; i++) {
        if(r[i].checked==true){
          jumlah_cek++;
        }
      }
      let disabled_btn_assign = jumlah_cek>0?false:true;
      $('#btn_assign').prop('disabled',disabled_btn_assign);
      // console.log($(this).prop('checked'));
    })
    

  })
</script>