<h1>Mahasiswa Aktif <span class=debug>Manual</span></h1>
<style>.img_expand{
  padding: 3px;
  box-shadow: 0 0 2px black;
  border-radius: 3px;
  margin-left: 15px;
  cursor: pointer;
  transition: .2s;
}.img_expand:hover{
  transform:scale(1.2);
}.expand_v1{
  /* zzz here */
  background: white;
}</style>
<?php
# =============================================================
# NORMAL FLOW
# =============================================================
$null = '<span class="red miring kecil">null</span>';
$s = "SELECT * FROM tb_mhs a";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));

$rid_prodi = [41,42,43,31,32,99];
$rnama_prodi = ['TI','RPL','SI','MI','KA','Tanpa Prodi'];
$rangkatan = [2023,2022,2021,2020,'lainnya'];
$jumlah_aktif = 0;
for ($i=0; $i < count($rid_prodi); $i++){
  $jumlah_allmhs_prodi[$rid_prodi[$i]] = 0;
  $jumlah_aktif_prodi[$rid_prodi[$i]] = 0;
  for ($j=0; $j < count($rangkatan); $j++) { 
    $jumlah_allmhs_prodi_angkatan[$rid_prodi[$i]][$rangkatan[$j]] = 0;
    $jumlah_aktif_prodi_angkatan[$rid_prodi[$i]][$rangkatan[$j]] = 0;
  }
} 

$jumlah_allmhs = 0;
while ($d=mysqli_fetch_assoc($q)) {
  $jumlah_allmhs++;
  $aktif = $d['status_mhs'] ? 1 : 0;
  if($aktif) $jumlah_aktif++;
  if(!in_array($d['id_prodi'],$rid_prodi)) continue; //skip id_prodi 44
  if($d['id_prodi']==''){
    $jumlah_allmhs_prodi[99]++; //unprodi
    if($aktif) $jumlah_aktif_prodi[99]++; //allmhs
  }else{
    $jumlah_allmhs_prodi[$d['id_prodi']]++;
    if($aktif) $jumlah_aktif_prodi[$d['id_prodi']]++;

    if($d['angkatan']==''){
      $jumlah_allmhs_prodi_angkatan[$d['id_prodi']]['lainnya']++;
      if($aktif) $jumlah_aktif_prodi_angkatan[$d['id_prodi']]['lainnya']++;
    }else{
      $jumlah_allmhs_prodi_angkatan[$d['id_prodi']][$d['angkatan']]++;
      if($aktif) {
        $jumlah_aktif_prodi_angkatan[$d['id_prodi']][$d['angkatan']]++;

        # ============================================
        # HITUNG KELAS PADA PRODI ANGKATAN
        # ============================================
        # ZZZ HERE
      }
    }
  } 
}

$loop_allmhs = '';
$loop_aktif = '';
for ($i=0; $i < count($rid_prodi); $i++){
  $persen = $jumlah_allmhs_prodi[$rid_prodi[$i]]==0 ? 0 
  : round(100*$jumlah_aktif_prodi[$rid_prodi[$i]]/$jumlah_allmhs_prodi[$rid_prodi[$i]],2);
  $warna = $persen==0 ? 'merah' : '';
  // $persen = 100; // test debug
  $warna = $persen==100 ? 'biru tebal' : $warna;
  $hideit_prodi = ($rid_prodi[$i]==99 and $persen==0) ? 'hideit' : ''; //hide unprodi if count 0
  // $hideit_prodi = '';
  $loop_allmhs .= "<div class='wadah bg-white $hideit_prodi'><div class='darkblue mb2'>Prodi ".$rnama_prodi[$i]." : ".$jumlah_allmhs_prodi[$rid_prodi[$i]]."</div>";
  $loop_aktif .= "<div class='wadah bg-white $warna $hideit_prodi'><div class='$warna mb2'>Prodi ".$rnama_prodi[$i]." : ".$jumlah_aktif_prodi[$rid_prodi[$i]]." ($persen%)</div>";
  for ($j=0; $j < count($rangkatan); $j++) { 
    $persen = $jumlah_allmhs_prodi_angkatan[$rid_prodi[$i]][$rangkatan[$j]]==0 ? 0 
    : round(100*$jumlah_aktif_prodi_angkatan[$rid_prodi[$i]][$rangkatan[$j]]/$jumlah_allmhs_prodi_angkatan[$rid_prodi[$i]][$rangkatan[$j]],2);
    $gradasi = $persen==0 ? 'merah' : 'kuning';
    $gradasi = $persen==100 ? 'hijau' : $gradasi;
    $hideit_lainnya1 = $jumlah_allmhs_prodi_angkatan[$rid_prodi[$i]][$rangkatan[$j]]==0 ? 'hideit' : ''; //hide lainnya pada prodi if count 0
    $hideit_lainnya2 = $persen==0 ? 'hideit' : ''; //hide lainnya pada prodi if count 0
    $loop_allmhs .= "<div class='wadah gradasi-kuning $hideit_lainnya1'>$rnama_prodi[$i]-$rangkatan[$j] : ".$jumlah_allmhs_prodi_angkatan[$rid_prodi[$i]][$rangkatan[$j]]."</div>";
    $loop_aktif .= "<div class='wadah gradasi-$gradasi $hideit_lainnya2'>$rnama_prodi[$i]-$rangkatan[$j] : ".$jumlah_aktif_prodi_angkatan[$rid_prodi[$i]][$rangkatan[$j]]." ($persen%)</div>";
  }
  $loop_allmhs .= '</div>';
  $loop_aktif .= '</div>';
} 

$persen = $jumlah_allmhs==0 ? 0 : round(100*$jumlah_aktif/$jumlah_allmhs,2) ; 

echo "
<div class='row'>
  <div class='col-lg-6'>
    <div class='wadah gradasi-kuning'>
      <div class='biru mb2'>All Mhs : $jumlah_allmhs</div>
      $loop_allmhs
    </div>
  </div>


  <div class='col-lg-6'>
    <div class='wadah gradasi-hijau'>
      <div class='biru mb2'><a href='?list_mhs_aktif'>Mhs Aktif : $jumlah_aktif ($persen%)</a> <img class='img_expand expand_v1' src='../assets/img/icons/expand.png' height=20px></div>
      $loop_aktif
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
        if(strpos("salt$key",'sudah_allmhs')){
          echo $rnim[$i].'__ Set sudah allmhs ... OK<br>';
          $or_nim_sudah .= "OR nim='$rnim[$i]' ";
          $jumlah_sudah++;
        }else{
          $or_nim_belum .= "OR nim='$rnim[$i]' ";
          echo $rnim[$i].'__ Set belum allmhs ... OK<br>';
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
    $s = "UPDATE tb_mhs SET status_mhs=1 WHERE $or_nim_sudah";
    echo "<div class=debug>$s</div>";
    $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  }  
  
  if($jumlah_belum){
    $or_nim_belum = str_replace('(OR ','(',$or_nim_belum);
    $or_nim_belum .= ')';
    $s = "UPDATE tb_mhs SET status_mhs=NULL WHERE $or_nim_belum";
    echo "<div class=debug>$s</div>";
    $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  }

  echo div_alert('success','Update sukses.');
  echo "<script>location.replace('?pemallmhsan_manual&keyword=$keyword')</script>";
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