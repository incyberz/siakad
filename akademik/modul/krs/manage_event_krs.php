<?php
$judul = 'Manage Event KRS';
$undef = '<span style="color:#f77; font-style:italic">undefined</span>';


if (isset($_POST['btn_set_krs_default'])) {
  $angkatan = $_POST['angkatan'];
  $id_prodi = $_POST['id_prodi'];
  $s = "SELECT id,nominal_default FROM tb_krs_manual";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  $values = '';
  
  while ($d=mysqli_fetch_assoc($q)) {
    $id = $d['id'];
    $nominal = $d['nominal_default'];
    $values .= "('$id','$angkatan','$id_prodi','$nominal'),";
    
  }
  $s = "INSERT INTO tb_krs_mk_manual (id_krs,angkatan,id_prodi,nominal) VALUES $values".'__';
  $s = str_replace(',__','',$s);
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  echo div_alert('success', 'Set Nominal Default success. Redirecting ...');
  echo "<script>location.replace('?event_krs&angkatan=$angkatan&id_prodi=$id_prodi')</script>";
  exit;

}

// echo $s;


# =====================================================
# BUTUH ANGKATAN
# =====================================================
$angkatan = isset($_GET['angkatan']) ? $_GET['angkatan'] : '';
if($angkatan==''){
  $s = "SELECT angkatan FROM tb_angkatan";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  $link='';
  while ($d=mysqli_fetch_assoc($q)) {
    $link .= "<a class='btn btn-info btn-sm' href='?event_krs&angkatan=$d[angkatan]'>$d[angkatan]</a> ";
  }
  echo "<h4>Seting Event KRS untuk Angkatan:</h4><div class=wadah>$link</div>";
  exit;
}

# =====================================================
# BUTUH ID_PRODI
# =====================================================
$id_prodi = isset($_GET['id_prodi']) ? $_GET['id_prodi'] : '';
if($id_prodi==''){
  echo "<h3>Event KRS angkatan $angkatan untuk prodi:</h3>";
  $s = "SELECT id,nama,jenjang FROM tb_prodi";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  while ($d=mysqli_fetch_assoc($q)) {
    $d['nama'] = strtoupper($d['nama']);
    $primary = $d['jenjang']=='S1' ? 'primary' : 'success';
    echo "<div><a class='btn btn-$primary mb2 mt2 btn-blocks' href='?event_krs&angkatan=$angkatan&id_prodi=$d[id]'>$d[jenjang]-$d[nama]</a></div> ";
  }
  exit;
}else{
  $s = "SELECT nama,jenjang FROM tb_prodi where id=$id_prodi";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  if(mysqli_num_rows($q)==0) die('Data prodi tidak ditemukan.');
  $d = mysqli_fetch_assoc($q);
  $nama_prodi = "$d[jenjang]-$d[nama]";
}

echo "<span class=debug>id_prodi: <span id=id_prodi>$id_prodi</span> | angkatan: <span id=angkatan>$angkatan</span></span>";



# =====================================================
# TAMPIL KRS DARI SMT 1 S.D 8
# =====================================================
$tanggal_awal_show = $undef;
$tanggal_akhir_show = $undef;

$s = "SELECT a.* 
FROM tb_krs_manual a 
WHERE a.angkatan=$angkatan 
AND a.id_prodi=$id_prodi 
ORDER BY untuk_semester";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));

$tr_krs="
<thead>
  <th>Semester</th>
  <th>Tanggal Awal</th>
  <th>Tanggal Akhir</th>
  <th>Jumlah MK</th>
  <th>Registran</th>
</thead>
";
$i=0;
$null = '<code class=miring>null</code>';
while ($d=mysqli_fetch_assoc($q)) {
  $i++;
  $nominal = $d['nominal']=='' ? $d['nominal_default'] : $d['nominal'];
  $def = $d['nominal']=='' ? '<code>(nominal default)</code>' : '<span class="biru consolas">(custom)</span>';
  $besar_cicilan = $d['besar_cicilan']=='' ? $null : $d['besar_cicilan'];
  $id = $d['id'];
  $idx = $angkatan."__$id_prodi"."__$id";



  $tr_krs.="
  <tr>
    <td class= id=no__$id>$d[no]<span class=debug>$d[id]</span></td>
    <td class= id=nama__$id>$d[nama] $def</td>
    <td class='editable text-right consolas' id=nominal__$idx>$nominal</td>
    <td class='editable text-right consolas' id=besar_cicilan__$idx>$besar_cicilan</td>
  </tr>";
}

$sum_nominal = 1; /// zzz test
if($sum_nominal==0){
  // set to default
  $autoset = "
  <div class=wadah>
    <div class=mb2>
      <div class='alert alert-info tebal'>Data KRS angkatan: $angkatan id_prodi: $id_prodi masih kosong.</div> 
      <div class='wadah biru tebal'>Silahkan Set KRS Default kemudian edit nominal satu-persatu sesuai SK tiap angkatan!</div> 
    </div>
    <form method=post>
      <input class=debug name=angkatan value=$angkatan>
      <input class=debug name=id_prodi value=$id_prodi>
      <button class='btn btn-info' name=btn_set_krs_default onclick='return confirm(\"Set Semua Nominal KRS ke Default?\")'>Set KRS Default</button>
    </form>
  </div>";
  die($autoset);
  $reset = '';
}else{
  // reset to default
  $reset = "<div class=wadah>Anda sudah setting biaya angkatan $angkatan prodi $nama_prodi secara manual. <hr><a href='#' class='btn btn-danger'>Reset Semua KRS ke Default</a></div>";
  $reset = ''; // aborted fitur
  $autoset = '';
}



?>
<h1><?=$judul ?></h1>

<p>
  Berikut KRS untuk <a href="?event_krs"><b>Angkatan</b> : <?=$angkatan?></a>
  <a href="?event_krs&angkatan=<?=$angkatan?>"><b>Prodi</b> : <?=$nama_prodi?></a>
</p>
<table class="table table-striped">
  <?=$tr_krs?>
</table>
<!-- <div class="kecil miring abu">Jika besar cicilan = <code>null</code> maka pembayaran tidak dapat dicicil.</div> -->




















<script>
  $(function(){
    $(".editable").click(function(){
      let tid = $(this).prop('id');
      let rid = tid.split('__');
      let kolom = rid[0];
      let angkatan = rid[1];
      let id_prodi = rid[2];
      let id_krs = rid[3];

      let isi = $(this).text();
      let isi_baru = prompt('Masukan nominal:',isi);

      // VALIDASI CANCEL/EMPTY
      if(isi_baru===null) return;
      isi_baru = isi_baru.trim();
      if(isi_baru==isi) return;

      // ALLOW NULL
      // isi_baru = isi_baru==='' ? 'NULL' : isi_baru;
      
      // VALIDASI VALUE
      isi_baru = parseInt(isi_baru);
      if(isi_baru==0 || isi_baru % 1000 != 0){
        alert('Masukan nominal kelipatan 1000. Silahkan coba kembali!');
        return;
      }else if(isi_baru>=100000000){
        alert('Nominal harus kurang dari 100 juta. Silahkan coba kembali!');
        return;
      }
      
      let link_ajax = `ajax_akademik/ajax_set_krs_angkatan.php?nominal=${isi_baru}&kolom=${kolom}&angkatan=${angkatan}&id_prodi=${id_prodi}&id_krs=${id_krs}`;
      // return;

      $.ajax({
        url:link_ajax,
        success:function(a){
          if(a.trim()=='sukses'){
            $("#"+tid).text(isi_baru);
            $("#"+tid).addClass('biru tebal');

          }else{
            console.log(a);
            alert('Gagal mengubah data.');
          }
        }
      })


    });    
  })
</script>