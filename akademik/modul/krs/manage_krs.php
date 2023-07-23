<h1>MANAGE KRS</h1>
<p>Pada menu ini Anda dapat Seting Penanggalan KRS.</p>
<?php
$tnow = strtotime('now');
$undef = '<code>undefined</code>';
$null = '<code>null</code>';


$id_semester = $_GET['id_semester'] ?? '';
if($id_semester==''){
  $s = "SELECT * FROM tb_kalender ORDER BY angkatan,jenjang";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  $tr = '';
  while ($d=mysqli_fetch_assoc($q)) {
    
    $s2 = "SELECT a.id as id_semester, a.nomor as no_smt,
    (SELECT count(1) FROM tb_kurikulum_mk WHERE id_semester=a.id) jumlah_mk,
    a.tanggal_awal as tanggal_awal_smt, 
    a.tanggal_akhir as tanggal_akhir_smt 
    FROM tb_semester a 
    WHERE a.id_kalender=$d[id] ORDER BY a.nomor";
    $q2 = mysqli_query($cn,$s2) or die(mysqli_error($cn));
    $tr_smt='';
    while ($d2=mysqli_fetch_assoc($q2)) {
      $tawal = strtotime($d2['tanggal_awal_smt']);
      $takhir = strtotime($d2['tanggal_akhir_smt']);
      $type = 'warning';
      $border = '';
      if($tawal<$tnow AND $takhir>=$tnow){
        $type = 'primary';
        $border = 'style="border:solid 3px blue;"';
        $info = '[aktif]';
      }else if($tnow<$tawal){
        $info = '(next)';
        $type = 'info';
      }else{
        $info = '(lampau)';
      }

      if($d2['jumlah_mk']<1){
        $info = 'Blm ada MK';
        $type = 'danger';
      } 

      $tr_smt.="
      <a href='?manage_krs&id_semester=$d2[id_semester]' class='btn btn-sm btn-$type' $border>
        smt-$d2[no_smt] <div>$info</div>
      </a>";
    }

    $gradasi_jenjang = $d['jenjang']=='D3' ? 'hijau' : 'biru';

    $tr.= "
    <div class='wadah gradasi-$gradasi_jenjang'>
      <div class=mb1>Kalender $d[angkatan]-$d[jenjang]</div>
      <div>$tr_smt</div>
    </div>
    ";
  }

  die($tr);  
}






















# ===========================================================
# JIKA ID SEMESTER SUDAH TERPILIH
# ===========================================================
$info_keuangan = "
<div class='wadah gradasi-kuning'>
  <h4>Syarat Biaya KRS</h4>
  <div class='tebal darkred'>Perhatian! Syarat Biaya untuk Pengambilan KRS masih default (mengikuti syarat umum).</div>
  <div>
    Syarat Biaya KRS saat ini adalah:
    <ol class='darkblue tebal'>
      <li>Biaya Registrasi Semester (100%)</li>
      <li>Biaya Pendidikan Semester (100%)</li>
    </ol>
    Jika mahasiswa sudah membayar biaya-biaya diatas maka diperbolehkan untuk Pengambilan KRS.
    <div class='wadah mt2'>
      <div class=mb2>Jika ingin mengelola syarat <u>per angkatan atau per prodi</u>, silahkan Manage Syarat Biaya!</div>
      <a class='btn btn-info btn-sm' href='?manage_syarat_biaya&untuk=KRS'>Manage Syarat Biaya KRS</a>
    </div>
  </div> 
</div>
";

$s = "SELECT a.id as id_semester, a.*, b.angkatan, b.jenjang, b.id as id_kalender,
(SELECT count(1) FROM tb_kurikulum_mk WHERE id_semester=a.id) jumlah_mk 
FROM tb_semester a 
JOIN tb_kalender b ON a.id_kalender=b.id 
WHERE a.id=$id_semester";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
if(mysqli_num_rows($q)==0) die('Data semester tidak ditemukan.');
$d=mysqli_fetch_assoc($q);
$manage_semester = "<a class='btn btn-primary' href='?manage_semester&id_semester=$d[id_semester]'>Penanggalan Semester</a>";
$jenjang = $d['jenjang'];
$angkatan = $d['angkatan'];
$semester = $d['nomor'];
$id_kalender = $d['id_kalender'];

$tawal = strtotime($d['tanggal_awal']);
$takhir = strtotime($d['tanggal_akhir']);
if($tawal<$tnow AND $takhir>=$tnow){
  $info = '[aktif]';
}else if($tnow<$tawal){
  $info = '(next)';
}else{
  $info = '(lampau)';
}


$awal_krs = $d['awal_krs']=='' ? $null : date('d-M-Y',strtotime($d['awal_krs']));
$akhir_krs = $d['akhir_krs']=='' ? $null : date('d-M-Y',strtotime($d['akhir_krs']));

$link = $d['awal_krs']=='' ? div_alert('danger',"Penanggalan default KRS masih kosong. Silahkan Manage Penanggalan Semester ini terlebih dahulu!<hr>$manage_semester") : '';

if($d['awal_krs']==''){
  $info_krs = '';
}else{
  $tkrs_awal = strtotime($d['awal_krs']);
  $tkrs_akhir = strtotime($d['akhir_krs']);
  if($tkrs_awal<$tnow AND $tkrs_akhir>=$tnow){
    $info_krs = div_alert('success','<h3 class="darkblue tebal">KRS sedang berlangsung.</h3>Saat ini KRS sudah dapat diakses oleh mahasiswa. Jika ada kekeliruan silahkan Manage Penanggalan Semester kembali!<hr>'.$manage_semester);
  }else if($tnow<$tkrs_awal){
    $info_krs = div_alert('info','Belum masuk masa KRS. Saat ini mahasiswa belum bisa mengisi KRS. Jika ada kekeliruan silahkan Manage Penanggalan Semester kembali!<hr>'.$manage_semester);
  }else{
    $info_krs = div_alert('danger','Masa KRS sudah lewat. Jika ada kekeliruan silahkan Manage Penanggalan Semester kembali!<hr>'.$manage_semester);
  }
}

$info_mk = '';
$pilihan_mk = '';
if($d['jumlah_mk']<1){
  $info_mk = div_alert('danger',"Belum ada MK pada Kurikulum ini. Silahkan tambahkan MK pada <a href='?manage_kurikulum'>Manage Kurikulum</a>");
}else{

  $s2 = "SELECT 
  id as id_prodi, 
  nama as nama_prodi, 
  singkatan as singkatan_prodi  
  FROM tb_prodi WHERE jenjang='$jenjang'";
  $q2 = mysqli_query($cn,$s2) or die(mysqli_error($cn));
  while ($d2=mysqli_fetch_assoc($q2)) {
    $id_prodi = $d2['id_prodi'];
    $s3 = "SELECT id as id_kurikulum FROM tb_kurikulum WHERE id_kalender=$id_kalender AND id_prodi=$id_prodi";
    $q3 = mysqli_query($cn,$s3) or die(mysqli_error($cn));
    if(mysqli_num_rows($q3)==0) die('id_kurikulum tidak ditemukan.');
    $d3=mysqli_fetch_assoc($q3);
    $id_kurikulum = $d3['id_kurikulum'];

    $s3 = "SELECT a.*, 
    b.nama as nama_mk,  
    b.kode as kode_mk,  
    (b.bobot_teori + b.bobot_praktik) bobot   
    FROM tb_kurikulum_mk a 
    JOIN tb_mk b ON a.id_mk=b.id 
    WHERE id_semester=$id_semester 
    AND id_kurikulum=$id_kurikulum 
    ORDER BY b.nama 
    ";
    $q3 = mysqli_query($cn,$s3) or die(mysqli_error($cn));
    $mk = '';
    $i=0;
    $total_bobot=0;
    while ($d3=mysqli_fetch_assoc($q3)) {
      $i++;
      $total_bobot += $d3['bobot'];
      $mk.= "
      <tr>
        <td>$i</td>
        <td>$d3[nama_mk] | $d3[kode_mk]</td>
        <td>$d3[bobot] SKS</td>
      </tr>
      ";
    }
    $row_total = "
    <tr style='border-top: solid 3px #ccf'>
      <td colspan=2 class='tebal kanan'>Jumlah SKS</td>
      <td class='tebal'>$total_bobot SKS</td>
    </tr>";
    $tb_mk = "<table class=table>$mk$row_total</table>";

    $pilihan_mk.= "
    <div class='wadah gradasi-hijau'>
      <h4 class='tebal darkblue'>Pilihan MK</h4> 
      <div class='tebal mb2 darkblue'>Semester $semester ~ <a href='?manage_kurikulum&id_kurikulum=$id_kurikulum' target=_blank>Kurikulum $jenjang-$d2[singkatan_prodi]-$angkatan</a></div>
      $tb_mk
      <a href='?test_krs&id_kurikulum=$id_kurikulum' target=_blank class='btn btn-info btn-sm'>Test KRS</a>
    </div>";
  }
}

echo "
<div class=wadah>
  <a href='?manage_krs'><h4 class='tebal'>Semester $d[nomor] kalender $d[angkatan]-$d[jenjang]</h4></a>
  <div class='wadah bg-white'>
    <div>Tanggal KRS : $awal_krs s.d $akhir_krs</div>
  </div>
  $link
  $info_krs
  $info_keuangan
  $info_mk
  $pilihan_mk
</div>
";
