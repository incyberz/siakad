<h1>Manage Syarat Biaya</h1>
<p>Berikut adalah ketentuan-ketentuan untuk syarat pembayaran bagi mahasiswa.</p>
<?php
$event = $_GET['event'] ?? '';
if($event==''){
  $revent = ['KRS','UTS','UAS','TA','SIDANG','WISUDA'];
  $pilih_events = '';
  foreach ($revent as $event) {
    $pilih_events.="<a class='btn btn-info' href='?manage_syarat_biaya&event=$event'>$event</a> ";
  }

  echo "<div class=wadah><div class=mb2>Untuk event:</div>$pilih_events</div>";


  exit;
}

$angkatan = isset($_GET['angkatan']) ? $_GET['angkatan'] : '';
if($angkatan==''){
  $s = "SELECT angkatan FROM tb_angkatan";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  $link='';
  while ($d=mysqli_fetch_assoc($q)) {
    $link .= "<a class='btn btn-info btn-sm' href='?manage_syarat_biaya&event=$event&angkatan=$d[angkatan]'>$d[angkatan]</a> ";
  }
  echo "<h4>Seting Biaya $event untuk Angkatan:</h4><div class=wadah>$link</div>";
  exit;
}

$id_prodi = isset($_GET['id_prodi']) ? $_GET['id_prodi'] : '';
if($id_prodi==''){
  echo "<div>Seting Biaya $event untuk angkatan $angkatan prodi:</div>";
  $s = "SELECT id,nama,jenjang FROM tb_prodi";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  while ($d=mysqli_fetch_assoc($q)) {
    $d['nama'] = strtoupper($d['nama']);
    $primary = $d['jenjang']=='S1' ? 'primary' : 'success';
    echo "<div><a class='btn btn-$primary mb2 mt2 btn-blocks' href='?manage_syarat_biaya&event=$event&angkatan=$angkatan&id_prodi=$d[id]'>$d[jenjang]-$d[nama]</a></div> ";
  }
  exit;
}

$s = "SELECT a.nama,a.singkatan,a.jenjang,b.jumlah_semester FROM tb_prodi a JOIN tb_jenjang b on a.jenjang=b.jenjang where a.id=$id_prodi";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
if(mysqli_num_rows($q)==0) die('Data prodi tidak ditemukan.');
$d = mysqli_fetch_assoc($q);
$nama_prodi = "$d[jenjang]-$d[nama]";
$jenjang = "$d[jenjang]";
$jumlah_semester = "$d[jumlah_semester]";
$prodi = "$d[singkatan]";

echo "<span class=debug>id_prodi: <span id=id_prodi>$id_prodi</span> | angkatan: <span id=angkatan>$angkatan</span> | untuk: <span id=untuk>$event</span></span>";

$s = "SELECT a.id as id_kurikulum, 
b.id as id_kalender  
FROM tb_kurikulum a 
JOIN tb_kalender b ON a.id_kalender=b.id 
WHERE a.id_prodi=$id_prodi 
AND b.angkatan=$angkatan 
AND b.jenjang='$jenjang'
";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
if(mysqli_num_rows($q)==0) die('Data Kurikulum belum ada.');
if(mysqli_num_rows($q)>1) die('Duplikat Data Kurikulum terdeteksi. Silahkan hubungi Petugas!');
$d=mysqli_fetch_assoc($q);
$id_kurikulum = $d['id_kurikulum'];
$id_kalender = $d['id_kalender'];

if($event=='KRS'){
  $s = "SELECT a.*, a.id as id_biaya, 
  (SELECT nominal FROM tb_biaya_angkatan WHERE id_biaya=a.id AND angkatan=$angkatan AND id_prodi=$id_prodi) nominal, 
  (SELECT persen_biaya FROM tb_syarat_biaya WHERE id_biaya=a.id AND angkatan=$angkatan AND id_prodi=$id_prodi AND event='$event') persen_biaya 
  FROM tb_biaya a 
  WHERE a.untuk_semester is not null 
  order by a.untuk_semester,a.no";
  // echo $s;
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  $tr='';
  $last_smt = '';
  while ($d=mysqli_fetch_assoc($q)) {
    if($d['untuk_semester']>$jumlah_semester) continue;

    $border = $last_smt!=$d['untuk_semester'] ? 'style="border-top:solid 6px #fcf"' : '';
    $nominal = $d['nominal']=='' ? number_format($d['nominal_default']) : number_format($d['nominal']);
    $nominal_info = $d['nominal']=='' ? ' ~ <span class="darkred kecil miring pointer text_zoom">(default)</span>' : ' ~ <span class="darkblue kecil miring pointer text_zoom">(custom angkatan)</span>';

    $persen_biaya = $d['persen_biaya'] ?? 100;

    $tr.="
    <tr $border>
      <td>$d[untuk_semester]</td>
      <td>$d[nama] <a href='?manage_biaya_angkatan&angkatan=$angkatan&id_prodi=$id_prodi' onclick='return confirm(\"Ingin menuju Manage Biaya Angkatan?\")' target=_blank>$nominal_info</a><span class=debug> id:$d[id_biaya]</debug></td>
      <td>$nominal</td>
      <td>
        <span class='consolas biru'>
          <input class='debug' value='$persen_biaya' style='width:50px' id=persen_biaya2__$d[id_biaya]> 
          <input class='form-control tengah tebal biru persen_biaya' style='width:50px;display:inline' maxlength=3 value='$persen_biaya' id=persen_biaya__$d[id_biaya]> % 
        </span>
        <button class='btn btn-success btn-sm btn_apply hideit' id=btn_apply__$d[id_biaya]>Apply</button> 
      </td>
    </tr>
    ";
    $last_smt = $d['untuk_semester'];
  }
  echo "
  <table class=table>
    <thead>
      <th>Semester</th>
      <th>Syarat Biaya <a href='?manage_syarat_biaya'>$event</a> <a href='?manage_syarat_biaya&event=KRS&angkatan=$angkatan'>$jenjang-$prodi</a>-<a href='?manage_syarat_biaya&event=KRS'>$angkatan</a></th>
      <th>Nominal</th>
      <th class=darkblue>Agar dapat $event,<br>harus bayar minimal</th>
    </thead>
    $tr
  </table>
  <a href='?test_krs&id_kurikulum=$id_kurikulum' target=_blank class='btn btn-info btn-sm'>Test KRS</a>";
} // end if KRS
// show test krs zzz here

?>
<style>th{text-align:left}.text_zoom{transition:.2s}.text_zoom:hover{letter-spacing:1px;font-weight:bold}.bg-green{background:#cfc}</style>
<script>
  $(function(){
    $('.persen_biaya').keyup(function(){
      let val = parseInt($(this).val());
      let tid = $(this).prop('id');
      let rid = tid.split('__');
      let id = rid[1];
      let val2 = parseInt($('#persen_biaya2__'+id).val());

      console.log(val,val2);

      if(val==val2 || isNaN(val) || val>100){
        $('#btn_apply__'+id).hide();
      }else{
        $('#btn_apply__'+id).fadeIn();
      }

    })
    $('.btn_apply').click(function(){
      let tid = $(this).prop('id');
      let rid = tid.split('__');
      let id = rid[1];
      let persen_biaya = $('#persen_biaya__'+id).val();
      let angkatan = $('#angkatan').text();
      let id_prodi = $('#id_prodi').text();
      let event = $('#untuk').text();
      
      let link_ajax = `ajax_akademik/ajax_syarat_biaya.php?id_biaya=${id}&angkatan=${angkatan}&id_prodi=${id_prodi}&persen_biaya=${persen_biaya}&event=${event}`;
      console.log(link_ajax);

      $.ajax({
        url:link_ajax,
        success:function(a){
          if(a.trim()=='sukses'){
            $('#btn_apply__'+id).fadeOut();
            $('#persen_biaya__'+id).addClass('bg-green');
          }
        }
      })
      
    })
  })
</script>