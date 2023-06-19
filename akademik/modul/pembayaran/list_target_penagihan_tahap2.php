<?php
$null = '<span class="red miring kecil">null</span>';

# =============================================================
# NORMAL MHS AKTIF
# =============================================================
$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
$keyword = isset($_POST['keyword']) ? $_POST['keyword'] : $keyword;
$keyword2 = isset($_GET['keyword2']) ? $_GET['keyword2'] : '';
$keyword2 = isset($_POST['keyword2']) ? $_POST['keyword2'] : $keyword2;


$bg = $keyword2=='' ? '' : 'style="background: #0f0"';
$form_filter2 = "
<form method=post>
  <div class='wadah'>
    <input name='keyword' value='$keyword' class=debug> 
    <input name='keyword2' value='$keyword2' $bg required minlength=3 maxlength=20> 
    <button class='btn btn-primary btn-sm'>Filter</button>
    <a href='?list_mhs_aktif' class='btn btn-info btn-sm'>Clear</a>
  </div>
</form>
";

$from = " FROM tb_mhs a 
WHERE status_mhs = 1  
AND (a.nama LIKE '%$keyword2%' 
OR a.kelas_manual LIKE '%$keyword2%' 
OR a.nim LIKE '%$keyword2%')  
";
$s = "SELECT id $from";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
// echo "<span class=debug><pre>Debug Jumlah Aktif: $s</pre></span>";
$jumlah_aktif = mysqli_num_rows($q);

$s = "SELECT a.*,(
  SELECT singkatan FROM tb_prodi WHERE id=a.id_prodi 
) as nama_prodi 
$from ORDER BY a.nama LIMIT 40 ";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
$tr = '';
if(mysqli_num_rows($q)>0){
  while ($d=mysqli_fetch_assoc($q)) {
    $kelas = $d['kelas_manual']=='' ? $null : $d['kelas_manual'];
    $semester = $d['semester_manual']=='' ? $null : $d['semester_manual'];
    $tr .= "
      <tr id=tr__$d[nim]>
        <td>
          <span id='nama_mhs__$d[nim]'>$d[nama]</span>
          <div class='kecil miring abu'>$d[nim]</div>
          <div class=kecil><a href='?login_as&nim=$d[nim]' target=_blank onclick='return confirm(\"Ingin login sebagai mahasiswa ini?\")'>Login as</a></div>
        </td>
        <td class=kecil>
        <div><b>Prodi</b> : $d[nama_prodi]</div>
        <div><b>Kelas</b> : <a href='?list_mhs_aktif&keyword=$kelas&keyword2=$kelas'>$kelas</a></div>
        <div><b>Semester</b> : $semester</div>
        </td>
        <td>
          <button class='btn btn-danger btn-sm btn_aksi' id=set_non__$d[nim]>Set Non-Aktif</button>
        </td>
      </tr>
    ";
  }
}
$tb_aktif = $tr=='' ? div_alert('info','Mahasiswa Aktif tidak ditemukan.') 
: "<table class='table table-striped'>$tr</table>";

$limit_info2 = $jumlah_aktif>40 ? "| <code>Limit 40</code> | Silahkan Filter!" : '';








echo "
  <div class='wadah'>
    <h3 class='biru tebal'>List Mahasiswa Aktif</h3>
    <div class='small mb1'>$jumlah_aktif mhs aktif ditemukan. $limit_info2</div>
    $form_filter2
    $tb_aktif
  </div>
";



?>

<script>
  $(function(){
    $('.btn_aksi').click(function(){
      
    })
  })
</script>