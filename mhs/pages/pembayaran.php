<?php
$rbiaya = [
  'Formulir',
  'Awal Perkuliahan',
  'Almamater',
  'PKKMB dan KU',
  'Perpustakaan dan Asuransi',
  'KTM',
  'Kemahasiswaan',
  'Semester 1',
  'Semester 2',
  'Semester 3',
  'Semester 4',
  'Semester 5',
  'Semester 6',
  'Semester 7',
  'Semester 8',
  'Perpanjangan Studi',
  'Bimbingan Magang Industri',
  'Bimbingan Skripsi',
  'Ujian Perbaikan',
  'Investasi Buku',
  'Wisuda'
];

$tr_biaya='
<thead>
  <th>No</th>
  <th>Jenis Biaya</th>
  <th>Status Pembayaran</th>
  <th>Aksi</th>
</thead>
';
for ($i=0; $i < count($rbiaya); $i++) {
  $j=$i+1; 
  $tr_biaya.="
  <tr>
    <td>$j</td>
    <td>$rbiaya[$i]</td>
    <td>-</td>
    <td>-</td>
  </tr>";
}
?>

<section id="pembayaran" class="" data-aos="fade-left">
  <div class="container">

    <div class="section-title">
      <h2>Pembayaran</h2>
      <p>Berikut adalah Data Pembayaran yang pernah Anda bayarkan:</p>
      <div class="alert alert-info">
        Maaf, fitur ini belum bisa Anda gunakan.
      </div>
    </div>

    <table class="table">
      <?=$tr_biaya?>
    </table>


  </div>
</section>