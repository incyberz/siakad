<h1>Super Delete Kalender</h1>
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
$id_kalender = $_GET['id_kalender'] ?? '';
include 'include/include_rid_kalender.php';
if($id_kalender==''){
  foreach ($rid_kalender as $key => $id_kalender) {
    echo " <a class='btn btn-info' href='?super_delete_kalender&id_kalender=$id_kalender'>$id_kalender</a>";
  }
  exit;
}

echo "<div class=wadah>Angkatan: $id_kalender</div>";

$rsub = [
  'biaya_kalender',
  'kalender',
  'kelas',
  'krs_manual',
  'mk_manual',
  'mhs'
];


foreach ($rsub as $key => $tb) {
  echo "<hr><div class='lv1 gradasi-hijau'>For : $tb</div>";

  if($tb=='kalender'){
    $s = "SELECT id as id_$tb FROM tb_$tb WHERE id_kalender=$id_kalender";
    echo "<div class=lv1>$s</div>";
    $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
    while ($d=mysqli_fetch_assoc($q)) {
      $id_kalender = $d['id_kalender'];
      echo "<div class=blue>Processing $tb with id_kalender: $id_kalender</div>";

      $s2 = "DELETE FROM tb_kurikulum WHERE id_kalender=$id_kalender";
      echo "<div class=lv2>$s2</div>";
      $q2 = mysqli_query($cn,$s2) or die(mysqli_error($cn));
      echo "<div class='green mb2'>Delete kurikulum success.</div>";

      $s2 = "DELETE FROM tb_semester WHERE id_kalender=$id_kalender";
      echo "<div class=lv2>$s2</div>";
      $q2 = mysqli_query($cn,$s2) or die(mysqli_error($cn));
      echo "<div class='green mb2'>Delete semester success.</div>";

      
    }
  }

  $s = "DELETE FROM tb_$tb WHERE id_kalender=$id_kalender";
  echo "<div class=lv1>$s</div>";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  echo "<div class='green mb2'>Delete $tb success.</div>";

}

$s = "DELETE FROM tb_kalender WHERE id_kalender=$id_kalender";
echo "<div class=lv1>$s</div>";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
echo "<div class='green mb2'>Delete tb_kalender success.</div>";
