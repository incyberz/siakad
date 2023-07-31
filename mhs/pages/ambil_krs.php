<?php
$judul = 'Pengambilan KRS';
$sub_judul = 'KRS dapat diambil jika sudah masuk masanya dan semua persyaratan keuangan sudah terpenuhi.';
$tnow = strtotime('now');
$undef = '<code>undefined</code>';
$null = '<code>null</code>';
$valid_tanggal=0;
$is_lunas=0;
$info_lunas = '';

if(isset($_POST['btn_ambil_krs'])){
  $s = "SELECT 1 FROM tb_krs WHERE id_mhs='$_POST[id_mhs]' AND id_semester='$_POST[id_semester]'";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  if(mysqli_num_rows($q)==0){
    $s = "INSERT INTO tb_krs (id_mhs,id_semester) VALUES ('$_POST[id_mhs]','$_POST[id_semester]')";
    $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
    echo div_alert('success','Pengambilan KRS sukses.<hr><a href="?ambil_krs">Kembali</a>');
  }else{
    die("<script>location.replace('?ambil_krs&id_semester=$_POST[id_semester]')</script>");
  }
}

$id_semester = $_GET['id_semester'] ?? '';
if($id_semester==''){
  $s = "SELECT * FROM tb_kalender WHERE angkatan=$angkatan AND jenjang='$jenjang'  
  ORDER BY angkatan,jenjang";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  $tr = '';
  while ($d=mysqli_fetch_assoc($q)) {
    
    $s2 = "SELECT a.id as id_semester, a.nomor as no_smt,
    (SELECT count(1) FROM tb_kurikulum_mk WHERE id_semester=a.id) jumlah_mk,
    (SELECT tanggal FROM tb_krs WHERE id_semester=a.id and id_mhs=$id_mhs) tanggal_krs,
    a.tanggal_awal as tanggal_awal_smt, 
    a.tanggal_akhir as tanggal_akhir_smt 
    FROM tb_semester a 
    WHERE a.id_kalender=$d[id] ORDER BY a.nomor";
    $q2 = mysqli_query($cn,$s2) or die(mysqli_error($cn));
    $col_smt='';
    while ($d2=mysqli_fetch_assoc($q2)) {
      $tawal = strtotime($d2['tanggal_awal_smt']);
      $takhir = strtotime($d2['tanggal_akhir_smt']);
      $type = 'warning';
      $border = '';
      if($tawal<$tnow AND $takhir>=$tnow){
        $type = 'primary';
        $border = 'style="border:solid 3px blue;"';
        $info_smt = '[smt aktif]';
      }else if($tnow<$tawal){
        $info_smt = '<span class="kecil">(next smt)</span>';
        $type = 'info';
      }else{
        $info_smt = '<span class="kecil abu">(smt lampau)</span>';
      }

      if($d2['jumlah_mk']<1){
        $info_smt = 'Blm ada MK';
        $type = 'danger';
      } 

      if($d2['tanggal_krs']==''){
        $is_ambil = '<span class="badge badge-danger">Belum ambil KRS</span>';
      }else{
        $tanggal_krs = date('d-M-Y',strtotime($d2['tanggal_krs']));
        $is_ambil = '<span class="badge badge-success">Sudah KRS</span><div class="kecil">'.$tanggal_krs.'</div>';
      }

      $col_smt.="
      <div class='col-lg-2 mb2'>
      <a href='?ambil_krs&id_semester=$d2[id_semester]' class='btn btn-sm btn-$type btn-block' $border>
        <span style='font-size:24px' class=darkblue>$d2[no_smt]</span> 
        <div class=mb1>$info_smt</div>
        <div>$is_ambil</div>
      </a>
      </div>";
    }

    $gradasi_jenjang = $d['jenjang']=='D3' ? 'hijau' : 'biru';

    $tr.= "
    <div class='wadah gradasi-$gradasi_jenjang'>
      <div class='darkblue mb1'>Angkatan $d[angkatan]-$d[jenjang]. <div class='kecil biru'>Silahkan Anda pilih semester!</div></div>
      <div class=row>$col_smt</div>
    </div>
    ";
  }

  $krs = $tr;  
}else{

  
  $s = "SELECT a.id as id_semester, a.*, b.angkatan, b.jenjang, b.id as id_kalender,
  (SELECT tanggal FROM tb_krs WHERE id_semester=a.id) tanggal_krs,
  (SELECT count(1) FROM tb_kurikulum_mk WHERE id_semester=a.id) jumlah_mk 
  FROM tb_semester a 
  JOIN tb_kalender b ON a.id_kalender=b.id 
  WHERE a.id=$id_semester";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  if(mysqli_num_rows($q)==0) die('Data semester tidak ditemukan.');
  $d=mysqli_fetch_assoc($q);
  $manage_semester = "<a class='btn btn-primary' href='?manage_semester&id_semester=$d[id_semester]'>Penanggalan Semester</a>";
  $jenjang = $d['jenjang'];
  $angkatan = $d['angkatan'];
  $semester = $d['nomor'];
  $id_kalender = $d['id_kalender'];
  
  $tawal = strtotime($d['tanggal_awal']);
  $takhir = strtotime($d['tanggal_akhir']);
  if($tawal<$tnow AND $takhir>=$tnow){
    $info_smt = '[aktif]';
  }else if($tnow<$tawal){
    $info_smt = '(next)';
  }else{
    $info_smt = '(lampau)';
  }
  
  
  $awal_krs = $d['awal_krs']=='' ? $null : date('d-M-Y',strtotime($d['awal_krs']));
  $akhir_krs = $d['akhir_krs']=='' ? $null : date('d-M-Y',strtotime($d['akhir_krs']));
  $durasi_krs = "<div class=wadah>Tanggal KRS : $awal_krs s.d $akhir_krs</div>";
  
  $link = $d['awal_krs']=='' ? div_alert('danger',"Penanggalan default KRS masih kosong. Segera lapor petugas!") : '';
  
  if($d['awal_krs']==''){
    $info_krs = '';
  }else{
    $tkrs_awal = strtotime($d['awal_krs']);
    $tkrs_akhir = strtotime($d['akhir_krs']);
    if($d['tanggal_krs']==''){
      if($tkrs_awal<$tnow AND $tkrs_akhir>=$tnow){
        $info_krs = div_alert('success','<h3 class="darkblue tebal">KRS sedang berlangsung.</h3>Saat ini KRS sudah dapat diakses oleh Anda. Silahkan catat Paket Mata Kuliahnya lalu klik tombol <code>Ambil KRS</code> !');
        $valid_tanggal = 1;
      }else if($tnow<$tkrs_awal){
        $info_krs = div_alert('danger','Belum masuk masa KRS. Tunggu hingga Petugas Akademik memberikan informasinya.');
        // $durasi_krs = '';
        $durasi_krs = "<div class=wadah>Perkiraan KRS : $awal_krs s.d $akhir_krs</div>";
      }else{
        $info_krs = div_alert('danger','Maaf, masa pengambilan KRS sudah lewat. Jika ada kekeliruan silahkan hubungi Petugas!');
      }
    }else{
      $tanggal_krs = date('d-M-Y H:i', strtotime($d['tanggal_krs']));
      $info_krs = div_alert('success','Kamu sudah ambil KRS pada tanggal '.$tanggal_krs);
    }
  }
  
  $info_mk = '';
  $pilihan_mk = '';
  if($d['jumlah_mk']<1){
    $info_mk = div_alert('danger',"Belum ada MK pada Kurikulum ini. Silahkan laporkan ke Petugas!");
  }else{
  
    $s2 = "SELECT 
    id as id_prodi, 
    nama as nama_prodi, 
    singkatan as singkatan_prodi  
    FROM tb_prodi WHERE id='$id_prodi'";
    $q2 = mysqli_query($cn,$s2) or die(mysqli_error($cn));
    while ($d2=mysqli_fetch_assoc($q2)) {
      $id_prodi = $d2['id_prodi'];
      $s3 = "SELECT id as id_kurikulum FROM tb_kurikulum WHERE id_kalender=$id_kalender AND id_prodi=$id_prodi";
      $q3 = mysqli_query($cn,$s3) or die(mysqli_error($cn));
      if(mysqli_num_rows($q3)==0) die('id_kurikulum tidak ditemukan.');
      $d3=mysqli_fetch_assoc($q3);
      $id_kurikulum = $d3['id_kurikulum'];
  
      $s3 = "SELECT a.*, 
      b.nama as nama_mk,  
      b.kode as kode_mk,  
      (b.bobot_teori + b.bobot_praktik) bobot   
      FROM tb_kurikulum_mk a 
      JOIN tb_mk b ON a.id_mk=b.id 
      WHERE id_semester=$id_semester 
      AND id_kurikulum=$id_kurikulum 
      ORDER BY b.nama 
      ";
      $q3 = mysqli_query($cn,$s3) or die(mysqli_error($cn));
      $mk = '';
      $i=0;
      $total_bobot=0;
      while ($d3=mysqli_fetch_assoc($q3)) {
        $i++;
        $total_bobot += $d3['bobot'];
        $mk.= "
        <tr>
          <td>$i</td>
          <td>$d3[nama_mk] | $d3[kode_mk]</td>
          <td>$d3[bobot] SKS</td>
        </tr>
        ";
      }
      $row_total = "
      <tr style='border-top: solid 3px #ccf'>
        <td colspan=2 class='tebal kanan'>Jumlah SKS</td>
        <td class='tebal'>$total_bobot SKS</td>
      </tr>";
      $tb_mk = "<table class=table>$mk$row_total</table>";

      if($valid_tanggal){
        if($is_lunas){
          # ===========================================
          # JIKA SUDAH LUNAS DAN VALID TANGGAL
          # ===========================================
          $btn_ambil_krs = "
          <form method=post>
            $info_lunas
            <input class=debug name=id_semester value=$id_semester>
            <input class=debug name=id_mhs value=$id_mhs>
            <button class='btn btn-primary btn-block' name=btn_ambil_krs>Ambil KRS</button>
          </form>
          ";
        }else{
          # ===========================================
          # VALID TANGGAL TAPI BELUM LUNAS 
          # ===========================================
          $s = "SELECT * 
          FROM tb_syarat_biaya a 
          JOIN tb_biaya b ON a.id_biaya=b.id 
          WHERE a.event='KRS' 
          AND a.angkatan=$angkatan 
          AND a.id_prodi=$id_prodi 
          AND b.untuk_semester=$semester  
          ";

          $s3 = "SELECT a.*,a.id as id_biaya,
          (
            SELECT 1 FROM tb_bayar 
            WHERE id_biaya=a.id 
            AND id_mhs='$id_mhs' 
          ) sudah_bayar,   
          (
            SELECT persen_biaya FROM tb_syarat_biaya 
            WHERE id_biaya=a.id 
            AND event='KRS' 
            AND angkatan=$angkatan 
            AND id_prodi=$id_prodi 
          ) persen_biaya,   
          (
            SELECT nominal FROM tb_biaya_angkatan 
            WHERE id_biaya=a.id 
            AND angkatan=$angkatan 
            AND id_prodi=$id_prodi 
          ) nominal   
          FROM tb_biaya a 
          WHERE a.untuk_semester=$semester";
          $q3 = mysqli_query($cn,$s3) or die(mysqli_error($cn));
          $div_biaya = '';
          $k=0;
          while ($d3=mysqli_fetch_assoc($q3)) {
            $k++;
            $id_biaya = $d3['id_biaya'];
            $persen_biaya = $d3['persen_biaya'] ?? 100;
            $nominal = $d3['nominal'] ?? $d3['nominal_default'];
            $nominal_show = number_format($nominal,0);
            $minimal_bayar = $nominal * $persen_biaya / 100;
            $minimal_bayar_show = number_format($minimal_bayar,0);

            if($d3['sudah_bayar']){
              $s4 = "SELECT 
              a.*,
              a.nominal as nominal_bayar,
              (SELECT nama FROM tb_user WHERE id=a.verif_by) verifikator 

              FROM tb_bayar a 
              WHERE a.id_biaya=$id_biaya and a.id_mhs=$id_mhs
              ";
              echo $s4;
              $q4 = mysqli_query($cn,$s4) or die(mysqli_error($cn));
              if(mysqli_num_rows($q4)==0) die('Data pembayaran tidak ditemukan.');
              $d4 = mysqli_fetch_assoc($q4);

              $nominal_bayar = $d4['nominal_bayar'];
              $catatan = $d4['catatan'];
              $verif_by = $d4['verif_by'];
              $verif_date = $d4['verif_date'];
              $verif_status = $d4['verif_status'];
              $alasan_reject = $d4['alasan_reject'];
              $verifikator = $d4['verifikator'];

              $pembayaran_anda = number_format($nominal_bayar,0).',-';
              if($nominal_bayar==$nominal){
                if($verif_status){
                  $lunas_info =  "<span class=green>Lunas</span>";
                }else{
                  $lunas_info =  "<span class=darkred>Belum diverifikasi</span>";
                }
                
              }else{
                if($verif_status){
                  $lunas_info =  "<span class=darkred>Bayar Sebagian</span>";
                }else{
                  $lunas_info =  "<span class=darkred>Belum diverifikasi</span>";
                }

              }
            }else{
              $nominal_bayar = '';
              $catatan = '';
              $verif_by = '';
              $verif_date = '';
              $verif_status = '';
              $alasan_reject = '';
              $verifikator = '';

              $pembayaran_anda = '0,-';
              $lunas_info = "<span class=red>Belum melakukan pembayaran</span>";
            }

            $div_biaya .= "
            <div class=grid_biaya>
              <div class='darkblue mtb mt2 pt1'>$k. </div><div class='darkblue mtb mt2 pt1'>$d3[nama]<div class='kecil consolas'>Rp $nominal_show</div></div>
              <div>&nbsp;</div><div class='kecil miring mt1 abu'>Minimal bayar: $persen_biaya% <div class='kecil miring consolas abu'>(Rp $minimal_bayar_show)</div></div>

              <div>&nbsp;</div>
              <div class='kecil darkblue mt1'>
                Pembayaran Anda: <span class=consolas>Rp $pembayaran_anda</span> 
                <div class='kecil'>$lunas_info</div>
              </div>
            </div>
            ";
          }
          

          $info_lunas = "
          <div class='wadah gradasi-merah'>
            <h5>Persyaratan Keuangan</h5>
            $div_biaya 
            <div class='mtb mt2 pt1 mb1'>Belum bisa KRS. Masih ada persyaratan keuangan yang harus Anda penuhi.</div>
            <a class='btn btn-primary btn-sm' href=?pembayaran&untuk_semester=$semester>Cek Pembayaran Semester $semester</a>
          </div>";
          $btn_ambil_krs = "$info_lunas<button class='btn btn-danger btn-block' disabled>Ambil KRS</button>";
        }
      }else{
        $btn_ambil_krs = '';
      }
  
      $pilihan_mk.= "
      <div class='wadah gradasi-hijau'>
        <h4 class='tebal darkblue'>Pilihan MK</h4> 
        <div class='tebal mb2 darkblue'>Semester $semester ~ <a href='?lihat_kurikulum&id_kurikulum=$id_kurikulum' target=_blank>Kurikulum $jenjang-$d2[singkatan_prodi]-$angkatan</a></div>
        $tb_mk
      </div>
      $btn_ambil_krs
      ";
    }
  }
  $krs = "
  <div class='wadah bg-white'>
    <a href='?ambil_krs'><div class='tebal mb1'>Semester $d[nomor] ~ $d[angkatan]-$d[jenjang]</div></a>
    $durasi_krs 
    $link
    $info_krs
    $info_mk
    $pilihan_mk
  </div>
  ";
}

?>
<style>.grid_biaya{display:grid; grid-template-columns:20px auto;}.mtb{border-top:solid 6px #ccf;}</style>
<section id="" class="" data-aos="fade-up">
  <div class="container">

    <div class="section-title">
      <h2><?=$judul?></h2>
      <p><?=$sub_judul?></p>
    </div>

    <?=$krs?>


  </div>
</section>