<?php
# ==============================================================
# MASTER DATA HANDLER v.1.0.1
# ==============================================================

# ==============================================================
# FORM ACTION HANDLER
# ==============================================================
include 'manage_form_handler.php';
include 'manage_pesan_handler.php';


# ==============================================================
# GET PARAMS
# ==============================================================
$page = isset($_GET['p'])?$_GET['p']:'';
$aksi = isset($_GET['aksi'])?$_GET['aksi']:'';
$id = isset($_GET['id'])?$_GET['id']:'';

if($page==''){
  # ==============================================================
  # SHOW MANAGE MASTER PAGE
  # ==============================================================
  include 'manage_home.php';

}else{

  # ==============================================================
  # MANAGE SINGLE MASTER
  # ==============================================================

  # ==============================================================
  # DESCRIBING COLUMNS
  # ==============================================================
  $s = "DESCRIBE tb_$page";
  $q = mysqli_query($cn, $s)or die(mysqli_error($cn));
  $Field = [];
  $Type = [];
  $Null = [];
  $Key = [];
  $Default = [];
  $i=0;
  while ($d=mysqli_fetch_assoc($q)) {
    if($d['Extra']=='auto_increment') continue;
    if($d['Field']=='folder_uploads') continue;
    $Field[$i] = $d['Field'];
    $Type[$i] = $d['Type'];
    $Null[$i] = $d['Null'];
    $Key[$i] = $d['Key'];
    // $Default[$i] = $d['Default'];
    $i++;
  }

  # ==============================================================
  # PAGE TITLE
  # ==============================================================
  $param = $aksi=='' ? '' : "&p=$page";
  $btn_back = "<a href='?manage$param'><i class=icon_house_alt></i></a> | ";
  $aksi_title = $aksi=='' ? 'MANAGE' : $aksi; 
  $page_title = "<h1 class='judul-page upper'>$btn_back $aksi_title ". strtoupper($page). '</h1>';


  # ===============================================
  # CUSTOM PARAM FOR FOREIGN KEY LINK
  # ===============================================
  $param_id = $page=='jenjang' ? 'jenjang' : 'id';
  $param_id = $page=='angkatan' ? 'angkatan' : $param_id;

  $s = "SELECT * from tb_$page";

  # ==============================================================
  # CUSTOM PRIMARY / FOREIGN KEY
  # ==============================================================
  $kolom_acuan = $page=='jenjang' ? 'jenjang' : 'id';
  $s = !isset($_GET[$kolom_acuan]) ? $s : "SELECT * from tb_$page where $kolom_acuan='$_GET[$kolom_acuan]'";

  # ==============================================================
  # SQL LIMIT
  # ==============================================================
  $limit = 10;
  $s = $aksi=='' ? "$s limit $limit" : "$s limit 1";

  # ==============================================================
  # EXECUTE QUERY
  # ==============================================================
  echo "sql: $s";
  $q = mysqli_query($cn, $s)or die(mysqli_error($cn));

  # ==============================================================
  # HEADER FOR LIST MASTER
  # ==============================================================
  $th='';
  if($aksi==''){
    $th='<th class=text-left>No</th>';
    for ($i=0; $i < count($Field); $i++) { 
      $kolom_formatted = str_replace('_',' ',strtoupper($Field[$i]));
      $th.="<th class=text-left>$kolom_formatted</th>";
    }
  }


  $tr = '';
  $i=0;
  while ($d = mysqli_fetch_assoc($q)) {

    if($aksi==''){
      # ===============================================
      # SHOW LIST MASTER DATA
      # ===============================================
      $i++;
      $td='';
      for ($j=0; $j < count($Field); $j++) { 
        $isi = $d[$Field[$j]]!=null ? $d[$Field[$j]] : "<span class='abu miring'>-- NULL --</span>";
        $td.="<td>$isi</td>";
      }


      # ===============================================
      # TABEL ROW OUTPUT
      # ===============================================
      $tr.= "
        <tr>
          <td>$i</td>
          $td
          <td>
            <a class='btn btn-success btn-sm btn-block mb-2 upper' href='?manage&p=$page&aksi=update&$param_id=$d[$param_id]'>update</a>
            <a class='btn btn-danger btn-sm btn-block mb-2 upper' href='?manage&p=$page&aksi=hapus&$param_id=$d[$param_id]'>hapus</a>
          </td>
        </tr>
      ";

    }else{

      # ===============================================
      # SHOW FORM UPDATE / HAPUS / TAMBAH
      # ===============================================
      include 'manage_aksi.php';

    } // end else $aksi == ''
  }

  if($tr=='' and $aksi=='tambah') include 'manage_aksi.php';

  $tr = $tr=='' ? "<tr><td class='red'>Belum ada data $page.</td></tr>" : $tr;
  $tb = "<table class='table table-striped'><thead>$th</thead>$tr</table>";

  $btn_tambah = $aksi!=''?'':"<div style='padding:10px'><a href='?manage&p=$page&aksi=tambah' class='btn btn-primary'>Tambah</a></div>";

  echo "
  <form method=post>
    <div class=debug>
      <input name=tabel value=$page>
      <input name=kolom_acuan value=$param_id>
      <input name=id value=$id>
    </div>
    $page_title $btn_tambah $tb
  </form>
  ";
}
