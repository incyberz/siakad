<?php
$judul = '<h1>MANAGE JADWAL KULIAH</h1>';
if(isset($_POST['btn_set_dosen'])){
  $s = "SELECT 1 FROM tb_jadwal WHERE id_kurikulum_mk=$_POST[id_kurikulum_mk]";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  if(mysqli_num_rows($q)==0){
    $s = "INSERT INTO tb_jadwal (id_kurikulum_mk,id_dosen,keterangan) VALUES ($_POST[id_kurikulum_mk],$_POST[id_dosen],'$_POST[keterangan]')";
    $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
    die("<div class='alert alert-success'>Set Dosen Koordinator berhasil.
    <hr>
    <a class='btn btn-info' href='?manage_kurikulum&id_kurikulum=$_POST[id_kurikulum]'>Back to Manage Kurikulum</a> 
    <a class='btn btn-primary' href='?manage_jadwal&id_kurikulum_mk=$_POST[id_kurikulum_mk]'>Lanjutkan Manage Jadwal</a></div>
    ");
  }else{
    // sudah ada jadwal
    // cek jika dosen nya sama
    $s = "UPDATE tb_jadwal set id_dosen=$_POST[id_dosen] where id_kurikulum_mk=$_POST[id_kurikulum_mk]";
    $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
    die("<div class='alert alert-success'>Update Dosen Koordinator berhasil.<hr><a class='btn btn-primary' href='?manage_jadwal&id_kurikulum_mk=$_POST[id_kurikulum_mk]'>Lanjutkan Manage Jadwal</a></div>");

  }
}

$id_kurikulum_mk = isset($_GET['id_kurikulum_mk']) ? $_GET['id_kurikulum_mk'] : '';

if($id_kurikulum_mk==''){
  include 'modul/kurikulum/list_kurikulum_mk.php';
  exit;
}else{
  # ==========================================================
  # SELECT BLOK MK
  # ==========================================================
  $s = "SELECT 
  c.nama as nama_kurikulum,
  c.basis,
  b.nomor as semester_ke,
  d.kode as kode_mk,
  d.nama as nama_mk,
  c.id as id_kurikulum,
  c.id_kalender,
  a.id as id_semester_mk,
  a.id_semester,
  a.id_mk,
  (SELECT id from tb_jadwal where id_kurikulum_mk=a.id) as id_jadwal,  
  (SELECT id_dosen from tb_jadwal where id_kurikulum_mk=a.id) as id_dosen,  
  (
    SELECT k.nama from tb_jadwal j 
    JOIN tb_dosen k on k.id=j.id_dosen 
    where j.id_kurikulum_mk=a.id) as nama_dosen  

  FROM tb_kurikulum_mk a 
  JOIN tb_semester b on a.id_semester=b.id 
  JOIN tb_kurikulum c on a.id_kurikulum=c.id 
  JOIN tb_mk d on a.id_mk=d.id 
  WHERE a.id=$id_kurikulum_mk 
  ";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  if(mysqli_num_rows($q)==0) die('<span class=red>Data Kurikulum MK tidak ditemukan.');
  $d = mysqli_fetch_assoc($q);
  $keterangan = "Jadwal $d[nama_mk] / Semester $d[semester_ke] / $d[nama_kurikulum]";

  $id_kalender = $d['id_kalender'];
  $id_kurikulum = $d['id_kurikulum'];
  $id_semester = $d['id_semester'];
  $id_jadwal = $d['id_jadwal'];
  $id_dosen = $d['id_dosen'];
  $nama_dosen = $d['nama_dosen'];

  $back_to = "<div class=mb2>Back to : 
    <a href='?manage_kalender&id_kalender=$id_kalender' class=proper>Manage Kalender</a> | 
    <a href='?manage_kurikulum&id_kurikulum=$id_kurikulum' class=proper>Manage kurikulum</a> | 
    <a href='?manage_semester&id_semester=$id_semester' class=proper>Manage semester</a> | 
  </div>
  ";


  $koloms_mk = [];
  $i=0;
  $tr_mk = '';
  foreach ($d as $key => $value) {
    if($key=='nama_dosen') continue;
    $koloms_mk[$i] = str_replace('_',' ',$key);
    $debug = substr($key,0,2)=='id' ? 'debug' : 'upper';
    // echo substr($key,0,2)."<hr>";
    $tr_mk .= "<tr class=$debug><td>$koloms_mk[$i]</td><td>$value</td></tr>";
    $i++;
  }

  # ==========================================================
  # OUTPUT BLOK MK
  # ==========================================================
  $pilih_mk_lain = "<div class='btn-link kecil'>Opsi : <a href='?manage_kurikulum&id_kurikulum=$d[id_kurikulum]'>Pilih MK lain</a></div>";
  $blok_mk = "<table class=table>$tr_mk</table>$pilih_mk_lain";





  # ==========================================================
  # BLOK DOSEN
  # ==========================================================
  $s = "SELECT 
  a.id as id_dosen,
  a.nama as nama_dosen
  FROM tb_dosen a 
  WHERE a.id != '$id_dosen' 
  ORDER BY a.nama
  ";

  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  $option_dosen = '';
  while ($d=mysqli_fetch_assoc($q)) {
    $selected = 0 ? 'selected' : '';
    $option_dosen .= "<option class='pilihan pilihan-dosen' value=$d[id_dosen] $selected>$d[nama_dosen] ~ $d[id_dosen]</option>";
  }

  $link_opsi = "<div class='btn-link kecil ml2'>Opsi : <a class='proper' href='?master&p=dosen'>manage dosen</a></div>";
  $p = $nama_dosen=='' ? "
    <p>Silahkan pilih dosen (KOORDINATOR) untuk mata kuliah ini!</p>" : "
    <div class='wadah bg-white'>Dosen Koordinator : <span class=proper id='nama_dosen'>$nama_dosen</span></div>
    <p>Silahkan pilih kembali untuk mengubahnya.</p>
  ";

  $primary_set_dosen = $nama_dosen=='' ? 'primary' : 'warning';
  $btn_set_sesi = $nama_dosen=='' ? '' : "
    <div class=btn-link>
      <a href='?manage_kelas&id_jadwal=$id_jadwal' class='btn btn-primary btn-block'>Set Peserta Kuliah</a>
    </div>
    <div class=btn-link>
      <a href='?manage_sesi&id_jadwal=$id_jadwal' class='btn btn-primary btn-block'>Set Jadwal Sesi Kuliah</a>
    </div>
  ";
  $btn_set_dosen = "<div class='btn-link'><button class='btn btn-$primary_set_dosen btn-block hideit' name='btn_set_dosen' id='btn_set_dosen'>Set Dosen Koordinator</button></div>";

  $script = "
    <script>
      $(function(){
        $('#id_dosen').change(function(){
          let id = parseInt($(this).val());
          console.log(id);
          if(id){
            $('#btn_set_dosen').fadeIn();
          }else{
            $('#btn_set_dosen').fadeOut();
          }
        })
      })
    </script>
  ";

  $blok_dosen = $option_dosen=='' 
    ? "<div class='alert alert-danger'>Belum ada DATA DOSEN yang aktif (bersedia mengajar).<hr>$link_opsi</div>" 
    : "
    <input class=debug name=id_kurikulum value=$id_kurikulum>
    $p
    <select class='form-control' id=id_dosen name=id_dosen>
      <option value=0 selected>-- Pilih --</option>
      $option_dosen
    </select>
    $script
    $link_opsi
    $btn_set_dosen
    $btn_set_sesi
  ";




}





?>
<style>.btn-link{margin-top:10px;} .ml2{margin-left: 10px}</style>
<?=$back_to?>
<?=$judul?>
<div class="wadah gradasi-hijau">
  <h3>Mata Kuliah</h3>
  <form method=post>
    <input class=debug name='keterangan' value='<?=$keterangan?>'>
    <input class=debug name='id_kurikulum_mk' value='<?=$id_kurikulum_mk?>'>
    <?=$blok_mk ?>
    <hr>
    <h3>Dosen Koordinator</h3>
    <?=$blok_dosen ?>
  </form>
</div>
<?=$back_to?>




