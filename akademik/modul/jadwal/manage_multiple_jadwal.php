<?php $judul = "<h1>MANAGE MULTIPLE JADWAL</h1>"; ?>
<style>
  .ids-kurikulum h2{margin-top:0; color: darkblue; }
  .kurikulum {}
  .semester-ke {font-size:24px !important; color:darkblue !important; margin-bottom:10px}
  .tb-semester-mk th{text-align:left}

  .btn_tambah_semester {margin-bottom:10px}
  .tb_aksi td{
    padding:0 1px !important;
    border: none !important;
  }
</style>
<?php

$id_kurikulum = isset($_GET['id_kurikulum']) ? $_GET['id_kurikulum'] : '';
if($id_kurikulum<1) die('<script>location.replace("?master&p=kurikulum")</script>');

# ==============================================================
# GET KURIKULUM DATA
# ==============================================================
$s = "SELECT 
b.nama as nama_prodi, 
a.nama as nama_kurikulum, 
c.angkatan,
d.nama as jenjang,
a.basis, 
c.jumlah_semester,
a.is_publish, 
a.tanggal_penetapan, 
a.ditetapkan_oleh,
c.jumlah_bulan_per_semester,
b.id as id_prodi, 
c.id as id_kalender, 
a.id as id_kurikulum 

FROM tb_kurikulum a 
JOIN tb_prodi b ON b.id=a.id_prodi 
JOIN tb_kalender c ON c.id=a.id_kalender  
JOIN tb_jenjang d ON d.jenjang=c.jenjang  
WHERE a.id='$id_kurikulum'";
$q = mysqli_query($cn, $s)or die(mysqli_error($cn));
if(!mysqli_num_rows($q)) die('Data kurikulum tidak ditemukan.');
$d = mysqli_fetch_assoc($q);
$jumlah_semester = $d['jumlah_semester'];
$nama_kurikulum = $d['nama_kurikulum'];
$id_kalender = $d['id_kalender'];
$id_prodi = $d['id_prodi'];

$back_to = "<div class=mb2>Back to : 
  <a href='?manage_kalender&id_kalender=$id_kalender' class=proper>Manage kalender</a> | 
  <a href='?manage_kurikulum&id_kurikulum=$id_kurikulum' class=proper>Manage kurikulum</a>  
</div>
";


echo "<div class=debug id=keterangan_kurikulum>$d[nama_kurikulum] Prodi $d[nama_prodi] Angkatan $d[angkatan] Jenjang $d[jenjang]</div>";

$tr='';
foreach($d as $kolom=>$isi){
  // if($kolom=='is_publish') {$isi = $isi==0 ? 'belum' : 'sudah'; $isi="<span class='abu miring'>-- $isi --</span>"; }
  if($kolom=='is_publish' 
  || $kolom=='nama_prodi' 
  || $kolom=='angkatan' 
  || $kolom=='jenjang' 
  || $kolom=='tanggal_penetapan' 
  || $kolom=='ditetapkan_oleh') continue;
  $debug = substr($kolom,0,3)=='id_' ? 'debug' : '';
  $kolom_caption = str_replace('_',' ',$kolom);
  $isi = $isi=='' ? '<span class="abu miring">-- null --</span>' : $isi;
  $tr.="<tr class=$debug><td class=upper>$kolom_caption</td><td id='$kolom'>$isi</td></td>";
}


echo "
$back_to
$judul
<div class='wadah ids-kurikulum'>
<h2>Identitas Kurikulum</h2>
<table class=table>
  $tr
</table>
<div class=text-right><a href='?master&p=kurikulum&aksi=update&id=$id_kurikulum'>Update Identitas Kurikulum</a></div>
</div>";




# ==============================================================
# TAMPIL SEMESTERS
# ==============================================================
$s = "SELECT 
a.id as id_semester,
a.tanggal_awal,
a.tanggal_akhir,
a.nomor as no_semester 
FROM tb_semester a 
JOIN tb_kalender b ON b.id=a.id_kalender 
JOIN tb_kurikulum c ON c.id_kalender=b.id  

WHERE c.id='$id_kurikulum' 
ORDER BY a.nomor 
";
$q = mysqli_query($cn, $s)or die(mysqli_error($cn));

$jumlah_semester_real = mysqli_num_rows($q);
$semesters = '';
$rnomor_semester = [];
$i=0;
while ($d=mysqli_fetch_assoc($q)) {
  $i++; 
  array_push($rnomor_semester,$d['no_semester']);

  # ==============================================================
  # LIST MATA KULIAH
  # ==============================================================
  $s2 = "SELECT 
  a.id as id_mk,
  a.kode as kode_mk,
  a.nama as nama_mk,
  b.id as id_kurikulum_mk, 
  (SELECT count(1) from tb_kurikulum_mk WHERE id_mk=a.id) as jumlah_assign_mk, 
  (SELECT 1 from tb_jadwal WHERE id_kurikulum_mk=b.id) as telah_terjadwal  

  FROM tb_mk a 
  JOIN tb_kurikulum_mk b ON a.id=b.id_mk 
  JOIN tb_semester c ON b.id_semester=c.id  
  JOIN tb_kurikulum d ON b.id_kurikulum=d.id  
  WHERE c.id='$d[id_semester]' 
  AND d.id_prodi=$id_prodi
  ";
  $q2 = mysqli_query($cn, $s2)or die(mysqli_error($cn));
  $jumlah_mk = mysqli_num_rows($q2);
  echo "<span class=debug>jumlah_mk__$d[id_semester]: <span id='jumlah_mk__$d[id_semester]'>$jumlah_mk</span></span> ";

  $tr = '';
  $j=0;
  while ($d2=mysqli_fetch_assoc($q2)) {
    $j++;

    $tr.="
    <tr id='tr__$d2[id_mk]'>
      <td>$j</td>
      <td class='editable' id='kode__mk__$d2[id_mk]'>$d2[kode_mk]</td>
      <td class='editable' id='nama__mk__$d2[id_mk]'>$d2[nama_mk]</td>
      <td>SELECT_DOSEN</td>
    </tr>    
    ";
  } //end while list MK

  $tr = $tr=='' ? "<tr><td class='red miring' colspan=9>Belum ada MK pada semester ini.</td></tr>" : $tr;

  $tanggal_awal_sty = strtotime($d['tanggal_awal']) < strtotime('2018-1-1') ? 'merah tebal' : '';
  $tanggal_akhir_sty = strtotime($d['tanggal_akhir']) < strtotime('2018-1-1') ? 'merah tebal' : '';
  $tanggal_awal_show = "<span class='$tanggal_awal_sty'>".date('d M Y', strtotime($d['tanggal_awal'])).'</span>';
  $tanggal_akhir_show = "<span class='$tanggal_awal_sty'>".date('d M Y', strtotime($d['tanggal_akhir'])).'</span>';

  $wadah = strtotime($d['tanggal_akhir']) < strtotime($today) ? 'wadah gradasi-merah' : 'wadah'; 
  $wadah = (strtotime($d['tanggal_awal']) <= strtotime($today) and strtotime($d['tanggal_akhir']) >= strtotime($today)) ? 'wadah_active' : $wadah; 
  $semester_aktif = $wadah=='wadah_active' ? '(Semester Aktif)' : ''; 
  $semester_lampau = $wadah=='wadah gradasi-merah' ? '(Semester Lampau)' : ''; 


  $semesters .= "
  <div class='col-lg-6' id='semester__$d[id_semester]'>
    <div class='$wadah'>
      <div class='semester-ke'>
        Semester $d[no_semester] $semester_aktif $semester_lampau
      </div>
      <p>Rentang Waktu: $tanggal_awal_show s.d $tanggal_akhir_show | <a href='?manage_kalender&id_kalender=$id_kalender'>Manage</a></p>
      <table class='table tb-semester-mk'>
        <thead>
          <th>No</th>
          <th>Kode</th>
          <th>Mata Kuliah</th>
          <th>Jadwalkan dengan Dosen:</th>
          </thead>
        
        $tr
        
      </table>
    </div>
  </div>
  ";

  if($i % 2 ==0) $semesters .= '</div><div class=row>';
} // end while semesters




$kurikulum = $semesters=='' ? '<div class="alert alert-danger">Belum ada data semester</div>' : "<div class='row kurikulum'>$semesters</div>";

# ==============================================================
# TAMBAH SEMESTER
# ==============================================================
$btn_tambah = $jumlah_semester==$jumlah_semester_real ? '' 
: die("
<div class=wadah>
  <p>Jumlah semester pada Kalender ini adalah $jumlah_semester_real of $jumlah_semester. Anda dapat menambahkannya pada Manage Kalender.</p>
  <a href='?manage_kalender&id_kalender=$id_kalender' class='btn btn-primary'>Tambah Semester</a>
</div>");
// echo $btn_tambah;

# ==============================================================
# FINAL OUTPUT SEMESTERS
# ==============================================================
echo "$kurikulum$back_to";




?>

