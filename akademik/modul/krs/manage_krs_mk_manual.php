<?php 
$judul = 'Manage KRS MK';
echo "<h1>$judul <span class=debug>Manual</span></h1>";

// $izin = ($admin_level==3||$admin_level==6||$admin_level==7) ? 1 : 0;
// if(!$izin) echo div_alert('danger','Maaf, hanya Bagian Akademik yang mempunyai akses penuh pada Menu ini.');
// $disabled = $izin ? '' : 'disabled';
?>

<?php
$null = '<span class="red miring kecil">null</span>';

# =============================================================
# NORMAL FLOW NON-AKTIF
# =============================================================
$id_krs_manual = isset($_GET['id_krs_manual']) ? $_GET['id_krs_manual'] : die(erid('id_krs_manual'));
echo "<span class=debug>id_krs_manual: <span id=id_krs_manual>$id_krs_manual</span></span>";
$s = "SELECT * FROM tb_krs_manual WHERE id=$id_krs_manual";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
if(mysqli_num_rows($q)==0) die(erid('id_krs_manual :: null'));
$d = mysqli_fetch_assoc($q);
$angkatan = $d['angkatan'];
$id_prodi = $d['id_prodi'];
$semester = $d['untuk_semester'];
$tanggal_awal = $d['tanggal_awal'];
$tanggal_akhir = $d['tanggal_akhir'];
$ket = $d['ket'];

include '../include/include_rprodi.php';
$judul_left = "List MK untuk $rprodi[$id_prodi]-$angkatan-sm$semester";
$judul_right = "Daftar MK pada KRS  $rprodi[$id_prodi]-$angkatan-sm$semester";




$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
$keyword = isset($_POST['keyword']) ? $_POST['keyword'] : $keyword;



$bg = $keyword=='' ? '' : 'style="background: #0f0"';
$btn_filter_clear = "<button class='btn btn-primary btn-sm'>Filter</button>
<a href='?manage_krs_mk_manual&id_krs_manual=$id_krs_manual' class='btn btn-info btn-sm'>Clear</a>";

$form_filter = "
<form method=post>
  <div class='wadah'>
    <input name='keyword' value='$keyword' $bg required minlength=3 maxlength=20> 
    $btn_filter_clear
  </div>
</form>
";

$from = " FROM tb_mk_manual a 
LEFT JOIN tb_krs_mk_manual b on b.id_mk_manual=a.id
WHERE b.id is null
AND $angkatan=$angkatan 
AND id_prodi=$id_prodi 
AND semester=$semester  
AND (a.nama LIKE '%$keyword%' 
OR a.kode LIKE '%$keyword%')   
";
$s = "SELECT 1 $from";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
$jumlah_mk_manual = mysqli_num_rows($q);

$s = "SELECT a.*,
(
  SELECT singkatan FROM tb_prodi WHERE id=a.id_prodi 
) as nama_prodi, 
(
  SELECT nama FROM tb_dosen WHERE id=a.id_dosen 
) as nama_dosen   
$from ORDER BY a.nama LIMIT 40 ";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
$tr = '';
if(mysqli_num_rows($q)>0){
  while ($d=mysqli_fetch_assoc($q)) {
    
    $tr .= "
      <tr id=tr__$d[id]>
        <td>$d[kode]</td>
        <td id=nama_mk__$d[id]>$d[nama]</td>
        <td>$d[bobot] SKS</td>
        <td>
          <button class='btn btn-success btn-sm btn_aksi' id=tambah__$d[id]__new>Tambah</button>
        </td>
      </tr>
    ";
  }
}
$tb_non = $tr=='' ? div_alert('info','MK Manual tidak ditemukan.') 
: "<table class='table table-striped'>$tr</table>";

$limit_info = $jumlah_mk_manual>40 ? "| <code>Limit 40</code> | Silahkan Filter!" : '';






# =============================================================
# NORMAL RIGHT REGISTERED MK
# =============================================================
// $form_filter2 = "
// <form method=post>
//   <div class='wadah'>
//     <input name='keyword' value='$keyword' class=debug> 
//     $btn_filter_clear
//   </div>
// </form>
// ";

$from = " FROM tb_krs_mk_manual a 
JOIN tb_mk_manual b ON a.id_mk_manual=b.id 
WHERE id_krs_manual = '$id_krs_manual'  
";
$s = "SELECT a.id $from";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
$jumlah_aktif = mysqli_num_rows($q);

$s = "SELECT a.*,b.*, a.id as id_krs_mk_manual $from  LIMIT 40 ";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
$tr = '';
$total_sks = 0;
if(mysqli_num_rows($q)>0){
  while ($d=mysqli_fetch_assoc($q)) {
    $total_sks += $d['bobot'];
    $id = $d['id_krs_mk_manual'];
    $tr .= "
      <tr id=tr__$id>
      <td>$d[kode]</td>
      <td id=nama_mk__$id>$d[nama]</td>
      <td>$d[bobot] SKS</td>
      <td>
          <button class='btn btn-danger btn-sm btn_aksi' id=hapus__id__$id>Hapus</button>
        </td>
      </tr>
    ";
  }
}
$tr_sks = $tr=='' ? '' : "<tr class=tebal><td colspan=2>TOTAL SKS</td><td colspan=2>$total_sks SKS</td></tr>"; 
$tb_reg = $tr=='' ? div_alert('info','Registered MK tidak ditemukan.') 
: "<table class='table table-striped'>$tr$tr_sks</table>";

$limit_info2 = $jumlah_aktif>40 ? "| <code>Limit 40</code> | Silahkan Filter!" : '';








echo "
<div class='row'>
  <div class='col-sm-6'>
    <div class='wadah'>
      <h3 class=darkred>$judul_left</h3>
      <div class='small mb1'>$jumlah_mk_manual MK ditemukan. $limit_info</div>
      $form_filter
      $tb_non
    </div>
  </div>
  <div class='col-sm-6'>
    <div class='wadah'>
      <h3 class='biru tebal'>$judul_right</h3>
      <div class='small mb1'>$jumlah_aktif Registered-MK ditemukan. $limit_info2</div>
      $tb_reg
    </div>
  </div>
</div>
";



?>

<script>
  $(function(){
    $('.btn_aksi').click(function(){
      let tid = $(this).prop('id');
      let rid = tid.split('__');
      let aksi = rid[0];
      let id_mk_manual = rid[1];
      let id_krs_mk_manual = rid[2];
      let id_krs_manual = $('#id_krs_manual').text();
      let nama_mk = $('#nama_mk__'+id_krs_mk_manual).text();

      // let c = 'Undefined aksi: '+aksi;
      // if(aksi=='tambah'){
      //   c = `Yakin untuk menambahkan MK: ${nama_mk}?`;
      // }else if(aksi=='hapus'){
      //   c = `Yakin untuk menghapus MK: ${nama_mk}?`;
      // }
      // let y = confirm(c);
      // if(!y) return;




      console.log(aksi,id_krs_manual,id_mk_manual,id_krs_mk_manual);
      // let id_mk_manual = 'zzz'; // zzz
      let link_ajax = `ajax_akademik/ajax_crud_krs_mk.php?id_krs_manual=${id_krs_manual}&aksi=${aksi}&id_krs_mk_manual=${id_krs_mk_manual}&id_mk_manual=${id_mk_manual}`;
      $.ajax({
        url:link_ajax,
        success:function(a){
          if(a.trim()=='sukses'){
            if(aksi=='hapus'){
              $('#tr__'+id_krs_mk_manual).fadeOut();
            }else if(aksi=='tambah'){
              $('#tr__'+id_mk_manual).html(`<td colspan=4><div class='alert alert-info'>MK telah ditambahkan.</div></td>`);
            }
          }else{
            alert(a);
          }
        }
      })

    })
  })
</script>