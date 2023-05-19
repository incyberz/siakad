<?php
$post_nim = isset($_POST['nim']) ? $_POST['nim'] : die(div_alert('danger','Page ini tidak bisa diakses secara langsung. Silahkan menuju <a href="?khs">Menu KHS</a>'));
$dmks = isset($_POST['dmks']) ? $_POST['dmks'] : die(div_alert('danger','Belum ada Data KHS. Silahkan menuju <a href="?khs">Menu KHS</a>'));

echo "dmks: $dmks";

# ====================================================
# SPLIT IPK
# ====================================================
$rd = explode('||',$dmks);
$ipk = $rd[1];
$d_smts = $rd[0];
echo "<h1>IPK : $ipk</h1>";

# ====================================================
# SPLIT SETIAP SEMESTER
# ====================================================
$rd = explode('<hr>',$d_smts);
$jumlah_smt = count($rd);
for ($i=0; $i < $jumlah_smt ; $i++) { 

  # ====================================================
  # SPLIT IP TIAP SEMESTER
  # ====================================================
  $rdd = explode('|',$rd[$i]);
  $ip = isset($rdd[1]) ? $rdd[1] : 0;
  echo "<h1>IP : $ip</h1>";

  echo '<table class=table>'; 
  $rows = explode('<br>',$rdd[0]);
  for ($j=0; $j < count($rows); $j++) {
    echo '<tr>'; 
    $td = explode(';',$rows[$j]);
    for ($k=0; $k < count($td); $k++) { 
      echo "<td>$td[$k]</td>";
    }
    echo '</tr>'; 
  }
  echo '</table>'; 
}