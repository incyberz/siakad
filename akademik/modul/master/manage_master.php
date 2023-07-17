<h1>MANAGE MASTER DATA</h1>
<p>Master data adalah data yang dapat berdiri sendiri <code>tanpa foreign key</code> dari tabel lain. Pada menu ini Anda dapat mengelola Data-data Master yang dibutuhkan untuk proses pengelolaan SIAKAD. Perubahan pada data master akan mempengaruhi proses SIAKAD secara keseluruhan.</p>

<?php
$izin = (
  $admin_level==3
||$admin_level==6
||$admin_level==7
||$admin_level==8
||$admin_level==9
) ? 1 : 0;
if(!$izin) echo div_alert('danger','Maaf, hanya Bagian Akademik yang berhak mengakses Menu ini.');


$rmaster[0] = ['angkatan','angkatan yang aktif'];
$rmaster[1] = ['jenjang','jenjang yang ada'];
$rmaster[2] = ['prodi','program studi yg terdaftar'];
$rmaster[3] = ['user','manage data user'];
$rmaster[4] = ['dosen','manage data dosen'];
$rmaster[5] = ['mhs','manage data mhs'];
$rmaster[6] = ['mk','manage data mk'];
$rmaster[7] = ['bk','bidang keahlian untuk mk'];

echo '<div class="master-home">';
for ($i=0; $i < count($rmaster); $i++) { 
  $href = $izin ? '?master&p='.$rmaster[$i][0] : '#';
  $no_master = $i+1;
  echo "
  <div class='item-master'>
    <div>
      <div class=tengah>
        <div class=no_master>1.$no_master</div>
      </div>
      <a href='$href'>master<br> ".$rmaster[$i][0]."</a>
      <div class=ket_master>".$rmaster[$i][1]."</div>
    </div>
  </div>
  ";
}
echo '</div>';
?>