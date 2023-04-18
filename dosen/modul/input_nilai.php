<?php
if(isset($_POST['btn_simpan'])){

  echo '<pre>';
  var_dump($_POST);
  echo '</pre>';

  // $id_tipe_sesi = $_POST['id_tipe_sesi'];

  // $back_to = "<hr><a href='?input_soal&id_jadwal=$id_jadwal&id_tipe_sesi=$id_tipe_sesi' class='btn btn-primary'>Kembali ke Input Soal</a>";
  // $alert = $err=='' ? 'success' : 'danger';
  // $pesan = $err=='' ? 'Upload Media Soal sukses.' : $err;

  // echo "<div class='alert alert-$alert'>$pesan$back_to</div>";
  echo "aksi btn_simpan ready to code. $btn_back";
  foreach ($_POST as $key => $value) {
    if(strpos("salt$key",'nilai__')){
      // echo "<br>$key = $value";
      $r = explode('__',$key);
      $id_kelas_angkatan_detail = $r[1];
      echo "<br>id_kelas_angkatan_detail: $id_kelas_angkatan_detail";
    }
  }
  exit;
}



$id_jadwal = isset($_GET['id_jadwal']) ? $_GET['id_jadwal'] : die(erid('id_jadwal'));
$id_tipe_sesi = isset($_GET['id_tipe_sesi']) ? $_GET['id_tipe_sesi'] : die(erid('id_tipe_sesi'));
if($id_jadwal=='') die(erid('id_jadwal::empty'));
echo "<span class=debug>id_jadwal: <span id=id_jadwal>$id_jadwal</span></span>";
echo "<br><span class=debug>id_tipe_sesi: <span id=id_tipe_sesi>$id_tipe_sesi</span></span>";
$jumlah_soal = 30;
$uts = $id_tipe_sesi==8 ? 'UTS' : 'HARIAN';
$uts = $id_tipe_sesi==16 ? 'UAS' : $uts;
$judul = 'INPUT NILAI '.$uts;
$sub_judul = "Silahkan input $jumlah_soal soal untuk SOAL $uts";
include 'input_soal_styles.php';


# ====================================================
# JADWAL PROPERTIES
# ====================================================
$s = "SELECT 
c.nama as mata_kuliah, 
a.tanggal_approve_soal_uts,  
a.tanggal_approve_soal_uas  

FROM tb_jadwal a 
JOIN tb_kurikulum_mk b on b.id=a.id_kurikulum_mk 
JOIN tb_mk c on c.id=b.id_mk 
WHERE a.id=$id_jadwal";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
if(mysqli_num_rows($q)==0) die('Data Jadwal tidak ditemukan.');
$d = mysqli_fetch_assoc($q);
$sub_judul.= " MK $d[mata_kuliah].";
$tanggal_approve = $id_tipe_sesi==8 ? $d['tanggal_approve_soal_uts'] : $d['tanggal_approve_soal_uas'];


# ====================================================
# KELAS-KELAS PESERTA
# ====================================================
$s = "SELECT 
d.kelas 
FROM tb_kelas_peserta a  
JOIN tb_kurikulum_mk b ON a.id_kurikulum_mk=b.id 
JOIN tb_jadwal c ON b.id=c.id_kurikulum_mk 
JOIN tb_kelas_angkatan d ON d.id=a.id_kelas_angkatan  
WHERE c.id=$id_jadwal ";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
while ($d=mysqli_fetch_assoc($q)) {
  $judul = "NILAI $uts $d[kelas]";

  # ====================================================
  # KELAS PESERTA (MHS)
  # ====================================================
  $s2 = "SELECT 
  a.id,
  a.id_mhs,
  b.last_update_nilai_uts,
  b.last_update_nilai_uas,
  b.tanggal_approve_nilai_uts,
  b.tanggal_approve_nilai_uas,
  c.nama as nama_mhs,
  c.nim,
  (SELECT CONCAT(z.np,';',z.nt,';',z.nuts,';',z.nuas,';',z.date_created) from tb_nilai z where z.id_kelas_angkatan_detail=a.id ORDER BY date_created DESC LIMIT 1) as data_nilai

  FROM tb_kelas_angkatan_detail a 
  JOIN tb_kelas_angkatan b ON a.id_kelas_angkatan=b.id   
  JOIN tb_mhs c ON c.id=a.id_mhs    
  WHERE b.kelas='$d[kelas]'";
  $q2 = mysqli_query($cn,$s2) or die(mysqli_error($cn));
  $tr_mhs='';
  $j=0;
  while ($d2=mysqli_fetch_assoc($q2)) {
    $j++;
    $id = $d2['id'];
    $last_update = $id_tipe_sesi==8 ? $d2['last_update_nilai_uts'] : $d2['last_update_nilai_uts'];
    $tanggal_approve = $id_tipe_sesi==8 ? $d2['tanggal_approve_nilai_uts'] : $d2['tanggal_approve_nilai_uts'];

    if($d2['data_nilai']==''){
      $np=0;
      $nt=0;
      $nuts=0;
      $nuas=0;
      $date_created='';
    }else{
      $r = explode(';',$d2['data_nilai']);
      $np=$r[0];
      $nt=$r[1];
      $nuts=$r[2];
      $nuas=$r[3];
      $date_created=$r[4];
    }

    $nilai = $id_tipe_sesi==8 ? $nuts : '?';
    $nilai = $id_tipe_sesi==16 ? $nuas : $nilai;
    $nilai = $nilai=='' ? 0 : $nilai;

    $tr_mhs .= "
    <div class='row mb-4'>
      <div class='col-lg-6'>
        <div class=row>
          <div class=col-1>
            $j
          </div>
          <div class=col-11>
            $d2[nama_mhs]<span class=debug>$d2[id_mhs]</span>
          </div>
        </div>
      </div>
      <div class='col-lg-3'>NIM. $d2[nim]</span></div>
      <div class='col-lg-3 mt-2'>
        <input type=number min=0 max=100 required class='form-control input_nilai' name=nilai__$id id=nilai__$id value=$nilai>
        <span class=debug>nilai:<span id=span_nilai__$id>$nilai</span></span>
      </div>
    </div>
    ";

  }
  $thead = '';
  $tb_mhs = $tr_mhs='' ? '<div>No Data Mhs</div>' : "$thead$tr_mhs";

  $last_update_show = $last_update=='' ? '<span class=red>none</span> | Silahkan Anda Simpan terlebih dahulu sebelum Pengesahan Nilai '.$uts : date('d-M-Y H:i:s', strtotime($last_update));
  $disabled_pengesahan = $last_update=='' ? 'disabled' : '';
  $primary = $last_update=='' ? 'primary' : 'info';

  echo "
  <div class='wadah gradasi-hijau'>
    <h3 class='darkblue mb-4'>$judul</h3>
    <form method=post>
      <input class=debug name=id_tipe_sesi value=$id_tipe_sesi>
      $tb_mhs 
      <div class='kecil miring mb2'>Last Update: $last_update_show</div>
      <button class='btn btn-$primary btn-block' name=btn_simpan>Simpan Draft Nilai UTS</button>
      <button class='btn btn-danger btn-block' $disabled_pengesahan>Pengesahan Nilai</button>
    </form>
  </div>
  ";

}








?>
<script>
  $(function(){
    $('.input_nilai').focusout(function(){
      let tid = $(this).prop('id');
      let rid = tid.split('__');
      let id_kelas_angkatan_detail = rid[1];
      let val = $(this).val();
      let tmp_val = $('#span_nilai__'+id_kelas_angkatan_detail).text();
      if(val!=tmp_val){
        // console.log(val,tmp_val,id_kelas_angkatan_detail);
        
        
      }else{
        console.log('not saved.');
        
      }
    })
  })
</script>