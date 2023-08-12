<h1>Super Delete Prodi</h1>
<?php
$id_prodi = $_GET['id_prodi'] ?? '';
include 'include/include_rid_prodi.php';
if($id_prodi==''){
  foreach ($rid_prodi as $key => $id_prodi) {
    echo " <a class='btn btn-info' href='?super_delete_prodi&id_prodi=$id_prodi'>$rprodi[$id_prodi]</a>";
  }
  exit;
}

$prodi = $rprodi[$id_prodi];
$nama_prodi = $rnama_prodi[$id_prodi];
echo "Prodi: $nama_prodi";

$rsub = [
  'mhs',
  'dosen',
  'pegawai',
  'konsentrasi',
  'kurikulum',
  'mk_manual',
  'krs_manual',
  'kelas',
  'biaya_angkatan',
];


foreach ($rsub as $key => $tb) {
  if($tb=='mhs'){
    $s = "SELECT id,nim FROM tb_mhs WHERE id_prodi=$id_prodi";
    echo "<div>$s</div>";
    $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
    while ($d=mysqli_fetch_assoc($q)) {
      $tb2 = 'tb_kelas_ta_detail';
      $s2 = "DELETE FROM $tb2 WHERE id_mhs=$d[id]";
      echo "<div>$s2</div>";
      $q2 = mysqli_query($cn,$s2) or die(mysqli_error($cn));
      echo "<div class='green mb2'>Delete $tb2 success.</div>";


      $tb2 = 'tb_nilai_manual';
      $s2 = "DELETE FROM $tb2 WHERE nim='$d[nim]'";
      echo "<div>$s2</div>";
      $q2 = mysqli_query($cn,$s2) or die(mysqli_error($cn));
      echo "<div class='green mb2'>Delete $tb2 success.</div>";
    }
  }
  
  if($tb=='kurikulum'){
    $s = "SELECT id FROM tb_$tb WHERE id_prodi=$id_prodi";
    echo "<div>$s</div>";
    $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
    while ($d=mysqli_fetch_assoc($q)) {
      echo "<div class=blue>Processing $tb with id: $d[id]</div>";
      $tb2 = 'kurikulum_mk';

      // delete jadwal where id_kurikulum_mk
      $s2 = "SELECT id as id_kurikulum_mk FROM tb_kurikulum_mk WHERE id_kurikulum=$d[id] ";
      echo "<div class=red>SQL SELECT: $s2</div>";
      $q2 = mysqli_query($cn,$s2) or die(mysqli_error($cn));
      while ($d2=mysqli_fetch_assoc($q2)) {
        $id_kurikulum_mk = $d2['id_kurikulum_mk'];
        echo "<div class='biru miring'>id_kurikulum_mk: $d2[id_kurikulum_mk]</div>";
        

        // delete sesi kuliah
        $s3 = "SELECT id as id_jadwal FROM tb_jadwal WHERE id_kurikulum_mk=$id_kurikulum_mk";
        echo "<div class='biru miring kecil'>SQL3: $s3</div>";
        $q3 = mysqli_query($cn,$s3) or die(mysqli_error($cn));
        while ($d3=mysqli_fetch_assoc($q3)) {
          $id_jadwal = $d3['id_jadwal'];
          echo "<div class='kecil pink'>id_jadwal: $id_jadwal</div>";


          // delete assign ruang
          $s4 = "SELECT id as id_sesi FROM tb_sesi WHERE id_jadwal=$id_jadwal";
          echo "<div class='darkred miring kecil'>SQL-SELECT4: $s4</div>";
          $q4 = mysqli_query($cn,$s4) or die(mysqli_error($cn));
          while ($d4=mysqli_fetch_assoc($q4)) {
            $id_sesi = $d4['id_sesi'];
            echo "<div class='kecil darkred'>id_sesi: $id_sesi</div>";

            $s5 = "DELETE FROM tb_assign_ruang WHERE id_sesi=$id_sesi";
            echo "<div class='darkblue kecil miring'>SQL5: $s5</div>";
            $q5 = mysqli_query($cn,$s5) or die(mysqli_error($cn));
            echo "<div class='green mb2 kecil miring'>Delete assign ruang success.</div>";


          }

          $s4 = "DELETE FROM tb_sesi WHERE id_jadwal=$id_jadwal";
          echo "<div class='pink kecil miring'>SQL4: $s4</div>";
          $q4 = mysqli_query($cn,$s4) or die(mysqli_error($cn));
          echo "<div class='green mb2 kecil miring'>Delete sesi kuliah success.</div>";
        }



        $s3 = "DELETE FROM tb_jadwal WHERE id_kurikulum_mk=$id_kurikulum_mk";
        echo "<div class='red kecil miring'>SQL3: $s3</div>";
        $q3 = mysqli_query($cn,$s3) or die(mysqli_error($cn));
        echo "<div class='green mb2 kecil miring'>Delete jadwal success.</div>";

        // delete kelas peserta
        $s3 = "DELETE FROM tb_kelas_peserta WHERE id_kurikulum_mk=$id_kurikulum_mk";
        echo "<div class='red kecil miring'>SQL3: $s3</div>";
        $q3 = mysqli_query($cn,$s3) or die(mysqli_error($cn));
        echo "<div class='green mb2 kecil miring'>Delete kelas_peserta success.</div>";
      }





      $s2 = "DELETE FROM tb_$tb2 WHERE id_$tb=$d[id]";
      echo "<div>$s2</div>";
      $q2 = mysqli_query($cn,$s2) or die(mysqli_error($cn));
      echo "<div class='green mb2'>Delete $tb2 success.</div>";


    }
  }


  if($tb=='kelas'){
    $s = "SELECT kelas FROM tb_kelas WHERE id_prodi=$id_prodi";
    echo "<div>$s</div>";
    $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
    while ($d=mysqli_fetch_assoc($q)) {
      $kelas = $d['kelas'];
      echo "<div>SQL2: kelas: $kelas</div>";

      // delete kelas angkatan detail
      $s2 = "SELECT id as id_kelas_ta FROM tb_kelas_ta WHERE kelas='$kelas' ";
      echo "<div class=red>SQL SELECT: $s2</div>";
      $q2 = mysqli_query($cn,$s2) or die(mysqli_error($cn));
      while ($d2=mysqli_fetch_assoc($q2)) {
        $id_kelas_ta = $d2['id_kelas_ta'];
        echo "<div>SQL3: id_kelas_ta: $id_kelas_ta</div>";

        $s3 = "DELETE FROM tb_kelas_ta_detail WHERE id_kelas_ta=$id_kelas_ta";
        echo "<div class='red kecil miring'>SQL3: $s3</div>";
        $q3 = mysqli_query($cn,$s3) or die(mysqli_error($cn));
        echo "<div class='green mb2 kecil miring'>Delete kelas_angkatan_detail success.</div>";
      }


      $s2 = "DELETE FROM tb_kelas_ta WHERE kelas='$kelas'";
      echo "<div>$s2</div>";
      $q2 = mysqli_query($cn,$s2) or die(mysqli_error($cn));
      echo "<div class='green mb2'>Delete kelas_angkatan success.</div>";

    }
  }
  
  $col = $tb=='dosen' ? 'homebase' : 'id_prodi';
  $s = "DELETE FROM tb_$tb WHERE $col=$id_prodi";
  echo "<div>$s</div>";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  echo "<div class='green mb2'>Delete $tb success.</div>";

}

$s = "DELETE FROM tb_prodi WHERE id=$id_prodi";
echo "<div>$s</div>";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
echo "<div class='green mb2'>Delete tb_prodi success.</div>";
