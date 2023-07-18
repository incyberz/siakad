<?php
$null = '<span class="red miring kecil">null</span>';
$img_login_as = '<img src="../assets/img/icons/login_as.png" height=20px>';


# =============================================================
# NORMAL MHS AKTIF
# =============================================================
$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
$keyword = isset($_POST['keyword']) ? $_POST['keyword'] : $keyword;


$bg = $keyword=='' ? '' : 'style="background: #0f0"';
$form_filter2 = "
<form method=post>
  <div class='wadah'>
    <input name='keyword' value='$keyword' $bg required minlength=3 maxlength=20> 
    <button class='btn btn-primary btn-sm'>Filter</button>
    <a href='?penagihan_biaya&angkatan=$angkatan&id_prodi=$id_prodi&id_biaya=$id_biaya' class='btn btn-info btn-sm'>Clear</a>
  </div>
</form>
";

$s = "SELECT a.*,
(
  SELECT tanggal_penagihan FROM tb_penagihan WHERE id_mhs=a.id AND id_biaya=$id_biaya  
) as tanggal_penagihan,  
(
  SELECT tanggal FROM tb_bayar WHERE id_mhs=a.id AND id_biaya=$id_biaya  
) as tanggal_bayar,  
(
  SELECT verif_status FROM tb_bayar WHERE id_mhs=a.id AND id_biaya=$id_biaya  
) as status_bayar   
FROM tb_mhs a 
WHERE status_mhs = 1  
AND (a.nama LIKE '%$keyword%' 
OR a.kelas_manual LIKE '%$keyword%' 
OR a.nim LIKE '%$keyword%')  
AND angkatan = $angkatan 
AND id_prodi = $id_prodi 
ORDER BY a.nama 
LIMIT 200 ";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
$jumlah_aktif = mysqli_num_rows($q);
$tr = '';
$jumlah_target = 0;
if(mysqli_num_rows($q)>0){
  while ($d=mysqli_fetch_assoc($q)) {
    $checkbox = "<input type=checkbox name=cek_tagih__$d[id] checked>";
    $kelas = $d['kelas_manual']=='' ? $null : $d['kelas_manual'];
    $semester = $d['semester_manual']=='' ? $null : $d['semester_manual'];
    $cek = $d['tanggal_penagihan']=='' ? $checkbox : "Tertagih pada $d[tanggal_penagihan]";

    $d['status_bayar'] = 1; /// test debug

    if($d['tanggal_bayar']!=''){
      $cek = "<div>Tanggal bayar: $d[tanggal_bayar]</div>";
      if($d['status_bayar']==1){
        $cek .= '<span class="biru tebal">Lunas Terverifikasi.</span>';
      }elseif($d['status_bayar']==-1){
        $cek .= '<span class=red>Bukti Bayar ditolak.</span>';
      }elseif($d['status_bayar']==''){
        $cek .= '<span class=darkred>Unverified.</span>';
      }
    }

    if($checkbox==$cek) $jumlah_target++;

    $tr .= "
      <tr class=kecil id=tr__$d[nim]>
        <td>
          $d[nama] - 
          $d[nim] <a href='?login_as&nim=$d[nim]' target=_blank onclick='return confirm(\"Yakin mau mencoba Login As mahasiswa ini?\")'>$img_login_as</a>
        </td><td> 
          $kelas - 
          SM$semester 
        </td>
        <td>
          $cek
        </td>
      </tr>
    ";
  }
}
$tb_aktif = $tr=='' ? div_alert('info','Mahasiswa Aktif tidak ditemukan.') 
: "<table class='table table-striped'>$tr</table>";

$limit_info2 = $jumlah_aktif>200 ? "| <code>Limit 200</code> | Silahkan Filter!" : '';






$ditemukan = $jumlah_aktif>0 ? "<span class=biru style='font-size:30px'>$jumlah_aktif</span> mhs aktif ditemukan." : '<span class=red>data tidak ditemukan.</span>';
$btn_tagihkan = $jumlah_target>0 ? '<button class="btn btn-primary btn-block" name=btn_tagihkan>Tagihkan</button>' : '';

echo "
  <div class='wadah'>
    <div class='small mb1'>$ditemukan $limit_info2</div>
    $form_filter2
    <form method=post>
      <input class=debug name=angkatan value=$angkatan>
      <input class=debug name=id_prodi value=$id_prodi>
      <input class=debug name=id_biaya value=$id_biaya>
      $tb_aktif
      $btn_tagihkan
    </form>
  </div>
";



?>

<script>
  $(function(){
    $('.btn_aksi').click(function(){
      
    })
  })
</script>