<?php
$id_jadwal = isset($_GET['id_jadwal']) ? $_GET['id_jadwal'] : die(erid('id_jadwal'));
$id_tipe_sesi = isset($_GET['id_tipe_sesi']) ? $_GET['id_tipe_sesi'] : die(erid('id_tipe_sesi'));
if($id_jadwal=='') die(erid('id_jadwal::empty'));
echo "<span class=debug id=id_jadwal>$id_jadwal</span>";
echo "<span class=debug id=id_tipe_sesi>$id_tipe_sesi</span>";
$jumlah_soal = 30;
$uts = $id_tipe_sesi==8 ? 'UTS' : 'HARIAN';
$uts = $id_tipe_sesi==16 ? 'UAS' : $uts;
$judul = 'INPUT SOAL '.$uts;
$sub_judul = "Silahkan input $jumlah_soal soal untuk SOAL $uts";


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
$s = "SELECT * FROM tb_soal WHERE id_jadwal=$id_jadwal and id_tipe_sesi=$id_tipe_sesi";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
$id_soals = [];
$no_soals = [];
$soals = [];
$opsi_as = [];
$opsi_bs = [];
$opsi_cs = [];
$opsi_ds = [];
$kjs = [];
$last_updates = [];
while ($d = mysqli_fetch_assoc($q)) {
  array_push($id_soals,$d['id']);
  array_push($no_soals,$d['no_soal']);
  array_push($soals,$d['soal']);
  array_push($opsi_as,$d['opsi_a']);
  array_push($opsi_bs,$d['opsi_b']);
  array_push($opsi_cs,$d['opsi_c']);
  array_push($opsi_ds,$d['opsi_d']);
  array_push($kjs,$d['kj']);
  array_push($last_updates,$d['last_update']);
}


$rabjad = ['a','b','c','d'];

// echo '<pre>';
// var_dump($no_soals);
// echo '</pre>';

$tr_soal = '';
$nav_soal = '';
for ($i=1; $i <= $jumlah_soal ; $i++) { 
  if(in_array($i,$no_soals)){
    // ektract from array
    // find currentIndex
    $x = array_search($i,$no_soals);

    $id_soal = $id_soals[$x];
    $soal = $soals[$x];
    $opsi_a = $opsi_as[$x];
    $opsi_b = $opsi_bs[$x];
    $opsi_c = $opsi_cs[$x];
    $opsi_d = $opsi_ds[$x];
    $kj = $kjs[$x];
    $last_update = $last_updates[$x];
  }else{
    // default value
    $id_soal = 'new';
    $soal = '';
    $opsi_a = '';
    $opsi_b = '';
    $opsi_c = '';
    $opsi_d = '';
    $kj = '<span class="abu miring">(Silahkan klik salah satu!)</span>';
    $last_update = '<span class="red miring">--none--</span>';
  }
  $ropsi = [$opsi_a,$opsi_b,$opsi_c,$opsi_d];
  $gradasi = $soal==''?'merah':'hijau';
  $nav_soal .= "<a href='#tr__$i' class='nav_soal_item gradasi-$gradasi' id='nav_soal_item__$i'>$i</a> ";
  $opsies = '';
  for ($j=0; $j < count($rabjad); $j++) { 
    $opsies.= "
      <div class='blok_opsi'>
        <div class='text-center'>$rabjad[$j].</div>
        <div>
          <input class='form-control ketikan' id=opsi__$i"."__$rabjad[$j] value='$ropsi[$j]'>
        </div>
        <div>
          <button class='btn btn-secondary btn-sms btn-block upper btn_aksi' id='set_kj__$i"."__$rabjad[$j]'>kj = $rabjad[$j]</button>
        </div>
      </div>
    ";
  }

  $gambar_soal = ''; //zzz

  $tmp = "$soal$opsi_a$opsi_b$opsi_c$opsi_d$kj";
  $none = 'style="display:none"';
  $none_hapus = $soal=='' ? $none : '';

  $tr_soal .= "
    <div class='wadah gradasi-$gradasi' id='tr__$i'>
      <div class='debug' id=tmp__$i>$tmp</div>
      <div class='form-group'>
        <label>Soal No: $i <span class=debug id='id_soal__$i'>$id_soal</span> </label>
        <textarea id='soal__$i' rows='5' class='form-control mb-2 ketikan'>$soal</textarea>
        <div class=wadah>
          $gambar_soal
          <div class='kecil toggle_upload' id=toggle__$i><button class='btn btn-secondary btn-sm'>Upload Gambar</button></div>
          <form method=post enctype='multipart/form-data' id='form_upload__$i' class=hideit>
            <p class='kecil miring mt-2'>Untuk gambar soal sifatnya opsional. Silahkan upload gambar JPG antara 10 s.d 200kB !</p>
            <input class=debug name=id_jadwal value=$id_jadwal>
            <input class=debug name=no_soal value=$i>
            <div class=blok_upload>
              <div><input type=file class=form-control name=file$i required></div>
              <div><button class='btn btn-info btn-block btn-sm' name=btn_upload>Upload</button></div>
              <div><button class='btn btn-danger btn-block btn-sm' name=btn_hapus>Hapus</button></div>
            </div>
          </form>
        </div>
        $opsies
        <div class='mt-2'>Kunci Jawab: <span id='kj__$i' class='biru tebal'>$kj</span></div>
        <div class='mt-2'>
          <div class=footer_soal>
            <div class=kecil>Last update: <span id=last_update__$i>$last_update</span></div>
            <div><button class='btn btn-primary btn-block btn_aksi hideit' id='simpan__$i' $none>Simpan</button></div>
            <div><button class='btn btn-danger btn-block btn_aksi' id='hapus__$i' $none_hapus>Hapus</button></div>
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
    $('.ketikan').keyup(function(){
      let tid = $(this).prop('id');
      let rid = tid.split('__');
      let no_soal = rid[1];
      let tmp = $('#tmp__'+no_soal).text();
      
      let soal = $('#soal__'+no_soal).val().trim();
      let kj = $('#kj__'+no_soal).text();
      let opsi_a = $('#opsi__'+no_soal+'__a').val().trim();
      let opsi_b = $('#opsi__'+no_soal+'__b').val().trim();
      let opsi_c = $('#opsi__'+no_soal+'__c').val().trim();
      let opsi_d = $('#opsi__'+no_soal+'__d').val().trim();
      
      let tmp2 = soal+opsi_a+opsi_b+opsi_c+opsi_d+kj;
      if(tmp==tmp2){
        $('#simpan__'+no_soal).hide();
        // console.log('sama');
      }else{
        // console.log('beda',tmp,tmp2);
        $('#simpan__'+no_soal).show();
      }
    });

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
      let id_soal = $('#id_soal__'+no_soal).text();
      let id_tipe_sesi = $('#id_tipe_sesi').text(); //uts

      // console.log(id_jadwal,no_soal,aksi,kj);
      
      if(aksi=='set_kj'){
        $('#kj__'+no_soal).text(rid[2].toUpperCase());
      }else if(aksi=='simpan'){
        let soal = $('#soal__'+no_soal).val().trim();
        let opsi_a = $('#opsi__'+no_soal+'__a').val().trim();
        let opsi_b = $('#opsi__'+no_soal+'__b').val().trim();
        let opsi_c = $('#opsi__'+no_soal+'__c').val().trim();
        let opsi_d = $('#opsi__'+no_soal+'__d').val().trim();
        // console.log(soal,opsi_a,opsi_b,opsi_c,opsi_d);

        if(soal.length<10){
          alert('Silahkan isi kalimat soal minimal 10 huruf.'); return;
        }

        if(opsi_a.length<3 || opsi_b.length<3 || opsi_c.length<3 || opsi_d.length<3 ){
          alert('Silahkan isi opsi soal minimal 3 huruf.'); return;
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

        // all simpan-validation passed
        let link_ajax = `ajax_dosen/ajax_simpan_soal.php?id_soal=${id_soal}&no_soal=${no_soal}&soal=${soal}&opsi_a=${opsi_a}&opsi_b=${opsi_b}&opsi_c=${opsi_c}&opsi_d=${opsi_d}&kj=${kj}&id_tipe_sesi=${id_tipe_sesi}&id_jadwal=${id_jadwal}`;
        $.ajax({
          url:link_ajax,
          success:function(a){
            if(a.trim()=='sukses'){
              location.reload();
              // $('#'+tid).fadeOut();
              // $('#tr__'+no_soal).removeClass('gradasi-merah');
              // $('#tr__'+no_soal).addClass('gradasi-hijau');
              // $('#nav_soal_item__'+no_soal).removeClass('gradasi-merah');
              // $('#nav_soal_item__'+no_soal).addClass('gradasi-hijau');
              // $('#last_update__'+no_soal).text('saat ini');
              // $('#simpan__'+no_soal).fadeOut();
              // $('#hapus__'+no_soal).fadeIn();
              // $('.ketikan__'+no_soal).prop('disabled',true);
            }else{
              alert(a)
            }
          }
        })

      }else if(aksi=='hapus'){

        let y = prompt('Yakin untuk menghapus soal ini?')
        // all simpan-validation passed
        let link_ajax = `ajax_dosen/ajax_simpan_soal.php?id_soal=${id_soal}&no_soal=${no_soal}&soal=${soal}&opsi_a=${opsi_a}&opsi_b=${opsi_b}&opsi_c=${opsi_c}&opsi_d=${opsi_d}&kj=${kj}&id_tipe_sesi=${id_tipe_sesi}&id_jadwal=${id_jadwal}`;
        $.ajax({
          url:link_ajax,
          success:function(a){
            if(a.trim()=='sukses'){
              location.reload();
              // $('#'+tid).fadeOut();
              // $('#tr__'+no_soal).removeClass('gradasi-merah');
              // $('#tr__'+no_soal).addClass('gradasi-hijau');
              // $('#nav_soal_item__'+no_soal).removeClass('gradasi-merah');
              // $('#nav_soal_item__'+no_soal).addClass('gradasi-hijau');
              // $('#last_update__'+no_soal).text('saat ini');
              // $('#simpan__'+no_soal).fadeOut();
              // $('#hapus__'+no_soal).fadeIn();
              // $('.ketikan__'+no_soal).prop('disabled',true);
            }else{
              alert(a)
            }
          }
        })
      }
    })
  })
</script>