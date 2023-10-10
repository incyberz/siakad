<h1>Sesi Mingguan</h1>
<style>.blok_filter{
  display: flex; gap:5px
}.show_records{
  font-size:small;
  border:solid 1px #ccc; 
  display:flex; 
  gap:15px; 
  background:white; 
  border-radius:5px; 
  padding:5px 10px; 
  margin-bottom:5px
}.bg_green{ background: #2a2; color:white
}.bg_red{ background: #fcc;
}.sesi-next{ background: #cfc;
}.sesi-now{ background: #afa; border: solid 3px blue;
}.sesi-prev{ background: #ffa;
}
</style>
<?php
include 'include/akademik_icons.php';
# =============================================================
# OPTIONAL GET VARIABLE
# =============================================================
$keyword = $_POST['keyword'] ?? '';
$angkatan = $_POST['angkatan_filter'] ?? '';
$range_date = $_POST['range_date_filter'] ?? '';
$id_prodi = $_POST['id_prodi_filter'] ?? '';
$shift = $_POST['shift_filter'] ?? '';

$bg_green_keyword = $keyword=='' ? '' : 'bg_green';
$bg_green_range_date = ($range_date==1 || $range_date=='') ? '' : 'bg_green';
$bg_green_angkatan = ($angkatan=='all'||$angkatan=='') ? '' : 'bg_green';
$bg_green_id_prodi = ($id_prodi=='all'||$id_prodi=='') ? '' : 'bg_green';
$bg_green_shift = ($shift=='all'||$shift=='') ? '' : 'bg_green';

for ($i=1; $i <= 9; $i++) { 
  $arr_range_date[$i] = $range_date==$i ? 'selected' : '';
}

$selected_shift['pagi'] = $shift=='pagi' ? 'selected' : '';
$selected_shift['sore'] = $shift=='sore' ? 'selected' : '';

# =============================================================
# INCLUDES
# =============================================================
include '../include/include_rid_prodi.php';
include '../include/include_rangkatan.php';

# =============================================================
# GLOBAL VARIABEL
# =============================================================
$null = '<span class="red miring kecil">null</span>';



# =============================================================
# DATE MANAGEMENTS
# =============================================================
$hari_ini = date('Y-m-d');
$w = date('w',strtotime($hari_ini));
$ahad_skg = date('Y-m-d',strtotime("-$w day",strtotime($hari_ini)));
$besok = date('Y-m-d H:i',strtotime('+1 day', strtotime('today')));
$lusa = date('Y-m-d H:i',strtotime('+2 day', strtotime('today')));

$senin_skg = date('Y-m-d',strtotime("+1 day",strtotime($ahad_skg)));
$selasa_skg = date('Y-m-d',strtotime("+2 day",strtotime($ahad_skg)));
$rabu_skg = date('Y-m-d',strtotime("+3 day",strtotime($ahad_skg)));
$kamis_skg = date('Y-m-d',strtotime("+4 day",strtotime($ahad_skg)));
$jumat_skg = date('Y-m-d',strtotime("+5 day",strtotime($ahad_skg)));
$sabtu_skg = date('Y-m-d',strtotime("+6 day",strtotime($ahad_skg)));
$ahad_depan = date('Y-m-d',strtotime("+7 day",strtotime($ahad_skg)));

$senin_skg_show = 'Senin, '.date('d M Y',strtotime($senin_skg));
$sabtu_skg_show = 'Sabtu, '.date('d M Y',strtotime($sabtu_skg));

$hari_ini_show = $nama_hari[date('w',strtotime('today'))].', '.date('d M Y H:i',strtotime('now'));
echo "<div class=flexy><div class='mb2'>Hari ini : $hari_ini_show</div><div>|</div><div>Minggu ini :  $senin_skg_show s.d $sabtu_skg_show</div></div>";



# =============================================================
# BLOK FILTER
# =============================================================
?>
<form method=post>

  <div class="blok_filter mb2">
    <div>
      <input class="<?=$bg_green_keyword?> form-control input-sm " placeholder='MK atau Nama Dosen' id=keyword name=keyword style='width:100px' value='<?=$keyword?>'>
      <span id=keyword_tmp class=debug><?=$keyword?></span>
    </div>
  
    <div>
      <select class="<?=$bg_green_range_date?> form-control input-sm filter filter_select" id="range_date_filter" name="range_date_filter">
        <option value='1' <?=$arr_range_date[1]?>>Minggu ini</option>
        <option value='2' <?=$arr_range_date[2]?>>Hari ini</option>
        <option value='3' <?=$arr_range_date[3]?>>Besok</option>
        <option value='4' <?=$arr_range_date[4]?>>Hari Senin</option>
        <option value='5' <?=$arr_range_date[5]?>>Hari Selasa</option>
        <option value='6' <?=$arr_range_date[6]?>>Hari Rabu</option>
        <option value='7' <?=$arr_range_date[7]?>>Hari Kamis</option>
        <option value='8' <?=$arr_range_date[8]?>>Hari Jumat</option>
        <option value='9' <?=$arr_range_date[9]?>>Hari Sabtu</option>
      </select>
    </div>
  
    <div>
      <select class="<?=$bg_green_angkatan?> form-control input-sm filter filter_select filter_green" id="angkatan_filter" name="angkatan_filter">
        <option value=all>All Angkatan</option>
        <?php 
        for ($i=0; $i < count($rangkatan) ; $i++) { 
          $selected = $rangkatan[$i] == $angkatan ? 'selected' : '';
          echo "<option value='$rangkatan[$i]' $selected>$rangkatan[$i]</option>";
        }
        ?>
      </select>
    </div>
  
    <div>
      <select class="<?=$bg_green_id_prodi?> form-control input-sm filter filter_select filter_green" id="id_prodi_filter" name="id_prodi_filter">
        <option value=all>All Prodi</option>
        <?php 
        for ($i=0; $i < count($rprodi) ; $i++) { 
          $prodi = $rprodi[$rid_prodi[$i]];
          $selected = $rid_prodi[$i] == $id_prodi ? 'selected' : '';
          echo "<option value='$rid_prodi[$i]' $selected>$prodi</option>";
        }
        ?>
      </select>
    </div>
    
    <div>
      <select class="<?=$bg_green_shift?> form-control input-sm filter filter_select filter_green" id="shift_filter" name='shift_filter'>
        <option value=all>All Shift</option>
        <option value='pagi' <?=$selected_shift['pagi']?>>Kelas Pagi</option>
        <option value='sore' <?=$selected_shift['sore']?>>Kelas Sore</option>
      </select>
    </div>
  
  
    <div>
      <button class="btn btn-primary btn-sm" name=btn_filter>Filter</button>
      <a href='?sesi_mingguan' class="btn btn-info btn-sm" >Clear</a>
    </div>
  
  </div>
</form>






<?php
$sql_date = "a.tanggal_sesi >= '$ahad_skg' AND a.tanggal_sesi < '$ahad_depan' ";
if($range_date==2) $sql_date = "a.tanggal_sesi >= '$hari_ini' AND a.tanggal_sesi < '$besok' "; //hari ini
if($range_date==3) $sql_date = "a.tanggal_sesi >= '$besok' AND a.tanggal_sesi < '$lusa' "; //besok
if($range_date==4) $sql_date = "a.tanggal_sesi >= '$senin_skg' AND a.tanggal_sesi < '$selasa_skg' "; //hari senin
if($range_date==5) $sql_date = "a.tanggal_sesi >= '$selasa_skg' AND a.tanggal_sesi < '$rabu_skg' "; //hari selasa
if($range_date==6) $sql_date = "a.tanggal_sesi >= '$rabu_skg' AND a.tanggal_sesi < '$kamis_skg' "; //hari rabu
if($range_date==7) $sql_date = "a.tanggal_sesi >= '$kamis_skg' AND a.tanggal_sesi < '$jumat_skg' "; //hari kamis
if($range_date==8) $sql_date = "a.tanggal_sesi >= '$jumat_skg' AND a.tanggal_sesi < '$sabtu_skg' "; //hari jumat
if($range_date==9) $sql_date = "a.tanggal_sesi >= '$sabtu_skg' AND a.tanggal_sesi < '$ahad_depan' "; //hari sabtu

$sql_keyword = trim($keyword)=='' ? '1' : "(b.nama like '%$keyword%' or e.nama like '%$keyword%')";

$sql_angkatan = ($angkatan=='all'||$angkatan=='') ? '1' : "i.angkatan='$angkatan'";
$sql_id_prodi = ($id_prodi=='all'||$id_prodi=='') ? '1' : "h.id='$id_prodi'";
$sql_shift = ($shift=='all'||$shift=='') ? '1' : "c.shift='$shift'";

$s = "SELECT a.id,
a.tanggal_sesi,
a.pertemuan_ke,
a.nama as nama_sesi,
b.id as id_dosen,
b.nama as nama_dosen,
c.id as id_jadwal,
c.awal_kuliah,
c.akhir_kuliah,
c.shift,
h.singkatan as prodi,
(e.bobot_teori+e.bobot_praktik) as bobot,
e.nama as nama_mk,
f.nomor as semester,
g.id as id_kurikulum,
i.angkatan,
(SELECT count(1) FROM tb_assign_ruang WHERE id_sesi=a.id) jumlah_assign_ruang

FROM tb_sesi a 
JOIN tb_dosen b ON a.id_dosen=b.id 
JOIN tb_jadwal c ON a.id_jadwal=c.id 
JOIN tb_kurikulum_mk d ON c.id_kurikulum_mk=d.id 
JOIN tb_mk e ON d.id_mk=e.id 
JOIN tb_semester f ON d.id_semester=f.id 
JOIN tb_kurikulum g ON d.id_kurikulum=g.id 
JOIN tb_prodi h ON g.id_prodi=h.id 
JOIN tb_kalender i ON g.id_kalender=i.id 
WHERE $sql_date 
AND $sql_keyword 
AND $sql_angkatan 
AND $sql_id_prodi 
AND $sql_shift 

ORDER BY a.tanggal_sesi
";
// echo '<pre>';
// echo $s;
// echo '</pre>';
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));

$tr = '';
if(mysqli_num_rows($q)){
  $i=0;
  while ($d=mysqli_fetch_assoc($q)) {
    $i++;

    $hari_show = $nama_hari[date('w',strtotime($d['tanggal_sesi']))];
    $tanggal_show = date('d M Y',strtotime($d['tanggal_sesi']));
    $pukul_show = date('H:i',strtotime($d['tanggal_sesi']));

    $durasi = ($d['awal_kuliah']=='' || $d['akhir_kuliah']=='') ? $d['bobot']*45*60 : (strtotime($d['akhir_kuliah'])-strtotime($d['awal_kuliah']))/60;

    $jam_akhir = date('H:i', strtotime($d['awal_kuliah'])+$durasi*60);
    $pukul_show.= " s.d $jam_akhir ($durasi menit)";

    if($d['jumlah_assign_ruang']){
      $ruang_show = "<a href='?manage_sesi_detail&id_jadwal=$d[id_jadwal]' target=_blank onclick='return confirm(\"Menuju Reset Ruangan di TAB baru?\")'>$d[jumlah_assign_ruang] ruangan</a>";

      $s2 = "SELECT a.*,
      b.nama as mode_sesi,
      c.nama as nama_ruang  
      FROM tb_assign_ruang a 
      JOIN tb_mode_sesi b ON a.id_tipe_sesi=b.id 
      JOIN tb_ruang c ON a.id_ruang=c.id 
      WHERE a.id_sesi='$d[id]'";
      $q2 = mysqli_query($cn,$s2) or die(mysqli_error($cn));
      while ($d2=mysqli_fetch_assoc($q2)) {
        $ruang_show.= " | $d2[nama_ruang]";
      }

    }else{
      $ruang_show = "$unset | <a href='?manage_sesi_detail&id_jadwal=$d[id_jadwal]' target=_blank>Set</a>";
    }

    $eta = strtotime($d['tanggal_sesi']) - strtotime('now');

    if($eta<0){
      $sesi_sty = 'sesi-prev';
    }else{
      if($eta > $durasi*60){
        $sesi_sty = 'sesi-next';
      }else{
        $sesi_sty = 'sesi-now';
      }
    }

    $tr.= "
    <tr class='$sesi_sty'>
      <td>$i $eta $durasi</td>
      <td>
        $d[nama_mk]
        <br>P$d[pertemuan_ke] | $d[nama_sesi]
        <br>Pengajar: 
        <a href='?lihat_dosen&id_dosen=$d[id_dosen]'  target=_blank onclick='return confirm(\"Lihat Profil Dosen di TAB baru?\")'>$d[nama_dosen]</a> 
        <a href='?login_as_dosen&id_dosen=$d[id_dosen]'  target=_blank onclick='return confirm(\"Login as Dosen ini di TAB baru?\")'>$img_aksi[login_as]</a> 
        
        <br>$d[bobot] SKS
      </td>
      <td>
        Kurikulum: <a href='?manage_kurikulum&id_kurikulum=$d[id_kurikulum]' target=_blank onclick='return confirm(\"Manage Kurikulum ini di TAB baru?\")'>$d[prodi]-$d[angkatan]</a>
        <br>Semester: $d[semester]
        <br>Shift: Kelas $d[shift]
      </td>
      <td>
        <div>Hari: $hari_show</div>
        <div>Tanggal: $tanggal_show</div>
        <div>Pukul: $pukul_show</div>
        <div>Ruang: $ruang_show</div>
      </td>
    </tr>";
  }
}

echo $tr=='' ? div_alert('danger', 'Data sesi perkuliahan tidak ada. Silahkan coba filter kembali!') : "<table class=table>$tr</table>";

