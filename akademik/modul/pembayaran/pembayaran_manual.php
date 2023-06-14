<h1>Manage Status Pembayaran <span class=debug>Manual</span></h1>

<?php
if(isset($_POST['btn_simpan'])){
  
  $keyword = $_POST['keyword'];
  
  $rnim = explode(';',$_POST['nims']);
  $or_nim_sudah = '(';
  $or_nim_belum = '(';
  $jumlah_sudah=0;
  $jumlah_belum=0;
  for ($i=0; $i < count($rnim) ; $i++) { 
    // if(find)
    foreach ($_POST as $key => $value) {
      if($rnim[$i]=='') continue;
      if(strpos("salt$key",$rnim[$i])){
        if(strpos("salt$key",'sudah_bayar')){
          echo $rnim[$i].'__ Set sudah bayar ... OK<br>';
          $or_nim_sudah .= "OR nim='$rnim[$i]' ";
          $jumlah_sudah++;
        }else{
          $or_nim_belum .= "OR nim='$rnim[$i]' ";
          echo $rnim[$i].'__ Set belum bayar ... OK<br>';
          $jumlah_belum++;
        }
      }else{
        // echo 'gada<br>';
      }
    } // end foreach
  }

  if($jumlah_sudah){
    $or_nim_sudah = str_replace('(OR ','(',$or_nim_sudah);
    $or_nim_sudah .= ')';
    $s = "UPDATE tb_mhs SET status_bayar_manual=1 WHERE $or_nim_sudah";
    echo "<div class=debug>$s</div>";
    $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  }  
  
  if($jumlah_belum){
    $or_nim_belum = str_replace('(OR ','(',$or_nim_belum);
    $or_nim_belum .= ')';
    $s = "UPDATE tb_mhs SET status_bayar_manual=NULL WHERE $or_nim_belum";
    echo "<div class=debug>$s</div>";
    $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  }

  echo div_alert('success','Update sukses.');
  echo "<script>location.replace('?pembayaran_manual&keyword=$keyword')</script>";
  exit;

}



# =============================================================
# NORMAL FLOW
# =============================================================
$null = '<span class="red miring kecil">null</span>';
$s = "SELECT 1 FROM tb_mhs a WHERE status_mhs=1";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
$jumlah_mhs_aktif = mysqli_num_rows($q);

$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
$keyword = isset($_POST['keyword']) ? $_POST['keyword'] : $keyword;
$bg = $keyword=='' ? '' : 'style="background: #0f0"';
$form_filter = "
<form method=post>
  <div class='wadah'>
    <input name='keyword' value='$keyword' $bg> 
    <button class='btn btn-primary btn-sm'>Filter</button>
    <a href='?pembayaran_manual' class='btn btn-info btn-sm'>Clear</a>
  </div>
</form>
";

echo "<p>Jumlah Mhs Aktif yang tercatat adalah $jumlah_mhs_aktif mhs. Silahkan lakukan filter berdasarkan kelas/nama/nim!</p>$form_filter";

if($keyword!=''){
  $s = "SELECT a.*,
  (SELECT nama FROM tb_prodi WHERE id=a.id_prodi) nama_prodi  
  FROM tb_mhs a 
  WHERE status_mhs=1  
  AND (a.nama LIKE '%$keyword%' OR a.nim LIKE '%$keyword%' OR a.kelas_manual LIKE '%$keyword%')
  ORDER BY a.nama 
  ";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  $jumlah_mhs_filter = mysqli_num_rows($q);

  $limit_info = '';
  if($jumlah_mhs_filter>50){
    $s = "$s LIMIT 50";
    $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
    $limit_info = ' | Limit 50';
  }

  $jumlah_data = mysqli_num_rows($q);

  $i=0;
  $tr='';
  $nims='';
  $tnow = strtotime('now');
  $jumlah_bayar = 0;
  while ($d=mysqli_fetch_assoc($q)) {
    $i++;
    if($d['status_bayar_manual']!='') $jumlah_bayar++;
    $nims .= "$d[nim];";

    $id = $d['id'];
    $prodi = $d['nama_prodi'];
    $kelas = $d['kelas_manual']=='' ? $null : $d['kelas_manual'];
    $semester = $d['semester_manual']=='' ? $null : $d['semester_manual'];
    $status_bayar_manual = $d['status_bayar_manual'];
    $merah = $status_bayar_manual=='' ? 'gradasi-merah' : '';

    if($d['kelas_manual']=='' or $d['semester_manual']==''){
      // redirect to manage mhs aktif
      $status_bayar = "<div class='red kecil miring mb1'>Kelas atau Posisi Semester mahasiswa masih kosong.</div><a class='btn btn-primary' href='?manage_keaktifan&nim=$d[nim]'>Manage Status Keaktifan</a>";

    }else{
      $set_belum_bayar = "<label class='btn btn-danger'><input type=checkbox name=belum_bayar__$d[nim]> Set Belum Bayar</label>";
      $checked = $status_bayar_manual=='' ? '' : 'checked';
      $status_bayar = $status_bayar_manual=='' 
      ? "<label class='btn btn-warning'><input type=checkbox $checked name=sudah_bayar__$d[nim]> Set Sudah Bayar</label>" 
      : "<div class=mb2><span class='badge badge-success'>Sudah Bayar</span></div>$set_belum_bayar";
    }

    $tr.="
    <tr class='$merah'>
      <td>$i</td>
      <td>
        $d[nama]
        <div class='kecil miring abu'>$d[nim]</div>
      </td>
      <td>
        <b>Prodi</b> : $prodi <br>
        <b>Kelas</b> : $kelas <br>
        <b>Semester</b> : $semester
      </td>
      <td>
        $status_bayar
      </td>
    </tr>";
  }

  // $jumlah_bayar = $jumlah_data-1; //zzz

  $jumlah_bayar_info = $jumlah_bayar==0 ? '<span class=red>Belum ada yang bayar</span>' : "<span class=red>Jumlah bayar: $jumlah_bayar of $jumlah_data</span>";
  $jumlah_bayar_info = $jumlah_bayar==$jumlah_data ? '<span class="blue bold">Semua sudah bayar</span>' : $jumlah_bayar_info;
  $jumlah_bayar_info = $jumlah_data==0 ? '' : ' | '.$jumlah_bayar_info;


  $tb="
  <div class='kecil miring abu mb2'>$jumlah_mhs_filter mhs ditemukan. $limit_info $jumlah_bayar_info</div>
  <table class='table'>
    <thead>
      <th>No</th>
      <th>Mahasiswa</th>
      <th>Info Akademik</th>
      <th>Status Bayar <span class=debug>Manual</span></th>
    </thead>
    $tr
  </table>
  <span class=debug>nims: <input class=debug name=nims value='$nims'></span>
  ";

  $disabled = $jumlah_data==0 ? 'disabled' : '';
  $form = "
  <form method=post>
    <input class=debug name=keyword value='$keyword'>
    $tb
    <div style='position:sticky; bottom:0; background:white;padding-bottom:10px;padding-top:5px;'>
      <button class='btn btn-primary btn-block' name=btn_simpan onclick='return confirm(\"Simpan Status Pembayaran?\")' $disabled>Simpan</button>
    </div>
  </form>
  ";
  echo $form;
}

?>











<script>
  $(function(){
    $(".editable").click(function(){
      let tid = $(this).prop('id');
      let rid = tid.split('__');
      let kolom = rid[0];
      let id = rid[1];
      let isi = $(this).text();

      let isi_baru = prompt(`Data ${kolom} baru:`,isi);
      if(isi_baru===null) return;
      if(isi_baru.trim()==isi) return;

      isi_baru = isi_baru.trim()==='' ? 'NULL' : isi_baru.trim();
      
      // VALIDASI UPDATE DATA
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