<style>ol{padding:0 0 0 17px}</style>
<?php
include 'session_security.php';
include '../../conn.php';

$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : die(erid('keyword'));
$id_bulan = isset($_GET['id_bulan']) ? $_GET['id_bulan'] : die(erid('id_bulan'));

# ===================================================
# LIST JADWAL
# ===================================================

$limit = 50;
$s = "SELECT 
a.id as id_dosen,
a.nidn,
a.nama as nama_dosen

FROM tb_dosen a 
WHERE (a.nidn like '%$keyword%' OR a.nama like '%$keyword%') 

ORDER BY a.nama    
";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
$jumlah_row = mysqli_num_rows($q);

$s .= " LIMIT $limit 
";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));

$thead = '
  <thead>
    <th>No</th>
    <th>DOSEN</th>
    <th>MK KOORDINATOR</th>
    <th>SKS TIM TEACHING BULAN INI</th>
  </thead>
';
$tr = '';
$i=0;
while ($d = mysqli_fetch_assoc($q)) {
  $i++;

  # ==============================================
  # MK AKTIF SEMESTER INI
  # ==============================================
  $today = date('Y-m-d');
  $s2 = "SELECT 
  a.id as id_jadwal,
  (c.bobot_teori + c.bobot_praktik) as bobot, 
  c.nama as nama_mk 

  FROM tb_jadwal a 
  JOIN tb_kurikulum_mk b on b.id=a.id_kurikulum_mk 
  JOIN tb_mk c on c.id=b.id_mk 
  JOIN tb_semester d on d.id=b.id_semester 
  where a.id_dosen=$d[id_dosen] 
  AND d.tanggal_awal <= '$today'
  AND d.tanggal_akhir > '$today' 
  ";
  // die($s2);
  $q2 = mysqli_query($cn,$s2) or die(mysqli_error($cn));
  $li_mk='';
  $total_sks_mk=0;
  while ($d2=mysqli_fetch_assoc($q2)) {
    $li_mk.="<li>$d2[nama_mk] ($d2[bobot] SKS)</li>";
    $total_sks_mk += $d2['bobot'];
  }
  $li_mk = $li_mk=='' ? '<span class="kecil red miring">none</span>' : "<ol>$li_mk</ol><div class='tebal darkblue'>$total_sks_mk SKS</div>";


  # ==============================================
  # SESI AKTIF BULAN INI
  # ==============================================
  $s3 = "SELECT 
  a.nama as nama_sesi, 
  d.nama as nama_mk, 
  (d.bobot_teori + d.bobot_praktik) as bobot  
  FROM tb_sesi a 
  JOIN tb_jadwal b ON b.id=a.id_jadwal  
  JOIN tb_kurikulum_mk c ON c.id=b.id_kurikulum_mk  
  JOIN tb_mk d ON d.id=c.id_mk  
  WHERE a.id_dosen = $d[id_dosen] 
  AND a.tanggal_sesi >= '2023-4-1' 
  AND a.tanggal_sesi < '2023-5-1' 
  ";
  // die($s3);
  $q3 = mysqli_query($cn,$s3) or die(mysqli_error($cn));
  $li_sesi='';
  $total_sks_sesi=0;
  while ($d3=mysqli_fetch_assoc($q3)) {
    $li_sesi.="<li>$d3[nama_sesi] ($d3[bobot] SKS)<br><span class='kecil miring'>MK: $d3[nama_mk]</span></li>";
    $total_sks_sesi += $d3['bobot'];
  }
  $li_sesi = $li_sesi=='' ? '<span class="kecil red miring">none</span>' : "<ol>$li_sesi</ol><div class='tebal darkblue'>$total_sks_sesi SKS</div>";

  $tr .= "<tr id='tr__$d[id_dosen]'>
    <td>$i</td>
    <td>
      $d[nama_dosen]
      <div class='kecil miring'>$d[nidn]</div>
    </td>
    <td>$li_mk</td>
    <td>$li_sesi</td>
  </tr>";
}

$jumlah_row_of = "Tampil $i data dari $jumlah_row total";
$sisa_data = $i==$limit ? "$jumlah_row_of <span class=red>(masih ada data sisa)</span> silahkan filter dengan lebih spesifik." : $jumlah_row_of;

$tb = $tr=='' ? '<div class="alert alert-info">Mahasiswa tidak ditemukan. | <a href="?master&p=dosen">Master dosen</a></div>' : "<table class='table table-hover'>$thead$tr</table>";

$debug = "<div class=debug>$s</div>";
echo "<div class=wadah>$sisa_data</div>$tb$debug";