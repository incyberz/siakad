<h1>Manage Kurikulum</h1>
<?php $judul = ''; ?>
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
include 'include/include_rid_prodi.php';

$id_kurikulum = $_GET['id_kurikulum'] ?? '';
if($id_kurikulum==''){
  $angkatan = $_GET['angkatan'] ?? '';
  $jenjang = $_GET['jenjang'] ?? '';
  include 'include/include_rangkatan.php';
  include 'include/include_rjenjang.php';


  if($angkatan=='' || $jenjang==''){

    // get count MK All Semester
    $s = "SELECT a.id as id_kurikulum,
    (SELECT count(1) FROM tb_kurikulum_mk WHERE id_semester=c.id AND id_kurikulum=a.id) count 
    FROM tb_kurikulum a 
    JOIN tb_kalender b ON a.id_kalender=b.id 
    JOIN tb_semester c ON c.id_kalender=b.id
    ";
    $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
    $allsetting=mysqli_num_rows($q);
    $unsetting=0;
    $runsetting = [];
    while ($d=mysqli_fetch_assoc($q)) {
      if($d['count']==0){
        $unsetting++;
        if(isset($runsetting[$d['id_kurikulum']])){
          $runsetting[$d['id_kurikulum']]++;
        }else{
          $runsetting[$d['id_kurikulum']]=1;
        }
      } 
      // echo "<br>Kur: $d[jenjang]-$d[id_prodi]-$d[angkatan]-sm$d[nomor]: $d[count]";
    }

    $settinged = $allsetting-$unsetting;
    $persen_setting = $allsetting==0 ? 0 : round(($settinged/$allsetting)*100,2);

    $green_color = intval($persen_setting/100*155);
    $red_color = intval((100-$persen_setting)/100*255);
    $rgb = "rgb($red_color,$green_color,50)";
    echo "
    <div class='kecil miring consolas' style='color:$rgb'>Progres Pengisian MK : $persen_setting% | $settinged of $allsetting</div>
    <div class=progress>
      <div class='progress-bar progress-bar-danger' style='width:$persen_setting%;background:$rgb;'></div>
    </div>
    ";

    $s = "INSERT INTO tb_unsetting (kolom,unsetting,total) VALUES ('kurikulum',$unsetting,$allsetting) ON DUPLICATE KEY UPDATE unsetting=$unsetting,total=$allsetting";
    $q = mysqli_query($cn,$s) or die(mysqli_error($cn));

    echo "
    $unsetting | $allsetting
    <div class=mb2>Untuk angkatan: </div>";
    foreach ($rangkatan as $key => $angkatan) {
      echo "<div class=wadah><h3 class=mb2>Angkatan $angkatan</h3>";
      // echo " <a class='btn btn-info' href='?manage_kalender&angkatan=$angkatan'>$angkatan</a>";
      foreach ($rjenjang as $key => $jenjang) {
        $btn_type = $jenjang=='D3' ? 'success' : 'info';

        // get kurikulum dalam satu angkatan dan satu jenjang
        $s = "SELECT id as id_kalender,
        (SELECT count(1) FROM tb_semester WHERE id_kalender=a.id) jumlah_semester, 
        (SELECT count(1) FROM tb_kurikulum WHERE id_kalender=a.id) jumlah_kurikulum 
        FROM tb_kalender a WHERE a.jenjang='$jenjang' AND a.angkatan=$angkatan";
        $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
        $jumlah_kalender = mysqli_num_rows($q);
        if($jumlah_kalender){
          $d=mysqli_fetch_assoc($q);
          $jumlah_semester = $d['jumlah_semester'];
          $id_kalender = $d['id_kalender'];
          $link = '';
          if($jumlah_semester==$rjumlah_semester[$jenjang]){
            $aclass = '';
            $err = '';
            $s = "SELECT id as id_prodi,singkatan FROM tb_prodi WHERE jenjang='$jenjang'";
            $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
            while ($d=mysqli_fetch_assoc($q)) {

              # ===================================================
              # GET KURIKULUM SINGLE
              # ===================================================
              $s2 = "SELECT a.id as id_kurikulum,
              (SELECT count(1) FROM tb_kurikulum_mk WHERE id_kurikulum=a.id) jumlah_mk,  
              (
                SELECT (sum(m.bobot_teori) + sum(m.bobot_praktik)) as jumlah_sks 
                FROM tb_mk m 
                JOIN tb_kurikulum_mk n ON m.id=n.id_mk 
                JOIN tb_kurikulum o ON n.id_kurikulum=o.id 
                WHERE o.id=a.id 

              ) jumlah_sks   
              FROM tb_kurikulum a 
              JOIN tb_kalender b ON a.id_kalender=b.id
              WHERE b.jenjang='$jenjang' 
              AND b.angkatan=$angkatan 
              AND a.id_prodi=$d[id_prodi] 
              ";
              $q2 = mysqli_query($cn,$s2) or die(mysqli_error($cn));

              $jumlah_kurikulum = mysqli_num_rows($q2);

              if($jumlah_kurikulum==1){
                $d2 = mysqli_fetch_assoc($q2);
                $id_kurikulum = $d2['id_kurikulum'];
                $unsetting_this = $runsetting[$id_kurikulum] ?? 0;

                $jumlah_mk = $d2['jumlah_mk'];
                $jumlah_smt_tanpa_mk = $unsetting_this 
                ? "<div class=red>$unsetting_this semester tanpa MK</div>"
                : "<div class=green>Semua semester sudah terisi</div>";
                if($jumlah_mk){
                  $gr = 'hijau';
                  $jumlah_mk_show = "<div class='mt1 kecil miring'>$jumlah_mk MK :: $d2[jumlah_sks] SKS</div>";
                }else{
                  $gr = 'merah';
                  $jumlah_mk_show = "<div class='mt1 kecil miring red'>Belum ada MK pada kurikulum ini.</div>";
                }

                $link .= "
                <div class='wadah mt2 mb2 gradasi-$gr'>
                  <a class='btn btn-$btn_type' href='?manage_kurikulum&id_kurikulum=$d2[id_kurikulum]'>
                    Kurikulum $d[singkatan]-$angkatan-$jenjang <span class=debug>$d2[id_kurikulum]</span>
                  </a> 
                  <div class='kecil miring'>
                    $jumlah_mk_show 
                    $jumlah_smt_tanpa_mk 
                  </div> 
                </div> 
                ";
              }elseif($jumlah_kurikulum==0){
                $link .= "
                <div class='wadah mt2 mb2 gradasi-merah'>
                  <a class='btn btn-danger' href='?manage_kalender&angkatan=$angkatan&jenjang=$jenjang'>
                    Buat Kurikulum $d[singkatan]-$angkatan-$jenjang <span class=debug></span>
                  </a>
                  <div class='kecil miring red'>Kurikulum $d[singkatan]-$angkatan-$jenjang belum ada. Silahkan buat pada Manage Kalender (opsi paling bawah).</div>
                </div>
                ";
              }else{
                $link .= '<div class="wadah gradasi-merah">';
                $prodi = $rprodi[$d['id_prodi']];
                $link .= div_alert('danger',"<h4>Multiple Kurikulum Terdeteksi</h4>Kurikulum angkatan $angkatan jenjang $jenjang prodi $prodi Lebih dari satu. Silahkan hapus salah satu!<div class='kecil miring abu mt1'>Jika tidak bisa dihapus silahkan hubungi DB-Admin!</div>");
                while ($d2=mysqli_fetch_assoc($q2)) {
                  $disabled = $d2['jumlah_mk'] ? 'disabled' : '';
                  $link .= "
                  <form method=post>
                    <button class='btn btn-danger' value=$d2[id_kurikulum] name=btn_hapus_kurikulum $disabled>Hapus Kurikulum | $d2[jumlah_mk] MK | id:$d2[id_kurikulum]</button> 
                  </form>
                  ";
                }
                $link .= '</div>';
              }

            }
          }else{
            $aclass = ' class="btn btn-danger" ';
            $err = "<div class='kecil miring red'>Jumlah semester pada Kalender tidak sama dengan Jumlah Semester Jenjang $jenjang. Silahkan Manage Kalender terlebih dahulu!</div>";
          }

          $link_manage_kalender = "<div class='mb2'><a href='?manage_kalender&angkatan=$angkatan&jenjang=$jenjang' $aclass>Kalender $angkatan-$jenjang <span class=debug>id:$id_kalender</span></a>$err</div>";

          $gradasi = $jenjang=='D3' ? 'hijau' : 'biru';

          echo "
          <div class='wadah gradasi-$gradasi'>
            $link_manage_kalender
            $link
          </div>
          ";
        }else{ // end mysqli_num_rows true
          echo " <a class='btn btn-primary' href='?manage_kalender&angkatan=$angkatan&jenjang=$jenjang'>AutoCreate Kalender $angkatan-$jenjang</a>";
        }
      }
      echo '</div>';
    }
    exit;
  }
}

include 'include/akademik_icons.php';


# ==============================================================
# GET KURIKULUM DATA
# ==============================================================
$s = "SELECT 
b.nama as nama_prodi, 
CONCAT('Kurikulum ',c.jenjang,'-',c.angkatan) as nama_kurikulum, 
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
// echo "<pre>$s</pre>";
$q = mysqli_query($cn, $s)or die(mysqli_error($cn));
if(!mysqli_num_rows($q)) die('Data kurikulum tidak ditemukan.');
$d = mysqli_fetch_assoc($q);
$jumlah_semester = $d['jumlah_semester'];
$nama_kurikulum = $d['nama_kurikulum'];
$id_kalender = $d['id_kalender'];
$id_prodi = $d['id_prodi'];

$back_to = "<div class=mb2>Back to : 
  <a href='?manage_kalender&id_kalender=$id_kalender' class=proper>Manage kalender</a> | 
  <a href='?manage_jadwal_dosen&id_kurikulum=$id_kurikulum'>Manage Jadwal Dosen</a>
";


echo "<div class=debug id=keterangan_kurikulum>$d[nama_kurikulum] Prodi $d[nama_prodi] Angkatan $d[angkatan] Jenjang $d[jenjang]</div>";

$tr_kurikulum='';
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
  $tr_kurikulum.="<tr class=$debug><td class=upper>$kolom_caption</td><td id='$kolom'>$isi</td></td>";
}






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
// echo "<pre>$s</pre>";

$q = mysqli_query($cn, $s)or die(mysqli_error($cn));

$jumlah_semester_real = mysqli_num_rows($q);


$semesters = '';
$rnomor_semester = [];
$total_teori = 0;
$total_praktik = 0;
$total_mk = 0;
$tr_mbkm = '';
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
  a.bobot_teori,
  a.bobot_praktik,
  a.prasyarat,
  b.id as id_kurikulum_mk, 
  (SELECT count(1) FROM tb_kurikulum_mk WHERE id_mk=a.id) as jumlah_assign_mk,  
  (SELECT count(1) FROM tb_nilai WHERE id_kurikulum_mk=b.id limit 1) as sub_trx_nilai  

  FROM tb_mk a 
  JOIN tb_kurikulum_mk b ON a.id=b.id_mk 
  JOIN tb_semester c ON b.id_semester=c.id  
  JOIN tb_kurikulum d ON b.id_kurikulum=d.id  
  WHERE c.id='$d[id_semester]' 
  AND d.id_prodi=$id_prodi 
  ORDER BY a.nama,a.kode 
  ";
  $q2 = mysqli_query($cn, $s2)or die(mysqli_error($cn));
  $jumlah_mk = mysqli_num_rows($q2);

  $tr = '';
  $jumlah_teori[$d['id_semester']] = 0;
  $jumlah_praktik[$d['id_semester']] = 0;
  $j=0;
  // list MK looping
  while ($d2=mysqli_fetch_assoc($q2)) { 
    $j++;
    $total_mk++;
    $jumlah_teori[$d['id_semester']] += $d2['bobot_teori'];
    $jumlah_praktik[$d['id_semester']] += $d2['bobot_praktik'];

    $hapus = $d2['jumlah_assign_mk'] > 1 ? "<span onclick='alert(\"Tidak bisa hapus MK ini karena dipakai di Kurikulum lain.\")'>$img_aksi[delete_disabled]</span>" : "<span class='btn_aksi' id='hapus__mk__$d2[id_mk]__$d[id_semester]'>$img_aksi[delete]</span>";
    $drop = $d2['sub_trx_nilai'] ? "<span onclick='alert(\"Tidak bisa Drop MK karena sudah ada trx-nilai pada MK ini.\")'>$img_aksi[drop_disabled]</span>" : "<span class='btn_aksi' id='drop__mk__$d2[id_mk]'>$img_aksi[drop]</span>";
    $merge = "<a href='?merge_mk&id_kurikulum_mk=$d2[id_kurikulum_mk]' target=_blank onclick='return confirm(\"Menuju laman Merge MK?\")'>$img_aksi[merge]</a>";
    
    //sub trx
    $total_trx = $d2['sub_trx_nilai']; // only nilai zzz
    $detail = !$d2['sub_trx_nilai'] ? '' : " <a target=_blank onclick='return confirm(\"Menuju detail trx MK ini?\")' href=?trx_mk&id_kurikulum_mk=$d2[id_kurikulum_mk]>$img_aksi[detail]</a> | <span class=kecil>$total_trx trx</span>";

    $merah = ($d2['bobot_teori'] + $d2['bobot_praktik'])==0 ? ' style="color:red; font-weight:bold" ' : '';

    $editable = $d2['jumlah_assign_mk'] > 1 ? '' : 'editable';

    $ctr="
    <tr id='tr__$d2[id_mk]'>
      <td>$j<span class=debug>$d2[id_mk]</span></td>
      <td class='$editable' id='kode__mk__$d2[id_mk]'>$d2[kode_mk]</td>
      <td class='$editable' id='nama__mk__$d2[id_mk]' $merah>$d2[nama_mk]</td>
      <td class='$editable' id='bobot_teori__mk__$d2[id_mk]' $merah>$d2[bobot_teori]</td>
      <td class='$editable' id='bobot_praktik__mk__$d2[id_mk]' $merah>$d2[bobot_praktik]</td>
      <td>
        <table class=tb_aksi>
          <tr>
            <td>$drop</td>
            <td>$hapus</td>
            <td>$merge</td>
            <td style='padding-left:15px !important'>$detail</td>
          </tr>
        </table>
      </td> 
    </tr>    
    ";
    if(strpos('salt'.strtoupper($d2['kode_mk']),'MBKM')>0){
      $tr_mbkm.=$ctr;
    }else{
      $tr.=$ctr;
    }
  } //end while list MK

  $total_teori +=   $jumlah_teori[$d['id_semester']];
  $total_praktik +=   $jumlah_praktik[$d['id_semester']];


  $tr = $tr=='' ? "<tr><td class='red miring' colspan=9>Belum ada MK pada semester ini.</td></tr>" : $tr;

  $tr .= "
  <tr>
    <td colspan=3>Total SKS</td>
    <td>".$jumlah_teori[$d['id_semester']]."</td>
    <td>".$jumlah_praktik[$d['id_semester']]."</td>
    <td colspan=4>(".($jumlah_teori[$d['id_semester']]+$jumlah_praktik[$d['id_semester']])." SKS Total)</td>
  </tr>";

  $tanggal_awal_sty = strtotime($d['tanggal_awal']) < strtotime('2018-1-1') ? 'merah tebal' : '';
  $tanggal_akhir_sty = strtotime($d['tanggal_akhir']) < strtotime('2018-1-1') ? 'merah tebal' : '';
  $tanggal_awal_show = "<span class='$tanggal_awal_sty'>".date('d M Y', strtotime($d['tanggal_awal'])).'</span>';
  $tanggal_akhir_show = "<span class='$tanggal_awal_sty'>".date('d M Y', strtotime($d['tanggal_akhir'])).'</span>';

  $wadah = strtotime($d['tanggal_akhir']) < strtotime($today) ? 'wadah gradasi-kuning' : 'wadah'; 
  $wadah = (strtotime($d['tanggal_awal']) <= strtotime($today) and strtotime($d['tanggal_akhir']) >= strtotime($today)) ? 'wadah_active' : $wadah; 
  $semester_aktif = $wadah=='wadah_active' ? '(Semester Aktif)' : ''; 
  $semester_lampau = $wadah=='wadah gradasi-kuning' ? '(Semester Lampau)' : ''; 


  $semesters .= "
  <div class='col-lg-12' id='semester__$d[id_semester]'>
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
          <th>Teori</th>
          <th>Praktik</th>
          <th colspan=3 style='text-align:center'>Aksi</th>
        </thead>
        
        $tr
        
      </table>

      <div class='text-right'>
        <a href='?assign_mk&id_kurikulum=$id_kurikulum&id_semester=$d[id_semester]&no_semester=$d[no_semester]&nama_kurikulum=$nama_kurikulum' class='btn btn-primary btn-sm'>Assign MK</a>
        <button class='btn btn-primary btn-sm btn_aksi' id='tambah_dan_assign__mk__$d[id_semester]__$d[no_semester]'>Tambah MK</button>
      </div>
    </div>
  </div>
  ";

  if($i % 2 ==0) $semesters .= '</div><div class=row>';
} // end while semesters

$semesters.= "
<div class='wadah' style='margin:15px'>
  <div class='semester-ke'>
    Mata Kuliah MBKM
  </div>
  <table class='table tb-semester-mk'>
    <thead>
      <th>No</th>
      <th>Kode</th>
      <th>Mata Kuliah</th>
      <th>Teori</th>
      <th>Praktik</th>
      <th colspan=3 style='text-align:center'>Aksi</th>
    </thead>
    
    $tr_mbkm
    
  </table>
</div>
";

$total_sks = $total_praktik + $total_teori;


if($total_sks==0){
  $persen_praktik=0;
  $persen_teori=0;
}else{
  $persen_teori = round($total_teori/$total_sks*100,2);
  $persen_praktik = round($total_praktik/$total_sks*100,2);
}

$tr_rekap = "
<tr><td class=upper>Total MK</td><td>$total_mk MK</td></tr>
<tr><td class=upper>Total Teori</td><td>$total_teori SKS ($persen_teori%)</td></tr>
<tr><td class=upper>Total Praktik</td><td>$total_praktik SKS ($persen_praktik%)</td></tr>
<tr class='tebal biru gradasi-kuning'><td class=upper>Total SKS</td><td>$total_sks SKS</td></tr>
";



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
# CETAK PDF
# ==============================================================
$disabled_pdf = '';
$btn_cetak = "<button class='btn btn-primary' $disabled_pdf>$img_aksi[pdf] Cetak PDF</button>";
$form_cetak_pdf = div_alert('success',"Semua MK sudah terjadwal. Silahkan Anda boleh mencetak Kurikulum PDF.<hr>
<form method=post target=_blank action='../pdf/pdf_kurikulum.php'>
  <input class=debug name=id_kurikulum value=$id_kurikulum>
  $btn_cetak
</form>  
") 
;

if(1){
  $disabled_pdf='';
  echo '<span class=red>Perhatian! Cetak PDF bypass.</span>';
  $form_cetak_pdf = div_alert('success',"Semua MK sudah terjadwal. Silahkan Anda boleh mencetak Kurikulum PDF.<hr>
    <form method=post target=_blank action='../pdf/pdf_kurikulum.php'>
      <input class=debug name=id_kurikulum value=$id_kurikulum>
      <button class='btn btn-primary'>$img_aksi[pdf] Cetak PDF (by-pass)</button>
    </form>  
  ");
}


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
<div class='wadah ids-kurikulum'>
  <h2>Identitas Kurikulum</h2>
  <table class=table>
    $tr_kurikulum
    $tr_rekap
  </table>
  <div class=text-right>
    <a href='?master&p=kurikulum&aksi=update&id=$id_kurikulum'>Update Identitas Kurikulum</a>
  </div>
</div>
$blok_semesters
$form_cetak_pdf
$back_to
";





?>
<div style="position:fixed; top:5px; right: 5px; z-index:9999; display:none; cursor:pointer" id="blok_refresh">
  <div class="alert alert-info" style="border-radius: 10px; border:solid 3px white">
    <span style="display:inline-block; margin-right:15px">Anda melakukan perubahan.</span> <button class="btn btn-info btn-sm" onclick="location.reload()">Refresh</button>
  </div>
</div>




















<script>
  $(function(){
    // ===============================================
    // GLOBAL AKSI AND EDITABLE HANDLER
    // v.1.0.1
    // by: InSho
    // ===============================================
    $(".btn_aksi").click(function(){
      let tid = $(this).prop('id');
      let rid = tid.split('__');
      let aksi = rid[0];
      let tabel = rid[1];
      let id_mk = rid[2];
      let id_kurikulum = $('#id_kurikulum').text();

      if(aksi=='hapus' || aksi=='drop'){
        let y = aksi=='hapus' ? confirm('Yakin untuk menghapus data ini?\n\nPERHATIAN! Data MK akan hilang dari database.') 
        : confirm('Yakin untuk dropping data ini?\n\nDrop = melepas tanpa menghapus data');
        if(!y) return;
        let link_ajax = '';

        if(tabel=='semester'){
          // delete semester not available

        }else if(tabel=='mk'){
          if(aksi=='hapus'){
            link_ajax = `ajax_akademik/ajax_hapus_mk_kurikulum.php?`;
          }else if(aksi=='drop'){
            link_ajax = `ajax_akademik/ajax_drop_mk_kurikulum.php?id_kurikulum=${id_kurikulum}&id_mk=${id_mk}&`;
          }
        }else{
          alert('Belum ada ajax target untuk aksi tabel: '+tabel);
          return;
        }

        // console.log(link_ajax, id_kurikulum, id_mk); 
        // return;

        $.ajax({
          url:link_ajax,
          success:function(a){
            console.log(a);
            if(a.trim()=='sukses'){
              if(tabel=='mk'){
                $('#tr__'+id_mk).fadeOut();
              }else if(tabel=='semester'){
                $('#semester__'+id_mk).fadeOut();
              }
            }else{
              console.log(a);
              if(a.toLowerCase().search('cannot delete or update a parent row')>0){
                alert('Gagal menghapus data. \n\nData ini dibutuhkan untuk relasi data ke tabel lain.\n\n'+a);
              }else{
                alert('Gagal menghapus data.');
              }
            }
          }
        })
      } // end of hapus

      if(aksi=='tambah_dan_assign'){ // untuk tambah MK baru
        // let y = confirm(`Ingin menambah ${tabel.toUpperCase()} Baru?`);
        // if(!y) return;
        
        let koloms = null;
        let isis = null;

        if(tabel=='mk'){
          let r = '_'+Math.random();
          let kode = 'MK'+id+ r.substring(3,8);

          let id_prodi = $('#id_prodi').text();
          let id_kalender = $('#id_kalender').text();
          let id_kurikulum = $('#id_kurikulum').text();

          let nama = `NEW-MK SM${rid[3]} PROD${id_prodi} KUR${id_kurikulum} KAL${id_kalender}`;
          let singkatan = 'SINGKATAN-MK';
          koloms = 'kode,nama,singkatan,bobot_teori,bobot_praktik,is_publish';
          isis = `'${kode}','${nama}','${singkatan}',0,0,-1`;
        }

        let id_semester = id; //id_semester
        let id_kurikulum = $("#id_kurikulum").text();

        let link_ajax = `ajax_akademik/ajax_insert_and_assign_mk_to_kurikulum.php?koloms=${koloms}&isis=${isis}&id_semester=${id_semester}&id_kurikulum=${id_kurikulum}`;
        // console.log(link_ajax); return;
        $.ajax({
          url:link_ajax,
          success:function(a){
            if(a.trim()=='sukses'){
              // alert('Proses tambah sukses.');
              location.reload();
            }else{
              // alert('Gagal menambah data.');
              console.log(a);
            }
          }
        })        
      }      
    }) // end btn_aksi

    $(".editable").click(function(){
      console.log('editable call');
      let tid = $(this).prop('id');
      let rid = tid.split('__');
      let kolom = rid[0];
      let tabel = rid[1];
      let acuan = rid[2];
      let isi = $(this).text();

      let petunjuk = `Data ${kolom} baru:`;

      let isi_baru = prompt(petunjuk,isi);
      if(isi_baru===null) return;
      if(isi_baru.trim()==isi) return;

      isi_baru = isi_baru.trim()==='' ? 'NULL' : isi_baru.trim();
      
      // VALIDASI UPDATE DATA
      if(kolom=='no_wa' || kolom=='no_hp'){
        // if((isi_baru.substring(0, 3)=='628' || isi_baru.substring(0, 2)=='08') && isi_baru.length>9 && isi_baru.length<15){
        //   // alert('OK');
        //   if(isi_baru.substring(0, 2)=='08'){
        //     isi_baru = '62'+ isi_baru.substring(1, isi_baru.length);
        //   }
        // }else{
        //   alert('Format No. HP tidak tepat. Awali dengan 08xx, antara 10 s.d 13 digit.');
        //   return;
        // }
      }else if(kolom=='bobot_teori' || kolom=='bobot_praktik'){
        if(isNaN(isi_baru) || parseInt(isi_baru)>6){
          alert('Invalid bobot. \n\nMasukan bobot SKS antara 0 s.d 6');
          return;
        }
      }

      let kolom_acuan = 'id';
      let link_ajax = `../ajax_global/ajax_global_update.php?tabel=tb_${tabel}&kolom_target=${kolom}&isi_baru=${isi_baru}&acuan=${acuan}&kolom_acuan=${kolom_acuan}`;

      $.ajax({
        url:link_ajax,
        success:function(a){
          if(a.trim()=='sukses'){
            $("#"+kolom+"__"+tabel+"__"+acuan).text(isi_baru);
            $("#blok_refresh").fadeIn();
            
          }else{
            console.log(a);
            if(a.toLowerCase().search('cannot delete or update a parent row')>0){
              alert('Gagal mengubah data. \n\nData ini dibutuhkan untuk relasi data ke tabel lain.\n\n'+a);
            }else if(a.toLowerCase().search('duplicate entry')>0){
              alert(`Kode ${isi_baru} telah dipakai pada data lain.\n\nSilahkan masukan kode unik lainnya.`)
            }else{
              alert('Gagal mengubah data.'+a);
            }

          }
        }
      })


    });    

    $("#blok_refresh").click(function(){
      $(this).fadeOut();
    });

  })
</script>