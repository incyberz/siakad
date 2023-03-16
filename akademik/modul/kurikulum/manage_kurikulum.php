<?php $judul = "<h1>MANAGE KURIKULUM</h1>"; ?>
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
  <a href='?manage_multiple_jadwal&id_kurikulum=6'>Manage Multiple Jadwal</a>
</div>
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
$q = mysqli_query($cn, $s)or die(mysqli_error($cn));

$jumlah_semester_real = mysqli_num_rows($q);


$semesters = '';
$rnomor_semester = [];
$total_teori = 0;
$total_praktik = 0;
$total_mk = 0;
$total_mk_terjadwal = 0;
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
  $jumlah_teori[$d['id_semester']] = 0;
  $jumlah_praktik[$d['id_semester']] = 0;
  $j=0;
  // list MK looping
  while ($d2=mysqli_fetch_assoc($q2)) { 
    $j++;
    $total_mk++;
    if($d2['telah_terjadwal']) $total_mk_terjadwal++;
    $jumlah_teori[$d['id_semester']] += $d2['bobot_teori'];
    $jumlah_praktik[$d['id_semester']] += $d2['bobot_praktik'];

    $hapus = $d2['jumlah_assign_mk'] > 1 ? '' : "<span class='btn_aksi' id='hapus__mk__$d2[id_mk]__$d[id_semester]'>$img_aksi[delete]</span>";
    $drop = "<span class='btn_aksi' id='drop__mk__$d2[id_mk]__$d[id_semester]'>$img_aksi[drop]</span>";

    $img_aksi_next = $d2['telah_terjadwal'] ? $img_aksi['check'] : $img_aksi['next'] ;
    $onclick = $d2['telah_terjadwal'] ? " onclick='return confirm(\"MK ini sudah dijadwalkan. Apakah ingin Manage Jadwal kembali?\")' " : '';
    $merah = ($d2['bobot_teori'] + $d2['bobot_praktik'])==0 ? ' style="color:red; font-weight:bold" ' : '';
    $jadwal = "<span><a $onclick href='?manage_jadwal&id_kurikulum_mk=$d2[id_kurikulum_mk]'>$img_aksi_next</a></span>";
    $tr.="
    <tr id='tr__$d2[id_mk]'>
      <td>$j</td>
      <td class='editable' id='kode__mk__$d2[id_mk]'>$d2[kode_mk]</td>
      <td class='editable' id='nama__mk__$d2[id_mk]' $merah>$d2[nama_mk]</td>
      <td class='editable' id='bobot_teori__mk__$d2[id_mk]' $merah>$d2[bobot_teori]</td>
      <td class='editable' id='bobot_praktik__mk__$d2[id_mk]' $merah>$d2[bobot_praktik]</td>
      <td class='editable' id='prasyarat__mk__$d2[id_mk]'>$d2[prasyarat]</td>
      <td>
        <table class=tb_aksi>
          <tr>
            <td>$drop</td>
            <td>$hapus</td>
            <td>$jadwal</td>
          </tr>
        </table>
      </td> 
    </tr>    
    ";
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
          <th>Teori</th>
          <th>Praktik</th>
          <th>Pra-syarat</th>
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



$total_sks = $total_praktik + $total_teori;

$merah = $total_mk==$total_mk_terjadwal ? '' : 'merah';
$ket = $total_mk==$total_mk_terjadwal ? '' : "<div class='merah miring '>Terdapat MK yang belum dijadwalkan dengan Dosen Pengampunya. Silahkan klik <code>Tombol Next</code> $img_aksi[next] pada tiap MK!<br><a href='?manage_multiple_jadwal&id_kurikulum=6' class='btn btn-primary'>Manage Multiple Jadwal</a></div>";

$persen_teori = round($total_teori/$total_sks*100,2);
$persen_praktik = round($total_praktik/$total_sks*100,2);

$tr_rekap = "
<tr><td class=upper>Total MK</td><td>$total_mk MK</td></tr>
<tr><td class=upper>Total MK Terjadwal</td><td class='$merah gradasi-$merah'>$total_mk_terjadwal Jadwal dari $total_mk total$ket</td></tr>
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
$disabled_pdf = $total_mk==$total_mk_terjadwal ? '' : 'disabled';
$btn_cetak = "<button class='btn btn-primary' $disabled_pdf>$img_aksi[pdf] Cetak PDF</button>";
$cetak_pdf = $total_mk==$total_mk_terjadwal 
? div_alert('success',"Semua MK sudah terjadwal. Silahkan Anda boleh mencetak Kurikulum PDF.<hr>$btn_cetak") 
: div_alert('danger',"Masih ada MK yang belum Anda jadwalkan.<hr>$btn_cetak <a href='?manage_multiple_jadwal&id_kurikulum=6' class='btn btn-primary'>Manage Multiple Jadwal</a>");


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
$cetak_pdf
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
      let id = rid[2];

      if(aksi=='hapus' || aksi=='drop'){
        let y = aksi=='hapus' ? confirm('Yakin untuk menghapus data ini?\n\nPERHATIAN! Data MK akan hilang dari database.') 
        : confirm('Yakin untuk dropping data ini?\n\nDrop = melepas tanpa menghapus data');
        if(!y) return;
        let link_ajax = '';
        let kolom_acuan = '';
        let acuan = '';
        let tabel2 = '';
        let kolom_acuan2 = '';
        let acuan2 = '';

        if(tabel=='semester'){
          // kolom_acuan = 'id';
          // acuan = id;
          // link_ajax = `ajax_global/ajax_global_delete.php?tabel=${tabel}&kolom_acuan=${kolom_acuan}&acuan=${acuan}&`;
        }else if(tabel=='mk'){
          let tabel_semester = 'semester';
          kolom_acuan = 'id_semester';
          acuan = rid[3]; //id_semester
          tabel2 = 'kurikulum_mk';
          kolom_acuan2 = 'id_mk';
          acuan2 = id; //id_mk
          if(aksi=='hapus'){
            link_ajax = `ajax_global/ajax_global_drop_and_delete.php?tabel=${tabel}&kolom_acuan=${kolom_acuan}&acuan=${acuan}&tabel2=${tabel2}&kolom_acuan2=${kolom_acuan2}&acuan2=${acuan2}&`;
          }else if(aksi=='drop'){
            link_ajax = `ajax_global/ajax_global_drop.php?tabel=${tabel_semester}&kolom_acuan=${kolom_acuan}&acuan=${acuan}&tabel2=${tabel2}&kolom_acuan2=${kolom_acuan2}&acuan2=${acuan2}&`;
          }
        }else{
          alert('Belum ada ajax target untuk aksi tabel: '+tabel);
          return;
        }

        // console.log(link_ajax); return;

        $.ajax({
          url:link_ajax,
          success:function(a){
            if(a.trim()=='sukses'){
              if(tabel=='mk'){
                $('#tr__'+id).fadeOut();
              }else if(tabel=='semester'){
                $('#semester__'+id).fadeOut();
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
      let link_ajax = `ajax_global/ajax_global_update.php?tabel=${tabel}&kolom_target=${kolom}&isi_baru=${isi_baru}&acuan=${acuan}&kolom_acuan=${kolom_acuan}`;

      $.ajax({
        url:link_ajax,
        success:function(a){
          if(a.trim()=='sukses'){
            $("#"+kolom+"__"+tabel+"__"+acuan).text(isi_baru);
            $("#blok_refresh").fadeIn();
            
          }else{
            console.log(a);
            if(a.toLowerCase().search('cannot delete or update a parent row')>0){
              alert('Gagal menghapus data. \n\nData ini dibutuhkan untuk relasi data ke tabel lain.\n\n'+a);
            }else if(a.toLowerCase().search('duplicate entry')>0){
              alert(`Kode ${isi_baru} telah dipakai pada data lain.\n\nSilahkan masukan kode unik lainnya.`)
            }else{
              alert('Gagal menghapus data.');
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