<h1>Manage Awal Perkuliahan</h1>
<p>Manage Awal Perkuliah hanya bisa dilakukan setelah adanya Penjadwalan Dosen dan Penanggalan Semester.</p>
<?php 
$id_kurikulum = $_GET['id_kurikulum'] ?? '';
if($id_kurikulum==''){
  $s = "SELECT 
  a.id as id_kurikulum,
  b.angkatan,
  b.jenjang,
  c.singkatan  
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
    $green = $d['jenjang']=='D3' ? 'green gradasi-hijau' : 'darkblue gradasi-biru';
    $primary = $d['jenjang']=='D3' ? 'success' : 'primary';
    $tr .= "
    <tr class='$green' $border>
      <td>$i</td>
      <td>$d[angkatan]</td>
      <td>$d[jenjang]-$d[singkatan]</td>
      <td><a class='btn btn-$primary btn-sm' href='?manage_awal_kuliah&id_kurikulum=$d[id_kurikulum]'>Manage Awal Kuliah</a></td>
    </tr>
    ";
    $last_angkatan=$d['angkatan'];
  }

  echo "
  <p class=biru>Silahkan pilih link manage dari salah satu Kurikulum!</p>
  <table class='table'>
    <thead>
      <th>No</th>
      <th>Angkatan</th>
      <th>Prodi</th>
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
// $opt_jam['pagi'] = '';
// $opt_jam['sore'] = '';
// for ($i=7; $i <= 16 ; $i++) {
//   $i_show = $i<10 ? "0$i" : $i;
//   $opt_jam['pagi'].="<option value=$i>$i_show</option>";
// }
// for ($i=17; $i <= 22 ; $i++) {
//   $i_show = $i<10 ? "0$i" : $i;
//   $opt_jam['sore'].="<option value=$i>$i_show</option>";
// }
// $opt_menit = '';
// for ($i=0; $i <= 11 ; $i++) {
//   $j = $i*5;
//   $j_show = $j<10 ? "0$j" : $j;
//   $opt_menit.="<option value=$j>$j_show</option>";
// }

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
    SELECT awal_kuliah FROM tb_jadwal WHERE id_kurikulum_mk=b.id AND shift='$shift') as awal_kuliah 


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

    if($d2['awal_kuliah']==''){
      $awal_kuliah_show = $unset;
      $disabled_set = '';
      $debug = '';
      $tanggal_awal_kuliah = date('Y-m-d',strtotime($d2['awal_kuliah_uts']));
      $select_jam = '';
      $select_menit = '';
    }else{ // awal_kuliah berisi
      $jam_awal = date('H:i',strtotime($d2['awal_kuliah']));
      $select_jam = date('H',strtotime($d2['awal_kuliah']));
      $select_menit = date('i',strtotime($d2['awal_kuliah']));
      
      $jam_akhir = date('H:i',strtotime($d2['awal_kuliah'])+($bobot*45*60));

      $awal_kuliah_show = '<span class=green>'.date('D, d-M-Y',strtotime($d2['awal_kuliah']))." Pukul $jam_awal - $jam_akhir $img_aksi[check]</span>";
      $disabled_set = 'disabled';

      $tanggal_awal_kuliah = date('Y-m-d',strtotime($d2['awal_kuliah']));
      $debug = "<div class=debug style=background:yellow>
        tanggal_awal_kuliah2:<span id=tanggal_awal_kuliah2__$id_jadwal>$tanggal_awal_kuliah</span> | 
        select_jam2:<span id=select_jam2__$id_jadwal>$select_jam</span> | 
        select_menit2:<span id=select_menit2__$id_jadwal>$select_menit</span> | 
      </div>";


      $opt_jam = '';
      foreach ($rjam[$shift] as $jam) {
        $selected = $select_jam==$jam ? 'selected' : '';
        // echo "<div class=debug>$select_jam :: $jam</div>";
        $jam_show = $jam<10 ? '0'.$jam : $jam;
        $opt_jam.= "<option value='$jam' $selected>$jam_show</option>";
      }

      $opt_menit = '';
      for ($i=0; $i < 12; $i++) { 
        $menit = $i*5;
        $selected = $select_menit==$menit ? 'selected' : '';
        $menit_show = $menit<10 ? '0'.$menit : $menit;
        $opt_menit.= "<option value='$menit' $selected>$menit_show</option>";
      }
    } //// end awal_kuliah berisi


    
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
        <div><button class='btn btn-info btn-sm btn_aksi' id=set__$id_jadwal $disabled_set>Set</button></div>
        $debug
      </div>
    ";

    $bobot_show = ($d2['bobot']>0 AND $d2['bobot']<=6) ? "$d2[bobot] SKS" : '<span class=red>invalid bobot SKS</span>';

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
      <td width=40% class=''>$blok_awal_kuliah</td>
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

      let jap = $('#tanggal_awal_kuliah__'+id_jadwal).val() + ' ' 
        + $('#select_jam__'+id_jadwal).val() + ':'
        + $('#select_menit__'+id_jadwal).val();
        
      let jap2 = $('#tanggal_awal_kuliah2__'+id_jadwal).text() + ' ' 
        + $('#select_jam2__'+id_jadwal).text() + ':'
        + $('#select_menit2__'+id_jadwal).text();

      // console.log(tid, id_jadwal, jap, jap2);
      $('#set__'+id_jadwal).prop('disabled',0);

    });

    $('.btn_aksi').click(function(){
      let tid = $(this).prop('id');
      let rid = tid.split('__');
      let aksi = rid[0];
      let id_jadwal = rid[1];

      if(aksi=='set'){
        // validasi konflik jam untuk mhs zzz here
        let awal_kuliah = '2023-04-05 17:35';
        
        let link_ajax = `ajax_akademik/ajax_set_awal_kuliah.php?id_jadwal=${id_jadwal}&awal_kuliah=${awal_kuliah}&`;
        $.ajax({
          url:link_ajax,
          success:function(a){
            if(a.trim()=='sukses'){
              location.reload(); // ambil cepat zzz
            }else{
              alert(a);
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