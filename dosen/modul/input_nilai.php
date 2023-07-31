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
  # ====================================================
  # EXTRACT NILAIS AS VALUES
  # ====================================================
  // foreach ($_POST as $key => $value) {
  //   if(strpos("salt$key",'nilai__')){
  //     // echo "<br>$key = $value";
  //     $r = explode('__',$key);
  //     $id_kelas_angkatan_detail = $r[1];
  //     echo "<br>id_kelas_angkatan_detail: $id_kelas_angkatan_detail";
  //   }
  // }


  # ====================================================
  # UPDATE LAST_UPDATE UTS/UAS AT TB_KELAS_ANGKATAN
  # ====================================================

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
JOIN tb_kelas_ta d ON d.id=a.id_kelas_ta  
WHERE c.id=$id_jadwal ";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
while ($d=mysqli_fetch_assoc($q)) {
  $kelas = $d['kelas'];
  $judul = "NILAI $uts $kelas";

  # ====================================================
  # KELAS PESERTA (MHS)
  # ====================================================
  $nuts = $id_tipe_sesi==8 ? 'nuts' : 'nuas';
  $s2 = "SELECT 
  a.id,
  a.id_mhs,
  b.id as id_kelas_ta,
  b.last_update_nilai_uts,
  b.last_update_nilai_uas,
  b.tanggal_approve_nilai_uts,
  b.tanggal_approve_nilai_uas,
  c.nama as nama_mhs,
  c.nim,
  (
    SELECT z.$nuts 
    FROM tb_nilai z where z.id_kelas_angkatan_detail=a.id 
    ORDER BY date_created DESC LIMIT 1) as nilai

  FROM tb_kelas_ta_detail a 
  JOIN tb_kelas_ta b ON a.id_kelas_ta=b.id   
  JOIN tb_mhs c ON c.id=a.id_mhs    
  WHERE b.kelas='$kelas'";
  // echo "<pre class=debug>$s2</pre>";

  $q2 = mysqli_query($cn,$s2) or die(mysqli_error($cn));
  $tr_mhs='';
  $jumlah_mhs=0;
  $jumlah_valid=0;
  while ($d2=mysqli_fetch_assoc($q2)) {
    $jumlah_mhs++;
    $id = $d2['id'];
    $id_kelas_ta = $d2['id_kelas_ta'];
    $last_update = $id_tipe_sesi==8 ? $d2['last_update_nilai_uts'] : $d2['last_update_nilai_uts'];
    $tanggal_approve = $id_tipe_sesi==8 ? $d2['tanggal_approve_nilai_uts'] : $d2['tanggal_approve_nilai_uts'];

    $nilai = $d2['nilai'];
    $merah = ($nilai>=0 and $nilai <=100 and $nilai!='') ? '' : 'merah';
    if($merah=='') $jumlah_valid++;

    $tr_mhs .= "
    <div class='row mb-4'>
      <div class='col-lg-6'>
        <div class=row>
          <div class=col-1>
            $jumlah_mhs
          </div>
          <div class='col-11 upper'>
            $d2[nama_mhs]<span class=debug>$d2[id_mhs]</span>
          </div>
        </div>
      </div>
      <div class='col-lg-3'>NIM. $d2[nim]</span></div>
      <div class='col-lg-3 mt-2'>
        <input type=number min=0 max=100 required class='form-control input_nilai gradasi-$merah' id='$kelas"."__$id' value=$nilai>
        <span class=debug>nilai:<span id=span_nilai__$id>$nilai</span></span>
      </div>
    </div>
    ";

  }
  $thead = '';
  $debug_jumlah_mhs = "<span class=debug>jumlah_mhs__$kelas: <span id=jumlah_mhs__$kelas>$jumlah_mhs</span></span>";
  $debug_jumlah_valid = "<span class=debug>jumlah_valid__$kelas: <span id=jumlah_valid__$kelas>$jumlah_valid</span></span>";
  $disabled = $jumlah_mhs==$jumlah_valid ? '' : 'disabled';
  $disabled_info = $jumlah_mhs==$jumlah_valid ? '' : "<span class=red id=disabled_info__$kelas>Lengkap <span id=jumlah_valid_show__$kelas>$jumlah_valid</span> of $jumlah_mhs | </span>";

  $tb_mhs = $tr_mhs='' ? '<div>No Data Mhs</div>' : "$thead$tr_mhs $debug_jumlah_mhs $debug_jumlah_valid";

  $last_update_show = $last_update=='' ? '<span class=red>none</span> | Silahkan Anda Simpan terlebih dahulu sebelum Pengesahan Nilai '.$uts : date('d-M-Y H:i:s', strtotime($last_update));
  $disabled_pengesahan = $last_update=='' ? 'disabled' : '';
  $primary = $last_update=='' ? 'primary' : 'info';

  echo "
  <div class='wadah gradasi-hijau'>
    <h3 class='darkblue mb-4'>$judul</h3>
    <form method=post>
      <span class=debug>id_tipe_sesi</span>: <input class=debug name=id_tipe_sesi value=$id_tipe_sesi>
      <span class=debug>id_kelas_ta</span>: <input class=debug name=id_kelas_ta value=$id_kelas_ta>
      $tb_mhs 
      <div class='kecil miring mb2'>$disabled_info Last Update: $last_update_show</div>
      <button class='btn btn-$primary btn-block' name=btn_simpan id=btn_simpan__$kelas $disabled>Simpan Draft Nilai UTS</button>
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
      let kelas = rid[0];
      let id_kelas_angkatan_detail = rid[1];
      let val = $(this).val();
      let tmp_val = $('#span_nilai__'+id_kelas_angkatan_detail).text();
      let id_tipe_sesi = $('#id_tipe_sesi').text();
      let jumlah_mhs = parseInt($('#jumlah_mhs__'+kelas).text());
      let jumlah_valid = parseInt($('#jumlah_valid__'+kelas).text());
      // console.log(kelas, jumlah_mhs);

      if(val!=tmp_val && parseInt(val)>=0){

        // console.log(val,tmp_val,id_kelas_angkatan_detail);
        let link_ajax = `ajax_dosen/ajax_input_nilai.php?id_kelas_angkatan_detail=${id_kelas_angkatan_detail}&id_tipe_sesi=${id_tipe_sesi}&nilai=${val}&`;

        $.ajax({
          url:link_ajax,
          success:function(a){
            if(a.trim()=='sukses'){
              $('#span_nilai__'+id_kelas_angkatan_detail).text(val);
              $('#'+tid).removeClass('gradasi-merah');
              
              jumlah_valid++;
              if(jumlah_valid==jumlah_mhs){
                $('#btn_simpan__'+kelas).prop('disabled',false);
                $('#disabled_info__'+kelas).hide();
              }else{
                $('#btn_simpan__'+kelas).prop('disabled',true);
                $('#disabled_info__'+kelas).show();
              }
              $('#jumlah_valid__'+kelas).text(jumlah_valid);
              $('#jumlah_valid_show__'+kelas).text(jumlah_valid);

              console.log(jumlah_valid,jumlah_mhs,kelas);
            }else{
              alert(a)
            }
          }
        })
      }else{
        console.log('not saved.');
        
      }
    })
  })
</script>