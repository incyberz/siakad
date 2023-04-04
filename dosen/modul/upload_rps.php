<?php
$judul = "UPLOAD RPS (RENCANA PEMBELAJARAN SEMESTER)";
$sub_judul = "Silahkan Upload RPS dengan ekstensi PDF.";
if(isset($_POST['btn_upload'])){
  
  // $s = "INSERT INTO tb_presensi_dosen 
  // (id_jadwal,id_dosen) VALUES 
  // ($_POST[id_jadwal],$_POST[id_dosen])";
  // $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  // echo div_alert('success',"Terimakasih Anda sudah mengisi Presensi.<hr><a class='btn btn-primary' href='?jadwal_dosen'>Kembali ke Jadwal</a>");
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
a.sesi_uts,  
a.sesi_uas,  
a.jumlah_sesi,
a.tanggal_jadwal,   
b.id as id_kurikulum_mk,
c.nama as nama_mk,
c.bobot_teori,
c.bobot_praktik,
d.nama as dosen_koordinator,  
e.nomor as nomor_semester,   
e.awal_kuliah_uts as awal_perkuliahan,   
e.id_kalender,
g.nama as nama_prodi, 
h.jenjang,
h.angkatan 


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
$id_kurikulum_mk = $d['id_kurikulum_mk'];
$nomor_semester = $d['nomor_semester'];
$awal_perkuliahan = $d['awal_perkuliahan'];
$jumlah_sesi = $d['jumlah_sesi'];
$sesi_uts = $d['sesi_uts'];
$sesi_uas = $d['sesi_uas'];
$nama_mk = $d['nama_mk'];
$bobot = $d['bobot_teori']+$d['bobot_praktik'];

$sub_judul .= "<div class='miring kecil mb2'>Misal: <code>RPS-$d[nama_mk]-$d[jenjang]-$d[nama_prodi]-$d[angkatan].pdf</code></div>";


$koloms = [];
$i=0;
$tr = '';
foreach ($d as $key => $value) {
  if($key=='sesi_uts'
  || $key=='sesi_uas'
  || $key=='jumlah_sesi'
  || $key=='tanggal_jadwal'
  ) continue;
  $koloms[$i] = str_replace('_',' ',$key);
  $debug = substr($key,0,2)=='id' ? 'debug' : 'upper';
  $tr .= "<tr class=$debug><td>$koloms[$i]</td><td id=$key>$value</td></tr>";
  $i++;
}




# ====================================================
# KELAS PESERTA
# ====================================================
$s2 = "SELECT * FROM tb_kelas_peserta a 
JOIN tb_kurikulum_mk b on b.id=a.id_kurikulum_mk  
JOIN tb_jadwal c on b.id=c.id_kurikulum_mk  
WHERE c.id=$id_jadwal ";
$q2 = mysqli_query($cn,$s2) or die(mysqli_error($cn));
if(mysqli_num_rows($q2)==0){
  $kelas_peserta = '<span class="miring red">--NULL--</span>';
}else{
  $kelas_peserta = '<ol style="padding-left:15px">';
  while ($d2=mysqli_fetch_assoc($q2)) {
    $kelas_peserta.= "<li>$d2[kelas]</li>";
  }
  $kelas_peserta .= '</ol>';
}


$tb_mk = "
<table class=table>
  $tr
  <tr>
    <td>KELAS PESERTA</td>
    <td>$kelas_peserta</td>
  </tr>
</table>";

$form_upload = "
<form method=post enctype='multipart/form-data'>
  <input type=file class=form-control name=file__$id_jadwal accept='.pdf' required>
  <button class='btn btn-primary btn-block mt-2' name=btn_upload>Upload</button>
</form>
";

echo "
<h3>$judul</h3>
$tb_mk
<div class=wadah>
  $sub_judul
  $form_upload
</div>
";
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