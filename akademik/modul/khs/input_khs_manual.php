<h1>Input KHS <span class=debug>Manual</span></h1>
<?php
$angkatan = $_GET['angkatan'] ?? '';
if($angkatan==''){
  include 'include/include_rangkatan.php';
  echo '<div class=mb2>Untuk angkatan:</div>';
  foreach ($rangkatan as $key => $angkatan) {
    echo "<a class='btn btn-info' href='?input_khs_manual&angkatan=$angkatan'>$angkatan</a> ";
  }
  exit;
}
echo "<span class=debug id=angkatan>$angkatan</span>";


$id_prodi = $_GET['id_prodi'] ?? '';
include 'include/include_rid_prodi.php';
if($id_prodi==''){
  echo "<div class=mb2>Untuk angkatan <b><a href='?input_khs_manual'>$angkatan</a></b> prodi:</div>";
  foreach ($rid_prodi as $key => $id_prodi) {
    echo "<a class='btn btn-info' href='?input_khs_manual&angkatan=$angkatan&id_prodi=$id_prodi'>$rprodi[$id_prodi]</a> ";
  }
  exit;
}
$prodi = $rprodi[$id_prodi] ?? "<code>id_prodi: $id_prodi</code>";
$nama_prodi = $rnama_prodi[$id_prodi] ?? "<code>id_prodi: $id_prodi</code>";
echo "<span class=debug id=id_prodi>$id_prodi</span>";
echo "<span class=debug id=prodi>$prodi</span>";
echo "<span class=debug id=nama_prodi>$nama_prodi</span>";


$nim = $_GET['nim'] ?? '';
if($nim==''){
  echo "<div class=mb2>Angkatan <b><a href='?input_khs_manual'>$angkatan</a></b> prodi <a href='?input_khs_manual&angkatan=$angkatan'>$prodi</a>, untuk NIM:</div>";
  
  echo "
    <div style='display: inline-block;max-width: 150px'><input class='form-control tebal biru consolas tengah' type=text minlength=8 maxlength=8 required name=nim id=nim style='font-size: 16pt'></div> <a href='#' class='btn btn-primary btn-sms' id=btn_next>Next</a>
    <div><small><i>Masukan 8 digit NIM, lalu klik next!</i></small></div>
  
    <script>
      $(function(){
        $('#nim').keyup(function(){
          let angkatan = $('#angkatan').text();
          let id_prodi = $('#id_prodi').text();
          $('#btn_next').prop('href',`?input_khs_manual&angkatan=${angkatan}&id_prodi=${id_prodi}&nim=` + $(this).val());
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
      $div .= "<div><a href='?input_khs_manual&angkatan=$angkatan&id_prodi=$id_prodi&nim=$d[nim]'><span class=$red>$d[nim]</span></a></div>";
    }

    echo "<div class=flexy>$div</div>";

  }
exit;
}


echo "<div class=mb2>
Angkatan <a class=tebal href='?input_khs_manual'>$angkatan</a> 
prodi <a class=tebal href='?input_khs_manual&angkatan=$angkatan&id_prodi=$id_prodi'>$prodi</a> 
untuk NIM <a class=tebal href='?input_khs_manual&angkatan=$angkatan&id_prodi=$id_prodi'>$nim</a>
:</div>";

$s = "SELECT a.* FROM tb_mhs a WHERE nim='$nim'";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
if(mysqli_num_rows($q)==0) die(div_alert('danger',"Tidak ada mahasiswa dengan NIM $nim ~ <span class='miring abu'>Silahkan klik NIM diatas untuk mengganti!</span>"));

$d = mysqli_fetch_assoc($q);
?>
<ul>
  <li>Nama: <?=$d['nama']?></li>
  <li>Kelas: <?=$d['kelas_manual']?></li>
  <li>Semester: <?=$d['semester_manual']?></li>
</ul>
<?php



$s = "SELECT a.*,
(SELECT nilai FROM tb_nilai_manual WHERE id_mk_manual=a.id and nim=$nim) nilai, 
(SELECT hm FROM tb_nilai_manual WHERE id_mk_manual=a.id and nim=$nim) hm 
FROM tb_mk_manual a 
WHERE angkatan='$angkatan' AND id_prodi='$id_prodi'  
ORDER BY a.semester, a.nama";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
if(mysqli_num_rows($q)==0){
  echo div_alert('danger',"Belum ada Data MK untuk angkatan <a href='?input_khs_manual'>$angkatan</a> prodi <a href='?input_khs_manual&angkatan=$angkatan'>$prodi</a>");
}else{
  $tr='';
  while ($d=mysqli_fetch_assoc($q)) {
    $ubah = $d['nilai']=='' ? '-' : "<a href='?ubah_nilai_khs&nim=$nim&id_mk_manual=$d[id]'>Ubah</a>";
    $input_nilai = $d['nilai']=='' ? "<input class='form-control editable' value='$d[nilai]'>" : $d['nilai'];
    $input_hm = $d['hm']=='' ? "<input class='form-control editable' value='$d[hm]'>" : $d['hm'];
    
    $hapus_mk = $d['nilai']=='' ? "<a href='?manage_mk_manual&aksi=hapus&id_mk_manual=$d[id]'>hapus</a>" : '';
    
    $tr .= "
    <tr>
      <td>$d[semester]</td>
      <td>$d[kode] / $d[nama]<span class=debug>id:$d[id]$hapus_mk</span></td>
      <td width=20%>$input_nilai</td>
      <td width=20%>$input_hm</td>
      <td>$ubah</td>
    </tr>
    ";
  }
  $thead = "
  <thead>
    <th>Semester</th>
    <th>MK</th>
    <th>Nilai</th>
    <th>Huruf</th>
    <th>Aksi</th>
  </thead>
  ";
  echo "<table class=table>$thead$tr</table>";
}