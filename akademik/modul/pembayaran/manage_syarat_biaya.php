<h1>Manage Syarat Biaya</h1>
<p>Berikut adalah ketentuan-ketentuan untuk syarat pembayaran bagi mahasiswa.</p>
<?php
$untuk = $_GET['untuk'] ?? '';
if($untuk==''){
  $revent = ['KRS','UTS','UAS','TA','SIDANG','WISUDA'];
  $pilih_events = '';
  foreach ($revent as $event) {
    $pilih_events.="<a class='btn btn-info' href='?manage_syarat_biaya&untuk=$event'>$event</a> ";
  }

  echo "<div class=wadah><div class=mb2>Untuk event:</div>$pilih_events</div>";


  exit;
}

$angkatan = isset($_GET['angkatan']) ? $_GET['angkatan'] : '';
if($angkatan==''){
  $s = "SELECT angkatan FROM tb_angkatan";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  $link='';
  while ($d=mysqli_fetch_assoc($q)) {
    $link .= "<a class='btn btn-info btn-sm' href='?manage_syarat_biaya&untuk=$untuk&angkatan=$d[angkatan]'>$d[angkatan]</a> ";
  }
  echo "<h4>Seting Biaya $untuk untuk Angkatan:</h4><div class=wadah>$link</div>";
  exit;
}

$id_prodi = isset($_GET['id_prodi']) ? $_GET['id_prodi'] : '';
if($id_prodi==''){
  echo "<div>Seting Biaya $untuk untuk angkatan $angkatan prodi:</div>";
  $s = "SELECT id,nama,jenjang FROM tb_prodi";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  while ($d=mysqli_fetch_assoc($q)) {
    $d['nama'] = strtoupper($d['nama']);
    $primary = $d['jenjang']=='S1' ? 'primary' : 'success';
    echo "<div><a class='btn btn-$primary mb2 mt2 btn-blocks' href='?manage_syarat_biaya&untuk=$untuk&angkatan=$angkatan&id_prodi=$d[id]'>$d[jenjang]-$d[nama]</a></div> ";
  }
  exit;
}

$s = "SELECT a.nama,a.singkatan,a.jenjang,b.jumlah_semester FROM tb_prodi a JOIN tb_jenjang b on a.jenjang=b.jenjang where a.id=$id_prodi";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
if(mysqli_num_rows($q)==0) die('Data prodi tidak ditemukan.');
$d = mysqli_fetch_assoc($q);
$nama_prodi = "$d[jenjang]-$d[nama]";
$jenjang = "$d[jenjang]";
$jumlah_semester = "$d[jumlah_semester]";
$prodi = "$d[singkatan]";

echo "<span class=debug>id_prodi: <span id=id_prodi>$id_prodi</span> | angkatan: <span id=angkatan>$angkatan</span></span>";

if($untuk=='KRS'){
  $s = "SELECT a.*,
  (SELECT nominal FROM tb_biaya_angkatan WHERE id_biaya=a.id AND angkatan=$angkatan AND id_prodi=$id_prodi) nominal 
  FROM tb_biaya a 
  WHERE a.untuk_semester is not null 
  order by a.untuk_semester,a.id";
  // echo $s;
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  $tr='';
  $last_smt = '';
  while ($d=mysqli_fetch_assoc($q)) {
    if($d['untuk_semester']>$jumlah_semester) continue;

    $border = $last_smt!=$d['untuk_semester'] ? 'style="border-top:solid 6px #fcf"' : '';
    $nominal = $d['nominal']=='' ? number_format($d['nominal_default']) : number_format($d['nominal']);
    $nominal_info = $d['nominal']=='' ? ' ~ <span class="darkred kecil miring pointer text_zoom">(default)</span>' : ' ~ <span class="darkblue kecil miring pointer text_zoom">(custom angkatan)</span>';
    $tr.="
    <tr $border>
      <td>$d[untuk_semester]</td>
      <td>$d[nama] <a href='?manage_biaya_angkatan&angkatan=$angkatan&id_prodi=$id_prodi' onclick='return confirm(\"Ingin menuju Manage Biaya Angkatan?\")' target=_blank>$nominal_info</a></td>
      <td>$nominal</td>
    </tr>
    ";
    $last_smt = $d['untuk_semester'];
  }
  echo "
  <table class=table>
    <thead>
      <th>Semester</th>
      <th>Syarat Biaya <a href='?manage_syarat_biaya'>$untuk</a> <a href='?manage_syarat_biaya&untuk=KRS&angkatan=$angkatan'>$jenjang-$prodi</a>-<a href='?manage_syarat_biaya&untuk=KRS'>$angkatan</a></th>
      <th>Nominal</th>
    </thead>
    $tr
  </table>";
}


?>
<style>th{text-align:left}.text_zoom{transition:.2s}.text_zoom:hover{letter-spacing:1px;font-weight:bold}</style>