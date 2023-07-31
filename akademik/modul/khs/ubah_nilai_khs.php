<h1>Ubah Nilai KHS</h1>
<style>th{text-align:left}</style>
<?php
if(isset($_POST['btn_update_nilai'])){
  echo '<pre>';
  var_dump($_POST);
  echo '</pre>';

  $na = $_POST['na'];
  $hm = $_POST['hm'];
  $nim = $_POST['nim'];
  $id_kurikulum_mk = $_POST['id_kurikulum_mk'];
  $alasan_update = $_POST['alasan_update'];

  // get old data
  $s = "SELECT * FROM tb_nilai WHERE id_kurikulum_mk=$id_kurikulum_mk AND nim='$nim'";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  if(mysqli_num_rows($q)!=1) die('Data nilai harus unik.'.$s);
  $d = mysqli_fetch_assoc($q);
  $tanggal_disetujui_mhs_or_null = $d['tanggal_disetujui_mhs']=='' ? 'NULL' : "'$d[tanggal_disetujui_mhs]'";

  // duplicate to history
  $s = "INSERT INTO tb_nilai_history 
  (
    change_by,
    nim,
    id_kurikulum_mk,
    na,
    hm,
    na_baru,
    hm_baru,
    date_created,
    tanggal_disetujui_mhs,
    alasan_update
    ) VALUES 
  (
    '$id_user',
    '$nim',
    '$id_kurikulum_mk',
    '$d[na]',
    '$d[hm]',
    '$na',
    '$hm',
    '$d[date_created]',
    $tanggal_disetujui_mhs_or_null,
    '$alasan_update'
  )";
  // die($s);
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  echo div_alert('success','Insert Nilai History sukses.');

  // update old data
  $s = "UPDATE tb_nilai SET 
  na='$na', 
  hm='$hm', 
  date_created=CURRENT_TIMESTAMP, 
  tanggal_disetujui_mhs=NULL 
  WHERE id_kurikulum_mk='$id_kurikulum_mk' AND nim='$nim'";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  echo div_alert('success','Update Nilai Lama sukses.');
  echo "<script>location.replace('?ubah_nilai_khs&id_kurikulum_mk=$id_kurikulum_mk&nim=$nim')</script>";
  exit;

}



$id_kurikulum_mk = $_GET['id_kurikulum_mk'] ?? '';
$nim = $_GET['nim'] ?? '';

# =============================================
# GET DATA MK
# =============================================
$disabled_mk = 'disabled';
$s = "SELECT b.kode,b.nama,(b.bobot_teori+b.bobot_praktik) bobot, c.nomor as semester  
FROM tb_kurikulum_mk a 
JOIN tb_mk b ON a.id_mk=b.id 
JOIN tb_semester c ON a.id_semester=c.id 
WHERE a.id='$id_kurikulum_mk'";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
if(mysqli_num_rows($q)==0) die('Data MK tidak ditemukan.');
$d = mysqli_fetch_assoc($q);
$kode_mk = $d['kode'];
$nama_mk = $d['nama'];
$bobot = $d['bobot'];
$semester = $d['semester'];


# =============================================
# GET DATA MHS
# =============================================
$disabled_mhs = 'disabled';
$s = "SELECT * FROM tb_mhs WHERE nim='$nim'";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
if(mysqli_num_rows($q)==0) die('Data Mhs tidak ditemukan.');
$d = mysqli_fetch_assoc($q);
$nama_mhs = $d['nama'];
$gender = $d['gender'];
$kelas_manual = $d['kelas_manual'];


# =============================================
# FORM UPDATE
# =============================================
$nilai_history = '<div class="kecil miring abu">Belum ada history nilai.</div>';

$s = "SELECT * FROM tb_nilai WHERE nim='$nim' AND id_kurikulum_mk=$id_kurikulum_mk";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
if(mysqli_num_rows($q)==0) die(div_alert('danger','Data nilai tidak ditemukan.'));
if(mysqli_num_rows($q)==1){
  $d = mysqli_fetch_assoc($q);
  $id_kurikulum_mk = $d['id_kurikulum_mk'];
  $na = $d['na'];
  $hm = $d['hm'];
  $nilai_sudah_ada = "";
  $konfirmasi_update = "Silahkan ketik kata `UPDATE`:<input name=konfirmasi_update class='form-control' minlength=6 maxlength=6 required >";
  $input_alasan_update = "
  
  ";
  
  $s = "SELECT a.*, b.nama as pengubah  
  FROM tb_nilai_history a 
  JOIN tb_user b ON a.change_by=b.id 
  WHERE a.nim='$nim' AND a.id_kurikulum_mk=$id_kurikulum_mk 
  ORDER BY change_date DESC";
  // $s = "SELECT * FROM tb_nilai WHERE nim='$nim' ";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  if(mysqli_num_rows($q)>0){
    $nilai_history = 'History Nilai :<ol>';
    $i = 0;
    while ($d = mysqli_fetch_assoc($q)) {
      $i++;
      $biru = $i==1 ? 'darkblue' : 'abu';
      $terbaru = $i==1 ? ' ~ (history terbaru)' : '';
      $tgl = date('M d, Y, H:i', strtotime($d['change_date']));
      $nilai_history .= "<li class='miring $biru'>Nilai asal: $d[na] ($d[hm]) ~ menjadi $d[na_baru] ($d[hm_baru]) | by $d[pengubah] at $tgl | Alasan: $d[alasan_update]$terbaru</li>";
    }
    $nilai_history.='</ol>';
  }
} 
?>


<form method=post>
  <div class="row">
    <div class="col-lg-6">
      <div class="wadah bg-white">
        <?=$nama_mk?> | <?=$kode_mk?> | <?=$bobot?>-SKS | SM-<?=$semester?>
      </div>
    </div>
    <div class="col-lg-6">
      <div class="wadah bg-white">
        <?=$nama_mhs?> | <?=$nim?> | <?=$kelas_manual?>
      </div>
    </div>
  </div>

  <span class='biru tebal'>Nilai sudah ada: <span id=nilai_awal><?=$na?></span>  (<span id=hm_awal><?=$hm?></span>)</span>. Ubah menjadi: <?=$bm?><input class="form-control" type=number min=0 max=100 step='0.01' value='<?=$na?>' name=na id=na required>
  <div class=consolas>Huruf Mutu: <span id=hm_show class='tebal' style='font-size:300%'><?=$hm?></span></div>
  <input class=debug name=hm id=hm value='<?=$hm?>'> 
  <input class=debug name=nim id=nim value='<?=$nim?>'> 
  <input class=debug name=id_kurikulum_mk id=id_kurikulum_mk value='<?=$id_kurikulum_mk?>'> 
  
  <div class='form-group'>
    <label for=alasan_update>Alasan Perubahan Nilai: <?=$bm?></label>
    <textarea class=form-control id=alasan_update name=alasan_update required minlength=10 maxlength=100></textarea>
  </div>

  <div style="margin-bottom: 10px">
    <button class="btn btn-primary btn-block" id=span_update_nilai disabled>Update Nilai | cek validasi...</button>
    <button class="btn btn-primary btn-block hideit" name=btn_update_nilai id=btn_update_nilai onclick="return confirm('Yakin untuk update nilai?')">Update Nilai</button>
    <small class='red consolas small' id=btn_update_nilai_info></small>
  </div>

  <div class='wadah kecil miring mb2'><span class='biru tebal'>Informasi penting:</span> Nilai lama akan tersimpan pada history nilai. Nilai terupdate akan muncul di KHS.<hr><?=$nilai_history?></div>
</form>



<script>
  
  $(function(){
    $("#na").keyup(function(){
      $("#btn_update_nilai").hide();
      let hm;
      let na = $(this).val();
      let nawal = $("#nilai_awal").text();
      if(isNaN(na) || na=='' || na>100 || na<0 || parseFloat(na)==parseFloat(nawal)){
        $("#span_update_nilai").show();
        $('#btn_update_nilai_info').text('Silahkan masukan angka dari 0 s.d 100');
        return;
      }else{
        $("#btn_update_nilai").show();
        $("#span_update_nilai").hide();
        hm = get_huruf_mutu(na);
        $('#hm').val(hm);
        $('#hm_show').text(hm);
        if(na<nawal){
          $('#btn_update_nilai_info').text('Perhatian! Nilai baru lebih kecil dari nilai awal.');
        }else if($('#hm_awal').text()==hm){
          $('#btn_update_nilai_info').text('Perhatian! Huruf Mutu baru sama dengan Huruf Mutu awal.');
        }else{
          $('#btn_update_nilai_info').text('');
        }
      }
      console.log(nawal,na);
    })

    $('#jumlah_peserta_show').click(function(){
      $('#peserta').fadeToggle();
    });
  })
</script>
