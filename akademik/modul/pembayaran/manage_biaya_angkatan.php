<h1>Manage Biaya Angkatan</h1>
<?php
include 'include/akademik_icons.php';

if (isset($_POST['btn_set_biaya_default'])) {
  $angkatan = $_POST['angkatan'];
  $id_prodi = $_POST['id_prodi'];
  $s = "SELECT id,nominal_default FROM tb_biaya";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  $values = '';
  
  while ($d=mysqli_fetch_assoc($q)) {
    $id = $d['id'];
    $nominal = $d['nominal_default'];
    $values .= "('$id','$angkatan','$id_prodi','$nominal'),";
    
  }
  $s = "INSERT INTO tb_biaya_angkatan (id_biaya,angkatan,id_prodi,nominal) VALUES $values".'__';
  $s = str_replace(',__','',$s);
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  echo div_alert('success', 'Set Nominal Default success. Redirecting ...');
  echo "<script>location.replace('?manage_biaya_angkatan&angkatan=$angkatan&id_prodi=$id_prodi')</script>";
  exit;

}

// echo $s;


# =====================================================
# WAJIB ADA ANGKATAN + ID_PRODI + SHIFT
# =====================================================
$angkatan = $_GET['angkatan'] ?? '';
$id_prodi = $_GET['id_prodi'] ?? '';
$shift = $_GET['shift'] ?? '';
$tr='';
$i=0;
$count_kurikulum=0;
$unsetting=0;
$last_angkatan = '';
if($angkatan==''||$id_prodi==''||$shift==''){


  $s = "SELECT 1 FROM tb_biaya";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  $count_biaya = mysqli_num_rows($q);

  $s = "SELECT angkatan FROM tb_angkatan";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  $link='';
  while ($d=mysqli_fetch_assoc($q)) {
    $angkatan = $d['angkatan'];
    $s2 = "SELECT a.id as id_prodi,a.singkatan,a.jenjang,
    (SELECT count(1) FROM tb_biaya_angkatan WHERE angkatan=$angkatan AND id_prodi=a.id AND shift='pagi') count_pagi, 
    (SELECT count(1) FROM tb_biaya_angkatan WHERE angkatan=$angkatan AND id_prodi=a.id AND shift='sore') count_sore 
    FROM tb_prodi a ";
    $q2 = mysqli_query($cn,$s2) or die(mysqli_error($cn));
    while ($d2=mysqli_fetch_assoc($q2)) {
      $i++;
      $count_kurikulum++;
      $border = $last_angkatan!=$angkatan ? 'style="border-top:solid 8px #faf"' : '';
      $prodi = $d2['singkatan'];
      $id_prodi = $d2['id_prodi'];
      $jenjang = $d2['jenjang'];
      $gradasi = $d2['jenjang']=='S1' ? 'biru' : 'hijau';
      $href = "?manage_biaya_angkatan&angkatan=$angkatan&id_prodi=$id_prodi";
      $showof_pagi = $d2['count_pagi'] == $count_biaya ? $img_aksi['check'] : "<span class='red small tebal'>$d2[count_pagi] of $count_biaya</span>";
      $showof_sore = $d2['count_sore'] == $count_biaya ? $img_aksi['check'] : "<span class='red small tebal'>$d2[count_sore] of $count_biaya</span>";
      $unsetting+=($count_biaya-$d2['count_pagi']);
      $unsetting+=($count_biaya-$d2['count_sore']);
      $tr.="
        <tr $border class='gradasi-$gradasi'>
          <td>$i</td>
          <td>$angkatan</td>
          <td>$jenjang-$prodi</td>
          <td>
            <a class='btn btn-success btn-sm' href='$href&shift=pagi' target=_blank>Pagi</a>
            $showof_pagi
          </td>
          <td>
            <a class='btn btn-primary btn-sm' href='$href&shift=sore' target=_blank>Sore</a>
            $showof_sore
          </td>
        </tr>
      ";

      $last_angkatan = $angkatan;

      // echo "<div><a class='btn btn-$primary mb2 mt2 ' href='?manage_biaya_angkatan&angkatan=$angkatan&id_prodi=$d[id]'>$d[jenjang]-$d[singkatan]</a></div> ";
    }
    $link .= "<a class='btn btn-info btn-sm' href='?manage_biaya_angkatan&angkatan=$d[angkatan]'>$d[angkatan]</a> ";
  }
  // echo "<h4>Seting Biaya untuk Angkatan:</h4><div class=wadah>$link</div>";

  $allsetting = $count_biaya * $count_kurikulum * 2; // 2 shift
  $settinged = $allsetting-$unsetting;
  $persen_setting = $allsetting==0 ? 0 : round(($settinged/$allsetting)*100,2);

  $green_color = intval($persen_setting/100*155);
  $red_color = intval((100-$persen_setting)/100*255);
  $rgb = "rgb($red_color,$green_color,50)";
  echo "
    <div class='kecil miring consolas' style='color:$rgb'>Progres Manage : $persen_setting% | $settinged of $allsetting Biaya Angkatan</div>
    <div class=progress>
      <div class='progress-bar progress-bar-danger' style='width:$persen_setting%;background:$rgb;'></div>
    </div>

    <table class=table>
      <thead>
        <th>No</th>
        <th>Angkatan</th>
        <th>Prodi</th>
        <th colspan=2>Manage Biaya</th>
      </thead>
      $tr
    </table>
  ";

  $s = "INSERT INTO tb_unsetting (kolom,unsetting,total,untuk) VALUES ('biaya_angkatan',$unsetting,$allsetting,'bau') ON DUPLICATE KEY UPDATE unsetting=$unsetting,total=$allsetting";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  
  
  exit;
}



# =====================================================
# WAJIB ADA ANGKATAN
# =====================================================
$angkatan = $_GET['angkatan'] ?? '';
if($angkatan==''){
  $s = "SELECT angkatan FROM tb_angkatan";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  $link='';
  while ($d=mysqli_fetch_assoc($q)) {
    $link .= "<a class='btn btn-info btn-sm' href='?manage_biaya_angkatan&angkatan=$d[angkatan]'>$d[angkatan]</a> ";
  }
  echo "<h4>Seting Biaya untuk Angkatan:</h4><div class=wadah>$link</div>";
  exit;
}

# =====================================================
# WAJIB ADA ID_PRODI
# =====================================================
$id_prodi = $_GET['id_prodi'] ?? '';
if($id_prodi==''){
  echo "<div>Untuk angkatan $angkatan prodi:</div>";
  $s = "SELECT id,nama,jenjang FROM tb_prodi";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  while ($d=mysqli_fetch_assoc($q)) {
    $d['nama'] = strtoupper($d['nama']);
    $primary = $d['jenjang']=='S1' ? 'primary' : 'success';
    echo "<div><a class='btn btn-$primary mb2 mt2 ' href='?manage_biaya_angkatan&angkatan=$angkatan&id_prodi=$d[id]'>$d[jenjang]-$d[nama]</a></div> ";
  }
  exit;
}

$s = "SELECT a.nama,a.jenjang,a.singkatan, b.id as id_kurikulum  
FROM tb_prodi a 
JOIN tb_kurikulum b ON b.id_prodi=a.id 
JOIN tb_kalender c ON b.id_kalender=c.id 
where a.id=$id_prodi 
AND c.angkatan=$angkatan 
";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
if(mysqli_num_rows($q)==0) die('Data prodi tidak ditemukan.');
if(mysqli_num_rows($q)>1) die('Duplikat data kurikulum terdeteksi.');
$d = mysqli_fetch_assoc($q);
$nama_prodi = "$d[jenjang]-$d[nama]";
$prodi = "$d[singkatan]";
$id_kurikulum = "$d[id_kurikulum]";

# =====================================================
# WAJIB ADA SHIFT
# =====================================================
$shift = $_GET['shift'] ?? '';
if($shift==''){
  echo "<div>Untuk angkatan $angkatan prodi $prodi shift:</div>";
  $s = "SELECT shift FROM tb_shift";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  while ($d=mysqli_fetch_assoc($q)) {
    $shift = $d['shift'];
    $primary = $shift=='pagi' ? 'success' : 'primary';
    echo "<div><a class='btn btn-$primary mb2 mt2 proper' href='?manage_biaya_angkatan&angkatan=$angkatan&id_prodi=$id_prodi&shift=$shift'>kelas $shift</a></div> ";
  }
  exit;
}

echo "<span class=debug>id_prodi: <span id=id_prodi>$id_prodi</span> | angkatan: <span id=angkatan>$angkatan</span></span>";



$s = "SELECT a.*,
(SELECT nominal FROM tb_biaya_angkatan WHERE id_biaya=a.id and angkatan=$angkatan and id_prodi=$id_prodi and shift='$shift') as nominal, 
(SELECT besar_cicilan FROM tb_biaya_angkatan WHERE id_biaya=a.id and angkatan=$angkatan and id_prodi=$id_prodi and shift='$shift') as besar_cicilan 
FROM tb_biaya a ORDER BY no";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));

$tr_biaya="
<thead>
  <th>No</th>
  <th>Komponen Biaya</th>
  <th class=proper>Nominal $shift</th>
  <th>Besar Cicilan</th>
</thead>
";
$i=0;
$null = '<code class=miring>null</code>';
while ($d=mysqli_fetch_assoc($q)) {
  $i++;
  $nominal = $d['nominal']=='' ? $d['nominal_default'] : $d['nominal'];
  $def = $d['nominal']=='' ? '<code>(nominal default)</code>' : '<span class="biru consolas">'.$img_aksi['check'].'</span>';
  $besar_cicilan = $d['besar_cicilan']=='' ? $null : $d['besar_cicilan'];
  $id = $d['id'];
  $idx = $angkatan."__$id_prodi"."__$id";



  $tr_biaya.="
  <tr>
    <td class= id=no__$id>$d[no]<span class=debug>$d[id]</span></td>
    <td class= id=nama__$id>$d[nama] $def</td>
    <td class='editable text-right consolas' id=nominal__$idx>$nominal</td>
    <td class='editable text-right consolas' id=besar_cicilan__$idx>$besar_cicilan</td>
  </tr>";
}


echo "
<p>Berikut adalah Nominal Biaya untuk 
  <a href='?manage_biaya_angkatan'><b><u>Angkatan $angkatan</u></b></a> prodi 
  <a href='?manage_biaya_angkatan&angkatan=$angkatan'><b><u>$nama_prodi</u></b></a> kelas 
  <a href='?manage_biaya_angkatan&angkatan=$angkatan&id_prodi=$id_prodi'><b><u class=proper id=shift>$shift</u></b></a> 
  <span class=consolas> | </span> 
  <a href='?test_pembayaran&id_kurikulum=$id_kurikulum&shift=$shift' target=_blank onclick='return confirm(\"Ingin Login As Mhs untuk mengetes Seting Pembayaran ini?\")'><b><u class=proper>Test Pembayaran</u></b></a> 
</p>
";

?>

<table class="table table-striped">
  <?=$tr_biaya?>
</table>
<div class="kecil miring abu">Jika besar cicilan = <code>null</code> maka pembayaran tidak dapat dicicil.</div>
<div class="kecil miring abu">Biaya dengan <code>nominal default</code> artinya sama dengan nominal pada <a href='?manage_komponen_biaya' target=_blank>Komponen Biaya</a>.</div>




















<script>
  $(function(){
    $(".editable").click(function(){
      let tid = $(this).prop('id');
      let rid = tid.split('__');
      let kolom = rid[0];
      let angkatan = rid[1];
      let id_prodi = rid[2];
      let id_biaya = rid[3];

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
      if(isi_baru==0){
        alert('Silahkan masukan nominal yang benar!');
        return;
      }else if(isi_baru>=100000000){
        alert('Nominal harus kurang dari 100 juta. Silahkan coba kembali!');
        return;
      }
      
      let shift = $('#shift').text();
      let link_ajax = `ajax_akademik/ajax_set_biaya_angkatan.php?nominal=${isi_baru}&kolom=${kolom}&angkatan=${angkatan}&id_prodi=${id_prodi}&id_biaya=${id_biaya}&shift=${shift}`;
      console.log(link_ajax);
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