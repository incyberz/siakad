<h1>Super Delete Kurikulum</h1>
<style>.lv1,.lv2,.lv3,.lv4,.lv5{font-family:consolas}
.lv1{color:blue; font-size:24px}
.lv2{color:red; font-size:20px}
.lv3{color:purple; font-size:16px}
.lv4{color:darkblue; font-size:12px}
.lv5{color:darkred; font-size:10px}
</style>
<?php
# ============================================
# UNFINISHED CODE
# ============================================
$id_kurikulum = $_GET['id_kurikulum'] ?? '';
include 'include/include_rid_kurikulum.php';
if($id_kurikulum==''){
  foreach ($rid_kurikulum as $key => $id_kurikulum) {
    echo " <a class='btn btn-info mt1' href='?super_delete_kurikulum&id_kurikulum=$id_kurikulum'>$rnama_kurikulum[$id_kurikulum]</a>";
  }
  exit;
}

echo "<div class=wadah>Kurikulum: $id_kurikulum</div>";

$rsub = [
  'kurikulum_mk'
];


foreach ($rsub as $key => $tb) {
  echo "<hr><div class='lv1 gradasi-hijau'>For : $tb</div>";

  // delete sub tb_jadwal
  $s = "SELECT id as id_kurikulum_mk FROM tb_kurikulum_mk WHERE id_kurikulum=$id_kurikulum";
  echo "<div class=lv1>$s</div>";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  while ($d=mysqli_fetch_assoc($q)) {
    $id_kurikulum_mk = $d['id_kurikulum_mk'];

    // delete sub tb_sesi
    $s2 = "SELECT id as id_jadwal FROM tb_jadwal WHERE id_kurikulum_mk=$id_kurikulum_mk";
    echo "<div class=lv2>$s2</div>";
    $q2 = mysqli_query($cn,$s2) or die(mysqli_error($cn));
    while ($d2=mysqli_fetch_assoc($q2)) {
      $id_jadwal = $d2['id_jadwal'];

      // delete sub tb_assign_ruang
      $s3 = "SELECT id as id_sesi FROM tb_sesi WHERE id_jadwal=$id_jadwal";
      echo "<div class=lv3>$s3</div>";
      $q3 = mysqli_query($cn,$s3) or die(mysqli_error($cn));
      while ($d3=mysqli_fetch_assoc($q3)) {
        $id_sesi = $d3['id_sesi'];

        // delete sub tb_assign_ruang


        $s4 = "DELETE FROM tb_assign_ruang WHERE id_sesi=$id_sesi";
        echo "<div class=lv4>$s4</div>";
        $q4 = mysqli_query($cn,$s4) or die(mysqli_error($cn));
        echo "<div class='green mb2'>Delete tb_assign_ruang success.</div>";
      }



      $s3 = "DELETE FROM tb_sesi WHERE id_jadwal=$id_jadwal";
      echo "<div class=lv3>$s3</div>";
      $q3 = mysqli_query($cn,$s3) or die(mysqli_error($cn));
      echo "<div class='green mb2'>Delete sesi_kuliah success.</div>";
    }


    $s2 = "DELETE FROM tb_jadwal WHERE id_kurikulum_mk=$id_kurikulum_mk";
    echo "<div class=lv2>$s2</div>";
    $q2 = mysqli_query($cn,$s2) or die(mysqli_error($cn));
    echo "<div class='green mb2'>Delete jadwal success.</div>";
  }




  $s = "DELETE FROM tb_$tb WHERE id_kurikulum=$id_kurikulum";
  echo "<div class=lv1>$s</div>";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  echo "<div class='green mb2'>Delete $tb success.</div>";

}

$s = "DELETE FROM tb_kurikulum WHERE id=$id_kurikulum";
echo "<div class=lv1>$s</div>";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
echo "<div class='green mb2'>Delete tb_kurikulum success.</div>";
