<?php
$judul = 'Manage Event KRS';
$undef = '<span style="color:#f77; font-style:italic">undefined</span>';
$null = '<code class=miring>null</code>';
$jumlah_mk = $null;
$registran = '<span class="kecil miring">0 of 0</span>';


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
  echo "<script>location.replace('?manage_krs&angkatan=$angkatan&id_prodi=$id_prodi')</script>";
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
    $link .= "<a class='btn btn-info btn-sm' href='?manage_krs&angkatan=$d[angkatan]'>$d[angkatan]</a> ";
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
    echo "<div><a class='btn btn-$primary mb2 mt2 btn-blocks' href='?manage_krs&angkatan=$angkatan&id_prodi=$d[id]'>$d[jenjang]-$d[nama]</a></div> ";
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
# GET Jenjang dan jumlah_max_smt from prodi
# =====================================================
$s = "SELECT a.jenjang, b.jumlah_semester FROM tb_prodi a JOIN tb_jenjang b ON a.jenjang=b.jenjang WHERE a.id=$id_prodi";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
if(mysqli_num_rows($q)==0) die('Data jenjang prodi tidak ditemukan.');
$d = mysqli_fetch_assoc($q);
$jenjang = $d['jenjang'];
$jumlah_semester = $d['jumlah_semester'];


# =====================================================
# GET ID-kalender
# =====================================================
$id_kalender = '';
$s = "SELECT id as id_kalender FROM tb_kalender WHERE angkatan='$angkatan' AND jenjang='$jenjang'";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
if(mysqli_num_rows($q)==0){
  $info_kalender = div_alert('info',"Perhatian! Anda wajib mengisi tanggal awal dan tanggal akhir KRS secara manual karena Kalender angkatan $angkatan jenjang $jenjang belum ada pada sistem SIAKAD.");
}else{
  $info_kalender = '';
  $d = mysqli_fetch_assoc($q);
  $id_kalender = $d['id_kalender'];

  $rtanggal_awal_krs = [];
  $rtanggal_akhir_krs = [];
  $rtanggal_awal_smt = [];
  $rtanggal_akhir_smt = [];

  # =====================================================
  # GET SEMESTERS DATA
  # =====================================================
  $s = "SELECT 
  a.nomor as semester_ke, 
  a.tanggal_awal as tanggal_awal_smt, 
  a.tanggal_akhir as tanggal_akhir_smt, 
  a.awal_krs, 
  a.akhir_krs 
  FROM tb_semester a WHERE a.id_kalender='$id_kalender'";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  if(mysqli_num_rows($q)==0){
    die('Data Semester belum lengkap. id_kalender: '.$id_kalender);
  }else{
    while ($d=mysqli_fetch_assoc($q)) {
      $rtanggal_awal_smt[$d['semester_ke']] = $d['tanggal_awal_smt']; 
      $rtanggal_akhir_smt[$d['semester_ke']] = $d['tanggal_akhir_smt']; 
      $rtanggal_awal_krs[$d['semester_ke']] = $d['awal_krs']; 
      $rtanggal_akhir_krs[$d['semester_ke']] = $d['akhir_krs']; 
    }
  }


}

// var_dump($rtanggal_awal_krs);

# =====================================================
# TAMPIL KRS DARI SMT 1 S.D 8
# =====================================================
include '../include/include_rprodi.php';
$tanggal_awal_show = $undef;
$tanggal_akhir_show = $undef;

$s = "SELECT a.*,
(SELECT count(1) FROM tb_krs_mk_manual WHERE id_krs_manual=a.id) as jumlah_mk,  
(SELECT sum(bobot) FROM tb_krs_mk_manual b JOIN tb_mk_manual c ON b.id_mk_manual=c.id WHERE b.id_krs_manual=a.id) as sum_sks,  
(SELECT count(1) FROM tb_mhs WHERE status_mhs=1 AND id_prodi=$id_prodi AND angkatan=$angkatan) as jumlah_mhs_aktif  
FROM tb_krs_manual a 
WHERE a.angkatan=$angkatan 
AND a.id_prodi=$id_prodi 
ORDER BY untuk_semester";

echo "<pre>$s</pre>";
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
$rid_krs = [];
$prodi = $rprodi[$id_prodi];
$registran = "<a target=_blank href='?list_mhs_aktif&keyword=$prodi-$angkatan&keyword2=$prodi-$angkatan'>0 of 0</a>"; 
$jumlah_mhs_aktif = 0;
$tanggal_awal_smt = '';
$tanggal_akhir_smt = '';
$semester_aktif = 0;
while ($d=mysqli_fetch_assoc($q)) {
  $i++;
  $id = $d['id'];
  $jumlah_mhs_aktif = $d['jumlah_mhs_aktif'];
  $rid_krs[$d['untuk_semester']] = $d['id'];

  $jumlah_mk = $d['jumlah_mk']==0 ? $null : $d['jumlah_mk'].' MK ('.$d['sum_sks'].' SKS)';
  $jumlah_registran = 0; //zzz

  $d['tanggal_awal'] = $d['tanggal_awal']=='' ? $rtanggal_awal_krs[$i] : $d['tanggal_awal'];
  $d['tanggal_akhir'] = $d['tanggal_akhir']=='' ? $rtanggal_akhir_krs[$i] : $d['tanggal_akhir'];

  $selisih_awal = strtotime('now') - strtotime($rtanggal_awal_smt[$i]);
  $selisih_akhir = strtotime('now') - strtotime($rtanggal_akhir_smt[$i]);

  $border = '';
  if($selisih_awal>=0 and $selisih_akhir<0){
    $border = 'solid 3px blue';
    $tanggal_awal_smt = $rtanggal_awal_smt[$i];
    $tanggal_akhir_smt = $rtanggal_akhir_smt[$i];
    $semester_aktif = $i;
  }

  $tr_krs.="
  <tr style='border:$border'>
    <td class= id=untuk_semester__$id>$d[untuk_semester]</td>
    <td class='editable' id=tanggal_awal__$id>$d[tanggal_awal]</td>
    <td class='editable' id=tanggal_akhir__$id>$d[tanggal_akhir]</td>
    <td>
      <a href=?manage_krs_mk_manual&id_krs_manual=$id>$jumlah_mk</a>
    </td>
    <td>$jumlah_registran</td>
  </tr>";
}


# =====================================================
# AUTO-INSERT :: CEK KRS SEMESTER YANG BELUM ADA
# =====================================================
$values = '';
for ($i=1; $i <= $jumlah_semester; $i++) { 
  if(!isset($rid_krs[$i])){
    $values .= "($angkatan,$id_prodi,$i),";
    // echo "auto set untuk semester $i<br>";
  }
}

if($values!=''){
  $values .= '__';
  $values = str_replace(',__','',$values);

  $s = "INSERT INTO tb_krs_manual (angkatan, id_prodi, untuk_semester) VALUES $values";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  echo '<script>location.reload()</script>';
  exit;

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
<style>th{text-align:left}</style>
<h1><?=$judul ?></h1>
<?=$info_kalender?>
<p>
  Berikut KRS untuk <b><a href="?manage_krs">Angkatan <?=$angkatan?></a></b>
  prodi <b><a href="?manage_krs&angkatan=<?=$angkatan?>"> <?=$nama_prodi?></a></b>
</p>
<ul>
  <li>Jumlah mahasiswa aktif saat ini : <a href="?mhs_aktif" target=_blank><?=$jumlah_mhs_aktif?> mhs</a></li>
  <li>Semester aktif: <a href="?manage_kalender&id_kalender=<?=$id_kalender?>" target=_blank>Semester <?=$semester_aktif?> dari <?=$tanggal_awal_smt?> s.d <?=$tanggal_akhir_smt?></a></li>
</ul>
<div class='kecil miring mb3'></div>
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