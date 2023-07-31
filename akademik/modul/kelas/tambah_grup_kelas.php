<style>th{text-align:left}.tb_semester{background:#ffffff77}</style>
<?php
$judul = "<h1>Buat Grup Kelas</h1>";
$id_kurikulum = $_GET['id_kurikulum'] ?? '';
if(!$id_kurikulum || $id_kurikulum<1) die('<script>location.replace("?manage_kelas")</script>');


# ==============================================================
# GET KURIKULUM DATA
# ==============================================================
$s = "SELECT 
CONCAT('Kurikulum ',c.jenjang,'-',b.singkatan,'-',c.angkatan) as nama_kurikulum, 
c.jumlah_semester,
b.id as id_prodi, 
b.singkatan as prodi,
c.id as id_kalender, 
c.angkatan, 
c.jenjang, 
a.id as id_kurikulum 

FROM tb_kurikulum a 
JOIN tb_prodi b ON b.id=a.id_prodi 
JOIN tb_kalender c ON c.id=a.id_kalender  
JOIN tb_jenjang d ON d.jenjang=c.jenjang  
WHERE a.id='$id_kurikulum'";
$q = mysqli_query($cn, $s)or die(mysqli_error($cn));
if(!mysqli_num_rows($q)) die('Data kurikulum tidak ditemukan.');
$d = mysqli_fetch_assoc($q);
$jumlah_semester = $d['jumlah_semester'];
$nama_kurikulum = $d['nama_kurikulum'];
$id_kalender = $d['id_kalender'];
$id_prodi = $d['id_prodi'];
$angkatan = $d['angkatan'];
$jenjang = $d['jenjang'];
$prodi = $d['prodi'];

echo "$judul<p>Proses assign angkatan, prodi, dan shift-kelas pada <a href='?manage_grup_kelas&id_kurikulum=$id_kurikulum'>Kurikulum $jenjang-$prodi-$angkatan</a></p>";

$s = "SELECT a.*,
(SELECT count(1) FROM tb_kelas_ta WHERE kelas=a.kelas) jumlah_kelas_ta 
FROM tb_kelas a 
WHERE a.angkatan=$angkatan 
AND a.id_prodi=$id_prodi 
ORDER BY kelas";
$q = mysqli_query($cn, $s)or die(mysqli_error($cn));
if(mysqli_num_rows($q)==0){
  // belum ada Grup Kelas pada angkatan $angkatan prodi $prodi 
  $tb_kelas = div_alert('danger',"Belum ada Grup Kelas pada angkatan $angkatan prodi $prodi | $link_buat_grup_kelas");
}else{
  $tr = '';
  $i=0;
  while ($d=mysqli_fetch_assoc($q)) {
    $i++;
    $tr.="
    <tr class='kelas-$d[id_prodi]-$d[angkatan]-$d[id_jalur]-$d[shift]'>
      <td>$i</td>
      <td>$d[kelas]</td>
    </tr>
    ";
  }
  $tb_kelas = "
  <table class=table>
    $tr
  </table>";
}



$jalur = 'REG';
$shift = 'P';
?>
<style>#tb_kelas_baru input{font-size:300%; height:70px; font-family:consolas; color:blue;}</style>
<div class="wadah">
  <form method=post>
    <div class="form-group">
      <label for="">Jalur Daftar</label>
      <select name="id_jalur" id="id_jalur" class='form-control assign_kelas'>
        <option value="1">REG</option>
        <option value="2">KIP</option>
      </select>
    </div>

    <div class="form-group">
      <label for="">Shift Kelas</label>
      <select name="id_jalur" id="id_jalur" class='form-control assign_kelas'>
        <option value="pagi">P ~ Pagi</option>
        <option value="sore">S ~ Sore</option>
      </select>
      
    </div>

    <input class=debug id=prodi value='<?=$prodi?>'>
    <input class=debug id=angkatan value='<?=$angkatan?>'>
    <input class=debug id=jalur value='<?=$jalur?>'>
    <input class=debug id=shift value='<?=$shift?>'>
    <input class=debug name=kelas id=kelas>
    <input class="debug" value='<?=$prodi?>-<?=$angkatan?>' />

    <?=$tb_kelas?>

    <div class="form-group">
      <label for="">Nama Kelas baru:</label>
      <table class=table id=tb_kelas_baru>
        <tr>
          <td>
            <input class="form-control kanan" value='<?=$prodi?>-<?=$angkatan?>-REG-P' disabled/>
          </td>
          <td>
            <input class="form-control" value='1'/>
          </td>
        </tr>

      </table>
    </div>

    <button class='btn btn-primary btn-block'>Tambah</button>

  </form>
</div>

<script>
  $(function(){
    $('.assign_kelas').change(function(){
      let prodi = $('#prodi').text();
      let angkatan = $('#angkatan').text();
      let jalur = $('#jalur').text();
      let shift = $('#shift').text();
    })
  })
</script>