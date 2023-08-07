<h1>Manage Kelas</h1>
<p>Proses Manage Grup Kelas untuk Kurikulum, Shift Kelas, dan Tahun Ajar tertentu.</p>
<?php
$angkatan = $_GET['angkatan'] ?? '';
$jenjang = $_GET['jenjang'] ?? '';
include 'include/include_rangkatan.php';
include 'include/include_rjenjang.php';


if($angkatan=='' || $jenjang==''){

  $total_kur=0;
  // $unsetting=0;
  $settinged_pagi=0;
  $settinged_sore=0;
  $div='';
  foreach ($rangkatan as $key => $angkatan) {
    $div.= "<div class=wadah><h3 class=mb2>Angkatan $angkatan</h3>";
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
            $total_kur++;
            $id_prodi = $d['id_prodi'];
            $s2 = "SELECT 
            a.id as id_kurikulum, 
            c.last_semester_aktif, 
            (
              SELECT count(1) FROM tb_kelas_ta_detail o  
              JOIN tb_kelas_ta p ON o.id_kelas_ta=p.id 
              JOIN tb_kelas q ON p.kelas=q.kelas 
              WHERE q.shift='pagi' AND q.angkatan=$angkatan AND q.id_prodi=$id_prodi) jumlah_mhs_pagi,      
            (
              SELECT count(1) FROM tb_kelas_ta_detail o  
              JOIN tb_kelas_ta p ON o.id_kelas_ta=p.id 
              JOIN tb_kelas q ON p.kelas=q.kelas 
              WHERE q.shift='sore' AND q.angkatan=$angkatan AND q.id_prodi=$id_prodi) jumlah_mhs_sore,      
            (
              SELECT count(1) FROM tb_kelas_ta p 
              JOIN tb_kelas q ON p.kelas=q.kelas 
              WHERE q.shift='pagi' AND q.angkatan=$angkatan AND q.id_prodi=$id_prodi) jumlah_kelas_ta_pagi,      
            (
              SELECT count(1) FROM tb_kelas_ta p 
              JOIN tb_kelas q ON p.kelas=q.kelas 
              WHERE q.shift='sore' AND q.angkatan=$angkatan AND q.id_prodi=$id_prodi) jumlah_kelas_ta_sore,      
            (
              SELECT count(1) FROM tb_kelas WHERE shift='pagi' AND angkatan=$angkatan AND id_prodi=$id_prodi) jumlah_kelas_pagi,      
            (
              SELECT count(1) FROM tb_kelas WHERE shift='sore' AND angkatan=$angkatan AND id_prodi=$id_prodi) jumlah_kelas_sore,       
            (
              SELECT count(1) FROM tb_mhs WHERE status_mhs=1 AND shift='pagi' AND angkatan=$angkatan AND id_prodi=$id_prodi) jumlah_mhs_aktif_pagi,       
            (
              SELECT count(1) FROM tb_mhs WHERE status_mhs=1 AND shift='sore' AND angkatan=$angkatan AND id_prodi=$id_prodi) jumlah_mhs_aktif_sore        
            FROM tb_kurikulum a 
            JOIN tb_kalender b ON a.id_kalender=b.id
            JOIN tb_angkatan c ON b.angkatan=c.angkatan 
            WHERE b.jenjang='$jenjang' 
            AND b.angkatan=$angkatan 
            AND a.id_prodi=$d[id_prodi] 
            ";
            $q2 = mysqli_query($cn,$s2) or die(mysqli_error($cn));

            $jumlah_kurikulum = mysqli_num_rows($q2);

            if($jumlah_kurikulum==1){
              $d2 = mysqli_fetch_assoc($q2);

              $jumlah_kelas_pagi = $d2['jumlah_kelas_pagi'];
              $jumlah_kelas_sore = $d2['jumlah_kelas_sore'];
              $jumlah_kelas_ta_pagi = $d2['jumlah_kelas_ta_pagi'];
              $jumlah_kelas_ta_sore = $d2['jumlah_kelas_ta_sore'];
              $jumlah_kelas = $jumlah_kelas_pagi + $jumlah_kelas_sore;
              $jumlah_kelas_ta = $jumlah_kelas_ta_pagi + $jumlah_kelas_ta_sore;

              $ta_aktif = $d2['last_semester_aktif']>2 ? intval($angkatan+($d2['last_semester_aktif']-1)/2) : $angkatan;

              if($jumlah_kelas_ta==0){
                // $unsetting++;
                $gr = 'merah';
                $jumlah_kelas_show = "<div class='mt1 kecil miring red'>Belum ada Grup-Kelas-TA untuk kurikulum ini pada Semester $d2[last_semester_aktif] TA.$ta_aktif.</div>";
              }else{
                $gr = 'hijau';
                $tanpa_kelas_pagi = $d2['jumlah_mhs_aktif_pagi']-$d2['jumlah_mhs_pagi'];
                $info_pagi = $tanpa_kelas_pagi==0 ? '<span class="green italic small">all assigned</span>' : "<span class=red>$tanpa_kelas_pagi mhs tanpa kelas</span>";
                $tanpa_kelas_sore = $d2['jumlah_mhs_aktif_sore']-$d2['jumlah_mhs_sore'];
                $info_sore = $tanpa_kelas_sore==0 ? '<span class="green italic small">all assigned</span>' : "<span class=red>$tanpa_kelas_sore mhs tanpa kelas</span>";
                $jumlah_kelas_show = "
                <div class='mt1 kecil miring green'>
                  Semester Aktif: Semester $d2[last_semester_aktif] TA.$ta_aktif<br>
                  Kelas Pagi TA.$ta_aktif: $jumlah_kelas_ta_pagi | $d2[jumlah_mhs_pagi] of $d2[jumlah_mhs_aktif_pagi] | $info_pagi<br>
                  Kelas Sore TA.$ta_aktif: $jumlah_kelas_ta_sore | $d2[jumlah_mhs_sore] of $d2[jumlah_mhs_aktif_sore] | $info_sore<br>
                </div>";
                // if($tanpa_kelas_pagi||$tanpa_kelas_sore) $unsetting++;
                if($tanpa_kelas_pagi==0) $settinged_pagi++;
                if($tanpa_kelas_sore==0) $settinged_sore++;
              }

              $link .= "
              <div class='wadah mt2 mb2 gradasi-$gr'>
                <a class='btn btn-$btn_type' href='?manage_grup_kelas&id_kurikulum=$d2[id_kurikulum]' target=_blank>
                  Manage Kelas @ Kurikulum $jenjang-$d[singkatan]-$angkatan <span class=debug>$d2[id_kurikulum]</span>
                </a> 
                $jumlah_kelas_show 
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
              $link .= div_alert('danger',"<h4>Multiple Kurikulum Terdeteksi</h4>Kurikulum angkatan $angkatan jenjang $jenjang prodi $prodi Lebih dari satu. Segera hubungi DB-Admin!</div>");
              $link .= '</div>';
            }

          }
        }else{
          $aclass = ' class="btn btn-danger" ';
          $err = "<div class='kecil miring red'>Jumlah semester pada Kalender tidak sama dengan Jumlah Semester Jenjang $jenjang. Silahkan Manage Kalender terlebih dahulu!</div>";
        }

        $link_manage_kalender = "<div class='mb2'><a href='?manage_kalender&angkatan=$angkatan&jenjang=$jenjang' $aclass>Kalender $angkatan-$jenjang <span class=debug>id:$id_kalender</span></a>$err</div>";

        $gradasi = $jenjang=='D3' ? 'hijau' : 'biru';

        $div.= "
        <div class='wadah gradasi-$gradasi'>
          $link_manage_kalender
          $link
        </div>
        ";
      }else{ // end mysqli_num_rows true
        $div.= " <a class='btn btn-primary' href='?manage_kalender&angkatan=$angkatan&jenjang=$jenjang'>AutoCreate Kalender $angkatan-$jenjang</a>";
      }
    }
    $div.= '</div>';
  }

  # =========================================================
  # PERSEN SETTINGED
  # =========================================================
  $allsetting = $total_kur * 2;
  $settinged = $settinged_pagi + $settinged_sore;
  $unsetting = $allsetting-$settinged;
  $persen_setting = $allsetting==0 ? 0 : round(($settinged/$allsetting)*100,2);

  $green_color = intval($persen_setting/100*155);
  $red_color = intval((100-$persen_setting)/100*255);
  $rgb = "rgb($red_color,$green_color,50)";
  echo "
  <div class='kecil miring consolas' style='color:$rgb'>Progres Manage : $persen_setting% | $settinged of $allsetting Grup Kelas Pagi/Sore</div>
  <div class=progress>
    <div class='progress-bar progress-bar-danger' style='width:$persen_setting%;background:$rgb;'></div>
  </div>
  
  <div class='mb2 biru'>Masih ada <span class='darkred tebal'>$unsetting Grup Kelas Pagi/Sore</span> yang harus Anda Kelola. Silahkan klik dari salah satu Kurikulum berikut! </div>
  $div
  ";

  $s = "INSERT INTO tb_unsetting (kolom,unsetting,total) VALUES ('kelas',$unsetting,$allsetting) ON DUPLICATE KEY UPDATE unsetting=$unsetting,total=$allsetting";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));  
  exit;
}
