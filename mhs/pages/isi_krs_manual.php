<?php
$judul = 'Pengisian KRS';
$undef = '<span style="color:#f77; font-style:italic">undefined</span>';
$null = '<code class=miring>null</code>';
$belum_ada = '<code class=miring>belum ada</code>';
$jumlah_mk = $null;

echo "<span class=debug>mhs_var | angkatan:$angkatan | id_prodi:$id_prodi |  id_kalender:$id_kalender |  id_kurikulum:$id_kurikulum | </span>";


if (isset($_POST['btn_set_krs_default'])) {
  $angkatan = $_POST['angkatan'];
  $id_prodi = $_POST['id_prodi'];
  $s = "SELECT id,nominal_default FROM tb_krs_manual";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  $values = '';
  
  while ($d=mysqli_fetch_assoc($q)) {
    $id = $d['id'];
    $nominal = $d['nominal_default'];
    $values .= "('$id','$angkatan','$id_prodi','$nominal'),";
    
  }
  $s = "INSERT INTO tb_krs_mk_manual (id_krs,angkatan,id_prodi,nominal) VALUES $values".'__';
  $s = str_replace(',__','',$s);
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  echo div_alert('success', 'Set Nominal Default success. Redirecting ...');
  echo "<script>location.replace('?manage_krs&angkatan=$angkatan&id_prodi=$id_prodi')</script>";
  exit;

}

// echo $s;




# =====================================================
# NORMAL FLOW :: DATA KRS
# =====================================================
$id_krs_manual = isset($_GET['id_krs_manual']) ? $_GET['id_krs_manual'] : die(erid('id_krs_manual'));

$s = "SELECT *,c.nama as nama_mk FROM tb_krs_manual a 
JOIN tb_krs_mk_manual b ON a.id=b.id_krs_manual  
JOIN tb_mk_manual c ON b.id_mk_manual=c.id  
WHERE a.id='$id_krs_manual'";
// echo "$s<br>";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
if(mysqli_num_rows($q)==0) die('Data KRS tidak ditemukan.');
$tr='';
$i=0;
$total_bobot=0;
while ($d=mysqli_fetch_assoc($q)) {
  $i++;
  $total_bobot += $d['bobot'];
  $untuk_semester = $d['untuk_semester'];
  $tr .= "
  <tr>
    <td>$i</td>
    <td>$d[nama_mk]</td>
    <td>$d[bobot] SKS</td>
  </tr>";
}

$total = "<tr class=gradasi-kuning><td colspan=2>TOTAL SKS</td><td colspan=2>$total_bobot SKS</td></tr>";

$tb = "<table class='table table-striped table-hover'>$tr$total</table>";
// die($tb);



# =====================================================
# NORMAL FLOW :: CEK KEUANGAN
# =====================================================
$s = "SELECT *, a.id as id_biaya,
(SELECT nominal FROM tb_biaya_angkatan WHERE id_biaya=a.id and angkatan=$angkatan and id_prodi=$id_prodi) as nominal,  
(SELECT tanggal_penagihan FROM tb_penagihan WHERE id_biaya=a.id and id_mhs=$id_mhs) as tanggal_penagihan,  
(SELECT status FROM tb_bayar WHERE id_biaya=a.id and id_mhs=$id_mhs) as status_bayar,  
(SELECT tanggal_bayar FROM tb_bayar WHERE id_biaya=a.id and id_mhs=$id_mhs) as tanggal_bayar  
FROM tb_biaya a 
WHERE a.untuk_semester=$untuk_semester AND a.jenis='Biaya Registrasi'";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
$d = mysqli_fetch_assoc($q);

$id_biaya = $d['id_biaya'];
$tanggal_penagihan = $d['tanggal_penagihan']=='' ? $belum_ada : $d['tanggal_penagihan'];
$tanggal_bayar = $d['tanggal_bayar']=='' ? $belum_ada : $d['tanggal_bayar'];
$status_bayar = $d['status_bayar']=='' ? $belum_ada : $d['status_bayar'];
$nominal = $d['nominal']!='' ? $d['nominal'] : $d['nominal_default'];

echo "<span class=debug>id_biaya:$id_biaya 
| nominal:$nominal 
</span>";

$s = "SELECT * FROM tb_semester WHERE id_kalender='$id_kalender' AND nomor=$untuk_semester";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
$tanggal_awal_smt = $undef;
$tanggal_akhir_smt = $undef;
if(mysqli_num_rows($q)){
  $d = mysqli_fetch_assoc($q);
  $tanggal_awal_smt = $d['tanggal_awal'];
  $tanggal_akhir_smt = $d['tanggal_akhir'];
}



?>
<style>th{text-align:left}</style>
<section id="krs" class="" data-aos="fade-left">
  <div class="container">
    <div class="section-title">
      <h2><?=$judul?></h2>
      <div class="wadah kecil">
        Syarat KRS:
        <ul>
          <li>Terdapat Penagihan Registrasi Semester | Tanggal penagihan: <?=$tanggal_penagihan ?></li>
          <li>Lunas Pembayaran Registrasi Semester <?=$untuk_semester ?> | Status: <?=$status_bayar?></li>
          <li>Masuk pada Semester <?=$untuk_semester ?> | <?=$tanggal_awal_smt ?> s.d <?=$tanggal_akhir_smt ?></li>
        </ul>
      </div>
      <p>Berikut adalah Mata Kuliah yang dapat Anda ambil pada Semester <?=$untuk_semester?></p>
    </div>
    
    <?=$tb?>

  </div>
</section>






















<script>
  $(function(){
    $(".editable").click(function(){
      let tid = $(this).prop('id');
      let rid = tid.split('__');
      let kolom = rid[0];
      let angkatan = rid[1];
      let id_prodi = rid[2];
      let id_krs = rid[3];

      let isi = $(this).text();
      let isi_baru = prompt('Masukan nominal:',isi);

      // VALIDASI CANCEL/EMPTY
      if(isi_baru===null) return;

      isi_baru = isi_baru.trim();
      if(isi_baru==isi) return;

      // ALLOW NULL
      // isi_baru = isi_baru==='' ? 'NULL' : isi_baru;
      
      // VALIDASI VALUE
      isi_baru = parseInt(isi_baru);
      if(isi_baru==0 || isi_baru % 1000 != 0){
        alert('Masukan nominal kelipatan 1000. Silahkan coba kembali!');
        return;
      }else if(isi_baru>=100000000){
        alert('Nominal harus kurang dari 100 juta. Silahkan coba kembali!');
        return;
      }
      
      let link_ajax = `ajax_akademik/ajax_set_krs_angkatan.php?nominal=${isi_baru}&kolom=${kolom}&angkatan=${angkatan}&id_prodi=${id_prodi}&id_krs=${id_krs}`;
      // return;

      $.ajax({
        url:link_ajax,
        success:function(a){
          if(a.trim()=='sukses'){
            $("#"+tid).text(isi_baru);
            $("#"+tid).addClass('biru tebal');

          }else{
            console.log(a);
            alert('Gagal mengubah data.');
          }
        }
      })


    });    
  })
</script>

