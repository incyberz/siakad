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
  //     $id_kelas_ta_detail = $r[1];
  //     echo "<br>id_kelas_ta_detail: $id_kelas_ta_detail";
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
a.tanggal_approve_soal_uts,  
a.tanggal_approve_soal_uas,  
a.shift,  
b.id as id_kurikulum_mk,
c.nama as mata_kuliah, 
d.id_prodi,
e.angkatan,
f.nomor as semester  

FROM tb_jadwal a 
JOIN tb_kurikulum_mk b on b.id=a.id_kurikulum_mk 
JOIN tb_mk c on c.id=b.id_mk 
JOIN tb_kurikulum d on b.id_kurikulum=d.id  
JOIN tb_kalender e on d.id_kalender=e.id  
JOIN tb_semester f on b.id_semester=f.id  
WHERE a.id=$id_jadwal";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
if(mysqli_num_rows($q)==0) die('Data Jadwal tidak ditemukan.');
$d = mysqli_fetch_assoc($q);
$id_kurikulum_mk= $d['id_kurikulum_mk']; 
echo "<span class=debug> | id_kurikulum_mk:<span id=id_kurikulum_mk>$id_kurikulum_mk</span></span>";
$sub_judul.= " MK $d[mata_kuliah].";
$tanggal_approve = $id_tipe_sesi==8 ? $d['tanggal_approve_soal_uts'] : $d['tanggal_approve_soal_uas'];






# ========================================================
# GET KELAS PESERTA
# ========================================================
$jumlah_peserta_mhs=0;
$tahun_ajar = $d['angkatan'] + intval(($d['semester']-1)/2);
$s = "SELECT *,
(SELECT count(1) FROM tb_kelas_ta_detail WHERE id_kelas_ta=a.id) jumlah_mhs 
FROM tb_kelas_ta a 
JOIN tb_kelas b ON a.kelas=b.kelas 
WHERE a.tahun_ajar='$tahun_ajar' 
AND b.angkatan='$d[angkatan]' 
AND b.id_prodi='$d[id_prodi]' 
AND b.shift='$d[shift]' 
";

$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
while ($d=mysqli_fetch_assoc($q)) {
  $kelas = $d['kelas'];
  $judul = "NILAI $uts $kelas TA.$tahun_ajar";

  # ====================================================
  # KELAS PESERTA (MHS)
  # ====================================================
  $nuts = $id_tipe_sesi==8 ? 'nuts' : 'nuas';
  $s2 = "SELECT 
  a.id,
  a.nim,
  b.id as id_kelas_ta,
  b.last_update_nilai_uts,
  b.last_update_nilai_uas,
  b.tanggal_approve_nilai_uts,
  b.tanggal_approve_nilai_uas,
  c.nama as nama_mhs,
  c.id as id_mhs,
  c.nim,
  (
    SELECT z.$nuts 
    FROM tb_nilai z where z.nim=a.nim  
    ORDER BY date_created DESC LIMIT 1) as nilai

  FROM tb_kelas_ta_detail a 
  JOIN tb_kelas_ta b ON a.id_kelas_ta=b.id   
  JOIN tb_mhs c ON c.nim=a.nim    
  WHERE b.kelas='$kelas'";
  echo "<pre class=debug>$s2</pre>";

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

    $bg = $jumlah_mhs%2==0 ? 'background: #0000ff11;' : '';

    $tr_mhs .= "
    <div style='border-top: solid 1px #faf; $bg; padding: 0 8px;'>
      <div class='row'>
        <div class='col-lg-5'>
          $jumlah_mhs. $d2[nama_mhs] | $d2[nim] <span class=debug>$d2[id_mhs]</span>
        </div>
        <div class='col-lg-3 mt-2'>
          <input type=number min=0 max=100 required class='form-control input_nilai input_nilai__$kelas gradasi-$merah' id='$d2[nim]__$kelas' value=$nilai>
          <span class=debug>
            | input-id:$d2[nim]__$kelas 
            | span_nilai__$id:<span id=span_nilai__$id>$nilai</span>
          </span>
        </div>
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
      let val = $(this).val();
      if(val==0){
        let y = confirm('Yakin untuk memberikan nilai 0 ?');
        if(!y){
          $(this).val('');
          return;
        }else{
          $(this).val(0);
          val = 0;
        }       
      }else if(val<0 || val>100){
        alert('Masukan nilai antara 0 s.d 100.00 !');
        $(this).val('');
        return;
      }
      let tid = $(this).prop('id');
      let rid = tid.split('__');
      let nim = rid[0];
      let kelas = rid[1];
      let tmp_val = parseFloat($('#span_nilai__'+kelas).text());
      let id_tipe_sesi = $('#id_tipe_sesi').text();
      let jumlah_valid = 0;
      let jumlah_mhs = 0;
      let id_kurikulum_mk = $('#id_kurikulum_mk').text();

      if(val!=tmp_val && val>=0){

        // console.log(val,tmp_val,kelas);
        let link_ajax = `ajax_dosen/ajax_input_nilai.php?nim=${nim}&id_kurikulum_mk=${id_kurikulum_mk}&id_tipe_sesi=${id_tipe_sesi}&nilai=${val}&`;

        $.ajax({
          url:link_ajax,
          success:function(a){
            if(a.trim()=='sukses'){
              $('#span_nilai__'+kelas).text(val);
              $('#'+tid).removeClass('gradasi-merah');

              let inputs = document.getElementsByClassName('input_nilai__'+kelas);
              // console.log(inputs);
              for (let i = 0; i < inputs.length; i++) {
                jumlah_mhs++;
                if(inputs[i].value == '') continue;
                if(inputs[i].value >= 0 && inputs[i].value <= 100) jumlah_valid++;
              }

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