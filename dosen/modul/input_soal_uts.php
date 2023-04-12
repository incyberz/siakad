<?php
$jumlah_soal = 30;
$judul = 'INPUT SOAL UTS';
$sub_judul = "Silahkan input $jumlah_soal soal untuk Soal UTS.";

$id_jadwal = isset($_GET['id_jadwal']) ? $_GET['id_jadwal'] : die(erid('id_jadwal'));
if($id_jadwal=='') die(erid('id_jadwal::empty'));
echo "<span class=debug id=id_jadwal>$id_jadwal</span>";

# ====================================================
# JADWAL PROPERTIES
# ====================================================
$s = "SELECT c.nama as mata_kuliah 

FROM tb_jadwal a 
JOIN tb_kurikulum_mk b on b.id=a.id_kurikulum_mk 
JOIN tb_mk c on c.id=b.id_mk 
WHERE a.id=$id_jadwal";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
if(mysqli_num_rows($q)==0) die('Data Jadwal tidak ditemukan.');
$d = mysqli_fetch_assoc($q);
$sub_judul.= " MK $d[mata_kuliah].";

# ====================================================
# SOAL-SOAL
# ====================================================
$s = "SELECT * FROM tb_soal WHERE id_jadwal=$id_jadwal and id_tipe_sesi=8";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
$no_soals = [];
$soals = [];
$opsi_as = [];
$opsi_bs = [];
$opsi_cs = [];
$opsi_ds = [];
$kjs = [];
while ($d = mysqli_fetch_assoc($q)) {
  array_push($no_soals,$d['no_soal']);
  array_push($soals,$d['soal']);
  array_push($opsi_as,$d['opsi_a']);
  array_push($opsi_bs,$d['opsi_b']);
  array_push($opsi_cs,$d['opsi_c']);
  array_push($opsi_ds,$d['opsi_d']);
  array_push($kjs,$d['kj']);
}


$ropsi = ['a','b','c','d'];

$tr_soal = '';
$nav_soal = '';
for ($i=1; $i <= $jumlah_soal ; $i++) { 
  $gradasi = 'merah';
  $nav_soal .= "<a href='#tr$i' class='nav_soal_item gradasi-merah' id='nav_soal_item$i'>$i</a> ";
  $opsies = '';
  for ($j=0; $j < count($ropsi); $j++) { 
    $opsies.= "
      <div class='blok_opsi'>
        <div class='text-center'>$ropsi[$j].</div>
        <div>
          <input class='form-control' id=opsi__$i"."__$ropsi[$j]>
        </div>
        <div>
          <button class='btn btn-secondary btn-sms btn-block upper btn_aksi' id='set_kj__$i"."__$ropsi[$j]'>kj = $ropsi[$j]</button>
        </div>
      </div>
    ";
  }

  $gambar_soal = ''; //zzz


  $tr_soal .= "
    <div class='wadah gradasi-$gradasi' id='tr$i'>
      <div class='form-group'>
        Soal No: <span id='soal_no$i'>$i</i>
        <textarea id='soal__$id_jadwal"."__$i' rows='5' class='form-control mb-2'></textarea>
        <div class=wadah>
          $gambar_soal
          <div class='kecil toggle_upload' id=toggle__$i><button class='btn btn-secondary btn-sm'>Upload Gambar</button></div>
          <form method=post enctype='multipart/form-data' id='form_upload__$i' class=hideit>
            <p class='kecil miring mt-2'>Untuk gambar soal sifatnya opsional. Silahkan upload gambar JPG antara 10 s.d 200kB !</p>
            <input class=debug name=id_jadwal value=$id_jadwal>
            <input class=debug name=soal_no value=$i>
            <div class=blok_upload>
              <div><input type=file class=form-control name=file$i required></div>
              <div><button class='btn btn-info btn-block btn-sm' name=btn_upload>Upload</button></div>
              <div><button class='btn btn-danger btn-block btn-sm' name=btn_hapus>Hapus</button></div>
            </div>
          </form>
        </div>
        $opsies
        <div class='mt-2'>Kunci Jawab: <span id='kj__$i' class='biru tebal'><span class='abu miring'>(Silahkan klik salah satu!)</span></span></div>
        <div class='mt-2'>
          <div class=footer_soal>
            <div class=kecil>Terakhir simpan: <span id=terakhir_simpan__$i><span class=red>--none--</span></span></div>
            <div><button class='btn btn-primary btn-block btn_aksi' id='simpan__$i'>Simpan</button></div>
            <div><button class='btn btn-danger btn-block btn_aksi' id='hapus__$i'>Hapus</button></div>
          </div>
        </div>
      </div>
    </div>
  ";
}

?>
<style>
  .blok_opsi{
    display:grid;
    grid-template-columns: 20px auto 80px;
    grid-gap: 10px;
    /* margin: 10px 0; */
    border-radius: 10px;
    padding: 5px 10px;
    transition: .2s;
  }
  .blok_opsi:hover{
    border: solid 1px #00f;
    background: #fcf
  }
  .nav_soal{
    position:sticky;
    top: 30px;
    background:white;
    padding: 5px;
    margin-bottom: 10px;
  }
  .nav_soal_item{
    display: inline-block;
    width: 25px;
    /* background:#ccf; */
    font-size: small;
    text-align:center;
    cursor:pointer;
    border-radius: 3px;
  }
  .nav_soal_item:hover{
    background: #fcf;
  }
  .blok_upload{
    display:grid;
    grid-template-columns: auto 70px 70px;
    grid-gap: 7px;
  }
  .footer_soal{
    display:grid;
    grid-template-columns: auto 80px 80px;
    grid-gap: 7px;
    border-top: solid 1px #ccc;
    padding-top: 10px;
  }
</style>
<?php
echo "
<h3>$judul</h3>
<div class=wadah>
  $sub_judul
  <div class='nav_soal'>$nav_soal</div>
  $tr_soal
</div>
";
?>


<script>
  $(function(){
    $('.toggle_upload').click(function(){
      let tid = $(this).prop('id');
      let rid = tid.split('__');
      let no_soal = rid[1];
      $('#form_upload__'+no_soal).fadeToggle();
    });
    $('.btn_aksi').click(function(){
      let tid = $(this).prop('id');
      let rid = tid.split('__');
      let aksi = rid[0];
      let no_soal = rid[1];
      let id_jadwal = $('#id_jadwal').text();
      let kj = $('#kj__'+no_soal).text();

      console.log(id_jadwal,no_soal,aksi,kj);
      
      if(aksi=='set_kj'){
        $('#kj__'+no_soal).text(rid[2].toUpperCase());
      }else if(aksi=='simpan'){
        let soal = $('#soal__'+id_jadwal+'__'+no_soal).val().trim();
        let opsi_a = $('#opsi__'+no_soal+'__a').val().trim();
        let opsi_b = $('#opsi__'+no_soal+'__b').val().trim();
        let opsi_c = $('#opsi__'+no_soal+'__c').val().trim();
        let opsi_d = $('#opsi__'+no_soal+'__d').val().trim();
        console.log(soal,opsi_a,opsi_b,opsi_c,opsi_d);

        if(soal.length<10){
          alert('Kalimat soal minimal 10 huruf.'); return;
        }

        if(opsi_a.length<3 || opsi_b.length<3 || opsi_c.length<3 || opsi_d.length<3 ){
          alert('Opsi soal minimal 3 huruf.'); return;
        }

        if(opsi_a.toUpperCase()==opsi_b.toUpperCase() 
        || opsi_a.toUpperCase()==opsi_c.toUpperCase()
        || opsi_a.toUpperCase()==opsi_d.toUpperCase()
        || opsi_b.toUpperCase()==opsi_c.toUpperCase()
        || opsi_b.toUpperCase()==opsi_d.toUpperCase()
        || opsi_c.toUpperCase()==opsi_d.toUpperCase()
        ){
          alert('Terdapat opsi ganda, silahkan perbaiki!'); return;
        }

        if(kj.length!=1 ){
          alert('Silahkan pilih dahulu Kunci Jawab.'); return;
        }

      }else if(aksi=='edit'){

      }
    })
  })
</script>