<h1>Rekap Mahasiswa Aktif</h1>
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
  background: white;
}</style>
<?php
# =============================================================
# NORMAL FLOW
# =============================================================
$null = '<span class="red miring kecil">null</span>';
$s = "SELECT * FROM tb_mhs a";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));

$rid_prodi = [41,42,43,31,32,99]; //zzz
$rnama_prodi = ['TI','RPL','SI','MI','KA','Tanpa Prodi']; //zzz
$rangkatan = [2023,2022,2021,2020,'lainnya']; //zzz
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
  $loop_allmhs .= "<div class='wadah bg-white $hideit_prodi'><div class='darkblue mb2'>Prodi $rnama_prodi[$i] : ".$jumlah_allmhs_prodi[$rid_prodi[$i]]."</div>";
  $loop_aktif .= "<div class='wadah bg-white $warna $hideit_prodi'><div class='$warna mb2'>Prodi $rnama_prodi[$i] : ".$jumlah_aktif_prodi[$rid_prodi[$i]]." ($persen%)</div>";
  for ($j=0; $j < count($rangkatan); $j++) { 
    $persen = $jumlah_allmhs_prodi_angkatan[$rid_prodi[$i]][$rangkatan[$j]]==0 ? 0 
    : round(100*$jumlah_aktif_prodi_angkatan[$rid_prodi[$i]][$rangkatan[$j]]/$jumlah_allmhs_prodi_angkatan[$rid_prodi[$i]][$rangkatan[$j]],2);
    $gradasi = $persen==0 ? 'merah' : 'kuning';
    $gradasi = $persen==100 ? 'hijau' : $gradasi;
    $hideit_lainnya1 = $jumlah_allmhs_prodi_angkatan[$rid_prodi[$i]][$rangkatan[$j]]==0 ? 'hideit' : ''; //hide lainnya pada prodi if count 0
    $hideit_lainnya2 = $persen==0 ? 'hideit' : ''; //hide lainnya pada prodi if count 0
    $prodi_ang = "$rnama_prodi[$i]-$rangkatan[$j]";
    $prodi_ang = $rangkatan[$j]=='lainnya' ? $prodi_ang : "<a href='?master_mhs&keyword=$prodi_ang&keyword2=$prodi_ang'>$prodi_ang</a>";
    $loop_allmhs .= "<div class='wadah gradasi-kuning $hideit_lainnya1'>$prodi_ang : ".$jumlah_allmhs_prodi_angkatan[$rid_prodi[$i]][$rangkatan[$j]]."</div>";
    $loop_aktif .= "<div class='wadah gradasi-$gradasi $hideit_lainnya2'>$prodi_ang : ".$jumlah_aktif_prodi_angkatan[$rid_prodi[$i]][$rangkatan[$j]]." ($persen%)</div>";
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
      <div class='biru mb2'><a href='?master_mhs'>Mhs Aktif : $jumlah_aktif ($persen%)</a> <img class='img_expand expand_v1' src='../assets/img/icons/expand.png' height=20px></div>
      $loop_aktif
    </div>
  </div>
</div>
";
