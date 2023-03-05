<h1>MANAGE JADWAL KULIAH</h1>
<?php
if(isset($_POST['btn_set_dosenko'])){
  die(var_dump($_POST));

}

$id_kurikulum_mk = isset($_GET['id_kurikulum_mk']) ? $_GET['id_kurikulum_mk'] : '';

if($id_kurikulum_mk==''){
  $blok_mk = "
  <div class='alert alert-info'>
    <span class=red>ID-MK pada Kurikulum belum terdefinisi.</span>
    <hr> 
    <div>Tahapan Seting Jadwal</div>
    <ol>
      <li>Pilih <a href='?master&p=kurikulum' class='btn btn-primary btn-sm'>MASTER KURIKULUM</a></li>
      <li>Klik salah satu tombol MANAGE</li>
      <li>Klik salah satu tombol JADWAL</li>
    </ol>
     
  </div>
  ";
  $blok_dosen = "<div class='alert alert-info'>Belum siap.</div>";
  $blok_kelas = "<div class='alert alert-info'>Belum siap.</div>";
  $btn_assign_pengajar = '';
}else{
  # ==========================================================
  # SELECT BLOK MK
  # ==========================================================
  $s = "SELECT 
  c.nama as nama_kurikulum,
  c.basis,
  b.nomor as semester_ke,
  d.kode as kode_mk,
  d.nama as nama_mk,
  c.id as id_kurikulum,
  a.id as id_semester_mk,
  a.id_semester,
  a.id_mk,
  (SELECT id from tb_jadwal where id_kurikulum_mk=a.id) as id_jadwal,  
  (SELECT id_dosen from tb_jadwal where id_kurikulum_mk=a.id) as dosenko  

  FROM tb_kurikulum_mk a 
  JOIN tb_semester b on a.id_semester=b.id 
  JOIN tb_kurikulum c on b.id_kurikulum=c.id 
  JOIN tb_mk d on a.id_mk=d.id 
  WHERE a.id=$id_kurikulum_mk 
  ";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  if(mysqli_num_rows($q)==0) die('<span class=red>Data Kurikulum MK tidak ditemukan.');
  $dkmk = mysqli_fetch_assoc($q);
  $koloms_mk = [];
  $i=0;
  $tr_mk = '';
  foreach ($dkmk as $key => $value) {
    $koloms_mk[$i] = str_replace('_',' ',$key);
    $debug = substr($key,0,2)=='id' ? 'debug' : 'upper';
    // echo substr($key,0,2)."<hr>";
    $tr_mk .= "<tr class=$debug><td>$koloms_mk[$i]</td><td>$value</td></tr>";
    $i++;
  }

  # ==========================================================
  # OUTPUT BLOK MK
  # ==========================================================
  $pilih_mk_lain = "<div class='btn-link kecil'>Opsi : <a href='?kurikulum&id=$dkmk[id_kurikulum]'>Pilih MK lain</a></div>";
  $blok_mk = "<table class=table>$tr_mk</table>$pilih_mk_lain";





  # ==========================================================
  # SELECT BLOK DOSEN
  # ==========================================================
  $s = "SELECT 
  a.id as id_dosen,
  a.nama as nama_dosen
  FROM tb_dosen a 
  ORDER BY a.nama
  ";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  $option_dosen = '';
  while ($d=mysqli_fetch_assoc($q)) {
    $selected = 0 ? 'selected' : '';
    $option_dosen .= "<option class='pilihan pilihan-dosen' value=$d[id_dosen] $selected>$d[nama_dosen] ~ $d[id_dosen]</option>";
  }

  
  # ==========================================================
  # OUTPUT BLOK PENGAJAR
  # ==========================================================
  $link_opsi = "<div class='btn-link kecil ml2'>Opsi : <a class='proper' href='?master&p=dosen'>manage dosen</a></div>";
  
  $blok_dosen_pengajar = $option_dosen=='' 
  ? "<div class='alert alert-danger'>Belum ada DATA DOSEN yang aktif (bersedia mengajar).<hr>$link_opsi</div>" 
  : "<select class='form-control' id=id_dosen_pengajar name=id_dosen_pengajar>
    $option_dosen
  </select>
  $link_opsi
  ";


  $dkmk['dosenko'] = 'Juned'; //zzz debug
  $p = $dkmk['dosenko']=='' ? "
    <p>Silahkan pilih dosen (KOORDINATOR) untuk mata kuliah ini!</p>" : "
    <div class='wadah bg-white'>Dosen Koordinator : <span class=proper id='dosenko'>$dkmk[dosenko]</span></div>
    <p>Silahkan pilih kembali untuk mengubahnya.</p>
  ";

  $primary_set_dosen = $dkmk['dosenko']=='' ? 'primary' : 'warning';
  $btn_set_sesi = $dkmk['dosenko']=='' ? '' : "
    <div class=btn-link><a href='?manage_sesi&id_jadwal=$dkmk[id_jadwal]' class='btn btn-primary btn-block'>Set Jadwal Sesi Kuliah</a></div>
  ";
  $btn_set_dosenko = "<div class='btn-link'><button class='btn btn-$primary_set_dosen btn-block' name='btn_set_dosenko'>Set Dosen Koordinator</button></div>";

  $blok_dosenko = $option_dosen=='' 
    ? "<div class='alert alert-danger'>Belum ada DATA DOSEN yang aktif (bersedia mengajar).<hr>$link_opsi</div>" 
    : "
    $p
    <select class='form-control' id=id_dosenko name=id_dosenko>
      $option_dosen
    </select>
    $link_opsi
    $btn_set_dosenko
    $btn_set_sesi
  ";



  # ==========================================================
  # SELECT BLOK kelas
  # ==========================================================
  $s = "SELECT kelas FROM tb_kelas";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  $option_kelas = '';
  while ($d=mysqli_fetch_assoc($q)) {
    $option_kelas .= "<option class='pilihan pilihan-kelas'>$d[kelas]</option>";
  }

  
  # ==========================================================
  # OUTPUT BLOK KELAS
  # ==========================================================
  $link_opsi = "<div class='btn-link kecil ml2'>Opsi : <a class='proper' href='?master&p=kelas'>manage kelas</a></div>";
  $blok_kelas = $option_kelas=='' 
  ? "<div class='alert alert-danger'>Belum ada DATA KELAS.<hr>$link_opsi</div>" 
  : "<select class='form-control' id=kelas>$option_kelas</select>$link_opsi";

  if($option_dosen!='' and $option_kelas !=''){
    $btn_assign_pengajar = "
    <div class='btn-link'>
      <button class='btn btn-primary btn-block'>Assign Dosen Pengajar dan Kelas</button>
    </div>
    ";
  }
}





?>
<style>.btn-link{margin-top:10px;} .ml2{margin-left: 10px}</style>
<div class="wadah gradasi-hijau">
  <form method=post>
    <h3>Mata Kuliah</h3>
    <?=$blok_mk ?>
    <hr>
    <h3>Dosen Koordinator</h3>
    <?=$blok_dosenko ?>
  </form>
</div>

<div class="wadah">
  <form method=post>
    <input class=debug name=id_kurikulum_mk value=<?=$id_kurikulum_mk?>>
    <div class='row'>
      <div class='col-lg-6'>
        <div class="wadah gradasi-hijau">
          <h3>Dosen Pengajar</h3>
          <p>Silahkan pilih dosen (PENGAJAR) yang akan mengajar di tiap kelas!</p>
          <?=$blok_dosen_pengajar ?>
        </div>
      </div>

      <div class='col-lg-6'>
        <div class="wadah gradasi-hijau">
          <h3>Peserta Kelas</h3>
          <p>Silahkan pilih kelas mana saja yang akan menjadi peserta kelas!</p>
          <?=$blok_kelas ?>
        </div>
      </div>
    </div>
    <?=$btn_assign_pengajar ?>
  </form>
</div>