<?php
$judul = 'Join Ruangan';
# =====================================================
# USER INTERFACE 2 SESI
# =====================================================
function ui($d){
  return "
  <div class=wadah>
    <table class='table table-striped table-hover'>
      <tr><td>Sesi</td><td>P$d[pertemuan_ke] | $d[nama_sesi]</td></tr>
      <tr><td>MK</td><td>$d[nama_mk] | $d[kode_mk]</td></tr>
      <tr><td>Kurikulum</td><td>$d[jenjang]-$d[prodi]-$d[angkatan]</td></tr>
      <tr><td>Pengajar</td><td>$d[pengajar]</td></tr>
      <tr><td>Peserta</td><td>zzz mhs</td></tr>
    </table>
  </div>";
}

$id_sesi = $_GET['id_sesi'] ?? die(erid('id_sesi'));
$join_with = $_GET['join_with'] ?? die(erid('join_with'));
$id_ruang = $_GET['id_ruang'] ?? die(erid('id_ruang'));



# =====================================================
# IDENTITAS RUANGAN
# =====================================================
$s = "SELECT * FROM tb_ruang WHERE id=$id_ruang";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
if(mysqli_num_rows($q)==0) die('Data ruangan tidak ditemukan.');
$d_ruang = mysqli_fetch_assoc($q);
echo "
<h1>Join Ruangan :: $d_ruang[nama]</h1>
<p>
<a href='?assign_ruang&id_sesi=$id_sesi'>Back</a> | 
Gabungkan 2 Sesi Perkuliahan menjadi satu ruangan di <b class='consolas darkblue'>$d_ruang[nama]</b> dg kapasitas <span class='darkblue consolas'>$d_ruang[kapasitas] kursi</span></p>";


# =====================================================
# START
# =====================================================
$s = "SELECT 
a.id as id_sesi,
a.nama as nama_sesi,
a.pertemuan_ke,
d.id as id_jadwal,
d.nama as nama_mk,
d.kode as kode_mk,
e.id as id_pengajar,  
e.nama as pengajar,
g.angkatan,
g.jenjang,
h.singkatan as prodi    
FROM tb_sesi a 
JOIN tb_jadwal b ON a.id_jadwal=b.id
JOIN tb_kurikulum_mk c ON b.id_kurikulum_mk=c.id
JOIN tb_mk d ON c.id_mk=d.id
JOIN tb_dosen e ON a.id_dosen=e.id
JOIN tb_kurikulum f ON c.id_kurikulum=f.id
JOIN tb_kalender g ON f.id_kalender=g.id
JOIN tb_prodi h ON f.id_prodi=h.id
";
$s1 = "$s WHERE a.id='$id_sesi'";
$q = mysqli_query($cn,$s1) or die(mysqli_error($cn));
if(mysqli_num_rows($q)==0) die('data sesi tidak ditemukan');
$d1=mysqli_fetch_assoc($q);

$s = "$s WHERE a.id='$join_with'";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
if(mysqli_num_rows($q)==0) die('data sesi tidak ditemukan');
$d2=mysqli_fetch_assoc($q);

$blok1 = ui($d1);
$blok2 = ui($d2);

echo "
<div style='display:grid; grid-template-columns: auto auto; grid-gap: 15px;'>
  <div>
    $blok1
  </div>
  <div>
    $blok2
  </div>
</div>
";
