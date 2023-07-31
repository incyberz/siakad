<style>th{text-align:left}.tb_semester{background:#ffffff77}</style>
<?php
$judul = "<h1>Manage Jadwal Dosen</h1><p>Proses assign Dosen Koordinator tiap MK dan rekap SKS Dosen.</p>";
$id_kurikulum = $_GET['id_kurikulum'] ?? '';
if(!$id_kurikulum || $id_kurikulum<1) die('<script>location.replace("?manage_jadwal")</script>');


# ==============================================================
# GET KURIKULUM DATA
# ==============================================================
$s = "SELECT 
CONCAT('Kurikulum ',c.jenjang,'-',b.singkatan,'-',c.angkatan) as nama_kurikulum, 
c.jumlah_semester,
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
  <a href='?manage_kurikulum&id_kurikulum=$id_kurikulum' class=proper>Manage kurikulum</a> | 
  <a href='?cek_all_sesi&id_kurikulum=$id_kurikulum' class=proper>Cek All Sesi</a>  
</div>
";



$default_option = '';
include 'include/option_dosen.php';
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
$set_dosen = []; //unik

$rid_dosen = []; // untuk hitung mk dan sks per dosen
$rid_mk = [];
$rnama_mk = [];
$rsks = [];
$rhomebase = [];

$rnomor_semester = [];
$i=0;
$k=0;
$total_mk=0;
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
  a.bobot_teori,
  a.bobot_praktik,
  b.id as id_kurikulum_mk, 
  (SELECT count(1) FROM tb_kurikulum_mk WHERE id_mk=a.id) as jumlah_assign_mk, 
  (SELECT id_dosen FROM tb_jadwal WHERE id_kurikulum_mk=b.id) as id_dosen,
  (
    SELECT d.nama FROM tb_jadwal c 
    JOIN tb_dosen d on c.id_dosen=d.id 
    WHERE c.id_kurikulum_mk=b.id
    ) as nama_dosen,    
  (
    SELECT e.nama FROM tb_jadwal c 
    JOIN tb_dosen d on c.id_dosen=d.id 
    JOIN tb_prodi e on d.homebase=e.id 
    WHERE c.id_kurikulum_mk=b.id
    ) as homebase     
     

  FROM tb_mk a 
  JOIN tb_kurikulum_mk b ON a.id=b.id_mk 
  JOIN tb_semester c ON b.id_semester=c.id  
  JOIN tb_kurikulum d ON b.id_kurikulum=d.id  
  WHERE c.id='$d[id_semester]' 
  AND d.id_prodi=$id_prodi
  ";
  $q2 = mysqli_query($cn, $s2)or die(mysqli_error($cn));
  // $jumlah_mk = mysqli_num_rows($q2);

  $tr = '';
  $j=0;
  while ($d2=mysqli_fetch_assoc($q2)) {
    $j++;

    if($d2['id_dosen']!=''){
      $rid_dosen[$k] = $d2['id_dosen'];
      $rid_mk[$k] = $d2['id_mk'];
      $rnama_mk[$k] = $d2['nama_mk'];
      $rhomebase[$k] = $d2['homebase'];
      $rsks[$k] = $d2['bobot_teori']+$d2['bobot_praktik'];
    }
    $k++;
    $total_mk++;


    $primary = $d2['id_dosen']=='' ? 'primary' : 'info';
    $nama_id_dosen = "$d2[nama_dosen] ~ $d2[id_dosen]";
    if(!in_array($nama_id_dosen,$set_dosen) and strlen($nama_id_dosen)>3){
      array_push($set_dosen,$nama_id_dosen);
    }

    $tr.="
    <tr id='tr__$d2[id_mk]'>
      <td>$j</td>
      <td>
        $d2[nama_mk] | $d2[kode_mk]
        <span class=debug  id='$d2[id_kurikulum_mk]__$d2[id_dosen]'>$d2[id_dosen]</span> 
      </td>
      <td>
        <select class='form-control select_id_dosen gradasi-merah' id='id_dosen__$d2[id_kurikulum_mk]__$d2[id_dosen]'>
          <option value=NULL class='abu miring'>-- Pilih --</option>
          $option_dosen
        </select>
      </td>
      <td>
        <button disabled id='btn_apply__$d2[id_kurikulum_mk]__$d2[id_dosen]' class='btn_apply btn btn-$primary btn-sm'>Apply</button>
      </td>
    </tr>    
    ";

    if($d2['id_dosen']){
      echo "
        <script>
          $(function(){
            let id_dosen = $('#$d2[id_kurikulum_mk]__$d2[id_dosen]').text();
            $('#id_dosen__$d2[id_kurikulum_mk]__$d2[id_dosen]').val(id_dosen);
            $('#id_dosen__$d2[id_kurikulum_mk]__$d2[id_dosen]').removeClass('gradasi-merah');
            $('#id_dosen__$d2[id_kurikulum_mk]__$d2[id_dosen]').addClass('gradasi-hijau');
          })
        </script>
      ";
    }

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
      <h4 class=darkblue>Semester $d[no_semester] $semester_aktif $semester_lampau</h4>
      <p class='kecil consolas miring'>$tanggal_awal_show s.d $tanggal_akhir_show</p>
      <table class='table tb_semester'>
        <thead>
          <th>No</th>
          <th>Mata Kuliah</th>
          <th>Dosen Koordinator</th>
          <th>Aksi</th>
          </thead>
        
        $tr
        
      </table>
    </div>
  </div>
  ";

  if($i % 2 ==0) $semesters .= '</div><div class=row>';
} // end while semesters




$kurikulum_semesters = $semesters=='' ? '<div class="alert alert-danger">Belum ada data semester</div>' : "<div class='row kurikulum'>$semesters</div>";

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
# DOSEN-DOSEN
# ==============================================================
sort($set_dosen);
$total_dosen = count($set_dosen);

$thead_dosen = '
<thead>
  <th>No</th>
  <th>Nama Dosen</th>
  <th>Homebase</th>
  <th>MK yang diampu</th>
  <th>Jumlah SKS</th>
</thead>
';
$tr_dosen = '';
for ($i=0; $i < count($set_dosen); $i++) {
  $j = $i+1; 
  $arr = explode(' ~ ',$set_dosen[$i]);
  $nama_dosen = $arr[0];
  $id_dosen = $arr[1];

  $li_mk='';
  $sum_sks=0;
  for ($m=0; $m < $total_mk; $m++) { 
    if(isset($rid_dosen[$m])){
      if($rid_dosen[$m]==$id_dosen){
        $sum_sks += $rsks[$m];
        $li_mk .= "<li>$rnama_mk[$m]</li>";
        $homebase = $rhomebase[$m];
      }
    }
  }


  $tr_dosen.= "
  <tr id=tr_dosen__$id_dosen>
    <td>$j</td>
    <td>$nama_dosen</td>
    <td>$homebase</td>
    <td>
      <ol style='margin:0; padding:0 0 0 15px'>
        $li_mk
      </ol>
    </td>
    <td>$sum_sks</td>
  </tr>
  ";
}

$total_mk_terjadwal = count($rid_mk);
$jumlah_sks_terjadwal = array_sum($rsks);
$tb_dosens = "
  <table class='table table-striped table-hover'>
    $thead_dosen
    $tr_dosen
    <tr><td colspan=3 class=text-right>JUMLAH TERJADWAL</td><td>$total_mk_terjadwal MK</td><td>$jumlah_sks_terjadwal SKS</td></tr>
  </table>
";

$bg_progres = $total_mk_terjadwal==$total_mk ? '' : 'gradasi-merah merah';

# ==============================================================
# FINAL OUTPUT SEMESTERS
# ==============================================================
// echo "<pre>";
// var_dump($rnama_mk);
// echo "</pre>";
echo "
$back_to
$judul
<div class='wadah gradasi-hijau'>
  <h4 class=darkblue>$nama_kurikulum</h4>
  <table class=table>
    <tr class='$bg_progres'><td>Progres Penjadwalan</td><td>$total_mk_terjadwal of $total_mk </td></tr>
    <tr><td>Jumlah Dosen terlibat</td><td>$total_dosen dosen</td></tr>
  </table>
</div>

$kurikulum_semesters
$back_to

<div class=wadah>
<h3>Rekap SKS per Dosen</h3>
$tb_dosens
</div>
$back_to
";


















?>
<script>
  $(function(){
    $(".select_id_dosen").change(function(){
      let tid = $(this).prop('id');
      let rid = tid.split('__');
      let id_kurikulum_mk = rid[1];
      let id_dosen = rid[2];
      let id_dosen_span = $('#'+id_kurikulum_mk+'__'+id_dosen).text();
      let val = $(this).val();
      if(id_dosen_span==val || (id_dosen_span=='' && val=='NULL')){
        $('#btn_apply__'+id_kurikulum_mk+'__'+id_dosen).prop('disabled',true);
      }else{
        $('#btn_apply__'+id_kurikulum_mk+'__'+id_dosen).prop('disabled',false);
      }
    });

    $(".btn_apply").click(function(){
      let tid = $(this).prop('id');
      let rid = tid.split('__');
      let id_kurikulum_mk = rid[1];
      let id_dosen = rid[2];
      let id_dosen_span = $('#'+id_kurikulum_mk+'__'+id_dosen).text();
      let new_id_dosen = $('#id_dosen__'+id_kurikulum_mk+'__'+id_dosen).val();

      if(new_id_dosen=='NULL'){
        let y = confirm('Apakah Anda ingin mengosongkan (menghapus) Jadwal untuk MK ini?');
        if(!y){
          $(this).val(id_dosen);
          return;
        }
      }
      
      
      let link_ajax = `ajax_akademik/ajax_insert_update_jadwal.php?id_kurikulum_mk=${id_kurikulum_mk}&new_id_dosen=${new_id_dosen}&id_dosen_span=${id_dosen_span}`;
      // console.log(id_kurikulum_mk,id_dosen,id_dosen_span,new_id_dosen,link_ajax); return;

      $.ajax({
        url:link_ajax,
        success:function(a){
          console.log(a);
          if(a.trim()=='sukses'){

            if(new_id_dosen=='NULL'){
              $('#'+id_kurikulum_mk+'__'+id_dosen).text('');
              $('#id_dosen__'+id_kurikulum_mk+'__'+id_dosen).removeClass('gradasi-hijau');
              $('#id_dosen__'+id_kurikulum_mk+'__'+id_dosen).addClass('gradasi-merah');
            }else{
              $('#'+id_kurikulum_mk+'__'+id_dosen).text(new_id_dosen);
              $('#id_dosen__'+id_kurikulum_mk+'__'+id_dosen).removeClass('gradasi-merah');
              $('#id_dosen__'+id_kurikulum_mk+'__'+id_dosen).addClass('gradasi-hijau');
            }

            $('#'+tid).prop('disabled',true);

          }else{
            if(a.toLowerCase().search('cannot delete or update a parent row')>0){
              alert('Jadwal tidak bisa dihapus karena sudah punya Sesi Kuliah.');
            }else{
              alert(a)
            }
          }
        }
      })
    })
  })
</script>
