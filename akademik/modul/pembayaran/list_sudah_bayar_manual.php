<h1>List Sudah Bayar <span class=debug>Manual</span></h1>

<?php
$null = '<span class="red miring kecil">null</span>';

# =============================================================
# NORMAL FLOW AKTIF
# =============================================================
$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
$keyword = isset($_POST['keyword']) ? $_POST['keyword'] : $keyword;
$keyword2 = isset($_GET['keyword2']) ? $_GET['keyword2'] : '';
$keyword2 = isset($_POST['keyword2']) ? $_POST['keyword2'] : $keyword2;


$bg = $keyword=='' ? '' : 'style="background: #0f0"';
$form_filter = "
<form method=post>
  <div class='wadah'>
    <input name='keyword2' value='$keyword2' class=debug> 
    <input name='keyword' value='$keyword' $bg required minlength=3 maxlength=20> 
    <button class='btn btn-primary btn-sm'>Filter</button>
    <a href='?list_mhs_aktif' class='btn btn-info btn-sm'>Clear</a>
  </div>
</form>
";

$from = " FROM tb_mhs a 
WHERE status_mhs=1 
AND (a.nama LIKE '%$keyword%' 
OR a.kelas_manual LIKE '%$keyword%' 
OR a.nim LIKE '%$keyword%') 

";
$s = "SELECT 1 $from";
echo "<span class=debug><pre>Debug Jumlah Aktif: $s</pre></span>";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
$jumlah_non = mysqli_num_rows($q);

$s = "SELECT a.*,(
  SELECT singkatan FROM tb_prodi WHERE id=a.id_prodi 
) as nama_prodi 
$from ORDER BY a.nama LIMIT 10 ";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
$tr = '';
if(mysqli_num_rows($q)>0){
  while ($d=mysqli_fetch_assoc($q)) {
    $kelas = $d['kelas_manual']=='' ? $null : $d['kelas_manual'];
    $semester = $d['semester_manual']=='' ? $null : $d['semester_manual'];
    $tr .= "
      <tr id=tr__$d[nim]>
        <td><span id='nama_mhs__$d[nim]'>$d[nama]</span><div class='kecil miring abu'>$d[nim]</div></td>
        <td class=kecil>
        <div><b>Prodi</b> : $d[nama_prodi]</div>
        <div><b>Kelas</b> : $kelas</div>
        <div><b>Semester</b> : $semester</div>
        </td>
        <td>
          <button class='btn btn-success btn-sm btn_aksi' id=set_lunas__$d[nim]>Set Lunas</button>
        </td>
      </tr>
    ";
  }
}
$tb_non = $tr=='' ? div_alert('info','Mahasiswa aktif tidak ditemukan.') 
: "<table class='table table-striped'>$tr</table>";

$limit_info = $jumlah_non>10 ? "| <code>Limit 10</code> | Silahkan Filter!" : '';






# =============================================================
# NORMAL BAYAR
# =============================================================
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
AND status_bayar_manual = 1 
";
$s = "SELECT id $from";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
echo "<span class=debug><pre>Debug Jumlah Bayar: $s</pre></span>";
$jumlah_bayar = mysqli_num_rows($q);

$s = "SELECT a.*,(
  SELECT singkatan FROM tb_prodi WHERE id=a.id_prodi 
) as nama_prodi 
$from ORDER BY a.nama LIMIT 10 ";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
$tr = '';
if(mysqli_num_rows($q)>0){
  while ($d=mysqli_fetch_assoc($q)) {
    $kelas = $d['kelas_manual']=='' ? $null : $d['kelas_manual'];
    $semester = $d['semester_manual']=='' ? $null : $d['semester_manual'];
    $tr .= "
      <tr id=tr__$d[nim]>
        <td><span id='nama_mhs__$d[nim]'>$d[nama]</span><div class='kecil miring abu'>$d[nim]</div></td>
        <td class=kecil>
        <div><b>Prodi</b> : $d[nama_prodi]</div>
        <div><b>Kelas</b> : $kelas</div>
        <div><b>Semester</b> : $semester</div>
        </td>
        <td>
          <button class='btn btn-danger btn-sm btn_aksi' id=set_belum_bayar__$d[nim]>Set Belum bayar</button>
        </td>
      </tr>
    ";
  }
}
$tb_aktif = $tr=='' ? div_alert('info','Data pembayaran tidak ditemukan.') 
: "<table class='table table-striped'>$tr</table>";

$limit_info2 = $jumlah_bayar>10 ? "| <code>Limit 10</code> | Silahkan Filter!" : '';








echo "
<div class='row'>
  <div class='col-sm-6'>
    <div class='wadah'>
      <h3>List Mahasiswa Aktif</h3>
      <div class='small mb1'>$jumlah_non mhs aktif ditemukan. $limit_info</div>
      $form_filter
      $tb_non
    </div>
  </div>
  <div class='col-sm-6'>
    <div class='wadah'>
      <h3 class='biru tebal'>List Sudah Bayar</h3>
      <div class='small mb1'>$jumlah_bayar lunas bayar ditemukan. $limit_info2</div>
      $form_filter2
      $tb_aktif
    </div>
  </div>
</div>
";



?>

<script>
  $(function(){
    $('.btn_aksi').click(function(){
      let tid = $(this).prop('id');
      let rid = tid.split('__');
      let aksi = rid[0];
      let nim = rid[1];
      let nama_mhs = $('#nama_mhs__'+nim).text();

      let c = '';
      if(aksi=='set_lunas'){
        c = `Yakin untuk set lunas atas nama ${nama_mhs}?`;
      }else if(aksi=='set_belum_bayar'){
        c = `Yakin untuk set belum bayar atas nama ${nama_mhs}?\n\nMahasiswa belum bayar tidak bisa mengakses KRS, Ujian, atau KHS.`;
      }
      let y = confirm(c);
      if(!y) return;




      console.log(aksi,nim, nama_mhs);
      let kolom = 'status_mhs'; // field pada table
      let link_ajax = `ajax_akademik/ajax_set_status_mhs.php?nim=${nim}&aksi=${aksi}&kolom=${kolom}`;
      $.ajax({
        url:link_ajax,
        success:function(a){
          if(a.trim()=='sukses'){
            let di = aksi=='set_lunas' ? `diaktifkan` : '<span class=red>dinonaktifkan</span>';
            $('#tr__'+nim).html(`<td colspan=3>Mahasiswa dengan nim: ${nim} telah ${di}.</td>`);
          }else{
            alert(a);
          }
        }
      })

    })
  })
</script>