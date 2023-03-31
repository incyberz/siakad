<?php 

$s = "SELECT id_prodi,nama_prodi,singkatan_prodi FROM tb_prodi";
$q = mysqli_query($cn,$s) or die("db_vars: tidak dapat mengakses data prodi");
$i=0;
$html_options_nama_prodi = '';

$html_options_singkatan_prodi = '';

while ($d=mysqli_fetch_assoc($q)) {
  $id_prodi = $d['id_prodi'];
  $nama_prodi = $d['nama_prodi'];
  $singkatan_prodi = $d['singkatan_prodi'];

  $rid_prodi[$i] = $id_prodi;
  $rnama_prodi[$i] = $nama_prodi;
  $rsingkatan_prodi[$i] = $singkatan_prodi;
  $html_options_nama_prodi.="<option value='$id_prodi'>$nama_prodi</option>";
  $html_options_singkatan_prodi.="<option value='$id_prodi'>$singkatan_prodi</option>";
  $i++;
}

$s = "SELECT a.id_dosen,a.nama_dosen,b.singkatan_prodi FROM tb_dosen a JOIN tb_prodi b on a.id_prodi=b.id_prodi ORDER BY b.singkatan_prodi, a.nama_dosen";
$q = mysqli_query($cn,$s) or die("db_vars: tidak dapat mengakses data dosen");
$i=0;
$html_options_nama_dosen = '';

while ($d=mysqli_fetch_assoc($q)) {
  $singkatan_prodi = $d['singkatan_prodi'];
  $id_dosen = $d['id_dosen'];
  $nama_dosen = ucwords(strtolower($d['nama_dosen']));
  $html_options_nama_dosen.="<option value='$id_dosen'>$singkatan_prodi ~ $nama_dosen</option>";
  $i++;
}

# ============================================================================
# OPTION ANGKATAN
# ============================================================================
$html_options_angkatan = '';

$s = "SELECT id_angkatan FROM tb_angkatan";
$q = mysqli_query($cn,$s) or die("db_vars: tidak dapat mengakses data angkatan");
while ($d=mysqli_fetch_assoc($q)) {
  $id_angkatan = $d['id_angkatan'];
  $html_options_angkatan.="<option>$id_angkatan</option>";

  // if($id_angkatan==date("Y")){
  //   $html_options_angkatan.="<option selected>$id_angkatan</option>";
  // }else{
  //   $html_options_angkatan.="<option>$id_angkatan</option>";
  // }
}

# ============================================================================
# OPTION STATUS MHS
# ============================================================================
$html_options_status_mhs = '';

$s = "SELECT * FROM tb_status_mhs";
$q = mysqli_query($cn,$s) or die("db_vars: tidak dapat mengakses data status mhs");
while ($d=mysqli_fetch_assoc($q)) {
  $status_mhs = $d['status_mhs'];
  $ket_status_mhs = $d['ket_status_mhs'];
  if($status_mhs==1){
    $html_options_status_mhs.="<option value='$status_mhs' selected>$status_mhs: $ket_status_mhs</option>";
  }else{
    $html_options_status_mhs.="<option value='$status_mhs'>$status_mhs: $ket_status_mhs</option>";
  }
}
$html_options_status_mhs.="<option value='all_aktif'>Semua Mhs Aktif</option>";
$html_options_status_mhs.="<option value='all_nonaktif'>Semua Non Aktif</option>";
$html_options_status_mhs.="<option value='all_data'>All Data Mhs</option>";


# ============================================================================
# OPTION JENJANG
# ============================================================================
$html_options_jenjang = "<option value='C'>Diploma III</option><option value='E'>Sarjana</option>";

# ============================================================================
# OPTION SEMESTER
# ============================================================================
$html_options_semester = '';

for ($i=1; $i <=8 ; $i++) { 
  if($i>=1 and $i<=2)  $html_options_semester.= "<option class='semester semester_jenjang_a'>$i</option>"; //D1
  if($i>=3 and $i<=4)  $html_options_semester.= "<option class='semester semester_jenjang_b'>$i</option>"; //D2
  if($i>=5 and $i<=6)  $html_options_semester.= "<option class='semester semester_jenjang_c'>$i</option>"; //D3
  if($i>=7 and $i<=8)  $html_options_semester.= "<option class='semester semester_jenjang_d semester_jenjang_e'>$i</option>"; //D4 or S1
}
$html_options_no_semester = $html_options_semester;




# ============================================================================
# OPTION KURIKULUM
# ============================================================================
$html_options_kurikulum = '';

$s = "SELECT * FROM tb_kurikulum";
$q = mysqli_query($cn,$s) or die("db_vars: tidak dapat mengakses data kurikulum");
while ($d=mysqli_fetch_assoc($q)) {
  $id_kurikulum = $d['id_kurikulum'];
  $nama_kurikulum = $d['nama_kurikulum'];
  $html_options_kurikulum.="<option value='$id_kurikulum'>$nama_kurikulum</option>";
}

# ============================================================================
# OPTION bk
# ============================================================================
$html_options_bk = '';

$s = "SELECT * FROM tb_bk";
$q = mysqli_query($cn,$s) or die("db_vars: tidak dapat mengakses data bk");
while ($d=mysqli_fetch_assoc($q)) {
  $id_bk = $d['id_bk'];
  $nama_bk = $d['nama_bk'];
  $html_options_bk.="<option value='$id_bk'>$nama_bk</option>";
}

# ============================================================================
# OPTION konsentrasi
# ============================================================================
$html_options_konsentrasi = '';

$s = "SELECT * FROM tb_konsentrasi";
$q = mysqli_query($cn,$s) or die("db_vars: tidak dapat mengakses data konsentrasi");
while ($d=mysqli_fetch_assoc($q)) {
  $id_konsentrasi = $d['id_konsentrasi'];
  $nama_konsentrasi = $d['nama_konsentrasi'];
  $html_options_konsentrasi.="<option value='$id_konsentrasi'>$nama_konsentrasi</option>";
}

# ============================================================================
# OPTION jenis_mk
# ============================================================================
$html_options_jenis_mk = '';

$s = "SELECT * FROM tb_jenis_mk";
$q = mysqli_query($cn,$s) or die("db_vars: tidak dapat mengakses data jenis_mk".mysqli_error($cn));
while ($d=mysqli_fetch_assoc($q)) {
  $jenis_mk = $d['jenis_mk'];
  $html_options_jenis_mk.="<option>$jenis_mk</option>";
}

?>