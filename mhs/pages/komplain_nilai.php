<?php
if(isset($_POST['btn_ajukan'])){
  echo '<pre>';
  var_dump($_POST);
  var_dump($_FILES);
  echo '</pre>';
  
  $id_nilai = $_POST['id_nilai'];
  $back = " | <a href='?komplain_nilai&id_nilai=$id_nilai'>Kembali</a>";
  if(move_uploaded_file($_FILES['bukti_komplain']['tmp_name'],"../uploads/komplain_nilai/$id_nilai.jpg")){
    $id_mhs = $_POST['id_mhs'];
    $id_dosen = $_POST['id_dosen'];
    $nilai_awal = $_POST['nilai_awal'];
    $seharusnya = $_POST['seharusnya'];
    
    $s = "INSERT INTO tb_komplain_nilai 
    (id_nilai,id_mhs,id_dosen,nilai_awal,seharusnya) values 
    ('$id_nilai','$id_mhs','$id_dosen','$nilai_awal','$seharusnya')";
    $q = mysqli_query($cn, $s) or die(mysqli_error($cn));

    die("<script>location.replace('?komplain_nilai&id_nilai=$id_nilai')</script>");
  }else{
    die(div_alert('danger', 'Proses Pengajuan Nilai gagal. '.$back));
  }
}

$form = $undef;
$id_nilai = isset($_GET['id_nilai']) ? $_GET['id_nilai'] : die(erid('id_nilai'));

$s = "SELECT a.*, b.dosen_manual, b.nama as nama_mk, b.id_dosen, 
(SELECT nama FROM tb_dosen WHERE id=b.id_dosen) as nama_dosen, 
(SELECT no_wa FROM tb_dosen WHERE id=b.id_dosen) as no_wa_dosen, 
(SELECT 1 FROM tb_komplain_nilai WHERE id_nilai=a.id) as sedang_komplain 

FROM tb_nilai_manual a 
JOIN tb_mk_manual b ON a.id_mk_manual=b.id  
WHERE a.id=$id_nilai";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$d = mysqli_fetch_assoc($q);

if($d['sedang_komplain']){
  $s = "SELECT a.*, 
  b.nama as nama_dosen,
  b.no_wa as no_wa_dosen 

  FROM tb_komplain_nilai a 
  JOIN tb_dosen b ON a.id_dosen=b.id  
  WHERE a.id_nilai=$id_nilai";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  $d=mysqli_fetch_assoc($q);

  // Status Komplain
  // 0|null belum diperiksa
  // 1 sudah dibaca
  // 2 disetujui
  // -1 rejected
  $status_komplain = $d['status']==1 ? 'Sedang diperiksa' : 'Belum dibaca';
  $status_komplain = $d['status']==2 ? 'Komplain Disetujui' : $status_komplain;
  $status_komplain = $d['status']==-1 ? 'Komplain Ditolak' : $status_komplain;

  $form = "
  <div class=wadah>
    <div class='tebal darkblue mb-2'>Status: Sedang Komplain.</div>
    <div class=wadah>
      <div>Kepada : $d[nama_dosen]</div>
      <div>Whatsapp : $d[no_wa_dosen]</div>
      <div>Status : $status_komplain</div>
      <div>
        <button class='not_ready btn btn-primary btn-block mt-2'>Kirim Pesan Whatsapp</button>
      </div>
    </div>
  </div>
  ";
}else{
  // $d['hm'] = strtoupper($d['hm']);
  // $d['id_dosen'] = 999; //zzz
  // $d['nama_dosen'] = 'IIN, M.Kom'; //zzz
  // $d['no_wa_dosen'] = '6287729007318'; //zzz
  // $d['hm'] = 'C'; //zzz

  $rhm = ['A','B','C','D','E'];
  $opthm = '';
  for ($i=0; $i < count($rhm); $i++) { 
    if($rhm[$i]==$d['hm']) break;
    $opthm.="<option>$rhm[$i]</option>";
  }

  if($d['id_dosen']==''){
    $form = div_alert('danger', 'Maaf, Data Mata Kuliah ini belum dipasangkan dengan Data Dosen pada SIAKAD.');
  }elseif($d['no_wa_dosen']==''){
    $form = div_alert('danger', "Maaf, Data Dosen dengan nama <u>$d[nama_dosen]</u> belum mempunyai data Nomor Whatsapp pada SIAKAD.");
  }elseif($d['hm']=='A'){
    $form = div_alert('info', "Nilai A tidak dapat diajukan Komplain Nilai.");
  }else{
    $form = "
      <form method=post enctype='multipart/form-data'>
        <input class='debug' value='$d[id]' name=id_nilai>
        <input class='debug' value='$id_mhs' name=id_mhs>
        <div class='form-group'>
          Nilai saat ini:
          <input class='form-control' value='$d[hm]' disabled>
          <input class='debug' value='$d[hm]' name=nilai_awal>
        </div>
        <div class='form-group'>
          Seharusnya:
          <select class='form-control' name=seharusnya>$opthm</select>
        </div>
        <div class='form-group'>
          Bukti Pendukung:
          <input type='file' required class=form-control accept='image/jpeg' name=bukti_komplain>
        </div>
        <div class='form-group'>
          Ajukan ke:
          <div class='wadah'>
            <div>Dosen: $d[nama_dosen]</div>
            <div>Whatsapp: $d[no_wa_dosen]</div>
            <input class=debug name=id_dosen value=$d[id_dosen]>
            <input class=debug name=no_wa_dosen value=$d[no_wa_dosen]>
          </div>
        </div>
        <div class='form-group'>
          <button class='btn btn-primary btn-block' name=btn_ajukan>Ajukan ke Dosen</button>
        </div>
      </form>
    ";
  }  
}
  

?>
<section id="" class="section-bg"  data-aos="fade-left">
  <div class="container">

    <div class="section-title">
      <h2>Komplain Nilai</h2>
      <p>Berikut adalah Fitur Komplain Nilai kepada Dosen Pengampu.</p>
      <div class=" wadah mt-2 kecil biru miring">Hanya berlaku bagi nilai selain A. Pastikan kamu punya Bukti Otentik berupa catatan dari dosen, hasil ujian, screenshoot, pesan whatsapp, atau data lainnya.</div>
    </div>

    <?=$form?>



  </div>
</section>