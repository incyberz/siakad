<h1>Batch Tanggal Sesi</h1>
<div class="alert alert-danger">Perhatian!!! Fitur ini masih dalam tahap pengembangan. [VIEW ONLY]</div>
<?php
if(isset($_POST['btn_buat_sesi_default'])){
  $s = " ";
  // $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  die(var_dump($_POST));
  exit;
  
}


$id_jadwal = isset($_GET['id_jadwal']) ? $_GET['id_jadwal'] : die(erid('id_jadwal'));
echo "<span class=debug id=id_jadwal>$id_jadwal</span>";

# ====================================================
# LIST SESI KULIAH
# ====================================================
$s = "SELECT 
a.id,
a.pertemuan_ke,
a.tanggal_sesi,
b.jumlah_sesi,
b.sesi_uts,
b.sesi_uas 

from tb_sesi_kuliah a 
join tb_jadwal b on b.id=a.id_jadwal  
where a.id_jadwal=$id_jadwal order by a.pertemuan_ke";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));


if(mysqli_num_rows($q)==0){
  $tb_sesi = "
  <div class='alert alert-info'>
    Belum ada sesi untuk jadwal ini.<hr>
    <form method=post>
      <input class=debug name=id_jadwal value='$id_jadwal'>
      <input class=debug name=id_dosen value='$id_dosen'>
      <input class=debug name=jumlah_sesi value='$jumlah_sesi'>
      <input class=debug name=sesi_uts value='$sesi_uts'>
      <input class=debug name=sesi_uas value='$sesi_uas'>
      <button class='btn btn-primary' name=btn_buat_sesi_default>Buat $d[jumlah_sesi] Sesi Kuliah Default</button>
    </form>
  </div>";
}else{

  $thead = "
  <thead>
    <th class='text-left upper'>Pertemuan ke</th>
    <th class='text-left upper'>Tanggal Sesi</th>
    <th class='text-left upper'>Hasil Batch</th>
  </thead>"; 
  $tr = '';
  while ($d=mysqli_fetch_assoc($q)) {
    if($d['pertemuan_ke']==1){
      echo "<div class=debug>
        jumlah_sesi:<span id='jumlah_sesi'>$d[jumlah_sesi]</span> 
        sesi_uts:<span id='sesi_uts'>$d[sesi_uts]</span> 
        sesi_uas:<span id='sesi_uas'>$d[sesi_uas]</span> 
      </div>";
      $tanggal_sesi_p1 = $d['tanggal_sesi'];
    } 
    $tr .= "
    <tr>
      <td class='upper'>$d[pertemuan_ke]</td>
      <td class='upper'>$d[tanggal_sesi]</td>
      <td class='upper'>
        <span class=debug>hasil_batch__$d[pertemuan_ke]</span>
        <input class='form-control hasil_batch' name='hasil_batch__$d[pertemuan_ke]' id='hasil_batch__$d[pertemuan_ke]' >
      </td>
    </tr>"; 
  }

  $awal_perkuliahan = date('Y-m-d',strtotime($tanggal_sesi_p1));
  $pukul_p1 = date('H:i',strtotime($tanggal_sesi_p1));

  $btn_apply = "<button class='btn btn-primary' id='btn_apply_batch'>Apply Batch Tanggal</button>";
  $tb_sesi = "<table class='table table-striped table-hover'>$thead$tr</table>$btn_apply";
}










?>
<div class="wadah">
  <h3>Opsi Batch</h3>
  <table class='table table-hover '>
    <tr>
      <td>Tanggal P1</td>
      <td colspan=2>
        <input class='form-control opsi_batch' type="date" value="<?=$awal_perkuliahan?>" id=awal_perkuliahan>
      </td>
    </tr>
    <tr>
      <td>Pukul</td>
      <td colspan=2>
        <input class='form-control opsi_batch' type="time" value="<?=$pukul_p1?>" id=pukul_p1>
      </td>
    </tr>
    <tr>
      <td>Recurrence setiap</td>
      <td>
        <select class='form-control opsi_batch' id=recurrence>
          <option>1</option>
          <option>2</option>
          <option>3</option>
          <option>4</option>
          <option>5</option>
          <option>6</option>
          <option selected>7</option>
        </select>
      </td>
      <td>hari</td>
    </tr>
    <tr>
      <td>Minggu tenang UTS</td>
      <td>
        <select class='form-control opsi_batch' id=minggu_tenang_uts>
          <option selected>0</option>
          <option>1</option>
          <option>2</option>
        </select>
      </td>
      <td>minggu</td>
    </tr>
    <tr>
      <td>Durasi UTS</td>
      <td>
        <select class='form-control opsi_batch' id=durasi_uts>
          <option>0</option>
          <option selected>1</option>
          <option>2</option>
        </select>
      </td>
      <td>minggu</td>
    </tr>
    <tr>
      <td>Minggu tenang UAS</td>
      <td>
        <select class='form-control opsi_batch' id=minggu_tenang_uas>
          <option selected>0</option>
          <option>1</option>
          <option>2</option>
        </select>
      </td>
      <td>minggu</td>
    </tr>
  </table>
  <div class="debug" id=debug1></div>
</div>
<h3>Hasil Batch Tanggal</h3>
<?=$tb_sesi ?>











<script>
  $(function(){
    $(".opsi_batch").change(function(){
      let awal_perkuliahan = $("#awal_perkuliahan").val();
      let pukul_p1 = $("#pukul_p1").val();
      let recurrence = $("#recurrence").val();
      let minggu_tenang_uts = $("#minggu_tenang_uts").val();
      let minggu_tenang_uas = $("#minggu_tenang_uas").val();
      let durasi_uts = $("#durasi_uts").val();

      $("#debug1").text(`
      awal_perkuliahan:${awal_perkuliahan}\n
      pukul_p1:${pukul_p1}\n
      recurrence:${recurrence}\n
      minggu_tenang_uts:${minggu_tenang_uts}\n
      minggu_tenang_uas:${minggu_tenang_uas}\n
      durasi_uts:${durasi_uts}\n
      `);

      let jumlah_sesi = $("#jumlah_sesi").text();
      let next_tanggal;
      // alert(jumlah_sesi); return;
      for (let i=1; i <= jumlah_sesi; i++) {
        $("#hasil_batch__"+i).val(pukul_p1);
        next_tanggal = 'zzz here';
      }

    });
    $(".opsi_batch").change();
  })
</script>