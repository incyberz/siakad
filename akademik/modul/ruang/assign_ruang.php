<style>.debug{background:yellow}</style>
<?php
if(isset($_POST['btn_assign'])){
  // echo '<pre>';
  // var_dump($_POST);
  // echo '</pre>';
  // exit;

  $values = '__';
  $jumlah_assigned_ruang=0;
  if($_POST['terapkan_pada']=='p_ini'){
    foreach ($_POST as $key => $value) {
      if(strpos("a$key",'cb__')){
        $rkey = explode('__',$key);
        $values .= ",($rkey[1],$_POST[id_sesi],$_POST[id_tipe_sesi])";
        $jumlah_assigned_ruang++;
      }
    }
  }else{
    // delete dahulu semua seting assign room
    $s = "SELECT a.id FROM tb_sesi a WHERE a.id_jadwal=$_POST[id_jadwal]";
    $q = mysqli_query($cn,$s) or die(mysqli_error($cn));

    $s_del = "DELETE FROM tb_assign_ruang WHERE 0 ";
    while ($d=mysqli_fetch_assoc($q)) {
      $s_del .= " OR id_sesi=$d[id] ";
    }

    $q = mysqli_query($cn,$s_del) or die(mysqli_error($cn));
    
    // terapkan pada semua pertemuan
    $id_jadwal = $_POST['id_jadwal'];
    $s = "SELECT id FROM tb_sesi WHERE id_jadwal=$id_jadwal";
    $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
    while ($d=mysqli_fetch_assoc($q)) {
      $id_sesi = $d['id'];
      foreach ($_POST as $key => $value) {
        if(strpos("a$key",'cb__')){
          $rkey = explode('__',$key);
          $values .= ",($rkey[1],$id_sesi,$_POST[id_tipe_sesi])";
          $jumlah_assigned_ruang++;
        }
      }

    }
    
    // die('Belum ada handler untuk aksi ini'.$values);
  }
  $values = str_replace('__,','',$values);

  $s = "INSERT INTO tb_assign_ruang 
  (id_ruang,id_sesi,id_tipe_sesi) VALUES 
  $values";
  // die($s);
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  echo "<div class='alert alert-success'>Assign sebanyak $jumlah_assigned_ruang Ruang Kelas Sukses.<hr><a class='btn btn-primary' href='?manage_sesi_detail&id_jadwal=$_POST[id_jadwal]'>Kembali ke Manage Sesi</a> | <a class='btn btn-info' href='?assign_ruang&id_sesi=$_POST[id_sesi]'>Lihat hasil</a></div>";
  exit;
}

if(isset($_POST['btn_drop'])){
  $s = "DELETE FROM tb_assign_ruang WHERE id_sesi=$_POST[id_sesi]";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  echo "<div class='alert alert-info'>Assign Ruang berhasil di drop. Silahkan pilih kembali ruangan!</div>";
}












$id_sesi = isset($_GET['id_sesi']) ? $_GET['id_sesi'] : die(div_alert('danger',"Tidak bisa diakses secara langsung<hr>$btn_back"));



$s = "SELECT * FROM tb_assign_ruang a 
where a.id=$id_sesi";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
$d=mysqli_fetch_assoc($q);


$s = "SELECT 
a.id as id_sesi,
a.id_jadwal,
a.pertemuan_ke,
a.nama as nama_sesi,
a.id_dosen, 
a.awal_sesi,
c.id_kurikulum_mk,
d.id_semester,
d.id_kurikulum,
e.nomor as no_semester,
b.nama as pengajar,
g.id as id_mk,
g.nama as nama_mk,
g.kode as kode_mk,
(g.bobot_teori + g.bobot_praktik) as bobot,
(SELECT count(1) FROM tb_assign_ruang WHERE id_sesi=a.id) as jumlah_ruang 

FROM tb_sesi a 
JOIN tb_dosen b on b.id=a.id_dosen 
JOIN tb_jadwal c on c.id=a.id_jadwal 
JOIN tb_kurikulum_mk d on d.id=c.id_kurikulum_mk 
JOIN tb_semester e on e.id=d.id_semester 
JOIN tb_kurikulum f on f.id=d.id_kurikulum 
JOIN tb_mk g on g.id=d.id_mk 
where a.id=$id_sesi";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
$d=mysqli_fetch_assoc($q);

$sesi = "P$d[pertemuan_ke]  | $d[nama_sesi] | $d[nama_mk] | $d[kode_mk]";

$awal_sesi = $d['awal_sesi'];
$tsesi = strtotime($awal_sesi);
$jam_keluar = date('H:i',$tsesi + $d['bobot']*45*60);
$stop_sesi = date('Y-m-d H:i',$tsesi + $d['bobot']*45*60);

$tanggal_sesi_show = '<h4 class="biru tebal">'.$nama_hari[date('w',$tsesi)].', '.date('d M Y / H:i',$tsesi).' s.d '.$jam_keluar.'</h4>';
$id_jadwal = $d['id_jadwal'];
$id_semester = $d['id_semester'];
$id_kurikulum = $d['id_kurikulum'];
$id_mk = $d['id_mk'];

$semester = "$d[no_semester]";


$kotaks = '';
$s = "SELECT b.*  
FROM tb_assign_ruang a 
JOIN tb_ruang b on a.id_ruang=b.id 
where id_sesi=$id_sesi";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
if(mysqli_num_rows($q)){
  while ($d2=mysqli_fetch_assoc($q)) {
    $kotaks.="
      <div class='kotak gradasi-hijau text-center'>
          $d2[nama]
      </div>
    ";
  }

  $tr_manage = "
    <tr>
      <td>Ruangan</td>
      <td>
        <div class=flexy>
          $kotaks
        </div>
      </td>
    </tr>

    <tr>
      <td>&nbsp;</td>
      <td>
        <button class='btn btn-danger btn-block' id=btn_drop name=btn_drop onclick=\"return confirm('Yakin untuk Drop Ruangan dan memilih lagi?')\">Drop Ruangan dan Re-Assign</button>
      </td>
    </tr>

  ";

}else{

  // belum ada assign ruang
  $kotaks='';
  $s2 = "SELECT a.* ,
  a.id as id_ruang,
  (
    SELECT q.id  
    FROM tb_assign_ruang p 
    JOIN tb_sesi q on q.id=p.id_sesi 
    WHERE (q.awal_sesi >= '$awal_sesi' and q.awal_sesi < '$stop_sesi')
    AND p.id_ruang=a.id  
    LIMIT 1) as terpakai_oleh   
  
  FROM tb_ruang a 
  WHERE kondisi=1 
  AND kapasitas>0
  ";
  echo '<pre>'; var_dump($s2); echo '</pre>'; 
  // exit;
  $q2 = mysqli_query($cn,$s2) or die(mysqli_error($cn));
  while ($d2=mysqli_fetch_assoc($q2)) {
    $id_ruang = $d2['id_ruang'];
    if($d2['terpakai_oleh']!=''){
      $s3 = "SELECT  
      a.id as id_sesi,
      a.nama as nama_sesi,
      a.awal_sesi,
      b.awal_kuliah,
      b.akhir_kuliah,
      d.nama as nama_mk,
      d.kode as kode_mk,
      e.nama as pengajar
      FROM tb_sesi a 
      JOIN tb_jadwal b ON a.id_jadwal=b.id 
      JOIN tb_kurikulum_mk c ON b.id_kurikulum_mk=c.id 
      JOIN tb_mk d ON c.id_mk=d.id 
      JOIN tb_dosen e ON a.id_dosen=e.id 
      WHERE a.id='$d2[terpakai_oleh]'
      ";

      // echo '<pre>';
      // var_dump($s3);
      // echo '</pre>';
      $q3 = mysqli_query($cn,$s3) or die(mysqli_error($cn));
      $d3 = mysqli_fetch_assoc($q3);

      $durasi_detik = strtotime($d3['akhir_kuliah']) - strtotime($d3['awal_kuliah']);
      $awal_sesi = $d3['awal_sesi'];
      $akhir_sesi = date('Y-m-d H:i',strtotime($awal_sesi)+$durasi_detik);

      $jam_awal_sesi = date('H:i',strtotime($awal_sesi));
      $jam_akhir_sesi = date('H:i',strtotime($akhir_sesi));


      $is_pengajar_sama = $d['pengajar']==$d3['pengajar'] ? 1 : 0;
      $is_mk_sama = $d['kode_mk']==$d3['kode_mk'] ? 1 : 0;

      $pengajar_sama_show = $is_pengajar_sama ? '<span class="blue bold">(sama)</span>' : '<span class=red>(beda)</span>';
      $mk_sama_show = $is_mk_sama ? '<span class="blue bold">(sama)</span>' : '<span class=red>(beda)</span>';

      if($is_pengajar_sama){
        // $join_ruang = "<a href='?join_ruang&id_ruang=$id_ruang&id_sesi=$id_sesi&join_with=$d3[id_sesi]' class='btn btn-primary btn-sm btn-block'>Join ke Ruangan ini</a>";
        $join_ruang = "<span class='kecil miring biru'>Bisa join ruang</span>";
      }else{
        $join_ruang = "<span class='kecil miring red'>Tidak bisa join ruang</span>";
      }

      $by_sesi = "
        <ul class='m0 p0 kecil' style='padding-left:15px; font-size: 10px'>
          <li>$d3[nama_mk] | $d3[kode_mk] $mk_sama_show</li>
          <li>$d3[pengajar] $pengajar_sama_show</li>
          <li><span class=red>$jam_awal_sesi - $jam_akhir_sesi</span></li>
        </ul>
        <div class=mt1>$join_ruang</div>
        ";
    }
    $terpakai_oleh = ($d2['terpakai_oleh']!='' and $d2['id']!=1) 
      ? "
      <div class='kecil red'>Telah dipakai oleh:</div>
      $by_sesi
      " 
      : '';
    $disabled = ($terpakai_oleh!='' && !$is_pengajar_sama)?'disabled':'';
    $gradasi = $terpakai_oleh==''?'hijau':'merah';
    $kotaks.="
      <div class='kotak gradasi-$gradasi' style='padding:0'>
        <label style='display:block;padding: 5px 8px 8px 8px; margin:0;cursor:pointer;'>
          <input type='checkbox' name='cb__$d2[id]' id='cb__$d2[id]' class=cb_ruang $disabled>
          $d2[nama] <span class=debug>$id_ruang</span> 
          $terpakai_oleh 
        </label>
      </div>
    ";
  }

  $tr_manage = "
    <tr>
      <td>Ruangan tersedia:</td>
      <td>
        <div class=flexy>
          $kotaks
        </div>
        <div class='wadah' style='margin-top:15px'>
          Tipe Sesi: <span class='tebal biru' id=tipe_sesi>-- Silahkan Ceklis Ruangan --</span> 
          <input class=debug id=id_tipe_sesi name=id_tipe_sesi >
        </div>
      </td>
    </tr>

    <tr>
      <td>Terapkan pada</td>
      <td>
        <div>
          <label>
            <input type=radio name=terapkan_pada value=p_ini > 
            Pertemuan ini saja
          </label>
        </div>
        <div>
          <label style='color:darkred'>
            <input type=radio name=terapkan_pada value=p_all checked> 
            Semua Pertemuan
            <div class='kecil miring'>Perhatian! Aturan ini akan me-replace aturan assign-ruang pada semua pertemuan</div>
          </label>
        </div>
      </td>
    </tr>

    <tr>
      <td>&nbsp;</td>
      <td>
        <button class='btn btn-primary btn-block' id=btn_assign name=btn_assign disabled>Assign</button>
      </td>
    </tr>

  ";

}




?>

<style>
  .kotak{
    border: solid 1px #ccc;
    border-radius: 5px;
    width: 200px;
    padding: 5px 15px;
  }
</style>

<h1>Assign Ruangan pada Sesi Kuliah</h1>
<form method=post>
  <table class='table table-hover table-dark'>
    <tr>
      <td>Sesi</td>
      <td>
        <?=$sesi?> | <?=$d['bobot'] ?> SKS | SMT-<?=$semester?>
        <div class="debug">
          id_sesi:<input name=id_sesi value=<?=$id_sesi?>>
          id_jadwal:<input name=id_jadwal value=<?=$id_jadwal?>>
        </div>
      </td>
    </tr>

    <tr>
      <td>Pengajar</td>
      <td><?=$d['pengajar'] ?></td>
    </tr>

    <tr>
      <td>Tanggal Sesi</td>
      <td><?=$tanggal_sesi_show ?></td>
    </tr>

    <?=$tr_manage?>

  </table>
</form>




<script>
  $(function(){
    
    $('.cb_ruang').click(function(){
      let r = document.getElementsByClassName('cb_ruang');
      let jumlah_cek = 0;
      let is_zoom = false;
      for (let i = 0; i < r.length; i++) {
        // console.log(r[i].id);
        if(r[i].checked==true) jumlah_cek++;
        if(r[i].id == 'cb__1' && r[i].checked==true) is_zoom = true;
      }
      let disabled_btn_assign = jumlah_cek>0?false:true;
      $('#btn_assign').prop('disabled',disabled_btn_assign);
      // console.log($(this).prop('checked'));

      let tipe_sesi = '-- Silahkan Ceklis Ruangan --';
      let id_tipe_sesi = 0;
      if(jumlah_cek>0){
        let id = $(this).prop('id');
        if(is_zoom && jumlah_cek==1){
          tipe_sesi = 'Teleconference';
          id_tipe_sesi = 1;
        }else if(is_zoom && jumlah_cek>1){
          tipe_sesi = 'Hybrid-Zoom';
          id_tipe_sesi = 2;
        }else if(jumlah_cek>1){
          tipe_sesi = 'Hybrid-Offline';
          id_tipe_sesi = 3;
        }else{
          tipe_sesi = 'Offline';
          id_tipe_sesi = 4;
        }

      }
      $('#tipe_sesi').text(tipe_sesi);
      $('#id_tipe_sesi').val(id_tipe_sesi);
        
    })
    

  })
</script>