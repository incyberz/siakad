<h1>Delete Empty MK</h1>
<?php
$s = "SELECT a.id as id_mk, a.*, 
(SELECT count(1) FROM tb_kurikulum_mk WHERE id_mk=a.id) used 
FROM tb_mk a ORDER BY a.nama";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
$i=0;
while ($d=mysqli_fetch_assoc($q)) {
  $i++;
  echo "
  <div class=row>
    <div class=col-lg-1>$i</div>
    <div class=col-lg-5>$d[nama] | $d[kode]</div>
    <div class=col-lg-3>$d[used]</div>
    <div class=col-lg-3>zzz</div>
  </div>
  ";
  if($d['used']==0){
    $s2 = "DELETE FROM tb_mk WHERE id=$d[id_mk]";
    $q2 = mysqli_query($cn,$s2) or die(mysqli_error($cn));
    echo "<span class=red>Deleted $d[nama]</span>";
  }
}