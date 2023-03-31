<?php $judul = "<h1>CEK ALL SESI PADA KURIKULUM</h1>"; ?>
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

include 'include/akademik_icons.php';


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
  <a href='?manage_multiple_jadwal&id_kurikulum=$id_kurikulum'>Manage Multiple Jadwal</a>
";


echo "<div class=debug id=keterangan_kurikulum>$d[nama_kurikulum] Prodi $d[nama_prodi] Angkatan $d[angkatan] Jenjang $d[jenjang]</div>";

// $tb_kurikulum='';
// foreach($d as $kolom=>$isi){
//   // if($kolom=='is_publish') {$isi = $isi==0 ? 'belum' : 'sudah'; $isi="<span class='abu miring'>-- $isi --</span>"; }
//   if($kolom=='is_publish' 
//   || $kolom=='nama_prodi' 
//   || $kolom=='basis' 
//   || $kolom=='jumlah_semester' 
//   || $kolom=='jumlah_bulan_per_semester' 
//   || $kolom=='nama_prodi' 
//   || $kolom=='angkatan' 
//   || $kolom=='jenjang' 
//   || $kolom=='tanggal_penetapan' 
//   || $kolom=='ditetapkan_oleh') continue;
//   $debug = substr($kolom,0,3)=='id_' ? 'debug' : '';
//   $kolom_caption = str_replace('_',' ',$kolom);
//   $isi = $isi=='' ? '<span class="abu miring">-- null --</span>' : $isi;
//   $tb_kurikulum.="<tr class=$debug><td class=upper>$kolom_caption</td><td id='$kolom'>$isi</td></td>";
// }
$tb_kurikulum = "<table class=table><tr><td class=upper>kurikulum</td><td class=upper>$d[nama_kurikulum]</td></td></table>";






# ==============================================================
# TAMPIL SEMESTERS
# ==============================================================
$s = "SELECT 
a.id as id_semester,
a.nomor as no_semester,
a.tanggal_awal, 
a.tanggal_akhir  
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
$total_mk = 0;
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
  (SELECT id FROM tb_jadwal WHERE id_kurikulum_mk=b.id) as id_jadwal,  
  (SELECT count(1) FROM tb_sesi_kuliah c 
  JOIN tb_jadwal d on c.id_jadwal=d.id  
  WHERE d.id_kurikulum_mk=b.id) as jumlah_sesi  

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
  // list MK looping
  while ($d2=mysqli_fetch_assoc($q2)) { 
    $j++;
    $total_mk++;

    $img_aksi_next = $d2['jumlah_sesi'] ? $img_aksi['check'] : $img_aksi['next'] ;
    $red_bold = $d2['jumlah_sesi'] ? '' : 'red bold' ;
    $link_manage_sesi = "<span><a href='?manage_sesi&id_jadwal=$d2[id_jadwal]'>$img_aksi_next</a></span>";


    $tr.="
    <tr id='tr__$d2[id_mk]'>
      <td>$j</td>
      <td class='$red_bold'>$d2[kode_mk]</td>
      <td class='$red_bold'>$d2[nama_mk]</td>
      <td class='$red_bold'>$d2[jumlah_sesi] sesi</td>
      <td>
        <table class=tb_aksi>
          <tr>
            <td>$link_manage_sesi</td>
          </tr>
        </table>
      </td> 
    </tr>    
    ";
  } //end while list MK



  $tr = $tr=='' ? "<tr><td class='red miring' colspan=9>Belum ada MK pada semester ini.</td></tr>" : $tr;

  $tanggal_awal_sty = strtotime($d['tanggal_awal']) < strtotime('2018-1-1') ? 'merah tebal' : '';
  $tanggal_akhir_sty = strtotime($d['tanggal_akhir']) < strtotime('2018-1-1') ? 'merah tebal' : '';
  $tanggal_awal_show = "<span class='$tanggal_awal_sty'>".date('d M Y', strtotime($d['tanggal_awal'])).'</span>';
  $tanggal_akhir_show = "<span class='$tanggal_awal_sty'>".date('d M Y', strtotime($d['tanggal_akhir'])).'</span>';

  $wadah = strtotime($d['tanggal_akhir']) < strtotime($today) ? 'wadah gradasi-kuning' : 'wadah'; 
  $wadah = (strtotime($d['tanggal_awal']) <= strtotime($today) and strtotime($d['tanggal_akhir']) >= strtotime($today)) ? 'wadah_active' : $wadah; 
  $semester_aktif = $wadah=='wadah_active' ? '(Semester Aktif)' : ''; 
  $semester_lampau = $wadah=='wadah gradasi-kuning' ? '(Semester Lampau)' : ''; 


  $semesters .= "
  <div class='col-lg-6' id='semester__$d[id_semester]'>
    <div class='$wadah'>
      <div class='semester-ke'>
        Semester $d[no_semester] $semester_aktif $semester_lampau
      </div>
      <p>Rentang Waktu: $tanggal_awal_show s.d $tanggal_akhir_show</p>
      <table class='table tb-semester-mk'>
        <thead>
          <th>No</th>
          <th>Kode</th>
          <th>Mata Kuliah</th>
          <th>Jumlah Sesi</th>
          <th colspan=3 style='text-align:center'>Manage Sesi</th>
        </thead>
        
        $tr
        
      </table>
    </div>
  </div>
  ";

  if($i % 2 ==0) $semesters .= '</div><div class=row>';
} // end while semesters





$blok_semesters = $semesters=='' ? '<div class="alert alert-danger">Belum ada data semester</div>' : "<div class='row kurikulum'>$semesters</div>";

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
# CLOSING BACKTO
# ==============================================================
$back_to.='</div>';

# ==============================================================
# FINAL OUTPUT SEMESTERS
# ==============================================================
echo "
$back_to
$judul
$tb_kurikulum
$blok_semesters
$back_to
";





?>
<div style="position:fixed; top:5px; right: 5px; z-index:9999; display:none; cursor:pointer" id="blok_refresh">
  <div class="alert alert-info" style="border-radius: 10px; border:solid 3px white">
    <span style="display:inline-block; margin-right:15px">Anda melakukan perubahan.</span> <button class="btn btn-info btn-sm" onclick="location.reload()">Refresh</button>
  </div>
</div>



















