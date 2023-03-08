<h1>MANAGE KURIKULUM</h1>
<style>
  .ids-kurikulum h2{margin-top:0; color: darkblue; }
  .kurikulum {}
  .semester-ke {font-size:24px !important; color:darkblue !important; margin-bottom:10px}
  .tb-semester-mk th{text-align:left}

  .btn_tambah_semester {margin-bottom:10px}
</style>
<?php

$id = isset($_GET['id']) ? $_GET['id'] : '';
if($id<1) die('<script>location.replace("?master&p=kurikulum")</script>');




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
c.id as id_kalender 

FROM tb_kurikulum a 
JOIN tb_prodi b ON b.id=a.id_prodi 
JOIN tb_kalender c ON c.id=a.id_kalender  
JOIN tb_jenjang d ON d.jenjang=c.jenjang  
WHERE a.id='$id'";
$q = mysqli_query($cn, $s)or die(mysqli_error($cn));
if(!mysqli_num_rows($q)) die('Data kurikulum tidak ditemukan.');
$d = mysqli_fetch_assoc($q);
$jumlah_semester = $d['jumlah_semester'];
$nama_kurikulum = $d['nama_kurikulum'];
$id_kalender = $d['id_kalender'];

echo "<div class=debug id=keterangan_kurikulum>$d[nama_kurikulum] Prodi $d[nama_prodi] Angkatan $d[angkatan] Jenjang $d[jenjang]</div>";

$tr='';
foreach($d as $kolom=>$isi){
  if($kolom=='is_publish') {$isi = $isi==0 ? 'belum' : 'sudah'; $isi="<span class='abu miring'>-- $isi --</span>"; }
  $kolom_caption = str_replace('_',' ',$kolom);
  $isi = $isi=='' ? '<span class="abu miring">-- null --</span>' : $isi;
  $tr.="<tr><td class=upper>$kolom_caption</td><td id='$kolom'>$isi</td></td>";
}


echo "
<div class='wadah ids-kurikulum'>
<h2>Identitas Kurikulum</h2>
<table class=table>
  $tr
</table>
<div class=text-right><a href='?master&p=kurikulum&aksi=update&id=$id'>Update Identitas Kurikulum</a></div>
</div>";




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

WHERE c.id='$id' 
ORDER BY a.nomor 
";
$q = mysqli_query($cn, $s)or die(mysqli_error($cn));

$jumlah_semester_real = mysqli_num_rows($q);
$semesters = '';
$rnomor_semester = [];
$total_teori = 0;
$total_praktik = 0;
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
  (SELECT count(1) from tb_kurikulum_mk WHERE id_mk=a.id) as jumlah_assign_mk 

  FROM tb_mk a 
  JOIN tb_kurikulum_mk b ON a.id=b.id_mk 
  JOIN tb_semester c ON b.id_semester=c.id  
  WHERE c.id='$d[id_semester]'";
  // echo "<hr>$s2";
  $q2 = mysqli_query($cn, $s2)or die(mysqli_error($cn));
  $jumlah_mk = mysqli_num_rows($q2);
  echo "<div class=debug>jumlah_mk__$d[id_semester]: <span id='jumlah_mk__$d[id_semester]'>$jumlah_mk</span></div>";

  $tr = '';
  $jumlah_teori[$d['id_semester']] = 0;
  $jumlah_praktik[$d['id_semester']] = 0;
  $j=0;
  while ($d2=mysqli_fetch_assoc($q2)) {
    $j++;
    $jumlah_teori[$d['id_semester']] += $d2['bobot_teori'];
    $jumlah_praktik[$d['id_semester']] += $d2['bobot_praktik'];

    $hapus = $d2['jumlah_assign_mk'] > 1 ? '' : "<td class='deletable btn_aksi text-center' id='hapus__mk__$d2[id_mk]__$d[id_semester]'>H</td>";
    $drop = "<td class='deletable btn_aksi text-center' id='drop__mk__$d2[id_mk]__$d[id_semester]'>D</td>";
    $jadwal = "<td class='text-center gradasi-biru'><a href='?manage_jadwal&id_kurikulum_mk=$d2[id_kurikulum_mk]'>J</a></td>";
    $tr.="
    <tr id='tr__$d2[id_mk]'>
      <td>$j</td>
      <td class='editable' id='kode__mk__$d2[id_mk]'>$d2[kode_mk]</td>
      <td class='editable' id='nama__mk__$d2[id_mk]'>$d2[nama_mk]</td>
      <td class='editable' id='bobot_teori__mk__$d2[id_mk]'>$d2[bobot_teori]</td>
      <td class='editable' id='bobot_praktik__mk__$d2[id_mk]'>$d2[bobot_praktik]</td>
      <td class='editable' id='prasyarat__mk__$d2[id_mk]'>$d2[prasyarat]</td>
      $drop  $hapus $jadwal  
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

  $semesters .= "
  <div class='col-lg-6' id='semester__$d[id_semester]'>
    <div class=wadah>
      <div class='semester-ke'>
        Semester $d[no_semester]
      </div>
      <table class='table tb-semester-mk'>
        <thead>
          <th>No</th>
          <th>Kode</th>
          <th>Mata Kuliah</th>
          <th>Teori</th>
          <th>Praktik</th>
          <th>Prasyarat</th>
          <th colspan=3 style='text-align:center'>Aksi</th>
        </thead>
        
        $tr
        
      </table>
      <div class='text-right'>
        <a href='?assign_mk&id_kurikulum=$id&id_semester=$d[id_semester]&no_semester=$d[no_semester]&nama_kurikulum=$nama_kurikulum' class='btn btn-primary btn-sm'>Assign MK</a>
        <button class='btn btn-primary btn-sm btn_aksi' id='tambah_dan_assign__mk__$d[id_semester]'>Tambah MK</button>
        <button class='btn btn-danger btn-sm btn_aksi' id='hapus__semester__$d[id_semester]'>Hapus Semester</button>
      </div>
    </div>
  </div>
  ";
} // end while semesters

$max_no_semester = 1;
for ($i=1; $i <= $jumlah_semester ; $i++) { 
  if(!in_array($i,$rnomor_semester)){
    $max_no_semester = $i; break;
  }
}
echo "<div class=debug id=max_no_semester>$max_no_semester</div>";

$total_sks = $total_praktik + $total_teori;
$ui_total_sks = "
<div class=wadah>
  <ul style='font-size:24px'>
    <li>Total Teori: $total_teori SKS</li>
    <li>Total Praktik: $total_praktik SKS</li>
    <li class='tebal biru'>Total SKS Kurikulum: $total_sks SKS</li>
  </ul>
</div>";

$kurikulum = $semesters=='' ? '<div class="alert alert-danger">Belum ada data semester</div>' : "<div class='row kurikulum'>$semesters</div>$ui_total_sks";

# ==============================================================
# TAMBAH SEMESTER
# ==============================================================
$btn_tambah = $jumlah_semester==$jumlah_semester_real ? '' 
: "
<div class=wadah>
  <p>Jumlah semester pada Kurikulum ini adalah $jumlah_semester_real of $jumlah_semester. Anda dapat menambahkannya pada Manage Kalender dan Semester.</p>
  <a href='?manage_kalender&id=$id_kalender' class='btn btn-primary'>Tambah Semester</a>
</div>";
echo $btn_tambah;

# ==============================================================
# FINAL OUTPUT SEMESTERS
# ==============================================================
echo $kurikulum;




?>

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

      // alert(`${aksi} ${tabel} ${id} `);return;

      if(aksi=='hapus' && tabel=='semester'){
        let jumlah_mk = $('#jumlah_mk__'+id).text();
        if(parseInt(jumlah_mk)>0){
          alert('Semester ini belum bisa dihapus.\n\nUntuk menghapus semester, silahkan DROP dahulu Semua Data MK pada semester ini!');
          return;
        }
      }

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
          kolom_acuan = 'id';
          acuan = id;
          link_ajax = `ajax_global/ajax_global_delete.php?tabel=${tabel}&kolom_acuan=${kolom_acuan}&acuan=${acuan}&`;
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

      if(aksi=='tambah'){ // untuk tambah Semester
        // let y = confirm(`Ingin menambah ${tabel.toUpperCase()} Baru?`);
        // if(!y) return;
        
        let koloms = null;
        let isis = null;

        if(tabel=='semester'){
          let max_no_semester = $("#max_no_semester").text();
          koloms = 'id_kurikulum,nomor';
          isis = `'${id}','${max_no_semester}'`;
        }

        let link_ajax = `ajax_global/ajax_global_insert.php?tabel=${tabel}&koloms=${koloms}&isis=${isis}`;
        $.ajax({
          url:link_ajax,
          success:function(a){
            if(a.trim()=='sukses'){
              // alert('Proses tambah sukses.');
              location.reload();
            }else{
              alert('Gagal menambah data.');
              console.log(a);
            }
          }
        })        
      }      


      if(aksi=='tambah_dan_assign'){ // untuk tambah MK baru
        // let y = confirm(`Ingin menambah ${tabel.toUpperCase()} Baru?`);
        // if(!y) return;
        
        let koloms = null;
        let isis = null;

        if(tabel=='mk'){
          let r = '_'+Math.random();
          let kode = 'MK'+id+ r.substring(3,8);
          let nama = 'NEW MATA KULIAH';
          let singkatan = 'SINGKATAN-MK';
          koloms = 'kode,nama,singkatan,bobot_teori,bobot_praktik,is_publish';
          isis = `'${kode}','${nama}','${singkatan}',0,0,-1`;
        }

        let tabel2 = 'kurikulum_mk'; //assign to tb_kurikulum_mk
        let kolom2 = 'id_semester'; //foreign key column in tb_kurikulum_mk
        let id2 = id; //id_semester

        let link_ajax = `ajax_global/ajax_global_insert_and_assign.php?tabel=${tabel}&koloms=${koloms}&isis=${isis}&tabel2=${tabel2}&id2=${id2}&kolom2=${kolom2}`;
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
        if(isNaN(isi_baru) || parseInt(isi_baru)>4){
          alert('Invalid bobot. \n\nMasukan bobot SKS antara 0 s.d 4');
          return;
        }
      }

      let kolom_acuan = 'id';
      let link_ajax = `ajax_global/ajax_global_update.php?tabel=${tabel}&kolom_target=${kolom}&isi_baru=${isi_baru}&acuan=${acuan}&kolom_acuan=${kolom_acuan}`;

      $.ajax({
        url:link_ajax,
        success:function(a){
          if(a.trim()=='sukses'){
            $("#"+kolom+"__"+tabel+"__"+acuan).text(isi_baru)
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
  })
</script>