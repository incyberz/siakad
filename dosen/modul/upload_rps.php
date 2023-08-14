<?php

$judul = "UPLOAD RPS (RENCANA PEMBELAJARAN SEMESTER)";
$sub_judul = "Silahkan Upload RPS dengan ekstensi PDF max 1MB.";
if(isset($_POST['btn_upload'])){

  $err='';
  $files = $_FILES['file__'.$_POST['id_jadwal']];
  if($files['type']!='application/pdf'){
    $err = 'Ekstensi harus PDF';
    echo "ekt";
  }elseif($files['size']>1024000){
    $err = 'File terlalu besar. Maksimal 1MB.';
  }else{
    if(!move_uploaded_file($files['tmp_name'],"$folder_rps/$_POST[id_jadwal].pdf")){
      $err = 'Server error. Gagal memindahkan file upload';
    }
  }

  $back_to = "<hr><a href='?mk_saya' class='btn btn-primary'>Kembali ke MK Saya</a>";
  $alert = $err=='' ? 'success' : 'danger';
  $pesan = $err=='' ? 'Upload RPS sukses.' : $err;

  echo "<div class='alert alert-$alert'>$pesan$back_to</div>";
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
c.nama as mata_kuliah,
c.bobot_teori,
c.bobot_praktik,
d.nama as dosen_koordinator,  
e.nomor as untuk_semester,   
e.awal_kuliah_uts as awal_perkuliahan,   
e.id_kalender,
g.nama as program_studi, 
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
// $id_kurikulum_mk = $d['id_kurikulum_mk'];
// $untuk_semester = $d['untuk_semester'];
// $awal_perkuliahan = $d['awal_perkuliahan'];
// $jumlah_sesi = $d['jumlah_sesi'];
// $sesi_uts = $d['sesi_uts'];
// $sesi_uas = $d['sesi_uas'];
// $mata_kuliah = $d['mata_kuliah'];
// $bobot = $d['bobot_teori']+$d['bobot_praktik'];

$sub_judul .= "<div class='miring kecil mb2'>Misal: <code>RPS-$d[mata_kuliah]-$d[jenjang]-$d[program_studi]-$d[angkatan].pdf</code></div>";


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





$tb_mk = "
<table class=table>
  $tr
</table>";

$form_upload = "
<form method=post enctype='multipart/form-data'>
  <input class=debug name=id_jadwal value=$id_jadwal >
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