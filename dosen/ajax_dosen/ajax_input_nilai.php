<?php 
include '../../conn.php';
include 'session_security.php';

# ================================================
# GET VARIABLES
# ================================================
$id_tipe_sesi = isset($_GET['id_tipe_sesi']) ? $_GET['id_tipe_sesi'] : die(erid("id_tipe_sesi"));
$id_kelas_angkatan_detail = isset($_GET['id_kelas_angkatan_detail']) ? $_GET['id_kelas_angkatan_detail'] : die(erid("id_kelas_angkatan_detail"));
$nilai = isset($_GET['nilai']) ? $_GET['nilai'] : die(erid("nilai"));

# ================================================
# STOP IF EMPTY
# ================================================
if($id_tipe_sesi=='' || $id_kelas_angkatan_detail=='' || $nilai=='') die(erid('empty-value'));

# ================================================
# CLEAN INPUTS
# ================================================
if($nilai<0 || $nilai>100) die('Nilai harus antara 0 s.d 100');


# ================================================
# CHECK IF ROW EXIST
# ================================================
$s = "SELECT id FROM tb_nilai WHERE id_kelas_angkatan_detail=$id_kelas_angkatan_detail";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
if(mysqli_num_rows($q)){
  $d=mysqli_fetch_assoc($q);
  $id=$d['id'];
}else{
  $id='new';
}

# ================================================
# MAIN HANDLE
# ================================================
$kolom_nilai = $id_tipe_sesi==8 ? 'nuts' : '';
$kolom_nilai = $id_tipe_sesi==16 ? 'nuas' : $kolom_nilai;
$kolom_nilai = $kolom_nilai=='' ? die(erid('kolom_nilai')) : $kolom_nilai;

if($id=='new'){
  $s = "INSERT INTO tb_nilai (id_kelas_angkatan_detail,$kolom_nilai) VALUES ($id_kelas_angkatan_detail,$nilai)";
}else{
  $s = "UPDATE tb_nilai SET $kolom_nilai=$nilai WHERE id=$id";
}
// die($s);
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));

die('sukses');
?>