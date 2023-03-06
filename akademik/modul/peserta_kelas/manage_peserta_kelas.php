<h1>Manage Peserta Kelas</h1>
<?php
if(isset($_POST['btn_assign_peserta_kelas'])){

  $values = '';
  foreach ($_POST as $key => $value) {
    if($key=='id_kurikulum_mk' || $key=='id_dosen' || $key=='btn_assign_peserta_kelas') continue;
    $values .= ",('$_POST[id_kurikulum_mk]','$_POST[id_dosen]','$key')";

  }
  if($values!=''){
    $values = "__$values";
    $values = str_replace('__,','',$values);

    $s = "INSERT INTO tb_peserta_kelas (id_kurikulum_mk,id_dosen,kelas) VALUES $values";
    // die($s);
    $q = mysqli_query($cn,$s) or die(mysqli_error($cn));


    echo div_alert('success',"Assign Peserta Kelas sukses.<hr><a href='?manage_peserta&id_jadwal=$_GET[id_jadwal]'>Lanjutkan Proses</a>");
    exit;
  }
}


$id_jadwal = isset($_GET['id_jadwal']) ? $_GET['id_jadwal'] : die(erid('id_jadwal'));
echo "<span class=debug id=id_jadwal>$id_jadwal</span>";
$s = "SELECT 
a.keterangan,
b.id as id_kurikulum_mk,
d.id as id_dosen,
d.nama as dosen_koordinator  

FROM tb_jadwal a 
JOIN tb_kurikulum_mk b on b.id=a.id_kurikulum_mk 
JOIN tb_mk c on c.id=b.id_mk 
JOIN tb_dosen d on d.id=a.id_dosen  
WHERE a.id=$id_jadwal";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
if(mysqli_num_rows($q)==0) die('Data Jadwal tidak ditemukan.');
$d = mysqli_fetch_assoc($q);
$id_kurikulum_mk = $d['id_kurikulum_mk'];
$id_dosen = $d['id_dosen'];

$koloms = [];
$i=0;
$tr = '';
foreach ($d as $key => $value) {
  if($key=='nama_dosen') continue;
  $koloms[$i] = str_replace('_',' ',$key);
  $debug = substr($key,0,2)=='id' ? 'debug' : 'upper';
  // echo substr($key,0,2)."<hr>";
  $tr .= "<tr class=$debug><td>$koloms[$i]</td><td>$value</td></tr>";
  $i++;
}

$tb_jadwal_info = "<table class=table>$tr</table>";

$default_option = '';
include 'include/option_angkatan.php';
include 'include/option_prodi.php';

?>
<?=$tb_jadwal_info ?>
<hr>
<div class="row">
  <div class="col-lg-6">
    <p>Silahkan pilih kelas mana saja yang akan menjadi peserta kelas!</p>
    <style>.blok_filter{display:flex; flex-wrap:wrap;} .blok_filter div{margin-right:10px}</style>
    <div class="blok_filter mb2">
      <div>Angkatan</div>
      <div><select class='form-control filter' id="angkatan"><?=$option_angkatan?></select></div>
      <div>Prodi</div>
      <div><select class='form-control filter' id="id_prodi"><?=$option_prodi?></select></div>
    </div>
    <form method="post">
      <input class=debug name=id_dosen value=<?=$id_dosen?>>
      <input class=debug name=id_kurikulum_mk value=<?=$id_kurikulum_mk?>>
      <div class="blok_pilihan_kelas"></div>
    </form>
    <div kelas='kecil'>
      Opsi : <a href="?master&p=kelas">Manage kelas</a>
    </div>
  </div>
  <div class="col-lg-6">
    <?php include 'modul/peserta_kelas/assigned_classess.php'; ?>
  </div>
</div>


<script>
  $(function(){
    $(".filter").change(function(){
      console.log('zzz');
      let angkatan = $("#angkatan").val();
      let id_prodi = $("#id_prodi").val();
      let id_jadwal = $("#id_jadwal").text();
      let link_ajax = `ajax_akademik/ajax_get_ceklis_kelas.php?angkatan=${angkatan}&id_prodi=${id_prodi}&id_jadwal=${id_jadwal}&`;
      $.ajax({
        url: link_ajax,
        success: function(a){
          $(".blok_pilihan_kelas").html(a);
        }
      })
    });
    $(".filter").change();
  })
</script>