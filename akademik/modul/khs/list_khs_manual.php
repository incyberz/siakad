<h1>List KHS <span class=debug>Manual</span></h1>
<style>th{text-align:left}</style>
<?php
$limit=50;
$keyword = '';
$keyword = isset($_GET['nim']) ? $_GET['nim'] : $keyword;
$keyword = isset($_GET['nama_mhs']) ? $_GET['nama_mhs'] : $keyword;
$keyword = isset($_GET['nama_mk']) ? $_GET['nama_mk'] : $keyword;
$keyword = isset($_POST['keyword']) ? $_POST['keyword'] : $keyword;
$where_kw = $keyword==''? ' 1 ' : " a.nim like '%$keyword%' OR c.nama like '%$keyword%'  OR b.nama like '%$keyword%' ";
$bg_filter = $keyword==''? '' : '#0f0';
$clear = $keyword==''? '' : "| <a href='?list_khs_manual'>Clear</a>";


$fields = '
  b.nama as nama_mk,
  b.*,
  a.*,
  c.nama as nama_mhs 
';
$s_from = 'FROM tb_nilai_manual a
JOIN tb_mk_manual b ON a.id_mk_manual=b.id 
JOIN tb_mhs c ON a.nim=c.nim  
WHERE '.$where_kw;
$q = mysqli_query($cn,"SELECT 1 $s_from") or die(mysqli_error($cn));
$jumlah_data = mysqli_num_rows($q);
$jumlah_data_show = $jumlah_data==0 ? ' | no records found' : " &nbsp;&nbsp;&nbsp; $jumlah_data records found.";

$tb='';
if($jumlah_data>0){
  $q = mysqli_query($cn,"SELECT $fields $s_from LIMIT $limit") or die(mysqli_error($cn));

  $tb='
  <thead>
    <th>No</th>
    <th>Mahasiswa</th>
    <th>Mata Kuliah</th>
    <th>Nilai / HM</th>
    <th>Aksi</th>
  </thead>';
  $i=0;
  while ($d=mysqli_fetch_assoc($q)) {
    $i++;
    $nilai = $d['nilai']==''?$null:$d['nilai'];
    $hm = $d['hm']==''?$null:$d['hm'];
    $tb.="
    <tr>
      <td>$i</td>
      <td>
        <a href='?list_khs_manual&nama_mhs=$d[nama_mhs]'>$d[nama_mhs]</a>
        <div class='kecil miring'><a href='?list_khs_manual&nim=$d[nim]'>$d[nim]</a></div>
        </td>
        <td>
        <a href='?list_khs_manual&nama_mk=$d[nama_mk]'>$d[nama_mk]</a>
        <div class='kecil miring'>Semester: $d[semester] ~ $d[bobot] SKS</div>
        <div class='kecil miring'>$d[prodi] ~ $d[angkatan]</div>
        </td>
        <td>
        $nilai / $hm
        <div class='kecil consolas'>$d[date_created]</div>
      </td>
      <td><a href='?input_khs_manual&nim=$d[nim]&id_mk_manual=$d[id_mk_manual]' onclick='return confirm(\"Anda yakin untuk mengubah nilai ini?\")'>Ubah</a></td>
    </tr>
    ";
  }
  $info_limit = $jumlah_data>$limit?'<div class="red miring kecil">Masih ada data tersembunyi (limit 50), silahkan klik atau filter untuk menampilkan data spesifik.</div>' : '';
  $tb = "<table class=table>$tb</table>$info_limit";
}else{
  $tb = div_alert('danger','Data Nilai tidak ditemukan.');
}

echo "
<div class=wadah>
  <form method=post>
    <table width=100%><tr><td>
    Filter: 
    <input name=keyword maxlength=20 style='background:$bg_filter' value='$keyword'> 
    <button>Filter</button> 
    $clear 
    $jumlah_data_show
    </td><td align=right>
      <a href='?input_khs_manual' class='btn btn-info btn-sm'>Input</a>  
      <a href='?import_khs_manual' class='btn btn-info btn-sm'>Import</a>
    </td></tr></table>
  </form>
</div>
$tb
";
