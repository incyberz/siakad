<?php
$id_semester = isset($_GET['id_semester']) ? $_GET['id_semester'] : '';
$link_home = $id_semester=='' ? '' : "<a href='?manage_semester'><i class='icon_house_alt'></i></a>";
echo "<h1>$link_home MANAGE KURIKULUM SEMESTER</h1>
";

// if(isset($_POST['btn_set_dosen'])){
//   $s = "SELECT 1 FROM tb_jadwal WHERE id_semester=$_POST[id_semester]";
//   $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
//   if(mysqli_num_rows($q)==0){
//     $s = "INSERT INTO tb_jadwal (id_semester,id_dosen,keterangan) VALUES ($_POST[id_semester],$_POST[id_dosen],'$_POST[keterangan]')";
//     $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
//     die("<div class='alert alert-success'>Set Dosen Koordinator berhasil.<hr><a class='btn btn-primary' href='?manage_jadwal&id_semester=$_POST[id_semester]'>Lanjutkan Manage Jadwal</a></div>");
//   }else{
//     // sudah ada jadwal
//     // cek jika dosen nya sama
//     $s = "UPDATE tb_jadwal set id_dosen=$_POST[id_dosen] where id_semester=$_POST[id_semester]";
//     $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
//     die("<div class='alert alert-success'>Update Dosen Koordinator berhasil.<hr><a class='btn btn-primary' href='?manage_jadwal&id_semester=$_POST[id_semester]'>Lanjutkan Manage Jadwal</a></div>");

//   }
// }


if($id_semester==''){
  include 'modul/kurikulum/list_kurikulum_semester.php';
  exit;
}else{
  # ==========================================================
  # IDENTITAS SEMESTER
  # ==========================================================
  $s = "SELECT 
  concat('Kurikulum ',b.jenjang,' Angkatan ',b.angkatan,' Prodi ', d.nama) as kurikulum,
  a.nomor as semester_ke, 
  a.tanggal_awal as batas_awal, 
  a.tanggal_akhir as batas_akhir, 
  a.tanggal_akhir as batas_akhir  
  FROM tb_semester a 
  JOIN tb_kalender b on a.id_kalender=b.id 
  JOIN tb_kurikulum c on a.id_kalender=c.id 
  JOIN tb_prodi d on c.id_prodi=d.id 
  where a.id=$id_semester";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  if(mysqli_num_rows($q)==0) die('<span class=red>Data SEMESTER tidak ditemukan.');
  $d = mysqli_fetch_assoc($q);
  $batas_awal = $d['batas_awal'];
  $batas_akhir = $d['batas_akhir'];
  $koloms_smt = [];
  $i=0;
  $tr_smt = '';
  foreach ($d as $key => $value) {
    if($key=='nama_dosen') continue;
    $koloms_smt[$i] = str_replace('_',' ',$key);
    $debug = substr($key,0,2)=='id' ? 'debug' : 'upper';
    // echo substr($key,0,2)."<hr>";
    $tr_smt .= "<tr class=$debug><td>$koloms_smt[$i]</td><td>$value</td></tr>";
    $i++;
  }

  # ==========================================================
  # OUTPUT BLOK SEMESTER
  # ==========================================================
  $blok_smt = "<table class=table>$tr_smt</table>";



  # ==========================================================
  # MANAGE SEMESTER
  # ==========================================================
  $s = "SELECT 
  a.krs_awal, 
  a.krs_akhir, 
  a.bayar_awal, 
  a.bayar_akhir, 
  a.sesi_awal, 
  a.sesi_akhir 
  FROM tb_semester a  
  where a.id=$id_semester";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  if(mysqli_num_rows($q)==0) die('<span class=red>Data SEMESTER tidak ditemukan.');
  $d = mysqli_fetch_assoc($q);
  $koloms_smt = [];
  $i=0;
  $tr_smt = '';
  foreach ($d as $key => $value) {
    if($key=='nama_dosen') continue;
    $koloms_smt[$i] = str_replace('_',' ',$key);
    $debug = substr($key,0,2)=='id' ? 'debug' : 'upper';
    // echo substr($key,0,2)."<hr>";
    $tr_smt .= "<tr class=$debug><td>$koloms_smt[$i]</td><td>
      <input class=form-control type=date name=$key id=$key value='$value' required>
    </td></tr>";
    $i++;
  }

  # ==========================================================
  # OUTPUT BLOK MANAGE SEMESTER
  # ==========================================================
  $blok_tgl = "<table class=table>$tr_smt</table>";

}

$w = date('w',strtotime($batas_awal));
$add_days = $w<=1 ? (1-$w) : (8-$w);

$tanggal_senin_pertama = date('Y-m-d',strtotime("+$add_days day",strtotime($batas_awal)));
$batas_awal_show = date('D, d M Y',strtotime($batas_awal));
// die("
// batas_awal: $batas_awal<br>
// w: $w<br>
// add_days: $add_days<br>
// tanggal_senin_pertama: $tanggal_senin_pertama<br>
// batas_awal_show: $batas_awal_show<br>
// ")




?>
<div class="wadah">
  <h3 class='m0 mb2'>Identitas Semester</h3>
  <?=$blok_smt ?>
</div>
<div class="wadah">
  <h3 class='m0 mb2'>Seting Pembayaran dan KRS</h3>
  <div class="form-group">
    <div>
      <label for="radio_senin_pertama">
        <input type="radio" id="radio_senin_pertama" name="radio_senin_pertama" checked> 
        <small>Awal Pembayaran mengacu ke Senin Pertama</small>
      </label>      
    </div>
    <div>
      <label for="radio_senin_pertama2">
        <input type="radio" id="radio_senin_pertama2" name="radio_senin_pertama"> 
        <small>Awal Pembayaran mengacu ke Batas Awal Semester</small>
      </label>      
    </div>

    <div class="wadah">
      <label for="senin_pertama_show">Senin Pertama <br><small><i>Hari Senin Pertama pada Batas Semester yaitu tanggal: </i></small></label>
      <input class="form-control mb2" type="date" name="senin_pertama_show" id="senin_pertama_show" value=<?=$tanggal_senin_pertama?> disabled>
      <input class=debuga id="senin_pertama" name="senin_pertama" value=<?=$tanggal_senin_pertama?>>
    </div>
  </div>
  <div class="form-group">
    <label for="durasi_pembayaran">Durasi Pembayaran <small><i>(hari)</i></small></label>
    <select name="durasi_pembayaran" id="durasi_pembayaran" class="form-control">
      <?php for ($i=1; $i <= 21 ; $i++) { 
        $selected = $i==14 ? 'selected' : '';
        echo "<option $selected>$i</option>";
      } ?>
    </select>
  </div>
  <div class="form-group">
    <div>
      <label for="jeda_krs">
        <input type="radio" id="jeda_krs" name="jeda_krs" checked> 
        Tanggal Awal KRS adalah sesudah Jatuh Tempo Pembayaran
      </label>      
    </div>
    <div>
      <label for="jeda_krs2">
        <input type="radio" id="jeda_krs2" name="jeda_krs"> 
        Tanggal Awal KRS adalah sama dengan Tanggal Awal Pembayaran
      </label>      
    </div>
    <div class='flexy'>
      <div>
        <label for="jeda_krs3">
          <input type="radio" id="jeda_krs3" name="jeda_krs"> 
          Tanggal Awal KRS bergeser selama:
        </label>      
      </div>
      <div>
        <select name="durasi_pembayaran" id="durasi_pembayaran" class="form-control">
          <?php for ($i=-14; $i <= 7 ; $i++) { 
            $selected = $i==0 ? 'selected' : '';
            echo "<option $selected>$i</option>";
          } ?>
        </select>
      </div>
      <div>
        <label for="jeda_krs3">hari setelah Jatuh Tempo Pembayaran</label>
      </div>

    </div><!-- End of Flexy -->
  </div><!-- End of Form-Group -->
  <div class="form-group">
    <label for="durasi_krs">Durasi KRS <small><i>(hari)</i></small></label>
    <select name="durasi_krs" id="durasi_krs" class="form-control">
      <?php for ($i=1; $i <= 14 ; $i++) { 
        $selected = $i==5 ? 'selected' : '';
        echo "<option $selected>$i</option>";
      } ?>
    </select>
  </div>
  <div class="form-group">
    <button class="btn btn-primary btn-block">Apply Setting</button>
    <small><i>Setelah Apply Setting Anda dapat menyimpan Aturan Tanggal pada Semester</i></small>
  </div>

</div>
<div class="wadah gradasi-hijau">
  <form method=post>
    <input class=debug name='id_semester' value='<?=$id_semester?>'>
    <h3 class='m0 mb2'>Aturan Tanggal pada Semester</h3>
    <?=$blok_tgl ?>
    <button class='btn btn-primary btn-block'>Simpan Aturan Tanggal</button>
  </form>
</div>
<div class="wadah gradasi-hijau">
  <h3>Ilustrasi Tanggal</h3>
</div>
