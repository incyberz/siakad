<?php
$judul = '<h1>Manage Kelas Peserta</h1>';
if(isset($_POST['btn_assign_kelas_peserta'])){

  $values = '';
  foreach ($_POST as $key => $value) {
    if($key=='id_kurikulum_mk' || $key=='id_dosen' || $key=='btn_assign_kelas_peserta') continue;
    $values .= ",('$_POST[id_kurikulum_mk]','$key')";

  }
  if($values!=''){
    $values = "__$values";
    $values = str_replace('__,','',$values);

    $s = "INSERT INTO tb_kelas_peserta (id_kurikulum_mk,id_kelas_angkatan) VALUES $values";
    // die($s);
    $q = mysqli_query($cn,$s) or die(mysqli_error($cn));


    echo div_alert('success',"Assign Kelas Peserta sukses.<hr><a href='?manage_kelas&id_jadwal=$_GET[id_jadwal]'>Lanjutkan Proses</a>");
    exit;
  }
}


$id_jadwal = isset($_GET['id_jadwal']) ? $_GET['id_jadwal'] : '';

if($id_jadwal==''){
  include 'modul/jadwal_kuliah/list_jadwal.php';
  exit;
}

echo "<span class=debug id=id_jadwal>$id_jadwal</span>";

$s = "SELECT 
CONCAT('JADWAL MK ',c.nama) as jadwal,
b.id as id_kurikulum_mk,
b.id_semester,
b.id_kurikulum,
d.id as id_dosen,
d.nama as dosen_koordinator,  
e.nomor as nomor_semester,   
e.id_kalender    

FROM tb_jadwal a 
JOIN tb_kurikulum_mk b on b.id=a.id_kurikulum_mk 
JOIN tb_mk c on c.id=b.id_mk 
JOIN tb_dosen d on d.id=a.id_dosen 
JOIN tb_semester e on b.id_semester=e.id 
WHERE a.id=$id_jadwal";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
if(mysqli_num_rows($q)==0) die('Data Jadwal tidak ditemukan.');
$d = mysqli_fetch_assoc($q);
$id_kurikulum = $d['id_kurikulum'];
$id_kurikulum_mk = $d['id_kurikulum_mk'];
$id_dosen = $d['id_dosen'];
$id_semester = $d['id_semester'];
$id_kalender = $d['id_kalender'];
$nomor_semester = $d['nomor_semester'];

$back_to = "Back to: 
<a href='?manage_kalender&id_kalender=$id_kalender' class=proper>manage kalender</a> | 
<a href='?manage_kurikulum&id_kurikulum=$id_kurikulum' class=proper>manage kurikulum</a> | 
<a href='?manage_jadwal&id_kurikulum_mk=$id_kurikulum_mk' class=proper>manage jadwal</a> |  
<a href='?manage_sesi&id_jadwal=$id_jadwal' class=proper>manage sesi</a> 
";


$koloms = [];
$i=0;
$tr = '';
foreach ($d as $key => $value) {
  if($key=='nomor_semester') continue;
  $koloms[$i] = str_replace('_',' ',$key);
  $debug = substr($key,0,2)=='id' ? 'debug' : 'upper';
  // echo substr($key,0,2)."<hr>";
  $tr .= "<tr class=$debug><td>$koloms[$i]</td><td>$value</td></tr>";
  $i++;
}

$tb_jadwal_info = "<div class=mb2>$back_to</div>$judul<table class=table>$tr</table>";

$default_option = '';
include 'include/option_angkatan.php';
include 'include/option_prodi.php';

?>
<?=$tb_jadwal_info ?>
<hr>
<div class="row">
  <div class="col-lg-6">
    <span class='proper subsistem'>ajax get ceklis kelas</span>
    <p>Silahkan pilih kelas mana saja yang akan menjadi kelas peserta!</p>
    <style>.blok_filter{display:flex; flex-wrap:wrap;} .blok_filter div{margin-right:10px}</style>
    <div class="blok_filter mb2">
      <div>Tahun Ajar</div>
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
    <?php include 'modul/kelas/assigned_classess.php'; ?>
  </div>
</div>


<script>
  $(function(){
    $(".filter").change(function(){
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