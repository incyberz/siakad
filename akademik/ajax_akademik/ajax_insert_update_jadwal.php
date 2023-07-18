<?php 
include 'session_security.php';
include '../../conn.php';

# ================================================
# GET VARIABLES
# ================================================
$id_kurikulum_mk = isset($_GET['id_kurikulum_mk']) ? $_GET['id_kurikulum_mk'] : die(erid("id_kurikulum_mk"));
$new_id_dosen = isset($_GET['new_id_dosen']) ? $_GET['new_id_dosen'] : die(erid("new_id_dosen"));
$id_dosen_span = isset($_GET['id_dosen_span']) ? $_GET['id_dosen_span'] : die(erid("id_dosen_span"));

# ================================================
# MAIN HANDLE
# ================================================
if(trim(strtoupper($new_id_dosen))=='NULL'){
  $s = "DELETE FROM tb_jadwal WHERE id_kurikulum_mk=$id_kurikulum_mk AND id_dosen=$id_dosen_span";
}else{
  # ================================================
  # GET ID
  # ================================================

  if($id_dosen_span!=''){
    $s = "SELECT id FROM tb_jadwal WHERE id_kurikulum_mk=$id_kurikulum_mk AND id_dosen=$id_dosen_span";
    $q = mysqli_query($cn,$s) or die(mysqli_error($cn));

    if(mysqli_num_rows($q)>1) die('Tidak boleh ada Jadwal Ganda. Harap segera lapor ke Petugas!');
    if(mysqli_num_rows($q)==1){
      $d = mysqli_fetch_assoc($q);
      $s = "UPDATE tb_jadwal SET id_kurikulum_mk=$id_kurikulum_mk , id_dosen=$new_id_dosen WHERE id=$d[id]";
    }else{
      die('Data assign awal tidak ada (id_dosen_span is empty). Harap segera lapor Petugas.');
    }
    
  }else{
    $s = "INSERT INTO tb_jadwal (id_kurikulum_mk,id_dosen) VALUES ($id_kurikulum_mk,$new_id_dosen)";
  }
}
// die($s);
$q = mysqli_query($cn,$s) or die("Error @ajax. Tidak bisa insert/update data. SQL:$s. ".mysqli_error($cn));

die('sukses');
?>