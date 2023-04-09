<?php
if(isset($_POST['btn_assign'])){
  echo '<pre>';
  var_dump($_POST);
  echo '</pre>';
  // exit;

  $values = '__';
  $jumlah_assigned_ruang=0;
  if($_POST['terapkan_pada']=='p_ini'){
    foreach ($_POST as $key => $value) {
      if(strpos("a$key",'cb__')){
        $rkey = explode('__',$key);
        $values .= ",($rkey[1],$_POST[id_sesi_kuliah],$_POST[id_tipe_sesi])";
        $jumlah_assigned_ruang++;
      }
    }
  }else{
    // delete dahulu semua seting assign room
    $s = "DELETE FROM tb_assign_ruang WHERE id_sesi_kuliah=$_POST[id_sesi_kuliah]";
    $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
    
    // terapkan pada semua pertemuan
    $id_jadwal = $_POST['id_jadwal'];
    $s = "SELECT id FROM tb_sesi_kuliah WHERE id_jadwal=$id_jadwal";
    $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
    while ($d=mysqli_fetch_assoc($q)) {
      $id_sesi_kuliah = $d['id'];
      foreach ($_POST as $key => $value) {
        if(strpos("a$key",'cb__')){
          $rkey = explode('__',$key);
          $values .= ",($rkey[1],$id_sesi_kuliah,$_POST[id_tipe_sesi])";
          $jumlah_assigned_ruang++;
        }
      }

    }
    
    // die('Belum ada handler untuk aksi ini'.$values);
  }
  $values = str_replace('__,','',$values);

  $s = "INSERT INTO tb_assign_ruang 
  (id_ruang,id_sesi_kuliah,id_tipe_sesi) VALUES 
  $values";
  // die($s);
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  echo "<div class='alert alert-success'>Assign sebanyak $jumlah_assigned_ruang Ruang Kelas Sukses.<hr><a class='btn btn-primary' href='?manage_sesi&id_jadwal=$_POST[id_jadwal]'>Kembali ke Manage Sesi</a> | <a class='btn btn-info' href='?assign_ruang&id_sesi_kuliah=$_POST[id_sesi_kuliah]'>Lihat hasil</a></div>";
  exit;
}

if(isset($_POST['btn_drop'])){
  $s = "DELETE FROM tb_assign_ruang WHERE id_sesi_kuliah=$_POST[id_sesi_kuliah]";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  echo "<div class='alert alert-info'>Assign Ruang berhasil di drop. Silahkan pilih kembali ruangan!</div>";
}












$id_sesi_kuliah = isset($_GET['id_sesi_kuliah']) ? $_GET['id_sesi_kuliah'] : die(div_alert('danger',"Tidak bisa diakses secara langsung<hr>$btn_back"));



$s = "SELECT * FROM tb_assign_ruang a 
where a.id=$id_sesi_kuliah";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
$d=mysqli_fetch_assoc($q);


$s = "SELECT 
a.id as id_sesi_kuliah,
a.id_jadwal,
a.pertemuan_ke,
a.nama as nama_sesi,
a.id_dosen, 
a.tanggal_sesi,
a.stop_sesi,
c.id_kurikulum_mk,
d.id_semester,
d.id_kurikulum,
e.nomor as no_semester,
b.nama as nama_dosen,
f.nama as nama_kurikulum,
g.id as id_mk,
g.nama as nama_mk,
(g.bobot_teori + g.bobot_praktik) as bobot,
(SELECT count(1) FROM tb_assign_ruang WHERE id_sesi_kuliah=a.id) as jumlah_ruang 

FROM tb_sesi_kuliah a 
JOIN tb_dosen b on b.id=a.id_dosen 
JOIN tb_jadwal c on c.id=a.id_jadwal 
JOIN tb_kurikulum_mk d on d.id=c.id_kurikulum_mk 
JOIN tb_semester e on e.id=d.id_semester 
JOIN tb_kurikulum f on f.id=d.id_kurikulum 
JOIN tb_mk g on g.id=d.id_mk 
where a.id=$id_sesi_kuliah";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
$d=mysqli_fetch_assoc($q);

$sesi = "$d[pertemuan_ke]  / $d[nama_sesi] / $d[nama_mk]";

$tanggal_sesi = $d['tanggal_sesi'];
$tsesi = strtotime($tanggal_sesi);
$stop_sesi = $d['stop_sesi'];
$jam_keluar = date('H:i',strtotime($stop_sesi));

$link_edit_tanggal_sesi = "<a href='?master&p=sesi_kuliah&aksi=update&id=$id_sesi_kuliah' target=_blank>Edit Manual</a>";
$tanggal_sesi_show = '<h4 class="biru tebal">'.$nama_hari[date('w',$tsesi)].', '.date('d M Y / H:i',$tsesi).' s.d '.$jam_keluar.'</h4>'.$link_edit_tanggal_sesi;
$id_jadwal = $d['id_jadwal'];
$id_semester = $d['id_semester'];
$id_kurikulum = $d['id_kurikulum'];
$id_mk = $d['id_mk'];

$semester = "$d[no_semester] / $d[nama_kurikulum]";
$back_to = "Back to: <a href='?manage_sesi&id_jadwal=$id_jadwal'>Manage Sesi</a>";


$kotaks = '';
$s = "SELECT b.*  
FROM tb_assign_ruang a 
JOIN tb_ruang b on a.id_ruang=b.id 
where id_sesi_kuliah=$id_sesi_kuliah";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
if(mysqli_num_rows($q)){
  while ($r=mysqli_fetch_assoc($q)) {
    $kotaks.="
      <div class='kotak gradasi-hijau text-center'>
          $r[nama]
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
  $s = "SELECT a.*,
  (
    SELECT concat(b.nama,' MK ',e.nama,'|',b.tanggal_sesi,'|',e.bobot_teori,'|',e.bobot_praktik) FROM tb_assign_ruang t 
    JOIN tb_sesi_kuliah b on b.id=t.id_sesi_kuliah 
    JOIN tb_jadwal c on c.id=b.id_jadwal 
    JOIN tb_kurikulum_mk d on d.id=c.id_kurikulum_mk 
    JOIN tb_mk e on e.id=d.id_mk 
    WHERE (b.tanggal_sesi >= '$tanggal_sesi' and b.tanggal_sesi < '$stop_sesi' 
    OR b.stop_sesi > '$tanggal_sesi' and b.stop_sesi <= '$stop_sesi')
    AND t.id_ruang=a.id  
    LIMIT 1) as terpakai_oleh   
  
  FROM tb_ruang a";
  echo '<pre>'; var_dump($s); echo '</pre>'; 
  // exit;
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  while ($r=mysqli_fetch_assoc($q)) {
    if($r['terpakai_oleh']!=''){
      $z = explode('|',$r['terpakai_oleh']);
      $by_sesi = $z[0]; // sesi + MK
      $start_sesi = $z[1]; // Y-m-d H:i:s
      $start_sesi_show = date('d M',strtotime($start_sesi)) ;
      $bobot = $z[2] + $z[3];
      $stop_sesi = date('Y-m-d H:i',strtotime($start_sesi)+$bobot*$menit_sks*60);
      $durasi = date('H:i',strtotime($start_sesi)).' s.d '.date('H:i',strtotime($stop_sesi));
    }
    $terpakai_oleh = ($r['terpakai_oleh']!='' and $r['id']!=1) ? "<br><span class='red kecil miring'>Sesi $by_sesi | $start_sesi_show |  $durasi</span>" : '';
    $disabled = $terpakai_oleh==''?'':'disabled';
    $gradasi = $terpakai_oleh==''?'hijau':'merah';
    $kotaks.="
            <div class='kotak gradasi-$gradasi' style='padding:0'>
              <label style='display:block;padding:5px 5px 5px 15px; margin:0;cursor:pointer;'>
                <input type='checkbox' name='cb__$r[id]' id='cb__$r[id]' class=cb_ruang $disabled>
                $r[nama] $terpakai_oleh 
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

<?=$back_to?>
<h1>Assign Ruangan pada Sesi Kuliah</h1>
<form method=post>
  <table class='table table-hover table-dark'>
    <tr>
      <td>Sesi</td>
      <td>
        <?=$sesi?>
        <div class="debug">
          id_sesi_kuliah:<input name=id_sesi_kuliah value=<?=$id_sesi_kuliah?>>
          id_jadwal:<input name=id_jadwal value=<?=$id_jadwal?>>
        </div>
      </td>
    </tr>

    <tr>
      <td>Semester</td>
      <td><?=$semester?></td>
    </tr>

    <tr>
      <td>Pengajar</td>
      <td><?=$d['nama_dosen'] ?> / <?=$d['bobot'] ?> SKS</td>
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