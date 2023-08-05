<h1>Assign Ruang Mengajar Dosen</h1>
<p>Assign Ruang khusus untuk Dosen. Assign Ruang untuk Mhs dapat dilakukan saat Manage Sesi.</p>
<?php 
$id_jadwal = $_GET['id_jadwal'] ?? die(erid('id_jadwal'));

include 'include/akademik_icons.php';
$unset = '<span class="red consolas miring">unset</span>';


# ==============================================================
# GET KURIKULUM DATA
# ==============================================================
$s = "SELECT 
a.id as id_kurikulum, 
b.id as id_prodi, 
b.singkatan as prodi, 
c.id as id_kalender, 
c.angkatan,
c.jenjang,
d.jumlah_semester,
f.shift,
f.awal_kuliah,
g.nama as nama_mk,
g.kode as kode_mk,
h.nama as nama_dosen,
h.nidn,
(g.bobot_teori+g.bobot_praktik) bobot 


FROM tb_kurikulum a 
JOIN tb_prodi b ON b.id=a.id_prodi 
JOIN tb_kalender c ON c.id=a.id_kalender  
JOIN tb_jenjang d ON d.jenjang=c.jenjang  
JOIN tb_kurikulum_mk e ON e.id_kurikulum=a.id   
JOIN tb_jadwal f ON f.id_kurikulum_mk=e.id   
JOIN tb_mk g ON e.id_mk=g.id   
JOIN tb_dosen h ON f.id_dosen=h.id   
WHERE f.id='$id_jadwal' 
";
$q = mysqli_query($cn, $s)or die(mysqli_error($cn));
if(!mysqli_num_rows($q)) die('Data kurikulum tidak ditemukan.');
$d = mysqli_fetch_assoc($q);
$jumlah_semester = $d['jumlah_semester'];
$id_kalender = $d['id_kalender'];
$id_kurikulum = $d['id_kurikulum'];
$shift = $d['shift'];
$id_prodi = $d['id_prodi'];
$prodi = $d['prodi'];
$jenjang = $d['jenjang'];
$bobot = $d['bobot'];

$awal_kuliah = $d['awal_kuliah'];
$akhir_kuliah = date('Y-m-d H:i',strtotime($awal_kuliah)+$d['bobot']*45*60);
$jam_kuliah = date('D, d-M-Y H:i',strtotime($d['awal_kuliah'])).' - '.date('H:i',strtotime($akhir_kuliah));

echo "
<div class='wadah bg-white'>
  <a href='?manage_ruang_mengajar_dosen&id_kurikulum=$id_kurikulum&shift=$shift' id=shift class=proper>Kembali</a> | 
  Kurikulum $d[jenjang]-$d[prodi]-$d[angkatan] ~ Kelas $shift
  <ul class=mt2>
    <li><b>MK</b>: $d[nama_mk] | $d[kode_mk] | $d[bobot] SKS</li>
    <li><b>Dosen</b>: $d[nama_dosen] | NIDN. $d[nidn]</li>
    <li><b>Jam Kuliah</b>: $jam_kuliah</li>
  </ul>
</div>
";


# ==============================================================
# DATA RUANG
# ==============================================================
$id_ruang = $_POST['id_ruang'] ?? '';
if($id_ruang==''){
  // tampil all-ruang
  $s = "SELECT a.id as id_ruang, a.* FROM tb_ruang a WHERE a.kondisi=1 AND a.kapasitas>0";
  $q = mysqli_query($cn, $s)or die(mysqli_error($cn));

  if(mysqli_num_rows($q)==0){
    die(div_alert('danger',"Belum ada Data Ruangan | <a href='?master&p=ruang&aksi=tambah' target=_blank>Tambah Ruang</a>"));
  }

  $divs = '';
  while ($d=mysqli_fetch_assoc($q)) {
    $divs.="<div><button class='btn btn-info' value=$d[id_ruang] name=id_ruang onclick='return confirm(\"Yakin untuk mengecek ketersediaan ruangan ini?\")'>$d[nama]</button></div>";
  }
  $flex_div = "
  <div class=wadah>
    <div class='blue mb2'>Silahkan Pilih Ruangan untuk mengecek ketersediaan ruang:</div>
    <form method=post>
      <div class='flexy' style='gap:5px'>
        $divs
      </div>
    </form>
  </div>
  ";
  echo $flex_div;

}else{
  // sudah klik ruang
  $disabled_assign='disabled';
  if($id_ruang==1){
    // exclusive zoom
    $disabled_assign='';
    $div_res = "
      <h4 class=green>Anda memilih Ruang Zoom.</h4>
      <div class='abu miring kecil'>Ruang zoom dianggap mempunyai kapasitas unlimited dan pararel.</div>
    ";
  }else{
    $s = "SELECT a.id as id_ruang, a.* FROM tb_ruang a WHERE a.id=$id_ruang";
    $q = mysqli_query($cn, $s)or die(mysqli_error($cn));
    if(mysqli_num_rows($q)==0) die('Data ruang not found.');
    $d=mysqli_fetch_assoc($q);


    $px='';
    for ($i=1; $i <= 16; $i++) { 

      $time = strtotime($awal_kuliah)+(($i-1)*7*24*60*60);
      $awal_kuliah_loop = date('Y-m-d H:i',$time);
      $akhir_kuliah_loop = date('Y-m-d H:i',$time+$bobot*45*60);

      $s2 = "SELECT * FROM tb_pemakaian_ruang 
      WHERE (awal >= '$awal_kuliah_loop' AND akhir < '$awal_kuliah_loop')
      OR  (awal >= '$akhir_kuliah_loop' AND akhir < '$akhir_kuliah_loop')
      ";
      $q2 = mysqli_query($cn,$s2) or die(mysqli_error($cn));
      if(mysqli_num_rows($q2)==0){
        $available = "<span class='green italic miring'>available</span>";
      }else{
        $d2=mysqli_fetch_assoc($q2);
        // zzz here
      }



      $tg = date('D, d-M-Y',$time);
      $tgw = date('H:i',$time);
      $tgk = date('H:i',strtotime($tgw)+$bobot*45*60);
      $px.="<div>P$i | $tg | $tgw - $tgk | ... $available</div>";
    }

    $div_res = "
      <h4 class=darkblue>Hasil Cek Ketersediaan untuk Ruang $d[nama]</h4>
      <div class='small consolas'>$px</div>
    ";
  }

  echo "
    <div class=wadah>
      <div class=mb2>$div_res</div>
      <form method=post>
        <button class='btn btn-primary btn-block' name=btn_assign value=$id_ruang $disabled_assign>Assign Dosen pada Ruangan ini</button>
      </form>
      <a href='?assign_ruang_mengajar_dosen&id_jadwal=$id_jadwal'>Pilih Ruang lainnya</a>
    </div>
  ";
}

?>

<!-- <style>.ruang_aktif{border:solid 5px yellow;}</style> -->

<script>
  $(function(){
    $('.ruang').click(function(){
      $('.ruang').removeClass('ruang_aktif');
      $(this).addClass('ruang_aktif');
    })
  })
</script>