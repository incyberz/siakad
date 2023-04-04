<?php
$judul = "SET JUDUL SESI";
$sub_judul = "<p>Untuk mengubah nama-nama sesi silahkan klik pada Cell Nama Sesi !</p>";
if(isset($_POST['btn_approve'])){
  $s = "UPDATE tb_jadwal set tanggal_approve_sesi=current_timestamp WHERE id=$_POST[id_jadwal] and  id_dosen=$_POST[id_dosen]";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  echo div_alert('success',"Terimakasih atas Approval Nama-nama Sesi Mata Kuliah Anda.<hr><a class='btn btn-primary' href='?mk_saya'>Kembali ke MK Saya</a>");
  exit;
}

$id_jadwal = isset($_GET['id_jadwal']) ? $_GET['id_jadwal'] : die(erid('id_jadwal'));
if($id_jadwal=='') die(erid('id_jadwal::empty'));


$back_to = "<div class='mb-2 mt-2' style='position:sticky;top:29px;z-index:998;padding:5px;border:solid 1px #ccc;background:white;font-size:small'>Back to: 
  <a href='?jadwal_dosen'>Jadwal Dosen</a>
</div>";


# ====================================================
# JADWAL PROPERTIES
# ====================================================
$s = "SELECT 
concat(c.nama,' / ', h.jenjang,'-', g.nama, ' ', h.angkatan) as jadwal,
b.id as id_kurikulum_mk,
b.id_semester,
b.id_kurikulum,
c.bobot_teori,
c.bobot_praktik,
d.id as id_dosen,
d.nama as dosen_koordinator,  
a.sesi_uts,  
a.sesi_uas,  
a.jumlah_sesi,
a.tanggal_jadwal,   
a.tanggal_approve_sesi,   
e.nomor as nomor_semester,   
e.awal_kuliah_uts as awal_perkuliahan,   
e.id_kalender    

FROM tb_jadwal a 
JOIN tb_kurikulum_mk b on b.id=a.id_kurikulum_mk 
JOIN tb_mk c on c.id=b.id_mk 
JOIN tb_dosen d on d.id=a.id_dosen 
JOIN tb_semester e on b.id_semester=e.id 
JOIN tb_kurikulum f on f.id=b.id_kurikulum 
JOIN tb_prodi g on g.id=f.id_prodi 
JOIN tb_kalender h on h.id=f.id_kalender 

WHERE a.id=$id_jadwal";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
if(mysqli_num_rows($q)==0) die('Data Jadwal tidak ditemukan.');
$d = mysqli_fetch_assoc($q);
$id_kurikulum = $d['id_kurikulum'];
$id_kurikulum_mk = $d['id_kurikulum_mk'];
$id_dosen = $d['id_dosen'];
$id_semester = $d['id_semester'];
$id_kalender = $d['id_kalender'];
$nomor_semester = $d['nomor_semester'];
$awal_perkuliahan = $d['awal_perkuliahan'];
$jumlah_sesi = $d['jumlah_sesi'];
$sesi_uts = $d['sesi_uts'];
$sesi_uas = $d['sesi_uas'];
$jadwal = $d['jadwal'];
$tanggal_approve_sesi = $d['tanggal_approve_sesi'];
$bobot = $d['bobot_teori']+$d['bobot_praktik'];

# ====================================================
# LIST SESI KULIAH
# ====================================================
$s = "SELECT 
a.id as id_sesi_kuliah,
a.id_dosen as id_dosen,
a.pertemuan_ke,
a.nama as nama_sesi,
b.nama as nama_dosen,
(SELECT count(1) FROM tb_assign_ruang WHERE id_sesi_kuliah=a.id) as jumlah_ruang, 
(SELECT count(1) FROM tb_presensi_dosen WHERE id_sesi_kuliah=a.id) as jumlah_presensi_dosen, 
(SELECT count(1) FROM tb_presensi WHERE id_sesi_kuliah=a.id) as jumlah_presensi_mhs 

FROM tb_sesi_kuliah a 
JOIN tb_dosen b on b.id=a.id_dosen 
where a.id_jadwal=$id_jadwal order by a.pertemuan_ke";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
if(mysqli_num_rows($q)==0){
  echo div_alert('danger','Belum ada sesi untuk MK ini. Silahkan hubungi Petugas Akademik!');
}else{


  $thead = "
  <thead>
    <th class='text-left upper'>Pertemuan ke</th>
    <th class='text-left upper'>Nama Sesi</th>
    <th class='text-left upper'>Tim Teaching</th>
  </thead>"; 
  $tr = '';
  $total_presensi_dosen =0;
  $total_presensi_mhs =0;
  $is_red = 0;
  while ($d=mysqli_fetch_assoc($q)) {
    
    # ========================================================
    # FINAL TR OUTPUT
    # ========================================================
    $editable = ($d['pertemuan_ke']==$sesi_uts || $d['pertemuan_ke']==$sesi_uas) ? '' : 'editable';
    $nama_sesi = strtoupper($d['nama_sesi']);
    $red = strpos("salt$nama_sesi",'NEW P') ? 'red' : '';
    $is_red = $red=='red' ? 1 : $is_red;
    $tr .= "
    <tr class=''>
      <td class='upper'>
        $d[pertemuan_ke] 
      </td>
      <td class='upper $editable $red' id='nama__$d[id_sesi_kuliah]'>$d[nama_sesi]</td>
      <td class='upper'>
        <a href='?dosen_detail&id_dosen=$d[id_dosen]' target='_blank'>$d[nama_dosen]</a>
      </td>
    </tr>"; 
  }

  // $total_presensi_dosen = 1; //debug
  $total_presensi = $total_presensi_dosen+$total_presensi_mhs;
  $disabled = $is_red ? 'disabled' : '';
  $hideit = $is_red ? '' : 'hideit';
  $tanggal_approve_sesi_show = $tanggal_approve_sesi==''?'':div_alert('success','Anda pernah approve pada tanggal: '.date('d-F-Y H:i:s',strtotime($tanggal_approve_sesi)).' Silahkan Anda boleh re-approve kembali.');

  echo "
  <h3>$judul</h3>
  $sub_judul
  <h5>$jadwal</h5>
  <table class='table table-striped table-hover'>
    $thead
    $tr
  </table>

  <div class='wadah'>
    <form method='post'>
      <input class=debuga name=id_jadwal value=$id_jadwal>
      <input class=debuga name=id_dosen value=$id_dosen>
      <h4>Persetujuan Nama-nama Sesi</h4>
      $tanggal_approve_sesi_show
      <div class='alert alert-danger $hideit'>
        Masih ada Default Name untuk Sesi Kuliah. Silahkan ubah sesuai RPS Anda! Kemudian Refresh!
      </div>
      <input type='checkbox' id=cek $disabled>
      <label for='cek'>Dengan ini saya menyatakan bahwa Nama-nama Sesi pada MK saya sudah benar.</label>
      <button class='btn btn-primary btn-block' name=btn_approve $disabled>Approve Nama-nama Sesi Kuliah</button>
    </form>
  </div>
  ";

} // end if jika sesi sudah ada

?>


<script>
  $(function(){
    $(".editable").click(function(){
      let tid = $(this).prop('id');
      let rid = tid.split('__');
      let acuan = rid[1];

      let isi = $(this).text();
      let isi_baru = prompt('Masukan antara 10 s.d 50 karakter:',isi);

      // VALIDASI CANCEL/EMPTY
      if(isi_baru===null) return;
      isi_baru = isi_baru.trim();
      if(isi_baru==isi) return;

      // ALLOW NULL
      // isi_baru = isi_baru==='' ? 'NULL' : isi_baru;
      
      // VALIDASI LENGTH
      if(isi_baru.length<10 || isi_baru.length>50){
        alert('Masukan antara 10 s.d 50 karakter. Silahkan coba kembali!');
        return;
      }

      let link_ajax = `../ajax_global/ajax_global_update.php?tabel=tb_sesi_kuliah&kolom_target=nama&isi_baru=${isi_baru.toUpperCase()}&acuan=${acuan}&kolom_acuan=id`;

      $.ajax({
        url:link_ajax,
        success:function(a){
          if(a.trim()=='sukses'){
            $("#"+tid).text(isi_baru);
            $("#"+tid).removeClass('red');

          }else{
            console.log(a);
            alert('Gagal mengubah data.');
          }
        }
      })


    });    
  })
</script>