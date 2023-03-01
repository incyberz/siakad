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

$tr='';
foreach($d as $kolom=>$isi){
  if($kolom=='is_publish') {$isi = $isi==0 ? 'belum' : 'sudah'; $isi="<span class='abu miring'>-- $isi --</span>"; }
  $kolom = str_replace('_',' ',$kolom);
  $isi = $isi=='' ? '<span class="abu miring">-- null --</span>' : $isi;
  $tr.="<tr><td class=upper>$kolom</td><td>$isi</td></td>";
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
# TAMBAH SEMESTER
# ==============================================================
$btn_tambah = "<button class='btn btn-primary btn_aksi btn_tambah_semester' id='tambah_semester__$id'>Tambah Semester</button>";
echo $btn_tambah;


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

WHERE b.id='$id'";
$q = mysqli_query($cn, $s)or die(mysqli_error($cn));

$jumlah_semester = mysqli_num_rows($q);
$semesters = '';
$i=0;
while ($d=mysqli_fetch_assoc($q)) {
  $i++; 

  $tr = '';

  $s2 = "SELECT *   
  FROM tb_mk a 
  JOIN tb_kurikulum_mk b ON a.id=b.id_mk 
  WHERE b.id='$d[id_semester]'";
  $q2 = mysqli_query($cn, $s2)or die(mysqli_error($cn));
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
      <td class='deletable btn_aksi' id='hapus_mk__$d2[id]'>Hapus</td>
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
        <button class='btn btn-primary btn-sm btn_aksi' id='tambah_mk__$d[id_semester]'>Tambah MK</button>
        <button class='btn btn-danger btn-sm btn_aksi' id='hapus_semester__$d[id_semester]'>Hapus Semester</button>
      </div>
    </div>
  </div>
  ";
}


$kurikulum = $semesters=='' ? 'Belum ada semester' : "<div class='row kurikulum'>$semesters</div>";
echo $kurikulum;




?>

<script>
  $(function(){
    $(".btn_aksi").click(function(){
      let tid = $(this).prop('id');
      alert(tid);return;
      let rid = tid.split('__');
      let aksi = rid[0];
      let id = rid[1];

      if(aksi=='hapus' || aksi=='delete'){
        let y = confirm('Yakin untuk menghapus data ini?');
        if(!y) return;

        let link_ajax = 'ajax/ajax_user_hapus.php?username='+username;
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
        let y = confirm('Ingin menambah data Petugas Baru?');
        if(!y) return;        


        let link_ajax = 'ajax/ajax_user_new.php';
        $.ajax({
          url:link_ajax,
          success:function(a){
            if(a.trim()=='sukses'){
              alert('Add Rows Data Baru sukses. Silahkan Edit isi data tersebut!');
              location.reload();
            }else{
              alert('Gagal menambah data.');
            }
          }
        })        
      }

      if(aksi=='ubah_password'){
        let y = confirm(`Ingin mengubah password atas username: ${username}?`);
        if(!y) return;

        let np = prompt('Password baru Anda:');
        if(!y) return;

        let cp = prompt('Konfirmasi Password baru Anda:');
        if(!y) return;

        if(np!==cp){
          alert('Maaf, password baru dan konfirmasi password tidak sama.');
          return;
        }

        let link_ajax = `ajax/ajax_user_update.php?username=${username}&kolom=password&isi_baru=${np}`;

        $.ajax({
          url:link_ajax,
          success:function(a){
            if(a.trim()=='sukses'){
              alert('Sukses mengubah password. \n\nSilahkan diingat dan dicatat password tersebut agar tidak lupa.');
            }else{
              alert('Gagal mengubah data.\n\n'+a)
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

      let link_ajax = `ajax/ajax_user_update.php?username=${username}&kolom=${kolom}&isi_baru=${isi_baru}`;

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