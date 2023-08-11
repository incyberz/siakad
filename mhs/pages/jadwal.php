<?php
$unset = '<span class="kecil miring consolas red">unset</span>';
$debug='';
$debug.="semester:$semester | ";
$debug.="id_semester:$id_semester | ";

// dek awal_kuliah == awal_sesi zzz here

$jadwal = div_alert('danger', 'Belum ada MK pada Semester ini. Segera Lapor Petugas!');
$s = "SELECT a.id as id_kurikulum_mk, d.id as id_jadwal, d.awal_kuliah, d.id_dosen,
(SELECT nama FROM tb_dosen WHERE id=d.id_dosen) nama_dosen
FROM tb_kurikulum_mk a 
JOIN tb_mk b ON a.id_mk=b.id 
JOIN tb_kurikulum c ON a.id_kurikulum=c.id 
JOIN tb_jadwal d ON d.id_kurikulum_mk=a.id 
WHERE a.id_semester='$id_semester' 
AND c.id='$id_kurikulum' 
";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
$rid_jadwal = [];
$rawal_kuliah = [];
$rid_dosen = [];
$rnama_dosen = [];
while ($d=mysqli_fetch_assoc($q)) {
  $rid_jadwal[$d['id_kurikulum_mk']] = $d['id_jadwal'];
  $rawal_kuliah[$d['id_kurikulum_mk']] = $d['awal_kuliah'];
  $rid_dosen[$d['id_kurikulum_mk']] = $d['id_dosen'];
  $rnama_dosen[$d['id_kurikulum_mk']] = $d['nama_dosen'];
}

$s = "SELECT a.id as id_kurikulum_mk,
b.id as id_mk,
b.nama as nama_mk,
b.kode as kode_mk,
(b.bobot_teori+b.bobot_praktik) bobot,
(
  SELECT count(1) FROM tb_sesi_kuliah p 
  JOIN tb_jadwal q ON p.id_jadwal=q.id 
  WHERE q.id_kurikulum_mk=a.id 
  AND q.shift='$shift') jumlah_sesi,    
(
  SELECT id FROM tb_jadwal WHERE id_kurikulum_mk=a.id and shift='$shift') id_jadwal    
FROM tb_kurikulum_mk a 
JOIN tb_mk b ON a.id_mk=b.id 
JOIN tb_kurikulum c ON a.id_kurikulum=c.id 
WHERE a.id_semester='$id_semester' 
AND c.id='$id_kurikulum' 

";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
$jumlah_mk = mysqli_num_rows($q);
$rdiv = [];
$rdiv_unset = [];
if(mysqli_num_rows($q)){
  $i=0;
  while ($d=mysqli_fetch_assoc($q)) {
    $i++;
    $id_jadwal = $d['id_jadwal']; //or null
    $id_kurikulum_mk = $d['id_kurikulum_mk'];
    $bobot = $d['bobot'];
    $nama_dosen = $rnama_dosen[$id_kurikulum_mk] ?? $unset; //dosen koordinator
    $nama_dosen_show = $nama_dosen==$unset ? $unset : "Dosen: <a href='?info_dosen&id_dosen=$rid_dosen[$id_kurikulum_mk]' onclick='return confirm(\"Ingin menuju laman Detail Dosen?\")'>$rnama_dosen[$id_kurikulum_mk]</a>";

    $awal_kuliah = $rawal_kuliah[$id_kurikulum_mk] ?? $unset;

    $awal_kuliah_show = $awal_kuliah==$unset ? "Jadwal: $unset" : $nama_hari[date('w',strtotime($awal_kuliah))].', '. date('H:i',strtotime($awal_kuliah)).' WIB';

    $nama_mk_show = "<a href='?info_mk&id_mk=$d[id_mk]' onclick='return confirm(\"Ingin menuju laman Info MK ini?\")'>$d[nama_mk] | $d[kode_mk]</a>";

    $debug2 = "<span class=debug>jumlah_sesi:$d[jumlah_sesi]</span>";

    if($d['jumlah_sesi']!=0 AND $d['jumlah_sesi']!=16){
      die(div_alert('danger','Jumlah sesi tidak sama dengan NULL atau 16. Segera laporkan ke Petugas!'));
    }elseif($d['jumlah_sesi']==0){
      $info_sesi_skg = '';
      $icon_sesi_skg = '';
      $nama_sesi_skg = $unset;
      $tsj='';
      $awal_sesi_skg = 'Tanggal sesi: '.$unset;
      $ruang_sesi_skg = 'Ruang: '.$unset;
      $closed_by_skg = '';
      $status_presensi_skg = '';
      // $icon_sesi_skg = "<span style='font-size: 20px; color: #88f'><i class='bx bx-camera-movie'></i></span>";
    }elseif($d['jumlah_sesi']==16){
      // $debug3 = 'ada sesi;';

      $senin_skg = date('D, Y-m-d',strtotime('now') - (date('w',strtotime('now'))-1)*24*60*60);
      $senin_depan = date('Y-m-d',strtotime($senin_skg)+7*24*60*60);

      $s2 = "SELECT 
      a.id as id_sesi,
      a.nama as nama_sesi,
      a.tanggal_sesi as awal_sesi,
      a.stop_sesi as akhir_sesi,
      b.id as id_dosen, 
      b.nama as dosen_pengajar, 
      b.nidn  ,
      (SELECT timestamp_masuk FROM tb_presensi WHERE id_mhs=$id_mhs AND id_sesi_kuliah=a.id) timestamp_masuk, 
      (SELECT timestamp_keluar FROM tb_presensi WHERE id_mhs=$id_mhs AND id_sesi_kuliah=a.id) timestamp_keluar 
      FROM tb_sesi_kuliah a 
      JOIN tb_dosen b ON a.id_dosen=b.id 
      WHERE a.id_jadwal='$id_jadwal'";
      $q2 = mysqli_query($cn,$s2) or die(mysqli_error($cn));
      while ($d2=mysqli_fetch_assoc($q2)) {
        $id_sesi = $d2['id_sesi'];
        $awal_sesi = $d2['awal_sesi'];
        $akhir_sesi = $d2['akhir_sesi'];
        $timestamp_masuk = $d2['timestamp_masuk'];
        $timestamp_keluar = $d2['timestamp_keluar'];
        $nama_dosen_show = $nama_dosen==$d2['dosen_pengajar'] ? $nama_dosen_show : "<span class='darkred pointer' onclick='alert(\"TT = Tim Teaching, artinya Dosen ini bekerjasama dengan Dosen Utama dalam proses pengajaran.\")'>Dosen (TT)</span>: <a href='?info_dosen&id_dosen=$d2[id_dosen]' onclick='return confirm(\"Ingin menuju laman Detail Dosen?\")'>$d2[dosen_pengajar]</a>";

        $nidn = $d2['nidn'];

        $cek_in = $timestamp_masuk=='' ? 0 : 1;
        $cek_out = $timestamp_keluar=='' ? 0 : 1;

        $icon_sesi_skg = ''; //zzz
        // $icon_sesi_skg = "<span style='font-size: 20px; color: #88f'><i class='bx bx-camera-movie'></i></span>"; //zzz

        // $nama_sesi = $d2['nama_sesi'];

        // get senin minggu ini
        $weekday_awal_sesi = date('w',strtotime($awal_sesi));
        if($weekday_awal_sesi==0) die(div_alert('danger', "Jadwal hari Ahad tidak diperkenankan. Segera Lapor Petugas!<hr>id_sesi: $id_sesi"));

        // die("$today");
        $selisih_detik = strtotime('now')-strtotime($awal_sesi);

        if(strtotime($akhir_sesi)<strtotime($senin_skg)){ // lampau
          $selisih_hari = intval($selisih_detik/(24*60*60));
          if($selisih_hari>30){
            $selisih_bulan = intval($selisih_hari/30);
            $info_lampau = "($selisih_bulan months ago)";
          }else{
            $info_lampau = "($selisih_hari days ago)";
          }
        }else{ // minggu aktif, dst :: sesi sedang berlangsung atau next

          if(strtotime($awal_sesi)>strtotime($senin_depan)){
            # ============================================================
            # MINGGU NEXT
            # ============================================================
            $info_lampau = 'next sesi';
            $closed_by = '';
            $status_presensi = 'next sesi presensi';
          }else{ // minggu aktif
            # ============================================================
            # MINGGU AKTIF
            # ============================================================
            $awal_sesi_skg = $nama_hari[date('w',strtotime($awal_sesi))].', '.date('d-M-Y H:i',strtotime($awal_sesi));
            $nama_sesi_skg = "<a href='?info_sesi&id_sesi=$d2[id_sesi]' onclick='return confirm(\"Ingin menuju laman Detail Sesi?\")'>$d2[nama_sesi]</a>";
            // $icon_sesi_skg = "<span style='font-size: 20px; color: #88f'><i class='bx bx-camera-movie'></i></span>"; //zzz

            # ============================================================
            # TIDAK SESUAI JADWAL
            # ============================================================
            $pindah_hari = date('w',strtotime($awal_kuliah))==date('w',strtotime($awal_sesi))? 0 : 1;
            $pindah_jam = date('H',strtotime($awal_kuliah))==date('H',strtotime($awal_sesi))? 0 : 1;
            if($pindah_hari and $pindah_jam){
              $tsj = 'Pindah Hari + Pindah Jam';
            }elseif($pindah_hari){
              $tsj = 'Pindah Hari';
            }elseif($pindah_jam){
              $tsj = 'Pindah Jam';
            }else{
              $tsj ='';
            }
            $tsj = $tsj=='' ? '' : "<div class='alert alert-info m-0 tengah kecil consolas red'>$tsj</div>";

            # ============================================================
            # GET RUANG-RUANG
            # ============================================================
            $s3 = "SELECT b.nama as nama_ruang FROM tb_assign_ruang a 
            JOIN tb_ruang b ON a.id_ruang=b.id 
            WHERE a.id_sesi_kuliah='$id_sesi'";
            $q3 = mysqli_query($cn,$s3) or die(mysqli_error($cn));
            if(mysqli_num_rows($q3)==0){
              $ruang_sesi_skg = 'Ruang sesi: '. $unset;
            }else{
              $ruang_sesi_skg = '__';
              while ($d3=mysqli_fetch_assoc($q3)) {
                $ruang_sesi_skg .= ", $d3[nama_ruang]";
              }
              $ruang_sesi_skg = str_replace('__, ','',$ruang_sesi_skg);
            }


            $info_lampau = 'jadwal minggu ini.';
            $selisih_menit = intval($selisih_detik/60);
            
            if($selisih_detik<0){
              // belum berlangsung
              $selisih_menit = abs($selisih_menit);
              if($selisih_menit>=60){
                $selisih_jam = intval($selisih_menit/60);
                if($selisih_jam>=24){
                  $selisih_hari = intval($selisih_jam/24);
                  $info_lampau = "berlangsung dalam $selisih_hari hari lagi";
                }else{
                  $info_lampau = "berlangsung dalam $selisih_jam jam lagi";
                }
              }else{
                $info_lampau = "berlangsung dalam $selisih_menit menit lagi";
              }
              $info_lampau = "<span class=biru>$info_lampau</span>";
              $status_presensi = 'bersiaplah untuk presensi!';
            }else{ // sedang atau sudah berlangsung
              

              
              $jam_selesai = date('Y-m-d H:i',strtotime($awal_sesi)+$bobot*45*60);
              $jam_selesai_show = date('H:i',strtotime($jam_selesai));
              $info_lampau = "sedang atau sudah berlangsung | $bobot SKS | $awal_sesi - $jam_selesai";
              if(strtotime($jam_selesai)>strtotime('now')){ //sedang berlangsung
                $eta_menit = intval((strtotime($jam_selesai)-strtotime('now'))/60)+1;
                $info_lampau = "<div class=berlangsung>Sedang Berlangsung s.d $jam_selesai_show ($eta_menit menit lagi)</div>";

                $presensi_dosen = 0; //zzz here
                if($presensi_dosen){
                  $closed_by = '<div class="kecil miring abu consolas">Presention system ready.</div>';
                  $closed_by .= "<a class='btn btn-primary btn-block' href='?presensi&id_sesi=$id_sesi'>Isi Presensi</a>";
                }else{
                  $closed_by = '<div class="kecil miring abu consolas">Dosen belum presensi ...</div>';
                  $closed_by .= "<button class='btn btn-danger btn-block' disabled>Isi Presensi</button>";
                }

                if($cek_in){
                  $status_presensi = "<div class=small><b>Presensi</b>: <span class=green>Anda sudah Check-In</span></div>";
                }else{
                  $status_presensi = "<div class=small><b>Presensi</b>: <span class=red>Anda belum Check-In</span></div>";
                }

              }else{ // telah selesai, tapi dlm minggu aktif
                $eta_menit = intval((strtotime('now')-strtotime($jam_selesai))/60);
                if($eta_menit>=60){
                  $eta_jam = intval($eta_menit/60);
                  if($eta_jam>=24){
                    $eta_hari = intval($eta_jam/24);
                    $eta_show = "$eta_hari hari yang lalu";
                  }else{
                    $eta_show = "$eta_jam jam yang lalu";
                  }
                }else{
                  $eta_show = "$eta_menit menit yang lalu";
                }
                $info_lampau = "<span class=red>Sudah Selesai. ($eta_show)</span>";
                $closed_by = "<div class='abu small'><i>Closed by system</i></div>";
                if($cek_in){
                  $status_presensi = "<div class=small><b>Presensi</b>: <span class=green>Anda sudah Check-In</span></div>";
                }else{
                  $status_presensi = "<div class=small><b>Presensi</b>: <span class=red>Anda tidak Check-In</span></div>";
                }
              } //end //telah selesai, tapi dlm minggu aktif
            }//end // sedang atau sudah berlangsung
            $info_sesi_skg = $info_lampau;
            $closed_by_skg = $closed_by;
            $status_presensi_skg = $status_presensi;
          } //end // minggu aktif
        } //end minggu aktif, dst
      } //end while d2
    } //endif jumlah_sesi==16

    $div="  
    <div class='bg-white mb2' style=';'>
      <div class=row>
        <div class='col-lg-5' >
          <div class='bingkai'>
            <div class='darkblue tebal'>$awal_kuliah_show</div>
            <div class=small>$nama_mk_show</div>
            <div class=small>$nama_dosen_show</div>
          </div>
        </div>
        <div class='col-lg-4'>
          <div class='bingkai'>
            $tsj
            <div><b>Sesi</b>: $nama_sesi_skg $icon_sesi_skg</div>
            <div class=small>$awal_sesi_skg</div>
            <div class=small>$ruang_sesi_skg</div>
            <div class='small miring'>$info_sesi_skg </div>
          </div>
        </div>
        <div class=col-lg-3>
          <div class='bingkai'>
            $closed_by_skg
            $status_presensi_skg
          </div>
        </div>
      </div>
    </div>
    <span class='debug'>$timestamp_masuk | $timestamp_keluar</span>
    ";
    if($awal_kuliah==$unset){
      array_push($rdiv_unset,$div);
    }else{
      array_push($rdiv,'<span class=debug>'.strtotime($awal_kuliah).'</span>'. $div);
      sort($rdiv);
    }

  }


  $divs='';
  foreach ($rdiv as $div) $divs.=$div;
  foreach ($rdiv_unset as $div) $divs.=$div;

  $jadwal = "<div class=wadash style=box-sizing:border-box>$divs</div>";

}


?>
<style>.bingkai{border-top: solid 1px #ccc; padding: 5px}.berlangsung{border:solid 5px blue;padding:10px; background: linear-gradient(#cfc,#afa);text-align:center}</style>
<section id="jadwal_kuliah" class="" data-aos="fade-left">
  <div class="container">
    <div class="debug"><?=$debug?></div>

    <div class="section-title">
      <h2>Jadwal Kuliah</h2>
      <p>Berikut adalah Jadwal Perkuliahan <?=$jenjang?>-<?=$prodi?>-<?=$angkatan?> Semester <?=$semester?> Kelas <span class="proper"><?=$shift?></span> ~ <?=$jumlah_mk?> MK</p>
    </div>

    <?=$jadwal?>


    <div class="section-title">
      <h2>Jadwal Kuliah</h2>
      <p><strong>Total MK semester ini</strong>: <span class="badge badge-primary badge-pill" style="font-size: 18pt">7 Mata Kuliah</span></p>
      <hr>
      <p><small >Jadwal Kuliah Anda hari ini:</small> <div class="link_selected text-center">Rabu, 27 Mei 2021 :: 4 Mata Kuliah</div></p>
    </div>

    <div style="padding: 0 15px">

      <div class="row div_table">
        <div class="col-lg-4 isi_tabel">
          <table>
            <tr>
              <td style="vertical-align: top;padding-right: 15px">
                <strong>1</strong>
              </td>
              <td>
                <strong>Pukul</strong>: 08.00 s.d 09.30
                <br><small><strong>Ruang</strong>: Teleconference</small>
              </td>
              <td>
                <span style="font-size: 30px; color: #88f"><i class="bx bx-camera-movie"></i></span>
              </td>
            </tr>
          </table>
        </div>
        <div class="col-lg-4 isi_tabel">
          <strong>Sesi</strong>: <a href="#jadwal_mengajar" class="not_ready">P3 Koneksi ke Database</a>
          <br><small><strong>MK</strong>: <a href="#jadwal_mengajar" class="not_ready">Pemrograman Web Lanjut ~ MKW-008</small></a></small>
        </div>
        <div class="col-lg-4 isi_tabel">
          Closed by system | <span class="tebal merah">You not present!</span>
          <br><small><strong>Dosen</strong>: <a href="#" class="not_ready">Dadang Sudrajat, M.Kom</a></small>
        </div>
      </div>


      <div class="row div_table">
        <div class="col-lg-4 isi_tabel">
          <table>
            <tr>
              <td style="vertical-align: top;padding-right: 15px">
                <strong>2</strong>
              </td>
              <td>
                <strong>Pukul</strong>: 10.00 s.d 11.30
                <br><small><strong>Ruang</strong>: Teleconference</small>
              </td>
              <td>
                <span style="font-size: 30px; color: #88f"><i class="bx bx-camera-movie"></i></span>
                <span style="font-size: 30px; color: #3d3"><i class="bx bxs-medal"></i></span>
              </td>
            </tr>
          </table>
        </div>
        <div class="col-lg-4 isi_tabel">
          <strong>Sesi</strong>: <a href="#jadwal_mengajar" class="not_ready">P14 Style Web dengan Bootstrap</a>
          <br><small><strong>MK</strong>: <a href="#jadwal_mengajar" class="not_ready">Web Design ~ MKW-001</small></a></small>
        </div>
        <div class="col-lg-4 isi_tabel">

          <span class="tebal" style="color: #3d3">You has been presented!</span> 

          <br><small><strong>Dosen</strong>: <a href="#" class="not_ready">Ade Irma P, M.Kom</a></small>
        </div>
      </div>


      <div class="row div_table">
        <div class="col-lg-4 isi_tabel">
          <table>
            <tr>
              <td style="vertical-align: top;padding-right: 15px">
                <strong>3</strong>
              </td>
              <td>
                <strong>Pukul</strong>: All weekdays
                <br><small><strong>Ruang</strong>: e-Learning</small>
              </td>
            </tr>
          </table>
        </div>
        <div class="col-lg-4 isi_tabel">
          <strong>Sesi</strong>: <a href="#jadwal_mengajar" class="not_ready">P5 PenerapanMachine Learning</a>
          <br><small><strong>MK</strong>: <a href="#jadwal_mengajar" class="not_ready">Machine Learning Dasar ~ MKP-002</a></small>
        </div>
        <div class="col-lg-4 isi_tabel">
          <a class="btn btn-primary btn-sm">Ikuti Perkuliahan</a> <a class="btn btn-success btn-sm">Go to eLearning</a> 
          <br><small>Opened by <a href="#" class="not_ready">Dian Ade Kurnia, M.Kom</a> 3 days ago</small>
        </div>
      </div>

      <div class="row div_table">
        <div class="col-lg-4 isi_tabel">
          <table>
            <tr>
              <td style="vertical-align: top;padding-right: 15px">
                <strong>4</strong>
              </td>
              <td>
                <strong>Pukul</strong>: 13.00 s.d 14.30
                <br><small><strong>Ruang</strong>: Teleconference</small>
              </td>
              <td>
                <span style="font-size: 30px; color: #88f"><i class="bx bx-camera-movie"></i></span>
              </td>

            </tr>
          </table>
        </div>
        <div class="col-lg-4 isi_tabel">
          <strong>Sesi</strong>: <a href="#jadwal_mengajar" class="not_ready">P3 Definisi Manajemen Akuntansi</a>
          <br><small><strong>MK</strong>: <a href="#jadwal_mengajar" class="not_ready">Manajemen Akuntasnsi ~ MKA-011</a></small>
        </div>
        <div class="col-lg-4 isi_tabel">
          <a class="btn btn-primary btn-sm">Ikuti Perkuliahan</a> <a class="btn btn-success btn-sm">Zoom Link</a> 
          <br><small>Started by <a href="#" class="not_ready">Nana Suarna, M.Kom</a> 9 minutes ago</small>
        </div>
      </div>


      Go to <a href="#" class="not_ready">Jadwal Kuliah Lengkap</a>
    </div>



  </div>
</section><!-- End Facts Section -->