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
    margin: 10px 0 15px 0;
    border: solid 1px #ccc;
    border-radius: 10px;
    padding: 15px 5px 10px 5px;
    font-size: 10px;
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
a.*,   
b.id as id_kurikulum_mk,
c.nama as nama_mk,
d.nama as dosen_koordinator,
f.angkatan,
g.nomor as semester,
e.id_prodi 

FROM tb_jadwal a 
JOIN tb_kurikulum_mk b on b.id=a.id_kurikulum_mk 
JOIN tb_mk c on c.id=b.id_mk 
JOIN tb_dosen d on d.id=a.id_dosen 
JOIN tb_kurikulum e on b.id_kurikulum=e.id 
JOIN tb_kalender f on e.id_kalender=f.id 
JOIN tb_semester g on b.id_semester=g.id 

WHERE a.id=$id_jadwal";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
if(mysqli_num_rows($q)==0) die('Data Jadwal tidak ditemukan.');
$d_jadwal = mysqli_fetch_assoc($q);




# ====================================================
# GET KELAS PESERTA
# ====================================================
$tahun_ajar = $d_jadwal['angkatan'] + intval(($d_jadwal['semester']-1)/2);
$ganjil_genap = ($d_jadwal['semester'] % 2 == 0) ? 2 : 1;

$s = "SELECT *, a.id as id_kelas_ta,
(SELECT count(1) FROM tb_kelas_ta_detail WHERE id_kelas_ta=a.id) jumlah_mhs 
FROM tb_kelas_ta a 
JOIN tb_kelas b ON a.kelas=b.kelas 
WHERE a.tahun_ajar='$tahun_ajar$ganjil_genap' 
AND b.angkatan='$d_jadwal[angkatan]' 
AND b.id_prodi='$d_jadwal[id_prodi]' 
AND b.shift='$d_jadwal[shift]' 
";

$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
$jumlah_kelas_ta = mysqli_num_rows($q);
// echo "jumlah_kelas_ta: $jumlah_kelas_ta";

if($jumlah_kelas_ta){
  while ($d=mysqli_fetch_assoc($q)) {
    $s2 = "SELECT a.nim,
    b.id as id_mhs,
    b.nama as nama_mhs,
    b.folder_uploads,   
    b.gender   
    FROM tb_kelas_ta_detail a 
    JOIN tb_mhs b ON a.nim=b.nim 
    WHERE a.id_kelas_ta='$d[id_kelas_ta]'
    ORDER BY b.nama 
    ";
    $q2 = mysqli_query($cn,$s2) or die(mysqli_error($cn));

    $nims = '';
    while ($d2=mysqli_fetch_assoc($q2)) {
      $gender = strtolower($d2['gender']);

      $path_img_default = "../assets/img/icons/student-$gender.png";
      if($d2['folder_uploads']==''){
        $path_img = $path_img_default;
      }else{
        $path_profile = "../mhs/uploads/$d2[folder_uploads]/_profile_$d2[id_mhs].jpg";
        if(file_exists($path_profile)){
          $path_img = $path_profile;
        }else{
          $path_img = "../assets/img/icons/student-warning.png";
        }
      }
      $img_profil = "<img src='$path_img' class='img-profil profil-$gender' />";

      $nims.="
      <div class='flexy-item'>
        <div>
          $img_profil
        </div>
        <div class='nama-$gender'>$d2[nim] | $d2[nama_mhs]</div>
      </div>";
    }


    echo "
    <div class=wadah>
      <div>Kelas $d[kelas] | $d[jumlah_mhs] mhs</div>
      <div class='flexy kecil'>$nims</div>
    </div>";
    
  }

}else{
  echo div_alert('danger','Data kelas-TA tidak ditemukan.');
}




