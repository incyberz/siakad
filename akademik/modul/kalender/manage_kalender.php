<h1>Manage Kalender dan Semester</h1>
<p>Pada menu ini Anda dapat Seting Kalender dan Penanggalan tiap Semesternya.</p>

<?php
if(isset($_POST['btn_delete_kalender'])){
  $s = "DELETE FROM tb_kalender WHERE id='$_POST[btn_delete_kalender]'";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  echo div_alert('success', 'Delete Kalender berhasil.');

  $z = parse_url($_SERVER['REQUEST_URI']);
  $uq = $z['query'];
  echo "<script>location.replace('?$uq')</script>";  
  exit;
}

$id_kalender = $_GET['id_kalender'] ?? '';
if($id_kalender==''){
  $angkatan = $_GET['angkatan'] ?? '';
  $jenjang = $_GET['jenjang'] ?? '';
  include 'include/include_rangkatan.php';
  include 'include/include_rjenjang.php';
  if($angkatan=='' || $jenjang==''){
    $unsetting = 0;
    $allsetting = 0;
    $div = '<div class="mb2 biru">Silahkan pilih salah satu Angkatan untuk Manage Kalender dan Penanggalan Semester: </div>';
    foreach ($rangkatan as $key => $angkatan) {
      $div .= "<div class=wadah><div class=mb2>Angkatan $angkatan</div>";
      // echo " <a class='btn btn-info' href='?manage_kalender&angkatan=$angkatan'>$angkatan</a>";
      foreach ($rjenjang as $key => $jenjang) {
        $info = $jenjang=='D3' ? 'success' : 'info';

        $s = "SELECT id as id_kalender, 
        (
          SELECT count(1) FROM tb_semester 
          WHERE id_kalender=a.id AND last_update IS NOT NULL) jumlah_semester_updated, 
        (
          SELECT count(1) FROM tb_semester 
          WHERE id_kalender=a.id) jumlah_semester 
        FROM tb_kalender a WHERE a.jenjang='$jenjang' AND a.angkatan=$angkatan";
        // echo "<h1 class=debug>$s</h1>";
        $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
        if(mysqli_num_rows($q)>1){
          div_alert('danger',"Duplikat Kalender untuk angkatan $angkatan jenjang $jenjang.");
          echo "<div class='wadah mt2 gradasi-merah'><div class=mb2><h4><b>Multiple Kalender Terdeteksi</b></h4>Kalender angkatan $angkatan jenjang $jenjang lebih dari satu. Silahkan delete salah satu Kalender! : <div class='kecil miring abu'>Jika tidak dapat dihapus hubungi DB-Admin! (terdapat table-relation security)</div></div>";
          while ($d=mysqli_fetch_assoc($q)) {
            $disabled = $d['jumlah_semester'] ? 'disabled' : '';
            echo "<form method=post><button class='btn btn-danger' name=btn_delete_kalender value='$d[id_kalender]' onclick='return confirm(\"Yakin untuk menghapus Kalender ini?\")' $disabled>Delete Kalender | id=$d[id_kalender] | $d[jumlah_semester] semester</button></form> ";
          }
          echo '</div>';
          exit;
        }elseif(mysqli_num_rows($q)==1){
          $d=mysqli_fetch_assoc($q);
          $jumlah_semester = $d['jumlah_semester'];
          $jumlah_semester_updated = $d['jumlah_semester_updated'];
          $bred = $jumlah_semester==$rjumlah_semester[$jenjang] ? '' : 'style="border: solid 2px red"';

          $unsetting += ($jumlah_semester-$jumlah_semester_updated);
          $allsetting += $jumlah_semester;

          $smt_lengkap = $jumlah_semester==$jumlah_semester_updated 
          ? "<div class='green miring small mb3'>Penanggalan Semester sudah terisi.</div>"
          : "<div class='red miring small mb3'>Penanggalan Semester: $jumlah_semester_updated of $jumlah_semester</div>";

          $div.="<div class=mb1><a class='btn btn-$info' href='?manage_kalender&angkatan=$angkatan&jenjang=$jenjang' $bred>$angkatan-$jenjang <span class=debug>id:$d[id_kalender]</span></a></div>$smt_lengkap";
        }else{
          $div.=" <a class='btn btn-primary' href='?manage_kalender&angkatan=$angkatan&jenjang=$jenjang'>AutoCreate Kalender $angkatan-$jenjang</a>";
        }
      }
      $div.='</div>';
    }

    $settinged = $allsetting-$unsetting;
    $persen_setting = $allsetting==0 ? 0 : round(($settinged/$allsetting)*100,1);

    $green_color = intval($persen_setting/100*155);
    $red_color = intval((100-$persen_setting)/100*255);
    $rgb = "rgb($red_color,$green_color,50)";
    echo "
    <div class='kecil miring consolas' style='color:$rgb'>Progres Seting Semester : $persen_setting% | $settinged of $allsetting</div>
    <div class=progress>
      <div class='progress-bar progress-bar-danger' style='width:$persen_setting%;background:$rgb;'></div>
    </div>
    $div
    ";

    $s = "INSERT INTO tb_unsetting (kolom,unsetting,total) VALUES ('semester',$unsetting,$allsetting) ON DUPLICATE KEY UPDATE unsetting=$unsetting,total=$allsetting";
    $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
    exit;
  }

  $jenjang = $_GET['jenjang'] ?? die(erid('jenjang'));
  $s = "SELECT id as id_kalender FROM tb_kalender WHERE angkatan=$angkatan AND jenjang='$jenjang'";
  echo "<pre class=debug>$s</pre>";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  if(mysqli_num_rows($q)==0){
    // auto insert kalender
    $auto_mode = isset($_POST['btn_auto_create_kalender']) ? 1 : 0;

    $s = "SELECT jumlah_semester FROM tb_jenjang WHERE jenjang='$jenjang'";
    $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
    $d = mysqli_fetch_assoc($q);
    $jumlah_semester = $d['jumlah_semester'];

    $tanggal_mulai = "$angkatan-09-01";
    $jumlah_bulan_per_semester = 5;
    $nama = "Kalender $jenjang-$angkatan";

    if($auto_mode){

      $jumlah_semester = $_POST['jumlah_semester'];
      $tanggal_mulai = $_POST['tanggal_mulai'];
      $jumlah_bulan_per_semester = $_POST['jumlah_bulan_per_semester'];

      $s = "INSERT INTO tb_kalender (
      angkatan,
      jenjang,
      tanggal_mulai,
      jumlah_semester,
      jumlah_bulan_per_semester,
      nama
      ) VALUES (
      '$angkatan',
      '$jenjang',
      '$tanggal_mulai',
      '$jumlah_semester',
      '$jumlah_bulan_per_semester',
      '$nama'
      )";
      // echo $s; exit;
      $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
      echo "<script>location.replace('?manage_kalender&angkatan=$angkatan&jenjang=$jenjang')</script>";
    }else{
      // mode manual :: kalender blm ada.
      $form = "
      <form method=post>
        <div class=form-group>
          <label>Jumlah Semester</label>
          <input class=form-control name=jumlah_semester value=$jumlah_semester>
          <div class='kecil miring abu mt1'>Jumlah semester untuk jenjang $jenjang adalah $jumlah_semester semester.</div>
        </div>

        <div class=form-group>
          <label>Jumlah Bulan per Semester</label>
          <input class=form-control name=jumlah_bulan_per_semester value=$jumlah_bulan_per_semester>
          <div class='kecil miring abu mt1'>Jika percepatan maka 5 bulan per semester, jika kalender biasa maka 6 bulan per semester</div>
        </div>

        <div class=form-group>
          <label>Tanggal Mulai</label>
          <input class=form-control name=tanggal_mulai value=$tanggal_mulai>
          <div class='kecil miring abu mt1'>Tanggal mulai adalah tahun ajaran baru untuk angkatan <b>$angkatan</b>, biasanya awal September $angkatan. </div>
        </div>

        <button class='btn btn-primary' name=btn_auto_create_kalender>Auto Create Kalender</button>
      </form>
      ";
      echo div_alert('danger',"Kalender $angkatan-$jenjang belum ada.<hr>$form");
      
    }
    exit;


  }elseif(mysqli_num_rows($q)>1){
    die(div_alert('danger',"Terdapat dua kalender yang sama untuk angkatan $angkatan jenjang $jenjang."));
  }else{
    $d = mysqli_fetch_assoc($q);
    $id_kalender = $d['id_kalender'];
  }
}

include 'manage_kalender_tambah_kurikulum_process.php';
?>
<style>
  .ids-kalender h2{margin-top:0; color: darkblue; }
  .kalender {}
  .semester-ke {font-size:24px !important; color:darkblue !important; margin-bottom:10px}
  .tb-semester-mk th{text-align:left}

  .btn_tambah_semester {margin-bottom:10px}
</style>
<?php





# ==============================================================
# GET KURIKULUM DATA
# ==============================================================
$s = "SELECT 
a.id as id_kalender, 
a.nama as nama_kalender, 
a.angkatan,
a.jenjang,
a.tanggal_mulai,
a.jumlah_semester,
a.jumlah_bulan_per_semester

FROM tb_kalender a 
JOIN tb_jenjang b ON b.jenjang=a.jenjang  
WHERE a.id='$id_kalender'";

$q = mysqli_query($cn, $s)or die(mysqli_error($cn));
if(mysqli_num_rows($q)==0) die('Data kalender tidak ditemukan.');
if(mysqli_num_rows($q)>1) die('Duplikat kalender ditemukan.');
$d = mysqli_fetch_assoc($q);
$jumlah_semester = $d['jumlah_semester'];
$nama_kalender = $d['nama_kalender'];
$id_kalender = $d['id_kalender'];
$jenjang = $d['jenjang'];
// die($jenjang);

echo "<div class=debug id=keterangan_kalender>Kalender Angkatan $d[angkatan] Jenjang $d[jenjang]</div>";

$tr='';
foreach($d as $key=>$isi){
  if($key=='id_kalender') continue;
  $kolom_caption = str_replace('_',' ',$key);
  $isi = $isi=='' ? '<span class="abu miring">-- null --</span>' : $isi;
  $tr.="<tr><td class=upper>$kolom_caption</td><td id='$key'>$isi</td></td>";
}


echo "
<div class='wadah ids-kalender'>
<h2>Batasan Kalender</h2>
<p>Batasan semester otomatis terbentuk mengacu pada <code>Tanggal Mulai</code> Kalender dan <code>Jumlah Bulan per Semester</code></p>
<table class=table>
  $tr
</table>
<div class=text-right><a href='?master&p=kalender&aksi=update&id=$id_kalender'>Update Identitas Kalender</a></div>
</div>";




# ==============================================================
# TAMPIL SEMESTERS
# ==============================================================
$s = "SELECT 
a.id as id_semester,
a.nomor as no_semester,
a.tanggal_awal, 
a.tanggal_akhir,
a.last_update,
(SELECT count(1) FROM tb_kurikulum_mk WHERE id_semester=a.id) as is_have_mk  
FROM tb_semester a 
JOIN tb_kalender b ON b.id=a.id_kalender 

WHERE b.id='$id_kalender' 
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

  $tanggal_awal = date('Y-m-d',strtotime($d['tanggal_awal']));
  $tanggal_akhir = date('Y-m-d',strtotime($d['tanggal_akhir']));

  $tr = "
  <tr>
    <td>Batas Awal</td>
    <td>
      <input class='form-control' type=date value='$tanggal_awal' disabled>
    </td>
  </tr>
  <tr>
    <td>Batas Akhir</td>
    <td>
      <input class='form-control' type=date value='$tanggal_akhir' disabled>
    </td>
  </tr>
  ";

  $lengkap = $d['last_update']=='' ? '<span class=red>-- none --</span>' : $d['last_update'];
  $primary = $d['last_update']=='' ? 'primary' : 'success';
  $caption = $d['last_update']=='' ? 'Penanggalan Semester' : 'Update Penanggalan';

  $btn_penanggalan_semester = "<div class=mb2><a href='?manage_semester&id_semester=$d[id_semester]' class='btn btn-$primary btn-sm' >$caption</a></div> <div class='kecil miring'>Last update: $lengkap</div>";
  $btn_hapus_semester = $d['is_have_mk'] ? "<span class='badge badge-info'>Mempunyai $d[is_have_mk] MK</badge>" : "<button class='btn btn-danger btn-sm btn_aksi' id='hapus__semester__$d[id_semester]'>Hapus</button>";

  $wadah = (strtotime($d['tanggal_awal']) <= strtotime($today) and strtotime($d['tanggal_akhir']) >= strtotime($today)) ? 'wadah_active' : 'wadah'; 
  $semester_aktif = $wadah=='wadah' ? '' : '(Semester Aktif)'; 


  $semesters .= "
  <div class='col-lg-6' id='semester__$d[id_semester]'>
    <div class=$wadah>
      <div class='semester-ke'>
        Semester $d[no_semester] $semester_aktif <span class=debug>id: $d[id_semester]</span>
      </div>
      <table class='table tb-semester-mk'>
        
        $tr
        
      </table>
      <div class='row'>
        <div class='col-lg-6'>
          $btn_penanggalan_semester 
        </div>
        <div class='col-lg-6 text-right'>
          $btn_hapus_semester
        </div>
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
echo "<div class=debug>
  max_no_semester:<span id=max_no_semester>$max_no_semester</span><br>
  id_kalender:<span id=id_kalender>$id_kalender</span>
</div>";

$kalender = $semesters=='' ? '<div class="alert alert-danger">Belum ada data semester</div>' : "<div class='row kalender'>$semesters</div>";
echo $kalender;

# ==============================================================
# LIST KURIKULUM YANG SUDAH ADA
# ==============================================================
include 'manage_kalender_list_kurikulum.php';




# ==============================================================
# TAMBAH SEMESTER OR TAMBAH KURIKULUM
# ==============================================================
if($jumlah_semester==$jumlah_semester_real){
  include 'manage_kalender_tambah_kurikulum.php';
}else{
  echo "
  <p>Jumlah semester pada Kalender ini adalah $jumlah_semester_real of $jumlah_semester. Anda dapat menambahkannya.</p>
  <button class='btn btn-primary btn_aksi mb2' id='tambah_semester__semester'>Tambah Semester $max_no_semester</button>
  ";
}




?>

<script>
  function get_tanggal_mulai_baru(max_no_semester,date_mulai,jumlah_bulan_per_semester){
    let selisih_bulan = (max_no_semester-1)*jumlah_bulan_per_semester;
    let bulan_mulai = date_mulai.getMonth()+1; // mulai kurikulum
    let sum_bulan_awal_baru = bulan_mulai + selisih_bulan;
    let bulan_awal_baru = (sum_bulan_awal_baru % 12 == 0) ? 12 : sum_bulan_awal_baru % 12;
    let tambah_tahun = parseInt((sum_bulan_awal_baru-1) / 12);
    let tanggal_start = date_mulai.getDate();
    let tahun_start = date_mulai.getFullYear();
    let tahun_start_baru = tahun_start + tambah_tahun;
    let tanggal_mulai_baru = `${tahun_start_baru}-${bulan_awal_baru}-${tanggal_start}`;
    // console.log('tanggal_mulai_baru:'+tanggal_mulai_baru);
    return tanggal_mulai_baru;
  }
  function get_tanggal_akhir_baru(max_no_semester,date_mulai,jumlah_bulan_per_semester){
    let tmp = get_tanggal_mulai_baru((max_no_semester+1),date_mulai,jumlah_bulan_per_semester);
    let d = new Date(tmp);
    d.setDate(d.getDate()-1);
    return `${d.getFullYear()}-${d.getMonth()+1}-${d.getDate()}`;
  }
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

      if(aksi=='hapus'){
        // let y = aksi=='hapus' ? confirm('Yakin untuk menghapus data ini?\n\nPERHATIAN! Data MK akan hilang dari database.') 
        // : confirm('Yakin untuk dropping data ini?\n\nDrop = melepas tanpa menghapus data');
        // if(!y) return;
        let link_ajax = '';
        let kolom_acuan = '';
        let acuan = '';
        let tabel2 = '';
        let kolom_acuan2 = '';
        let acuan2 = '';

        if(tabel=='semester'){
          kolom_acuan = 'id';
          acuan = id;
          link_ajax = `../ajax_global/ajax_global_delete.php?tabel=${tabel}&kolom_acuan=${kolom_acuan}&acuan=${acuan}&`;
        }else{
          alert('Belum ada ajax target untuk aksi tabel: '+tabel);
          return;
        }

        // console.log(link_ajax); return;

        $.ajax({
          url:link_ajax,
          success:function(a){
            if(a.trim()=='sukses'){
              location.reload();
              // $('#semester__'+id).fadeOut();
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

      if(aksi=='tambah_semester'){ // untuk tambah Semester
        // let y = confirm(`Ingin menambah ${tabel.toUpperCase()} Baru?`);
        // if(!y) return;
        
        let koloms = null;
        let isis = null;

        if(tabel=='semester'){
          let id_kalender = $('#id_kalender').text();
          let max_no_semester = parseInt($("#max_no_semester").text());
          let nama_kalender = $("#nama_kalender").text();
          let jumlah_bulan_per_semester = parseInt($("#jumlah_bulan_per_semester").text());
          let keterangan = `Semester ${max_no_semester} pada ${nama_kalender}`;

          // =========================================================
          // VALIDASI TANGGAL MULAI KURIKULUM
          // =========================================================
          let tanggal_acuan = '2020-1-1';
          let tanggal_mulai = $("#tanggal_mulai").text();
          let date_acuan = new Date(tanggal_acuan);
          let date_mulai = new Date(tanggal_mulai);
          if(date_mulai < date_acuan){
            alert(`Maaf, tanggal mulai invalid.\n\nTanggal Mulai Kurikulum (${tanggal_mulai}) harus lebih besar dari ${tanggal_acuan}.`);
            return;
          } 

          // =========================================================
          // PENENTUAN TANGGAL AWAL DAN AKHIR SEMESTER
          // =========================================================
          let tanggal_mulai_baru = get_tanggal_mulai_baru(max_no_semester,date_mulai,jumlah_bulan_per_semester);
          let tanggal_akhir_baru = get_tanggal_akhir_baru(max_no_semester,date_mulai,jumlah_bulan_per_semester);
          // console.log(tanggal_mulai,tanggal_mulai_baru); 
          // console.log(tanggal_akhir_baru,max_no_semester,date_mulai,jumlah_bulan_per_semester); return;

          koloms = 'id_kalender,nomor,tanggal_awal,tanggal_akhir,keterangan';
          isis = `'${id_kalender}','${max_no_semester}','${tanggal_mulai_baru}','${tanggal_akhir_baru}','${keterangan}'`;
        }

        let link_ajax = `../ajax_global/ajax_global_insert.php?tabel=${tabel}&koloms=${koloms}&isis=${isis}`;
        // alert(link_ajax);return;
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
      let link_ajax = `../ajax_global/ajax_global_update.php?tabel=${tabel}&kolom_target=${kolom}&isi_baru=${isi_baru}&acuan=${acuan}&kolom_acuan=${kolom_acuan}`;

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