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
a.ditetapkan_oleh

FROM tb_kurikulum a 
JOIN tb_prodi b ON b.id=a.id_prodi 
JOIN tb_kalender c ON c.id=a.id_kalender  
JOIN tb_jenjang d ON d.jenjang=c.jenjang  
WHERE a.id='$id'";
$q = mysqli_query($cn, $s)or die(mysqli_error($cn));
if(!mysqli_num_rows($q)) die('Data kurikulum tidak ditemukan.');
$d = mysqli_fetch_assoc($q);
$jumlah_semester = $d['jumlah_semester'];
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
JOIN tb_kurikulum b ON b.id=a.id_kurikulum 

WHERE b.id='$id' 
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


  $s2 = "SELECT *   
  FROM tb_mk a 
  JOIN tb_kurikulum_mk b ON a.id=b.id_mk 
  JOIN tb_semester c ON b.id_semester=c.id  
  WHERE c.id='$d[id_semester]'";
  // echo "<hr>$s2";
  $q2 = mysqli_query($cn, $s2)or die(mysqli_error($cn));

  $tr = '';
  $j=0;
  while ($d2=mysqli_fetch_assoc($q2)) {
    $j++;
    $tr.="
    <tr>
      <td>$j</td>
      <td class='editable' id='kode__$d2[id]'>$d2[kode]</td>
      <td class='editable' id='kode__$d2[id]'>$d2[nama]</td>
      <td class='editable' id='kode__$d2[id]'>$d2[bobot_teori]</td>
      <td class='editable' id='kode__$d2[id]'>$d2[bobot_praktik]</td>
      <td class='editable' id='kode__$d2[id]'>$d2[prasyarat]</td>
      <td class='deletable btn_aksi' id='hapus__mk__$d2[id]'>Hapus</td>
    </tr>    
    ";
  }

  $semesters .= "
  <div class='col-lg-6'>
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
          <th>Hapus</th>
        </thead>
        
        $tr
        
      </table>
      <div class='text-right'>
        <button class='btn btn-primary btn-sm btn_aksi' id='assign__mk__$d[id_semester]'>Tambah MK</button>
        <button class='btn btn-danger btn-sm btn_aksi' id='hapus__semester__$d[id_semester]'>Hapus Semester</button>
      </div>
    </div>
  </div>
  ";
}

$max_no_semester = 1;
for ($i=1; $i <= $jumlah_semester ; $i++) { 
  if(!in_array($i,$rnomor_semester)){
    $max_no_semester = $i; break;
  }
}
echo "<div class=debug id=max_no_semester>$max_no_semester</div>";

$kurikulum = $semesters=='' ? 'Belum ada semester' : "<div class='row kurikulum'>$semesters</div>";

# ==============================================================
# TAMBAH SEMESTER
# ==============================================================
$btn_tambah = $jumlah_semester==$jumlah_semester_real ? '' : "<button class='btn btn-primary btn_aksi btn_tambah_semester' id='tambah__semester__$id'>Tambah Semester</button>";
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

      if(aksi=='hapus' || aksi=='delete'){
        let y = confirm('Yakin untuk menghapus data ini?');
        if(!y) return;

        let link_ajax = 'ajax_global/ajax_global_delete.php?username='+username;
        $.ajax({
          url:link_ajax,
          success:function(a){
            if(a.trim()=='sukses'){
              $('#tr__'+username).fadeOut();
            }else{
              if(a.toLowerCase().search('cannot delete or update a parent row')){
                alert('Gagal menghapus data. \n\nData ini dibutuhkan untuk relasi data ke tabel lain.\n\n'+a);
              }else{
                alert('Gagal menghapus data.');
              }
            }
          }
        })
      } // end of hapus

      if(aksi=='tambah' || aksi=='add'){
        // let y = confirm(`Ingin menambah ${tabel.toUpperCase()} Baru?`);
        // if(!y) return;
        
        let koloms = null;
        let isis = null;

        if(tabel=='semester'){
          koloms = 'id_kurikulum,nomor,keterangan';
          isis = `'${id}','${$('#max_no_semester').text()}','${$('#keterangan_kurikulum').text()}'`;
        }

        if(tabel=='mk'){
          let kode = Math.random() % 10000;
          let nama = Math.random() % 10000;
          let singkatan = Math.random() % 10000;
          koloms = 'kode,nama,singkatan,bobot_teori,bobot_praktik,is_publish';
          isis = `'AAA-NEW${kode}','AAA-NEW${nama}','AAA-NEW${singkatan}',0,0,-1`;
        }

        let link_ajax = `ajax_global/ajax_global_insert.php?tabel=tb_${tabel}&koloms=${koloms}&isis=${isis}`;
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

      if(aksi=='assign'){
        // let y = confirm(`Ingin menambah ${tabel.toUpperCase()} Baru?`);
        // if(!y) return;
        
        let koloms = null;
        let isis = null;

        if(tabel=='mk'){
          let kode = Math.random() % 10000;
          let nama = Math.random() % 10000;
          let singkatan = Math.random() % 10000;
          koloms = 'kode,nama,singkatan,bobot_teori,bobot_praktik,is_publish';
          isis = `'AAA-NEW${kode}','AAA-NEW${nama}','AAA-NEW${singkatan}',0,0,-1`;
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
      let username = rid[1];
      let isi = $(this).text();

      let petunjuk = `Data ${kolom} baru:`;

      let isi_baru = prompt(petunjuk,isi);
      if(!isi_baru || isi_baru.trim()==isi) return;
      
      // VALIDASI UPDATE DATA
      if(kolom=='no_wa' || kolom=='no_hp'){
        if((isi_baru.substring(0, 3)=='628' || isi_baru.substring(0, 2)=='08') && isi_baru.length>9 && isi_baru.length<15){
          // alert('OK');
          if(isi_baru.substring(0, 2)=='08'){
            isi_baru = '62'+ isi_baru.substring(1, isi_baru.length);
          }
        }else{
          alert('Format No. HP tidak tepat. Awali dengan 08xx, antara 10 s.d 13 digit.');
          return;
        }
      }

      let link_ajax = `ajax_global/ajax_global_update.php?username=${username}&kolom=${kolom}&isi_baru=${isi_baru}`;

      $.ajax({
        url:link_ajax,
        success:function(a){
          if(a.trim()=='sukses'){
            $("#"+kolom+"__"+username).text(isi_baru)
          }else{
            alert('Gagal mengubah data.\n\n'+a)
          }
        }
      })


    });    
  })
</script>