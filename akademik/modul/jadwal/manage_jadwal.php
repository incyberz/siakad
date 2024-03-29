<h1>Manage Jadwal</h1>
<p>Proses assign dosen terhadap tiap MK yang ada pada Kurikulum.</p>
<?php
$angkatan = $_GET['angkatan'] ?? '';
$jenjang = $_GET['jenjang'] ?? '';
include 'include/include_rangkatan.php';
include 'include/include_rjenjang.php';


if($angkatan=='' || $jenjang==''){

  $s = "SELECT 1 FROM tb_kurikulum a JOIN tb_kurikulum_mk b ON b.id_kurikulum=a.id ";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  $total_mk = mysqli_num_rows($q);
  $s = "SELECT 1 FROM tb_kurikulum a JOIN tb_kurikulum_mk b ON b.id_kurikulum=a.id JOIN tb_jadwal c ON c.id_kurikulum_mk=b.id ";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  $total_jadwal = mysqli_num_rows($q);
  $unsetting = $total_mk-$total_jadwal;
  
  // echo "<h1>total MK : $total_mk | $total_jadwal </h1>";

  $allsetting = $total_mk;
  $settinged = $allsetting-$unsetting;
  $persen_setting = $allsetting==0 ? 0 : round(($settinged/$allsetting)*100,2);

  $green_color = intval($persen_setting/100*155);
  $red_color = intval((100-$persen_setting)/100*255);
  $rgb = "rgb($red_color,$green_color,50)";
  echo "
  <div class='kecil miring consolas' style='color:$rgb'>Progres Penjadwalan Dosen : $persen_setting% | $settinged of $allsetting Jadwal</div>
  <div class=progress>
    <div class='progress-bar progress-bar-danger' style='width:$persen_setting%;background:$rgb;'></div>
  </div>
  ";

  $s = "INSERT INTO tb_unsetting (kolom,unsetting,total) VALUES ('jadwal',$unsetting,$allsetting) ON DUPLICATE KEY UPDATE unsetting=$unsetting,total=$allsetting";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));

  echo "<div class='mb2 biru'>Silahkan klik salah satu Kurikulum untuk Penjadwalan Dosen! </div>";
  foreach ($rangkatan as $key => $angkatan) {
    echo "<div class=wadah><h3 class=mb2>Angkatan $angkatan</h3>";
    // echo " <a class='btn btn-info' href='?manage_kalender&angkatan=$angkatan'>$angkatan</a>";
    foreach ($rjenjang as $key => $jenjang) {
      $btn_type = $jenjang=='D3' ? 'success' : 'info';

      $s = "SELECT id as id_kalender,
      (SELECT count(1) FROM tb_semester WHERE id_kalender=a.id) jumlah_semester, 
      (SELECT count(1) FROM tb_kurikulum WHERE id_kalender=a.id) jumlah_kurikulum 
      FROM tb_kalender a WHERE a.jenjang='$jenjang' AND a.angkatan=$angkatan";
      $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
      $jumlah_kalender = mysqli_num_rows($q);
      if($jumlah_kalender){
        $d=mysqli_fetch_assoc($q);
        $jumlah_semester = $d['jumlah_semester'];
        $id_kalender = $d['id_kalender'];
        $link = '';
        if($jumlah_semester==$rjumlah_semester[$jenjang]){
          $aclass = '';
          $err = '';
          $s = "SELECT id as id_prodi,singkatan FROM tb_prodi WHERE jenjang='$jenjang'";
          $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
          while ($d=mysqli_fetch_assoc($q)) {

            $s2 = "SELECT a.id as id_kurikulum,
            (
              SELECT count(1) FROM tb_kurikulum_mk kmk 
              JOIN tb_mk ON kmk.id_mk=tb_mk.id  
              WHERE kmk.id_kurikulum=a.id 
              AND tb_mk.kode NOT LIKE '%MBKM%' 
              ) jumlah_mk,    
            (
              SELECT count(1) FROM tb_kurikulum_mk kmk
              JOIN tb_jadwal j ON j.id_kurikulum_mk=kmk.id
              JOIN tb_mk ON kmk.id_mk=tb_mk.id  
              WHERE kmk.id_kurikulum=a.id
              AND tb_mk.kode NOT LIKE '%MBKM%' 
              ) sudah_dijadwalkan     
            FROM tb_kurikulum a 
            JOIN tb_kalender b ON a.id_kalender=b.id
            WHERE b.jenjang='$jenjang' 
            AND b.angkatan=$angkatan 
            AND a.id_prodi=$d[id_prodi] 
            ";
            $q2 = mysqli_query($cn,$s2) or die(mysqli_error($cn));

            $jumlah_kurikulum = mysqli_num_rows($q2);

            if($jumlah_kurikulum==1){
              $d2 = mysqli_fetch_assoc($q2);

              $jumlah_mk = $d2['jumlah_mk'];
              if($jumlah_mk){
                $belum_terjadwal = $jumlah_mk - $d2['sudah_dijadwalkan'];
                if($belum_terjadwal){
                  $gr = 'merah';
                  $jumlah_mk_show = "<div class='mt1 kecil miring red'>Terdapat $belum_terjadwal MK yang belum dijadwalkan</div>";
                }else{
                  $gr = 'hijau';
                  $jumlah_mk_show = "<div class='mt1 kecil miring green'>Semua MK sudah dijadwalkan.</div>";
                }
              }else{
                $gr = 'merah';
                $jumlah_mk_show = "<div class='mt1 kecil miring red'>Belum ada MK pada kurikulum ini.</div>";
              }

              $link .= "
              <div class='wadah mt2 mb2 gradasi-$gr'>
                <a class='btn btn-$btn_type' href='?manage_jadwal_dosen&id_kurikulum=$d2[id_kurikulum]'>
                  Kurikulum $d[singkatan]-$angkatan-$jenjang <span class=debug>$d2[id_kurikulum]</span>
                </a> 
                $jumlah_mk_show 
              </div> 
              ";
            }elseif($jumlah_kurikulum==0){
              $link .= "
              <div class='wadah mt2 mb2 gradasi-merah'>
                <a class='btn btn-danger' href='?manage_kalender&angkatan=$angkatan&jenjang=$jenjang'>
                  Buat Kurikulum $d[singkatan]-$angkatan-$jenjang <span class=debug></span>
                </a>
                <div class='kecil miring red'>Kurikulum $d[singkatan]-$angkatan-$jenjang belum ada. Silahkan buat pada Manage Kalender (opsi paling bawah).</div>
              </div>
              ";
            }else{
              $link .= '<div class="wadah gradasi-merah">';
              $prodi = $rprodi[$d['id_prodi']];
              $link .= div_alert('danger',"<h4>Multiple Kurikulum Terdeteksi</h4>Kurikulum angkatan $angkatan jenjang $jenjang prodi $prodi Lebih dari satu. Silahkan hapus salah satu!<div class='kecil miring abu mt1'>Jika tidak bisa dihapus silahkan hubungi DB-Admin!</div>");
              while ($d2=mysqli_fetch_assoc($q2)) {
                $disabled = $d2['jumlah_mk'] ? 'disabled' : '';
                $link .= "
                <form method=post>
                  <button class='btn btn-danger' value=$d2[id_kurikulum] name=btn_hapus_kurikulum $disabled>Hapus Kurikulum | $d2[jumlah_mk] MK | id:$d2[id_kurikulum]</button> 
                </form>
                ";
              }
              $link .= '</div>';
            }

          }
        }else{
          $aclass = ' class="btn btn-danger" ';
          $err = "<div class='kecil miring red'>Jumlah semester pada Kalender tidak sama dengan Jumlah Semester Jenjang $jenjang. Silahkan Manage Kalender terlebih dahulu!</div>";
        }

        $link_manage_kalender = "<div class='mb2'><a href='?manage_kalender&angkatan=$angkatan&jenjang=$jenjang' $aclass>Kalender $angkatan-$jenjang <span class=debug>id:$id_kalender</span></a>$err</div>";

        $gradasi = $jenjang=='D3' ? 'hijau' : 'biru';

        echo "
        <div class='wadah gradasi-$gradasi'>
          $link_manage_kalender
          $link
        </div>
        ";
      }else{ // end mysqli_num_rows true
        echo " <a class='btn btn-primary' href='?manage_kalender&angkatan=$angkatan&jenjang=$jenjang'>AutoCreate Kalender $angkatan-$jenjang</a>";
      }
    }
    echo '</div>';
  }
  exit;
}
