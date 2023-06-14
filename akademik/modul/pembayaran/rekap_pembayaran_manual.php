<h1>Rekap Pembayaran <span class=debug>Manual</span></h1>
<?php
# =============================================================
# NORMAL FLOW
# =============================================================
$null = '<span class="red miring kecil">null</span>';
$s = "SELECT * FROM tb_mhs a WHERE status_mhs=1";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
$jumlah_mhs_aktif = mysqli_num_rows($q);

$rid_prodi = [41,42,43,31,32,99];
$rnama_prodi = ['TI','RPL','SI','MI','KA','Tanpa Prodi'];
$rangkatan = [2023,2022,2021,2020,'lainnya'];
$jumlah_bayar = 0;
for ($i=0; $i < count($rid_prodi); $i++){
  $jumlah_mhs_aktif_prodi[$rid_prodi[$i]] = 0;
  $jumlah_bayar_prodi[$rid_prodi[$i]] = 0;
  for ($j=0; $j < count($rangkatan); $j++) { 
    $jumlah_mhs_aktif_prodi_angkatan[$rid_prodi[$i]][$rangkatan[$j]] = 0;
    $jumlah_bayar_prodi_angkatan[$rid_prodi[$i]][$rangkatan[$j]] = 0;
  }
} 

while ($d=mysqli_fetch_assoc($q)) {
  $lunas = $d['status_bayar_manual'] ? 1 : 0;
  if($lunas) $jumlah_bayar++;
  if($d['id_prodi']==''){
    $jumlah_mhs_aktif_prodi[99]++; //unprodi
    if($lunas) $jumlah_bayar_prodi[99]++; //bayar
  }else{
    $jumlah_mhs_aktif_prodi[$d['id_prodi']]++;
    if($lunas) $jumlah_bayar_prodi[$d['id_prodi']]++;

    if($d['angkatan']==''){
      $jumlah_mhs_aktif_prodi_angkatan[$d['id_prodi']]['lainnya']++;
      if($lunas) $jumlah_bayar_prodi_angkatan[$d['id_prodi']]['lainnya']++;
    }else{
      $jumlah_mhs_aktif_prodi_angkatan[$d['id_prodi']][$d['angkatan']]++;
      if($lunas) $jumlah_bayar_prodi_angkatan[$d['id_prodi']][$d['angkatan']]++;
    }
  } 
}

$loop = '';
$loop_bayar = '';
for ($i=0; $i < count($rid_prodi); $i++){
  $loop .= "<div class='wadah bg-white'><div class='darkblue mb2'>Prodi ".$rnama_prodi[$i]." : ".$jumlah_mhs_aktif_prodi[$rid_prodi[$i]]."</div>";
  $persen = $jumlah_mhs_aktif_prodi[$rid_prodi[$i]]==0 ? 0 
  : round($jumlah_bayar_prodi[$rid_prodi[$i]]/$jumlah_mhs_aktif_prodi[$rid_prodi[$i]],3);
  $warna = $persen==0 ? 'merah' : '';
  // $persen = 100; // test debug
  $warna = $persen==100 ? 'biru tebal' : $warna;
  $loop_bayar .= "<div class='wadah bg-white $warna'><div class='$warna mb2'>Prodi ".$rnama_prodi[$i]." : ".$jumlah_bayar_prodi[$rid_prodi[$i]]." ($persen%)</div>";
  for ($j=0; $j < count($rangkatan); $j++) { 
    $loop .= "<div class='wadah gradasi-kuning'>$rnama_prodi[$i]-$rangkatan[$j] : ".$jumlah_mhs_aktif_prodi_angkatan[$rid_prodi[$i]][$rangkatan[$j]]."</div>";
    $persen = $jumlah_mhs_aktif_prodi_angkatan[$rid_prodi[$i]][$rangkatan[$j]]==0 ? 0 
    : round($jumlah_bayar_prodi_angkatan[$rid_prodi[$i]][$rangkatan[$j]]/$jumlah_mhs_aktif_prodi_angkatan[$rid_prodi[$i]][$rangkatan[$j]],3);
    $gradasi = $persen==0 ? 'merah' : 'kuning';
    $gradasi = $persen==100 ? 'hijau' : $gradasi;
    $loop_bayar .= "<div class='wadah gradasi-$gradasi'>$rnama_prodi[$i]-$rangkatan[$j] : ".$jumlah_bayar_prodi_angkatan[$rid_prodi[$i]][$rangkatan[$j]]." ($persen%)</div>";
  }
  $loop .= '</div>';
  $loop_bayar .= '</div>';
} 

$persen = $jumlah_mhs_aktif==0 ? 0 : round($jumlah_bayar/$jumlah_mhs_aktif,3) ; 

echo "
<div class='row'>
  <div class='col-lg-6'>
    <div class='wadah gradasi-kuning'>
      <div class='biru mb2'><a href='?mhs_aktif'>Mhs Aktif : $jumlah_mhs_aktif</a></div>
      $loop
    </div>
  </div>


  <div class='col-lg-6'>
    <div class='wadah gradasi-hijau'>
      <div class='biru mb2'>Sudah Bayar : $jumlah_bayar ($persen%)</div>
      $loop_bayar
    </div>
  </div>
</div>
";

exit;

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