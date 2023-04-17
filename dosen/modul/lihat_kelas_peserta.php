<style>
  .img-profil{
    width: 100px;
    height: 100px;
    border-radius: 50%;
    margin-bottom: 5px;
    transition:.2s;
  }
  .img-profil:hover{transform:scale(1.2)}
  .profil-p {border: solid 4px #f7f;}
  .profil-l {border: solid 4px #77f;}
  .nama-p {color: #c5c}
  .nama-l {color: #55c}
  .flexy-item{
    width: 130px;
    text-align: center;
    font-size: small;
    margin: 10px 0 15px 0;
    /* border: solid 1px red; */
  }
</style>
<?php

$judul = "LIHAT KELAS PESERTA";

$id_jadwal = isset($_GET['id_jadwal']) ? $_GET['id_jadwal'] : die(erid('id_jadwal'));
if($id_jadwal=='') die(erid('id_jadwal::empty'));


# ====================================================
# JADWAL PROPERTIES
# ====================================================
$s = "SELECT 
a.tanggal_jadwal,   
b.id as id_kurikulum_mk,
c.nama as nama_mk,
d.nama as dosen_koordinator 

FROM tb_jadwal a 
JOIN tb_kurikulum_mk b on b.id=a.id_kurikulum_mk 
JOIN tb_mk c on c.id=b.id_mk 
JOIN tb_dosen d on d.id=a.id_dosen 
JOIN tb_semester e on b.id_semester=e.id 
JOIN tb_kurikulum f on f.id=b.id_kurikulum 
JOIN tb_prodi g on g.id=f.id_prodi 
JOIN tb_kalender h on h.id=f.id_kalender 

WHERE a.id=$id_jadwal";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
if(mysqli_num_rows($q)==0) die('Data Jadwal tidak ditemukan.');
$d = mysqli_fetch_assoc($q);
$id_kurikulum_mk = $d['id_kurikulum_mk'];
$nama_mk = $d['nama_mk'];

$koloms = [];
$i=0;
$tr = '';
foreach ($d as $key => $value) {
  if($key=='tanggal_jadwal'
  ) continue;
  $koloms[$i] = str_replace('_',' ',$key);
  $debug = substr($key,0,2)=='id' ? 'debug' : 'upper';
  $tr .= "<tr class=$debug><td>$koloms[$i]</td><td id=$key>$value</td></tr>";
  $i++;
}




# ====================================================
# KELAS PESERTA
# ====================================================
$jumlah_mhs=0;
$s2 = "SELECT 
d.kelas  
FROM tb_kelas_peserta a 
JOIN tb_kurikulum_mk b on b.id=a.id_kurikulum_mk  
JOIN tb_jadwal c on b.id=c.id_kurikulum_mk  
JOIN tb_kelas_angkatan d on d.id=a.id_kelas_angkatan   
WHERE c.id=$id_jadwal ";
// echo $s2;
$q2 = mysqli_query($cn,$s2) or die(mysqli_error($cn));
if(mysqli_num_rows($q2)==0){
  $kelas_peserta = '<span class="miring red">--NULL--</span>';
}else{
  $kelas_peserta = '';
  $i=0;
  while ($d2=mysqli_fetch_assoc($q2)) {
    $i++;
    $kelas_peserta.= "<h5 class='darkblue p-2 gradasi-hijau'>$i. $d2[kelas]</h5>";
    $s3 = "SELECT
    c.id as id_mhs,
    c.nama as nama_mhs, 
    c.folder_uploads, 
    c.gender 

    FROM tb_kelas_angkatan a 
    JOIN tb_kelas_angkatan_detail b on a.id=b.id_kelas_angkatan  
    JOIN tb_mhs c on b.id_mhs=c.id   
    WHERE a.kelas='$d2[kelas]' ";
    // echo $s2;
    $q3 = mysqli_query($cn,$s3) or die(mysqli_error($cn));
    $list_mhs = '';
    $j=0;
    while ($d3=mysqli_fetch_assoc($q3)) {
      $j++;
      $path_profile='';
      $gender = strtolower($d3['gender']);
      $path_img_default = "../assets/img/icons/student-$gender.png";
      if($d3['folder_uploads']==''){
        $path_img = $path_img_default;
      }else{
        $path_profile = "../mhs/uploads/$d3[folder_uploads]/_profile_$d3[id_mhs].jpg";
        if(file_exists($path_profile)){
          $path_img = $path_profile;
        }else{
          $path_img = "../assets/img/icons/student-warning.png";
        }
      }
      $img_profil = "<img src='$path_img' class='img-profil profil-$gender' />";

      $list_mhs.= "
      <div class='upper flexy-item'>
        <a href='?lihat_mhs&id_mhs=$d3[id_mhs]' target=_blank>$img_profil</a>
        <div class='nama-$gender'>$j. $d3[nama_mhs]</div>
      </div>";
      $jumlah_mhs++;
    }
    $laporkan = "<a href='?lapor_kesalahan&fitur=manage_peserta&hal=List Mahasiswa masih kosong.&kelas=$d2[kelas]' class='btn btn-primary'>Laporkan</a>";
    $list_mhs = $list_mhs=='' ? div_alert('danger','List Mahasiswa masih kosong. '.$laporkan) : "<div class=flexy>$list_mhs</div>";
    $kelas_peserta .= $list_mhs;

  }
}


$tb_mk = "
<table class=table>
  $tr
</table>";

echo "
<h3>$judul</h3>
$tb_mk
<div class=wadah>
  <h4>KELAS PESERTA :: $jumlah_mhs Mhs</h4>
  $kelas_peserta
</div>
";
?>
