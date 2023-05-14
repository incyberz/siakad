
<?php

# ==========================================================
# SAAT INI
# ==========================================================
$s = "SELECT a.*, b.nama as nama_mk FROM tb_nilai_tmp a 
JOIN tb_mk_tmp b ON a.id_mk_tmp=b.id 
WHERE a.nim=$nim";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));

$tb_tmp=div_alert('Danger','Data Nilai tidak ditemukan (Anda belum KRS atau Petugas Akademik belum input nilai).');
if(mysqli_num_rows($q)>0){
  $tb_tmp='<thead>
    <th>No</th>
    <th>MK</th>
    <th>Nilai</th>
    <th>Mutu</th>
  </thead>';
  $i=0;
  while ($d=mysqli_fetch_assoc($q)) {
    $i++;
    $d['nilai'] = $d['nilai']==''?'<span style="color:#f55"><i>null</i></span>':$d['nilai'];
    $d['hm'] = $d['hm']==''?'E':$d['hm'];
    $tb_tmp.= "
    <tr>
      <td>$i</td>
      <td>$d[nama_mk]</td>
      <td>$d[nilai]</td>
      <td>$d[hm]</td>
    </tr>
    ";
  }
  $info = '<div class="mt-3"><small><i>)* jika terdapat nilai <code>null</code> maka dikarenakan Anda belum KRS.</i></small></div>';
  $tb_tmp="<table class=table>$tb_tmp</table>$info";
}
?>


<section id="khs_akd" class="section-bg"  data-aos="fade-left">
  <div class="container">

    <div class="section-title">
      <h2>KHS Temporer</h2>
      <p>Berikut adalah Kartu Hasil Studi (KHS) langsung dari Data Akademik (Non-SIAKAD).</p>
    </div>

    <?=$tb_tmp?>
  <hr>

  </div>
</section>

