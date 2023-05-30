<?php
if(isset($_POST['btn_setuju'])){
  echo '<pre>';
  var_dump($_POST);
  var_dump($_FILES);
  echo '</pre>';
  
  $id_nilai = $_POST['id_nilai'];
  $back = " | <a href='?komplain_nilai&id_nilai=$id_nilai'>Kembali</a>";

  $nilai_awal = $_POST['nilai_awal'];
  $seharusnya = $_POST['seharusnya'];
  
  $s = "UPDATE tb_nilai_manual SET tanggal_disetujui_mhs=CURRENT_TIMESTAMP";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));

  die("<script>location.replace('?khs')</script>");

}

$form = $undef;
$warn = '';
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
  $d['hm'] = strtoupper($d['hm']);
  $d['id_dosen'] = 999; //zzz
  $d['nama_dosen'] = 'IIN, M.Kom'; //zzz
  $d['no_wa_dosen'] = '6287729007318'; //zzz
  $d['hm'] = 'C'; //zzz

  $rhm = ['A','B','C','D','E'];
  $opthm = '';
  for ($i=0; $i < count($rhm); $i++) { 
    if($rhm[$i]==$d['hm']) break;
    $opthm.="<option>$rhm[$i]</option>";
  }

  if($d['id_dosen']==''){
    $warn = div_alert('warning', 'Perhatian! Data Nilai Mata Kuliah ini belum dipasangkan dengan Data Dosen pada SIAKAD sehingga tidak bisa Whatsapp Gateway ke Dosen.');
  }elseif($d['no_wa_dosen']==''){
    $warn = div_alert('warning', "Maaf, Data Dosen dengan nama <u>$d[nama_dosen]</u> belum mempunyai data Nomor Whatsapp pada SIAKAD.");
  }

  $form = "
    <form method=post>
      <input class='debug' value='$d[id]' name=id_nilai>
      <div class='form-group'>
        Huruf Mutu saat ini:
        <input class='form-control' value='$d[hm]' disabled>
      </div>
      <div class='form-group'>
        Nilai Angka:
        <input class='form-control' value='$d[nilai]' disabled>
      </div>
      <div class='form-group'>
        <div class=form-group>
          <label for=cek_setuju>Saya setuju dengan nilai tersebut.</label>
          <input class=cek_setuju type=checkbox name=cek_setuju id=cek_setuju>
        </div>
        <div class='form-group hideit' id=blok_setuju2>
          <label for=cek_setuju2>Saya tidak akan mengajukan komplain nilai.</label>
          <input class=cek_setuju type=checkbox name=cek_setuju2 id=cek_setuju2>
        </div>
      </div>
      <div class='form-group'>
        <button class='btn btn-primary btn-block' name=btn_setuju id=btn_setuju disabled>Saya Setuju</button>
      </div>
    </form>
  ";
}
  

?>
<section id="" class="section-bg"  data-aos="fade-left">
  <div class="container">

    <div class="section-title">
      <h2>Setuju Nilai</h2>
      <p>Silahkan kamu setuju jika nilai berikut sesuai dengan usaha kamu.</p>
      <div class=" wadah mt-2 kecil biru miring">)* Kamu perlu menyetujui semua nilai agar dapat cetak KHS.</div>
    </div>

    <?=$warn?>
    <?=$form?>



  </div>
</section>

<script>
  $(function(){
    $('.cek_setuju').click(function(){
      let cek_setuju = $('#cek_setuju').prop('checked');
      let cek_setuju2 = $('#cek_setuju2').prop('checked');
      if(cek_setuju){
        $('#blok_setuju2').slideDown();
      }else{
        $('#blok_setuju2').slideUp();
        $('#blok_setuju2').prop('checked',false);
      }

      if(cek_setuju && cek_setuju2){
        $('#btn_setuju').prop('disabled',false);
      }else{
        $('#btn_setuju').prop('disabled',true);
      }
    })
  })
</script>