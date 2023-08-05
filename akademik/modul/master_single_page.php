<?php
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
  if($d['Field']=='tanggal_buat') continue;
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
$btn_back = "<a href='?manage_master'><i class=icon_house_alt></i></a> | ";
$aksi_title = $aksi=='' ? 'MASTER' : $aksi; 
$page_title = "<h1 class='judul-page upper'>$btn_back $aksi_title ".str_replace('_',' ',$page). '</h1>';


# ===============================================
# CUSTOM PARAM FOR FOREIGN KEY LINK
# ===============================================
$param_id = $page=='jenjang' ? 'jenjang' : 'id';
$param_id = $page=='angkatan' ? 'angkatan' : $param_id;
$param_id = $page=='kelas' ? 'kelas' : $param_id;
$param_id = $page=='shift' ? 'shift' : $param_id; //new

$s = "SELECT * FROM tb_$page";

# ==============================================================
# CUSTOM PRIMARY / FOREIGN KEY
# ==============================================================
$kolom_acuan = $page=='jenjang' ? 'jenjang' : 'id';
$kolom_acuan = $page=='shift' ? 'shift' : $kolom_acuan; //new
$s = !isset($_GET[$kolom_acuan]) ? $s : "SELECT * FROM tb_$page WHERE $kolom_acuan='$_GET[$kolom_acuan]'";

# ==============================================================
# QUERY FILTERING
# ==============================================================
if($keyword!='' and $aksi==''){
  include 'include/keycol.php';
  $kolom_search = $keycol[$page];
  $search2 = isset($kolom_search[1]) ? " OR $kolom_search[1] like '%$keyword%'" : '';
  $s .= " WHERE $kolom_search[0] like '%$keyword%' $search2";
}

# ==============================================================
# SQL LIMIT
# ==============================================================
$limit = 10;
$s = $aksi=='' ? "$s limit $limit" : "$s limit 1";

# ==============================================================
# EXECUTE QUERY
# ==============================================================
// echo "<span class=debug>sql: $s</span>";
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
  $th.='<th class=text-left>Aksi</th>';
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
    # SPECIAL MANAGE FOR KALENDER / KURIKULUM
    # ===============================================
    $btn_manage_kalender = $page=='kalender' ? "<a class='btn btn-primary btn-sm btn-block mb-2 upper' href='?manage_kalender&id_kalender=$d[$param_id]'>manage</a>" : '';
    $btn_manage_kurikulum = $page=='kurikulum' ? "<a class='btn btn-primary btn-sm btn-block mb-2 upper' href='?manage_kurikulum&id_kurikulum=$d[$param_id]'>manage</a>" : '';



    # ===============================================
    # TABEL ROW OUTPUT
    # ===============================================
    $tr.= "
      <tr>
        <td>$i</td>
        $td
        <td>
          $btn_manage_kalender
          $btn_manage_kurikulum
          <a class='btn btn-success btn-sm btn-block mb-2 upper' href='?master&p=$page&aksi=update&$param_id=$d[$param_id]'>update</a>
          <a class='btn btn-danger btn-sm btn-block mb-2 upper' href='?master&p=$page&aksi=hapus&$param_id=$d[$param_id]'>hapus</a>
        </td>
      </tr>
    ";

  }else{

    # ===============================================
    # SHOW FORM UPDATE / HAPUS / TAMBAH
    # ===============================================
    include 'master_aksi.php';

  } // end else $aksi == ''
}

if($tr=='' and $aksi=='tambah') include 'master_aksi.php';

$colspan = count($Field)+2;
$tr = $tr=='' ? "<tr><td class='red' colspan=$colspan>Belum ada data $page. Silahkan tambah baru!</td></tr>" : $tr;
$tb = "<table class='table table-striped'><thead>$th</thead>$tr</table>";

$btn_tambah = $aksi!=''?'':"<a href='?master&p=$page&aksi=tambah' class='btn btn-primary'>Tambah</a>";
$btn_clear = $aksi!=''?'':"<a href='?master&p=$page' class='btn btn-info'>Clear</a>";

$bg_ijo = $keyword=='' ? '' : ' style="background:#0f0; color:blue" ';
$blok_filter = $aksi!=''?'':"
Filter: 
<div style='display:inline-block;width:100px'><input class='form-control' name=keyword value='$keyword' required $bg_ijo minlength=3 maxlength=10></div> 
<button class='btn btn-primary'>Filter</button>
";


echo "
$page_title 
<form method=post>$blok_filter $btn_clear $btn_tambah</form> 
<form method=post>
  <div class=debug>
    <input name=tabel value=$page>
    <input name=kolom_acuan value=$param_id>
    <input name=id value=$id>
  </div>
  $tb
</form>
";