<?php 
$debug_mode = 0;
$msg = "Error @AJAX. Missing index field";
if (!isset($_GET['id_angkatan']))die("$msg #1");
if (!isset($_GET['nama_mhs']))die("$msg #2");
if (!isset($_GET['status_mhs']))die("$msg #3");

if (!isset($_GET['freg']))die("$msg #4a");
if (!isset($_GET['ftrans']))die("$msg #4b");
if (!isset($_GET['fkip']))die("$msg #4c");

if (!isset($_GET['fkelas_p']))die("$msg #5a");
if (!isset($_GET['fkelas_s']))die("$msg #5b");

if (!isset($_GET['fti']))die("$msg #7");
if (!isset($_GET['frpl']))die("$msg #8");
if (!isset($_GET['fsi']))die("$msg #9");
if (!isset($_GET['fmi']))die("$msg #10");
if (!isset($_GET['fka']))die("$msg #11");

if (!isset($_GET['cpage']))die("$msg #12");

$get_id_angkatan = $_GET['id_angkatan'];
$nama_mhs = $_GET['nama_mhs'];
$status_mhs = $_GET['status_mhs'];

$freg = 0;
$ftrans = 0;
$fkip = 0;

$fkelas_p = 0;
$fkelas_s = 0;

$fti = 0;
$frpl = 0;
$fsi = 0;
$fmi = 0;
$fka = 0;

if($_GET['freg']=="true") $freg=1;
if($_GET['ftrans']=="true") $ftrans=1;
if($_GET['fkip']=="true") $fkip=1;

if($_GET['fkelas_p']=="true") $fkelas_p=1;
if($_GET['fkelas_s']=="true") $fkelas_s=1;

if($_GET['fti']=="true") $fti=1;
if($_GET['frpl']=="true") $frpl=1;
if($_GET['fsi']=="true") $fsi=1;
if($_GET['fmi']=="true") $fmi=1;
if($_GET['fka']=="true") $fka=1;

$cpage = $_GET['cpage'];
// die($cpage);
if($cpage=="" or $cpage<=0 or $cpage==null) $cpage=1;
$limit_start = $cpage*10-10;

$sql_where_nama_mhs = " 1 ";
$sql_where_status_mhs = " 1 ";

$sql_where_freg = " ( ";
$sql_where_ftrans = "   ";
$sql_where_fkip = " ) ";

$sql_where_fkelas_p = " ( ";
$sql_where_fkelas_s = " ) ";

$sql_where_fti = " ( ";
$sql_where_frpl = "  ";
$sql_where_fsi = "  ";
$sql_where_fmi = "  ";
$sql_where_fka = " ) ";

if(trim($nama_mhs)!="") $sql_where_nama_mhs = " nama_mhs like '%$nama_mhs%'  or nim like '%$nama_mhs%' ";

if($status_mhs>=-9 and $status_mhs<=9) $sql_where_status_mhs = " c.status_mhs = $status_mhs ";
if($status_mhs=="all_aktif") $sql_where_status_mhs = " c.status_mhs >= -1 AND c.status_mhs<9 ";
if($status_mhs=="all_nonaktif") $sql_where_status_mhs = " c.status_mhs < -1 ";
if($status_mhs=="all_data") $sql_where_status_mhs = " 1 ";

$fjalur = "$freg$ftrans$fkip";
$sql_where_jalur = " 1 ";
switch($fjalur){
  case "000": $sql_where_jalur = " 0 ";break;
  case "001": $sql_where_jalur = " (id_jalur>=3 and id_jalur<=12) ";break;
  case "010": $sql_where_jalur = " id_jalur=2 ";break;
  case "011": $sql_where_jalur = " id_jalur=2 or (id_jalur>=3 and id_jalur<=12) ";break;
  case "100": $sql_where_jalur = " id_jalur=1 ";break;
  case "101": $sql_where_jalur = " id_jalur=1 or (id_jalur>=3 and id_jalur<=12)  ";break;
  case "110": $sql_where_jalur = " id_jalur=1 or id_jalur=2 ";break;
  case "111": $sql_where_jalur = " 1 ";break;
}


$is_kelas_p = 0; if($fkelas_p) $is_kelas_p = " a.id_kelas=1 ";
$is_kelas_s = 0; if($fkelas_s) $is_kelas_s = " a.id_kelas=2 ";

$sql_where_kelas = " ( $is_kelas_p or $is_kelas_s) ";
if($is_kelas_p===0 and $is_kelas_s===0) $sql_where_kelas = " 0 ";

$is_ti = 0; if($fti) $is_ti = " b.id_prodi=1 ";
$is_rpl = 0; if($frpl) $is_rpl = " b.id_prodi=2 ";
$is_si = 0; if($fsi) $is_si = " b.id_prodi=3 ";
$is_mi = 0; if($fmi) $is_mi = " b.id_prodi=4 ";
$is_ka = 0; if($fka) $is_ka = " b.id_prodi=5 ";

$sql_where_prodi = " ( $is_ti or $is_rpl or $is_si or $is_mi or $is_ka) ";



include "../../config.php";


$s = "SELECT 
a.*,
b.singkatan_prodi,
c.status_mhs, 
c.ket_status_mhs,
d.singkatan_jndaftar,
e.singkatan_kelas    

FROM tb_mhs a 
JOIN tb_prodi b ON a.id_prodi=b.id_prodi 
JOIN tb_status_mhs c ON a.status_mhs=c.status_mhs 
JOIN tb_jalur d ON a.id_jalur=d.id_jndaftar  
JOIN tb_kelas e ON a.id_kelas=e.id_kelas 

WHERE id_angkatan = $get_id_angkatan 
AND $sql_where_nama_mhs 
AND $sql_where_status_mhs 

AND ($sql_where_jalur) 
AND $sql_where_kelas 
AND $sql_where_prodi 

order by a.nama_mhs  
";


$q = mysqli_query($cn,$s) or die("Tidak dapat mengakses data mhs @master_mhs.".mysqli_error($cn));
$jumlah_records = mysqli_num_rows($q);

$s .= " limit $limit_start,10 ";
$q = mysqli_query($cn,$s) or die("Tidak dapat melimit data mhs.".mysqli_error($cn));
$jumlah_records_limited = mysqli_num_rows($q);

$hasil="
<table class='table table-hover table-bordered table-striped'>
  <thead>
    <th>No</th>
    <th>Angkatan</th>
    <th>NIM</th>
    <th>Nama Mahasiswa</th>
    <th>IPK</th>
    <th>Status</th>
    <th>Sudah Registrasi</th>
    <th>Sudah KRS</th>
    <th>Aksi</th>
  </thead>
";

if ($jumlah_records_limited>0) {
  $i=0+($cpage-1)*10;
  while ($d = mysqli_fetch_array($q)) {
    $i++;
    $id_mhs = $d['id_mhs'];
    $id_angkatan = $d['id_angkatan'];
    $id_daftar = $d['id_daftar'];
    $id_biaya = $d['id_biaya'];
    $id_prodi = $d['id_prodi'];
    $id_jalur = $d['id_jalur'];
    $nim = $d['nim'];
    $nama_mhs = $d['nama_mhs'];
    $status_mhs = $d['status_mhs'];
    $ipk_terakhir = $d['ipk_terakhir'];
    $jenis_tinggal = $d['jenis_tinggal'];
    $jenis_kendaraan = $d['jenis_kendaraan'];
    $no_wa_mhs = $d['no_wa_mhs'];
    $email_mhs = $d['email_mhs'];
    $singkatan_prodi = $d['singkatan_prodi'];
    $ket_status_mhs = $d['ket_status_mhs'];
    $singkatan_jndaftar = $d['singkatan_jndaftar'];

    $is_sudah_reg = $d['is_sudah_reg'];
    $is_sudah_krs = $d['is_sudah_krs'];
    $is_reg_checked='';

    $is_krs_checked='';

    if($is_sudah_reg) $is_reg_checked="checked";
    if($is_sudah_krs) $is_krs_checked="checked";

    $nama_mhs = ucwords(strtolower($nama_mhs));

    $img_wa = "wa";
    $link_wa = "https://api.whatsapp.com/send?phone=62$no_wa_mhs&text=Selamat $waktu $nama_mhs";
    $link_email = "mailto:$email_mhs?subject=INFO%20PMB%20IKMI&body=Selamat $waktu $nama_mhs";


    if($status_mhs<=0)$ket_status_mhs = "<span class='merah'>$ket_status_mhs</span>";
    if($status_mhs>=1 and $status_mhs<9)$ket_status_mhs = "<span class='biru'>$ket_status_mhs</span>";
    if($status_mhs>=9)$ket_status_mhs = "<span class='ijo'>$ket_status_mhs</span>";

    $singkatan_kelas = $d['singkatan_kelas'];
    $ipk_terakhir = 3.25; //zzzz

    switch (strtolower($singkatan_jndaftar)) {
      case 'reg': $singkatan_jndaftar_html = "<span style='color:blue'>$singkatan_jndaftar</span>";break;
      case 'trans': $singkatan_jndaftar_html = "<span style='color:brown'>$singkatan_jndaftar</span>";break;
      case 'kip': $singkatan_jndaftar_html = "<span style='color:green'>$singkatan_jndaftar</span>";break;
      default: $singkatan_jndaftar_html = "<span style='color:red'>$singkatan_jndaftar</span>";break;
    }

    switch (strtolower($singkatan_prodi)) {
      case 'ti': $singkatan_prodi_html = "<span style='color:blue'>$singkatan_prodi</span>";break;
      case 'rpl': $singkatan_prodi_html = "<span style='color:brown'>$singkatan_prodi</span>";break;
      case 'si': $singkatan_prodi_html = "<span style='color:purple'>$singkatan_prodi</span>";break;
      case 'mi': $singkatan_prodi_html = "<span style='color:green'>$singkatan_prodi</span>";break;
      case 'ka': $singkatan_prodi_html = "<span style='color:orange'>$singkatan_prodi</span>";break;
      default: $singkatan_prodi_html = "<span style='color:red'>$singkatan_prodi</span>";break;
    }

    switch (strtolower($singkatan_kelas)) {
      case 'p': $singkatan_kelas_html = "<span style='color:blue'>$singkatan_kelas</span>";break;
      case 's': $singkatan_kelas_html = "<span style='color:red'>$singkatan_kelas</span>";break;
    }


    $hasil.= "
    <tr>
      <td class='tdcenter'>$i</td>
      <td class='tdcenter'>$id_angkatan-$singkatan_jndaftar_html-$singkatan_prodi_html-$singkatan_kelas_html</td>
      <td class='tdcenter'>$nim</td>
      <td class='' style='padding-left:10px'>
        <a href='?pmbdetail&id_calon=$id_mhs'>
          $nama_mhs
        </a>
      </td>
      <td class='tdcenter'>
        <a href='#' class='not_ready'>$ipk_terakhir</a>
      </td>
      <td class='tdcenter'>
        $status_mhs: $ket_status_mhs
      </td>
      <td class='tdcenter'>
        <a href='#' class='not_ready'>
          <span class='ijo'>1</span>
          <span class='ijo'>2</span>
          <span class='ijo'>3</span>
          <span class='ijo'>4</span>
          <span class='ijo'>5</span>
          <span class='merah'>0</span>
          <span class='merah'>0</span>
          <span class='merah'>0</span>
          <img src='img/icons/edit.png' width='20px'>
        </a> 

      </td>
      <td class='tdcenter'>
        <a href='#' class='not_ready'>
          <span class='ijo'>1</span>
          <span class='ijo'>2</span>
          <span class='ijo'>3</span>
          <span class='ijo'>4</span>
          <span class='ijo'>5</span>
          <span class='merah'>0</span>
          <span class='merah'>0</span>
          <span class='merah'>0</span>
          <img src='img/icons/edit.png' width='20px'>
        </a> 
      </td>

      <td class='tdcenter'>
        <a href='login_as_calon.php?email=$email_mhs&nama_mhs=$nama_mhs' target='_blank'><img src='img/icons/login_as.png' width='18px'></a> 
        <a href='?resetpaswd&id_calon=$id_mhs'><img src='img/icons/set_pass_sm.png' width='22px'></a> 
        <a href='$link_email' target='_blank'><img src='img/icons/mail.png' width='20px'></a> 
        <a href='$link_wa' target='_blank'><img src='img/icons/$img_wa.png' width='20px'></a> 
        <a href='?reject&id_calon=$id_mhs'><img src='img/icons/reject.png' width='20px'></a> 
        <a href='?delete&id_calon=$id_mhs'><img src='img/icons/delete.png' width='20px'></a> 
      </td>
    </tr>
    ";

    //        <input type='checkbox' class='is_regis' id='id_mhs~$id_mhs' $is_reg_checked>
    //<input type='checkbox' class='is_krs' id='id_mhs~$id_mhs' $is_krs_checked>
      
  }
}else{
  $hasil .= "<tr><td colspan=9 class='merah tengah'><h4>No Data Available.</h4> <hr>$s</td></tr>";
}


$hasil.="</table>";

echo "1__$jumlah_records"."__$hasil";
?>