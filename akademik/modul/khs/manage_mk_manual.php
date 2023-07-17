<h1>Manage MK Manual</h1>
<?php
$aksi = $_GET['aksi'] ?? '';
$angkatan = $_GET['angkatan'] ?? '';
$id_prodi = $_GET['id_prodi'] ?? '';
$id_mk_manual = $_GET['id_mk_manual'] ?? '';

include 'include/include_rid_prodi.php';

if($id_mk_manual!=''){
  $s = "SELECT * FROM tb_mk_manual WHERE id=$id_mk_manual";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  if(mysqli_num_rows($q)==0) die(div_alert('danger',"MK dengan id: $id_mk_manual tidak ditemukan."));
  $d = mysqli_fetch_assoc($q);
  $id_prodi = $d['id_prodi'];
  echo "
  <ul>
    <li>Kode: $d[kode]</li>
    <li>Nama MK: $d[nama]</li>
    <li>Bobot: $d[bobot] SKS</li>
    <li>Semester: $d[semester]</li>
    <li>Angkatan: $d[angkatan]</li>
    <li>Prodi: $rprodi[$id_prodi]</li>
  </ul>
  ";
}

echo "<span class=debug>aksi:$aksi | angkatan:$angkatan | id_prodi:$id_prodi | id_mk_manual:$id_mk_manual | </span>";
if($aksi=='hapus'){
  $s = "SELECT a.*,b.nama as nama_mhs, b.semester_manual, b.angkatan   
  FROM tb_nilai_manual a 
  JOIN tb_mhs b ON a.nim=b.nim 
  WHERE a.id_mk_manual=$id_mk_manual";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  if(mysqli_num_rows($q)>0){
    echo div_alert('danger', "MK ini tidak dapat dihapus karena dipakai pada data nilai KHS oleh:");
    while ($d=mysqli_fetch_assoc($q)) {
      echo "<div>$d[nim] - $d[nama_mhs] - semester $d[semester_manual] - angkatan $d[angkatan]</div>";
    }
  }

}