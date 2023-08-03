<style>th{text-align:left}.tb_semester{background:#ffffff77}</style>
<?php
$judul = "<h1>Manage Grup Kelas</h1>
<p>
<a href='?manage_kelas'>Back</a> | 
Proses assign prodi, angkatan, jalur daftar, dan shift kelas menjadi Grup Kelas.</p>";
$id_kurikulum = $_GET['id_kurikulum'] ?? '';
if(!$id_kurikulum || $id_kurikulum<1) die('<script>location.replace("?manage_kelas")</script>');

# ==============================================================
# GET OPTION JALUR DAFTAR
# ==============================================================
$rjalur = [1=>'REG',2=>'KIP',3=>'KIP-C',4=>'MBKM']; // zzz default | tidak sesuai db
$opt_jalur = '';
foreach ($rjalur as $key => $value) $opt_jalur.= "<option value='$key'>$value</option>";

# ==============================================================
# GET OPTION SHIFT KELAS
# ==============================================================
$rshift = ['pagi'=>'P','sore'=>'S']; // zzz default | tidak sesuai db
$opt_shift = '';
foreach ($rshift as $key => $value) $opt_shift.= "<option value='$key'>$value</option>";


# ==============================================================
# FORM PROCESSING
# ==============================================================
if(isset($_POST['btn_tambah']) || isset($_POST['btn_hapus'])){
  // echo '<pre>';
  // var_dump($_POST);
  // echo '</pre>';
  $angkatan = $_POST['angkatan'] ?? die(erid('angkatan'));
  $prodi = $_POST['prodi'] ?? die(erid('prodi'));
  $id_prodi = $_POST['id_prodi'] ?? die(erid('id_prodi'));
  $id_jalur = $_POST['id_jalur'] ?? die(erid('id_jalur'));
  $shift = $_POST['shift'] ?? die(erid('shift'));
  $counter = $_POST['counter'] ?? die(erid('counter'));
  
  if(isset($_POST['btn_tambah'])){
    $Shift = strtoupper(substr($shift,0,1));
    $jalur = $rjalur[$id_jalur];
    $kelas = "$prodi-$angkatan-$jalur-$Shift$counter";

    $s = "SELECT 1 FROM tb_kelas WHERE kelas='$kelas'";
    $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
    if(mysqli_num_rows($q)==1){
      echo div_alert('danger',"Kelas <b>$kelas</b> sudah ada. Silahkan pakai nama lain!");
    }else{
      $s = "INSERT INTO tb_kelas 
      (kelas,id_prodi,id_jalur,angkatan,shift) VALUES 
      ('$kelas','$id_prodi','$id_jalur','$angkatan','$shift')";
      $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
      echo div_alert('success', "Tambah kelas berhasil.");
    }
  }else{ // btn_hapus 
    $aksi = 'Hapus';
    $rid = explode('__',$_POST['btn_hapus']);
    $kelas = $rid[1];
    $s = "DELETE FROM tb_kelas WHERE kelas='$kelas'";
    $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
    echo div_alert('success', "Hapus kelas berhasil.");
  }
}

# ==============================================================
# GET KURIKULUM DATA
# ==============================================================
$s = "SELECT 
CONCAT('Kurikulum ',c.jenjang,'-',b.singkatan,'-',c.angkatan) as nama_kurikulum, 
c.jumlah_semester,
b.id as id_prodi, 
b.singkatan as prodi,
c.id as id_kalender, 
c.angkatan, 
a.id as id_kurikulum 

FROM tb_kurikulum a 
JOIN tb_prodi b ON b.id=a.id_prodi 
JOIN tb_kalender c ON c.id=a.id_kalender  
JOIN tb_jenjang d ON d.jenjang=c.jenjang  
WHERE a.id='$id_kurikulum'";
$q = mysqli_query($cn, $s)or die(mysqli_error($cn));
if(!mysqli_num_rows($q)) die('Data kurikulum tidak ditemukan.');
$d = mysqli_fetch_assoc($q);
$jumlah_semester = $d['jumlah_semester'];
$nama_kurikulum = $d['nama_kurikulum'];
$id_kalender = $d['id_kalender'];
$id_prodi = $d['id_prodi'];
$angkatan = $d['angkatan'];
$prodi = $d['prodi'];

# ==============================================================
# LIST KELAS
# ==============================================================
$link_buat_grup_kelas = "<a href='?tambah_grup_kelas&id_kurikulum=$id_kurikulum'>Buat Grup Kelas</a>";
$s = "SELECT a.*,
(SELECT count(1) FROM tb_kelas_ta WHERE kelas=a.kelas) jumlah_kelas_ta 
FROM tb_kelas a 
WHERE a.angkatan=$angkatan 
AND a.id_prodi=$id_prodi";
$q = mysqli_query($cn, $s)or die(mysqli_error($cn));
$tr = '';
$i=0;
while ($d=mysqli_fetch_assoc($q)) {
  $i++;
  $kelas = $d['kelas'];
  $jumlah_kelas_ta = $d['jumlah_kelas_ta'];
  $btn_hapus = $jumlah_kelas_ta ? '-' : "<button class='btn btn-danger btn-sm' onclick='return confirm(\"Yakin mau hapus kelas ini?\")' name=btn_hapus value=hapus__$kelas>Hapus</button>";
  $tr.="
  <tr>
    <td>$i</td>
    <td>$kelas</td>
    <td>$d[jumlah_kelas_ta] | <a href='?manage_kelas_ta&kelas=$kelas&id_kurikulum=$id_kurikulum'>manage</td>
    <td>$btn_hapus</td>
  </tr>
  ";
}
$tr_tambah = "
<tr>
  <td>#</td>
  <td colspan=3>
    $prodi-$angkatan-
    <select name=id_jalur>$opt_jalur</select>-
    <select name=shift>$opt_shift</select>
    <input name=counter value='1' style='width:50px'>
    <button class='btn btn-info btn-sm' name=btn_tambah>Tambah</button>
  </td>
</tr>
";
$tb_kelas = "
<p><b>Kelas TA</b> adalah kelas grup sesuai dengan Tahun Ajaran tertentu. Misal: kelas TI-2022-REG-P1 TA.2022 pesertanya tidak sama dengan kelas TI-2022-REG-P1 TA.2023. Hal ini bertujuan agar tiap tahun ajar mhs dapat berganti kelas dan agar grup kelas tidak berjumlah terlalu sedikit peserta.</p>
<form method=post>
<table class=table>
  <thead>
    <th>No</th>
    <th>Kelas</th>
    <th>Kelas TA</th>
    <th>Aksi</th>
  </thead>
  $tr
  $tr_tambah
</table>
<input class=debug name=prodi value='$prodi'>
<input class=debug name=angkatan value='$angkatan'>
<input class=debug name=id_prodi value='$id_prodi'>
</form>";


# ==============================================================
# FINAL OUTPUT SEMESTERS
# ==============================================================
// echo "<pre>";
// var_dump($rnama_mk);
// echo "</pre>";
echo "
$judul
<div class='wadah gradasi-hijau'>
  <h4 class=darkblue>Grup Kelas pada $nama_kurikulum</h4>
  $tb_kelas
</div>

";


















?>
<script>
  $(function(){
    $(".select_id_dosen").change(function(){
      let tid = $(this).prop('id');
      let rid = tid.split('__');
      let id_kurikulum_mk = rid[1];
      let id_dosen = rid[2];
      let id_dosen_span = $('#'+id_kurikulum_mk+'__'+id_dosen).text();
      let val = $(this).val();
      if(id_dosen_span==val || (id_dosen_span=='' && val=='NULL')){
        $('#btn_apply__'+id_kurikulum_mk+'__'+id_dosen).prop('disabled',true);
      }else{
        $('#btn_apply__'+id_kurikulum_mk+'__'+id_dosen).prop('disabled',false);
      }
    });

    $(".btn_apply").click(function(){
      let tid = $(this).prop('id');
      let rid = tid.split('__');
      let id_kurikulum_mk = rid[1];
      let id_dosen = rid[2];
      let id_dosen_span = $('#'+id_kurikulum_mk+'__'+id_dosen).text();
      let new_id_dosen = $('#id_dosen__'+id_kurikulum_mk+'__'+id_dosen).val();

      if(new_id_dosen=='NULL'){
        let y = confirm('Apakah Anda ingin mengosongkan (menghapus) Jadwal untuk MK ini?');
        if(!y){
          $(this).val(id_dosen);
          return;
        }
      }
      
      
      let link_ajax = `ajax_akademik/ajax_insert_update_jadwal.php?id_kurikulum_mk=${id_kurikulum_mk}&new_id_dosen=${new_id_dosen}&id_dosen_span=${id_dosen_span}`;
      // console.log(id_kurikulum_mk,id_dosen,id_dosen_span,new_id_dosen,link_ajax); return;

      $.ajax({
        url:link_ajax,
        success:function(a){
          console.log(a);
          if(a.trim()=='sukses'){

            if(new_id_dosen=='NULL'){
              $('#'+id_kurikulum_mk+'__'+id_dosen).text('');
              $('#id_dosen__'+id_kurikulum_mk+'__'+id_dosen).removeClass('gradasi-hijau');
              $('#id_dosen__'+id_kurikulum_mk+'__'+id_dosen).addClass('gradasi-merah');
            }else{
              $('#'+id_kurikulum_mk+'__'+id_dosen).text(new_id_dosen);
              $('#id_dosen__'+id_kurikulum_mk+'__'+id_dosen).removeClass('gradasi-merah');
              $('#id_dosen__'+id_kurikulum_mk+'__'+id_dosen).addClass('gradasi-hijau');
            }

            $('#'+tid).prop('disabled',true);

          }else{
            if(a.toLowerCase().search('cannot delete or update a parent row')>0){
              alert('Jadwal tidak bisa dihapus karena sudah punya Sesi Kuliah.');
            }else{
              alert(a)
            }
          }
        }
      })
    })
  })
</script>
