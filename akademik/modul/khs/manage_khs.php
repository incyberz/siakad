<h1>Manage Nilai KHS</h1>
<style>th{text-align:left}</style>
<?php
$angkatan = $_GET['angkatan'] ?? '';
if($angkatan==''){
  include 'include/include_rangkatan.php';
  echo '<h3 class=mb3>Silahkan pilih angkatan!</h3><div class=mb2>Untuk angkatan:</div>';
  foreach ($rangkatan as $key => $angkatan) {
    echo "<a class='btn btn-info' href='?manage_khs&angkatan=$angkatan'>$angkatan</a> ";
  }
  exit;
}
echo "<span class=debug id=angkatan>$angkatan</span>";


$id_prodi = $_GET['id_prodi'] ?? '';
include 'include/include_rid_prodi.php';
if($id_prodi==''){
  echo "<h3 class=mb3>Silahkan pilih prodi!</h3><div class=mb2>Untuk angkatan <b><a href='?manage_khs'>$angkatan</a></b> prodi:</div>";
  foreach ($rid_prodi as $key => $id_prodi) {
    echo "<a class='btn btn-info' href='?manage_khs&angkatan=$angkatan&id_prodi=$id_prodi'>$rprodi[$id_prodi]</a> ";
  }
  exit;
}
$prodi = $rprodi[$id_prodi] ?? "<code>id_prodi: $id_prodi</code>";
$nama_prodi = $rnama_prodi[$id_prodi] ?? "<code>id_prodi: $id_prodi</code>";
echo "<span class=debug id=id_prodi>$id_prodi</span>";
echo "<span class=debug id=prodi>$prodi</span>";
echo "<span class=debug id=nama_prodi>$nama_prodi</span>";

// SELECT KURIKULUM
$s = "SELECT a.id as id_kurikulum FROM tb_kurikulum a 
JOIN tb_kalender b ON a.id_kalender=b.id 
WHERE b.angkatan='$angkatan' 
AND a.id_prodi=$id_prodi 
";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
if(mysqli_num_rows($q)==0) die(div_alert('danger',"Belum ada Kurikulum untuk prodi $prodi angkatan $angkatan. Silahkan Manage Kurikulum terlebih dahulu!"));
if(mysqli_num_rows($q)>1) die(div_alert('danger',"Duplikat Kurikulum untuk prodi $prodi angkatan $angkatan. Silahkan hubungi DB-Admin!"));
$d = mysqli_fetch_assoc($q);
$id_kurikulum = $d['id_kurikulum'];



// SELECT NIM
$nim = $_GET['nim'] ?? '';
if($nim==''){
  echo "<h3 class=mb3>Silahkan ketik atau pilih NIM!</h3><div class=mb2>Angkatan <b><a href='?manage_khs'>$angkatan</a></b> prodi <a href='?manage_khs&angkatan=$angkatan'>$prodi</a>, untuk NIM:</div>";
  
  echo "
    <div style='display: inline-block;max-width: 150px'><input class='form-control tebal biru consolas tengah' type=text minlength=8 maxlength=8 required name=nim id=nim style='font-size: 16pt'></div> <a href='#' class='btn btn-primary btn-sms' id=btn_next>Next</a>
    <div><small><i>Masukan 8 digit NIM, lalu klik next!</i></small></div>
  
    <script>
      $(function(){
        $('#nim').keyup(function(){
          let angkatan = $('#angkatan').text();
          let id_prodi = $('#id_prodi').text();
          $('#btn_next').prop('href',`?manage_khs&angkatan=${angkatan}&id_prodi=${id_prodi}&nim=` + $(this).val());
        })
      })
    </script>
  ";


  $s = "SELECT nim FROM tb_mhs WHERE angkatan=$angkatan AND id_prodi=$id_prodi ORDER BY nim";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  $count = mysqli_num_rows($q);
  echo "<div class='mt4 mb2'>Pilihan $count NIM:</div>";
  if($count==0){
    echo div_alert('danger',"Data mhs angkatan $angkatan prodi $prodi tidak ada.");
  }else{
    $div = '';
    $i=0;
    while ($d=mysqli_fetch_assoc($q)) {
      $i++;
      $red = substr($angkatan,2,2)==substr($d['nim'],2,2) ? '' : 'red';
      $div .= "<div><a href='?manage_khs&angkatan=$angkatan&id_prodi=$id_prodi&nim=$d[nim]'><span class=$red>$d[nim]</span></a></div>";
    }

    echo "<div class=flexy>$div</div>";

  }
exit;
}


echo "<div class=mb2>
Angkatan <a class=tebal href='?manage_khs'>$angkatan</a> 
prodi <a class=tebal href='?manage_khs&angkatan=$angkatan&id_prodi=$id_prodi'>$prodi</a> 
untuk NIM <a class=tebal href='?manage_khs&angkatan=$angkatan&id_prodi=$id_prodi'>$nim</a>
:</div>";

$s = "SELECT a.* FROM tb_mhs a WHERE nim='$nim'";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
if(mysqli_num_rows($q)==0) die(div_alert('danger',"Tidak ada mahasiswa dengan NIM $nim ~ <span class='miring abu'>Silahkan klik NIM diatas untuk mengganti!</span>"));

$d = mysqli_fetch_assoc($q);
$semester_mhs = $d['semester_manual'];
?>
<ul>
  <li>Nama: <?=$d['nama']?> <a href="?login_as&nim=<?=$d['nim']?>" target=_blank><img src="../assets/img/icons/login_as.png" height=20px></a></li>
  <li>Kelas: <?=$d['kelas_manual']?></li>
  <li>Semester: <?=$semester_mhs?></li>
  <li><a href='?manage_kurikulum&id_kurikulum=<?=$id_kurikulum?>'>Kurikulum <?=$prodi?>-<?=$angkatan?></a></li>
</ul>
<?php



// SELECT MK KURIKULUM
$s = "SELECT 
a.id as id_kurikulum_mk,
a.*,
c.nomor as no_smt,
d.kode as kode_mk,
d.nama as nama_mk,
(d.bobot_teori + d.bobot_praktik) bobot, 
(SELECT CONCAT(na,'|',hm,'|',id) FROM tb_nilai WHERE id_kurikulum_mk=a.id and nim='$nim') data_nilai,
(SELECT tanggal_disetujui_mhs FROM tb_nilai WHERE id_kurikulum_mk=a.id and nim='$nim') tanggal_disetujui_mhs
FROM tb_kurikulum_mk a 
JOIN tb_kurikulum b ON a.id_kurikulum=b.id 
JOIN tb_semester c ON a.id_semester=c.id 
JOIN tb_mk d ON a.id_mk=d.id 
WHERE b.id='$id_kurikulum' 
ORDER BY c.nomor,d.nama,d.kode  
";
// die($s);
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
if(mysqli_num_rows($q)==0) die(div_alert('danger',"Belum ada MK untuk Kurikulum $prodi angkatan $angkatan. Silahkan Manage Kurikulum terlebih dahulu!"));
$tr='';
$i=0;
$last_smt = '';
$null = '<span class="miring kecil abu">NULL</span>';
while ($d=mysqli_fetch_assoc($q)) {
  $i++;
  $border = $last_smt!=$d['no_smt'] ? 'border-top: solid 9px #aaf;margin-top:15px;' : '';
  $bg = $d['no_smt']%2==0 ? "style='background:#fdf;$border'" : "style='$border'";
  $data_nilai = $d['data_nilai']=='' ? [$null,$null] : explode('|',$d['data_nilai']);

  $id_nilai = '';
  if($d['no_smt']>$semester_mhs){
    $editable = '';
    $status = '<span class="kecil miring abu">mhs blm masuk smt ini</span>';
  }else{
    if($d['data_nilai']==''){
      if(strpos('salt'.strtoupper($d['kode_mk']),'MBKM')){
        $status = "<code>MK MBKM</code>";
        $editable = '';
      }else{
        $editable = 'editable';
        $status = "<code>belum input nilai</code> | <a href='?drop_kurikulum_mk&id_kurikulum_mk=$d[id_kurikulum_mk]' target=_blank><span class='merah kecil'>Drop MK</span></a>";
      }
    }else{

      $id_nilai = $data_nilai[2];

      if($d['tanggal_disetujui_mhs']==''){
        $status = '<code>belum disetujui mhs</code>';
        $editable = 'editable';
      }else{
        $status = '<span class="consolas small green">sudah disetujui mhs</span>';
        $editable = '';
      }
    }
  }

  $nama_mk = strtoupper($d['nama_mk']);
  $kode_mk = strtoupper($d['kode_mk']);


  $tr.="
  <tr $bg>
    <td>$i</td>
    <td>$d[no_smt]</td>
    <td>$nama_mk | $kode_mk<span class=debug> id:$d[id_kurikulum_mk]</span></td>
    <td>$d[bobot]</td>
    <td class='debug' id=nilai2__$id_nilai>$data_nilai[0]</td>
    <td class='$editable' id=nilai__$id_nilai>$data_nilai[0]</td>
    <td class='debug' id=hm2__$id_nilai>$data_nilai[1]</td>
    <td class='$editable' id=hm__$id_nilai>$data_nilai[1]</td>
    <td>$status</td>
  </tr>
  ";
  $last_smt=$d['no_smt'];
  
}

$thead = '
<thead>
  <th>No</th>
  <th>Smt</th>
  <th>MK</th>
  <th>Bobot</th>
  <th class=debug>Debug</th>
  <th>Nilai</th>
  <th class=debug>Debug</th>
  <th>Huruf</th>
  <th>Status</th>
</thead>
';

$ket = "
<div>
  <div>Keterangan Status Nilai</div>
  <ul>
    <li><code>belum disetujui mhs</code> : mahasiswa belum melihat KHS dan menyetujui nilai-nilainya</li>
    <li><code>disetujui mhs</code> : nilai tidak dapat lagi diedit</li>
  </ul>
</div>
";

echo "<table class='table'>$thead$tr</table>$ket";
















































?>
<script>
  $(function(){
    $('.editable').click(function(){
      let tid = $(this).prop('id');
      let rid = tid.split('__');
      let id = rid[1];
      console.log(tid,id);
    })
  })
</script>