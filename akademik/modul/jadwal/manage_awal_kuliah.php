<h1>Manage Awal Perkuliahan</h1>
<p>Manage Awal Perkuliah hanya bisa dilakukan setelah adanya Penjadwalan Dosen dan Penanggalan Semester.</p>
<?php 
include 'include/akademik_icons.php';

if(isset($_GET['debug'])){
  if($_GET['debug']==0) echo "<style>.debug{display:none}</style>";
}
$id_kurikulum = $_GET['id_kurikulum'] ?? '';
if($id_kurikulum==''){

  $s = "SELECT 1 FROM tb_kurikulum a 
  JOIN tb_kurikulum_mk b ON b.id_kurikulum=a.id 
  JOIN tb_jadwal c ON c.id_kurikulum_mk=b.id ";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  $allsetting = mysqli_num_rows($q);
  
  $s = "SELECT 1 FROM tb_kurikulum a 
  JOIN tb_kurikulum_mk b ON b.id_kurikulum=a.id 
  JOIN tb_jadwal c ON c.id_kurikulum_mk=b.id 
  WHERE c.awal_kuliah is null 
  ";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  $unsetting = mysqli_num_rows($q);
  
  $settinged = $allsetting-$unsetting;
  $persen_setting = $allsetting==0 ? 0 : round(($settinged/$allsetting)*100,2);

  $green_color = intval($persen_setting/100*155);
  $red_color = intval((100-$persen_setting)/100*255);
  $rgb = "rgb($red_color,$green_color,50)";
  echo "
  <div class='kecil miring consolas' style='color:$rgb'>Progres Manage Awal Kuliah : $persen_setting% | $settinged of $allsetting Jadwal</div>
  <div class=progress>
    <div class='progress-bar progress-bar-danger' style='width:$persen_setting%;background:$rgb;'></div>
  </div>
  ";

  $s = "INSERT INTO tb_unsetting (kolom,unsetting,total) VALUES ('awal_kuliah',$unsetting,$allsetting) ON DUPLICATE KEY UPDATE unsetting=$unsetting,total=$allsetting";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));






  $s = "SELECT 
  a.id as id_kurikulum,
  b.angkatan,
  b.jenjang,
  c.singkatan,
  (SELECT count(1) FROM tb_kurikulum p 
  JOIN tb_kurikulum_mk q ON q.id_kurikulum=p.id 
  JOIN tb_jadwal r ON r.id_kurikulum_mk=q.id 
  WHERE r.awal_kuliah is null AND p.id=a.id AND r.shift='pagi') unsetting_pagi, 
  (SELECT count(1) FROM tb_kurikulum p 
  JOIN tb_kurikulum_mk q ON q.id_kurikulum=p.id 
  JOIN tb_jadwal r ON r.id_kurikulum_mk=q.id 
  WHERE r.awal_kuliah is null AND p.id=a.id AND r.shift='sore') unsetting_sore  

  FROM tb_kurikulum a 
  JOIN tb_kalender b ON a.id_kalender=b.id
  JOIN tb_prodi c ON a.id_prodi=c.id
  WHERE 1 
  ORDER BY b.angkatan DESC, c.id
  ";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  if(mysqli_num_rows($q)==0) die(div_alert('danger', "Belum ada Kurikulum. Silahkan <a href='?manage_kurikulum'>Manage Kurikulum</a> !"));
  $tr='';
  $i=0;
  $last_angkatan = '';
  while ($d=mysqli_fetch_assoc($q)) {
    $i++;
    $border = $last_angkatan==$d['angkatan'] ? '' : 'style="border-top: solid 6px #faf"';
    $gradasi = $d['jenjang']=='D3' ? 'green gradasi-hijau' : 'darkblue gradasi-biru';
    $primary = $d['jenjang']=='D3' ? 'success' : 'primary';
    $merah = ($d['unsetting_pagi']||$d['unsetting_sore']) ? 'red' : '';
    $unsetting_pagi_show = $d['unsetting_pagi'] ? "<span class='red bold'>$d[unsetting_pagi] unsetting awal kuliah pagi</span>" : '-';
    $unsetting_sore_show = $d['unsetting_sore'] ? "<span class='red bold'>$d[unsetting_sore] unsetting awal kuliah sore</span>" : '-';
    $tr .= "
    <tr class='$gradasi $merah' $border>
      <td>$i</td>
      <td>$d[jenjang]-$d[singkatan]-$d[angkatan]</td>
      <td>
        <div class=mb1>$unsetting_pagi_show</div>
        <div class=mb1>$unsetting_sore_show</div>
      </td>
      <td>
        <div class=mb1>
        <a class='btn btn-$primary btn-sm' href='?manage_awal_kuliah&id_kurikulum=$d[id_kurikulum]&shift=pagi' target=_blank>Manage Awal Kuliah Pagi</a></div>
        <a class='btn btn-$primary btn-sm' href='?manage_awal_kuliah&id_kurikulum=$d[id_kurikulum]&shift=sore' target=_blank>Manage Awal Kuliah Sore</a>
      </td>
    </tr>
    ";
    $last_angkatan=$d['angkatan'];
  }

  echo "
  <p class=biru>Silahkan pilih link manage dari salah satu Kurikulum!</p>
  <table class='table'>
    <thead>
      <th>No</th>
      <th>Kurikulum</th>
      <th>Unsetting Awal Kuliah</th>
      <th>Aksi</th>
    </thead>
    $tr
  </table>";
  exit;
}

$shift = $_GET['shift'] ?? '';
if($shift==''){
  $s = "SELECT * FROM tb_shift";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  while ($d=mysqli_fetch_assoc($q)) {
    echo "<a href='?manage_awal_kuliah&id_kurikulum=$id_kurikulum&shift=$d[shift]' class='btn btn-info proper mr2'>kelas $d[shift]</a> ";
  }
  exit;
}


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
d.jumlah_semester  

FROM tb_kurikulum a 
JOIN tb_prodi b ON b.id=a.id_prodi 
JOIN tb_kalender c ON c.id=a.id_kalender  
JOIN tb_jenjang d ON d.jenjang=c.jenjang  
WHERE a.id='$id_kurikulum' 
";
$q = mysqli_query($cn, $s)or die(mysqli_error($cn));
if(!mysqli_num_rows($q)) die('Data kurikulum tidak ditemukan.');
$d = mysqli_fetch_assoc($q);
$jumlah_semester = $d['jumlah_semester'];
$id_kalender = $d['id_kalender'];
$id_prodi = $d['id_prodi'];
$prodi = $d['prodi'];
$jenjang = $d['jenjang'];

$tb_kurikulum = "
<div class='wadah bg-white'>Kurikulum <a href='?manage_awal_kuliah'>$d[jenjang]-$d[prodi]-$d[angkatan]</a> ~ Kelas <a href='?manage_awal_kuliah&id_kurikulum=$id_kurikulum' id=shift class=proper>$shift</a></div>
";


# ==============================================================
# OPTION JAM BELAJAR
# ==============================================================
$rjam['pagi'] = [7,8,9,10,11,12,13,14,15];
$rjam['sore'] = [16,17,18,19,20,21];

# ==============================================================
# TAMPIL SEMESTERS
# ==============================================================
$s = "SELECT 
a.id as id_semester,
a.nomor as no_semester,
a.tanggal_awal, 
a.tanggal_akhir  
FROM tb_semester a 
JOIN tb_kalender b ON b.id=a.id_kalender 
JOIN tb_kurikulum c ON c.id_kalender=b.id  

WHERE c.id='$id_kurikulum' 
ORDER BY a.nomor 
";
$q = mysqli_query($cn, $s)or die(mysqli_error($cn));

$semesters = '';
$i=0;
while ($d=mysqli_fetch_assoc($q)) {
  $i++; 

  # ==============================================================
  # LIST MATA KULIAH
  # ==============================================================
  $s2 = "SELECT 
  a.id as id_mk,
  a.kode as kode_mk,
  a.nama as nama_mk,
  (a.bobot_teori + a.bobot_praktik) bobot,
  b.id as id_kurikulum_mk,
  c.id as id_semester, 
  c.awal_kuliah_uts, 
  (
    SELECT nama FROM tb_dosen p 
    JOIN tb_jadwal q ON q.id_dosen=p.id 
    JOIN tb_kurikulum_mk r ON q.id_kurikulum_mk=r.id 
    WHERE r.id=b.id AND q.shift='$shift') as nama_dosen,  
  (
    SELECT id FROM tb_jadwal WHERE id_kurikulum_mk=b.id AND shift='$shift') as id_jadwal, 
  (
    SELECT awal_kuliah FROM tb_jadwal WHERE id_kurikulum_mk=b.id AND shift='$shift') as awal_kuliah, 
  (
    SELECT akhir_kuliah FROM tb_jadwal WHERE id_kurikulum_mk=b.id AND shift='$shift') as akhir_kuliah 


  FROM tb_mk a 
  JOIN tb_kurikulum_mk b ON a.id=b.id_mk 
  JOIN tb_semester c ON b.id_semester=c.id  
  JOIN tb_kurikulum d ON b.id_kurikulum=d.id  
  WHERE c.id='$d[id_semester]' 
  AND d.id_prodi=$id_prodi
  ";
  $q2 = mysqli_query($cn, $s2)or die(mysqli_error($cn));
  // $jumlah_mk = mysqli_num_rows($q2);
  // echo "<span class=debug>jumlah_mk__$d[id_semester]: <span id='jumlah_mk__$d[id_semester]'>$jumlah_mk</span></span> ";

  $awal_kuliah_uts = $unset;
  $tr = '';
  $j=0;
  // list MK looping
  while ($d2=mysqli_fetch_assoc($q2)) { 
    $j++;
    $id_jadwal = $d2['id_jadwal'];
    $awal_kuliah_uts = $d2['awal_kuliah_uts']=='' ? "$unset | <a href='?manage_semester&id_semester=$d2[id_semester]' target=_blank>Manage Penanggalan Semester</a>" : "<a href='?manage_semester&id_semester=$d2[id_semester]' target=_blank onclick='return confirm(\"Ingin mengubah Awal Perkuliahan Semester ini?\")'>".date('D, d-M-Y',strtotime($d2['awal_kuliah_uts'])).'</a>';

    $jadwal_show = $d2['id_jadwal']=='' ? "$img_aksi[prev]" : $img_aksi['check'];
    $jadwal_show = "<a href='?manage_jadwal_dosen&id_kurikulum=$id_kurikulum&shift=$shift' target=_blank onclick='return confirm(\"Kembali ke Penjadwalan Dosen?\")'>$jadwal_show</a>";

    $nama_dosen = $d2['nama_dosen'] ?? $unset;
    $bobot = $d2['bobot'] ?? 0;

    // opt jam without selected
    $opt_jam = '';
    $opt_jam_akhir = '';
    foreach ($rjam[$shift] as $jam) {
      $jam_show = $jam<10 ? '0'.$jam : $jam;
      $opt_jam.= "<option value='$jam'>$jam_show</option>";
      $opt_jam_akhir.= "<option value='$jam'>$jam_show</option>";
    }
    // opt menit without selected
    $opt_menit = '';
    $opt_menit_akhir = '';
    for ($i=0; $i < 12; $i++) { 
      $menit = $i*5;
      $menit_show = $menit<10 ? '0'.$menit : $menit;
      $opt_menit.= "<option value='$menit'>$menit_show</option>";
      $opt_menit_akhir.= "<option value='$menit'>$menit_show</option>";
    }
    

    if($d2['awal_kuliah']==''){
      $awal_kuliah_show = $unset;
      // $disabled_set = '';
      $debug = '';
      $tanggal_awal_kuliah = date('Y-m-d',strtotime($d2['awal_kuliah_uts']));
      $select_jam = '';
      $select_menit = '';
      $lanjut_ke = '';
    }else{ // awal_kuliah telah diisi
      $awal_kuliah = date('H:i',strtotime($d2['awal_kuliah']));
      $akhir_kuliah = $d2['akhir_kuliah'] ?? date('Y-m-d H:i',strtotime($d2['awal_kuliah'])+($bobot*45*60));
      $akhir_kuliah_show = date('H:i',strtotime($akhir_kuliah));
      $awal_kuliah_show = '<span class=green>'.date('D, d-M-Y',strtotime($d2['awal_kuliah']))." Pukul $awal_kuliah - $akhir_kuliah_show $img_aksi[check]</span>";
      $lanjut_ke = "<div class='kecil'><a href='?manage_sesi_detail&id_jadwal=$id_jadwal' target=_blank>Manage Sesi Detail $img_aksi[next] </a></div>";

      $select_jam = date('H',strtotime($d2['awal_kuliah']));
      $select_menit = date('i',strtotime($d2['awal_kuliah']));
      $select_jam_akhir = date('H',strtotime($akhir_kuliah));
      $select_menit_akhir = date('i',strtotime($akhir_kuliah));
      
      
      $tanggal_awal_kuliah = date('Y-m-d',strtotime($d2['awal_kuliah']));
      $debug = "<div class=debug style=background:yellow>
        tanggal_awal_kuliah2:<span id=tanggal_awal_kuliah2__$id_jadwal>$tanggal_awal_kuliah</span> | 
        select_jam2:<span id=select_jam2__$id_jadwal>$select_jam</span> | 
        select_menit2:<span id=select_menit2__$id_jadwal>$select_menit</span> | 
        select_jam_akhir2:<span id=select_jam_akhir2__$id_jadwal>$select_jam_akhir</span> | 
        select_menit_akhir2:<span id=select_menit_akhir2__$id_jadwal>$select_menit_akhir</span> | 
      </div>";


      $opt_jam = '';
      $opt_jam_akhir = '';
      foreach ($rjam[$shift] as $jam) {
        $jam_show = $jam<10 ? '0'.$jam : $jam;

        $selected = $select_jam==$jam ? 'selected' : '';
        $opt_jam.= "<option value='$jam' $selected>$jam_show</option>";

        $selected = $select_jam_akhir==$jam ? 'selected' : '';
        $opt_jam_akhir.= "<option value='$jam' $selected>$jam_show</option>";
      }

      $opt_menit = '';
      for ($i=0; $i < 12; $i++) { 
        $menit = $i*5;
        $menit_show = $menit<10 ? '0'.$menit : $menit;

        $selected = $select_menit==$menit ? 'selected' : '';
        $opt_menit.= "<option value='$menit' $selected>$menit_show</option>";

        $selected = $select_menit_akhir==$menit ? 'selected' : '';
        $opt_menit_akhir.= "<option value='$menit' $selected>$menit_show</option>";
      }
    } //// end awal_kuliah berisi


    $btn_set = "<button class='btn btn-info btn-sm btn_aksi' id=set__$id_jadwal >Set</button>";

    $blok_awal_kuliah = ($d2['nama_dosen']=='' || $d2['awal_kuliah_uts']=='') ? '-' : "
      <div class='kecil miring mb1' id=awal_kuliah_show__$id_jadwal>Jam Kuliah : $awal_kuliah_show</div>
      <div class=flexy>
        <div><input type=date value='$tanggal_awal_kuliah' class='awal_kuliah_triger form-control' id=tanggal_awal_kuliah__$id_jadwal></div>
        <div>
          <select id=select_jam__$id_jadwal class='awal_kuliah_triger form-control'>
            $opt_jam
          </select>
        </div>
        <div>
          <select id=select_menit__$id_jadwal class='awal_kuliah_triger form-control'>
            $opt_menit
          </select>
        </div>
        <div>
          s.d
        </div>
        <div>
          <select id=select_jam_akhir__$id_jadwal class='awal_kuliah_triger form-control'>
            $opt_jam_akhir
          </select>
        </div>
        <div>
          <select id=select_menit_akhir__$id_jadwal class='awal_kuliah_triger form-control'>
            $opt_menit_akhir
          </select>
        </div>

        <div id=blok_btn_set__$id_jadwal class=hideit>$btn_set</div>
        <div id=ket_error_durasi__$id_jadwal class='red small miring'></div>
        $debug
      </div>
      $lanjut_ke
      <div id=hasil_ajax__$id_jadwal></div>
    ";

    $bobot_show = ($d2['bobot']>0 AND $d2['bobot']<=6) ? "<span id=bobot__$id_jadwal>$d2[bobot]</span> SKS" : '<span class=red>invalid bobot SKS</span>';

    $tr.="
    <tr id='tr__$d2[id_mk]'>
      <td width=5%>$j</td>
      <td>
        <div class='kecil miring'>
          $d2[nama_mk] | $d2[kode_mk] | $bobot_show 
          <span class=debug style=background:yellow>id_jadwal:$id_jadwal | </debug>
        </div>
        <div class=darkblue>$jadwal_show | Dosen: $nama_dosen</div>
      </td>
      <td width=60% class=''>$blok_awal_kuliah</td>
    </tr>    
    ";
  } //end while list MK



  $tr = $tr=='' ? "<tr><td class='red miring' colspan=9>Belum ada MK pada semester ini. | <a href='?manage_kurikulum&id_kurikulum=$id_kurikulum' target=_blank onclick='return confirm(\"Menuju Manage MK Kurikulum?\")'>Manage MK Kurikulum</a></td></tr>" : $tr;

  if(strtotime($d['tanggal_akhir']) < strtotime($today)){
    $waktu_smt = 'lampau';
    $color_smt = 'darkred';
    $wadah_class = 'wadah gradasi-kuning';
  }elseif(strtotime($d['tanggal_awal']) > strtotime($today)){
    $waktu_smt = 'depan';
    $color_smt = 'gray';
    $wadah_class = 'wadah';
  }else{
    $waktu_smt = 'aktif';
    $color_smt = 'biru tebal';
    $wadah_class = 'wadah_active';
  }

  $tanggal_awal_sty = strtotime($d['tanggal_awal']) < strtotime('2018-1-1') ? 'merah tebal' : '';
  $tanggal_akhir_sty = strtotime($d['tanggal_akhir']) < strtotime('2018-1-1') ? 'merah tebal' : '';
  $tanggal_awal_show = "<span class='$tanggal_awal_sty'>".date('d M Y', strtotime($d['tanggal_awal'])).'</span>';
  $tanggal_akhir_show = "<span class='$tanggal_awal_sty'>".date('d M Y', strtotime($d['tanggal_akhir'])).'</span>';


  $semesters .= "
  <div class='col-lg-12' id='semester__$d[id_semester]'>
    <div class=' $wadah_class '>
      <h4 class='$color_smt'>
        Semester $d[no_semester] <span class='proper kecil miring'>(Semester $waktu_smt)</span>
      </h4>
      <p class=consolas>$tanggal_awal_show s.d $tanggal_akhir_show</p>
      <table class='table tb-semester-mk'>
        <thead>
          <th>No</th>
          <th>Mata Kuliah</th>
          <th class=proper>
            Awal Perkuliahan $shift
            <div class='kecil darkblue consolas'>Mulai Kuliah: $awal_kuliah_uts</div>
          </th>
        </thead>
        
        $tr
        
      </table>
    </div>
  </div>
  ";

  if($i % 2 ==0) $semesters .= '</div><div class=row>';
} // end while semesters





$blok_semesters = $semesters=='' ? '<div class="alert alert-danger">Belum ada data semester</div>' : "<div class='row kurikulum'>$semesters</div>";




# ==============================================================
# FINAL OUTPUT SEMESTERS
# ==============================================================
echo "
$tb_kurikulum
$blok_semesters
";























?>
<script>
  $(function(){
    $('.awal_kuliah_triger').change(function(){
      let tid = $(this).prop('id');
      let rid = tid.split('__');
      let id_jadwal = rid[1];

      let jam_awal = parseInt($('#select_jam__'+id_jadwal).val());
      let menit_awal = parseInt($('#select_menit__'+id_jadwal).val());

      let jam_akhir = parseInt($('#select_jam_akhir__'+id_jadwal).val());
      let menit_akhir = parseInt($('#select_menit_akhir__'+id_jadwal).val());

      let durasi_kuliah = (jam_akhir*60+menit_akhir) - (jam_awal*60+menit_awal);

      if(durasi_kuliah>=60 && durasi_kuliah<181){
        $('#blok_btn_set__'+id_jadwal).fadeIn();
        $('#hasil_ajax__'+id_jadwal).html('');
        $('#ket_error_durasi__'+id_jadwal).text('');
      }else{
        $('#blok_btn_set__'+id_jadwal).hide();
        $('#ket_error_durasi__'+id_jadwal).text('Durasi kuliah minimal 60 menit dan maksimal 180 menit');
      }
    });

    $('.btn_aksi').click(function(){
      let tid = $(this).prop('id');
      let rid = tid.split('__');
      let aksi = rid[0];
      let id_jadwal = rid[1];

      let bobot = $('#bobot__'+id_jadwal).text();

      if(aksi=='set'){
        // validasi konflik jam untuk mhs zzz here
        let awal_kuliah = $('#tanggal_awal_kuliah__'+id_jadwal).val()
          + ' '
          + $('#select_jam__'+id_jadwal).val()
          + ':'
          + $('#select_menit__'+id_jadwal).val()
          ;
        
        let akhir_kuliah = $('#tanggal_awal_kuliah__'+id_jadwal).val()
          + ' '
          + $('#select_jam_akhir__'+id_jadwal).val()
          + ':'
          + $('#select_menit_akhir__'+id_jadwal).val()
          ;
        
        let link_ajax = `ajax_akademik/ajax_set_awal_kuliah.php?id_jadwal=${id_jadwal}&awal_kuliah=${awal_kuliah}&akhir_kuliah=${akhir_kuliah}&bobot=${bobot}&confirm=1`;
        // alert(link_ajax); 
        // return;

        $.ajax({
          url:link_ajax,
          success:function(a){
            if(a.trim()=='sukses'){
              location.reload(); // ambil cepat zzz
            }else{
              // alert(a);
              $('#hasil_ajax__'+id_jadwal).html(a);
            }
          }
        })
      }else{
        alert(`aksi ${aksi} belum terdapat handler.`);
        return;
      }

    })    
  })
</script>