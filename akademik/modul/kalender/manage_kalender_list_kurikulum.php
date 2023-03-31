<?php
$tr='';
$s = "SELECT 
id_prodi, 
id as id_kurikulum, 
nama as nama_kurikulum 
FROM tb_kurikulum WHERE id_kalender=$id_kalender ";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$arr_id_prodi = [];
$i=0;
while ($d=mysqli_fetch_assoc($q)) {
  $arr_id_prodi[$i] = $d['id_prodi']; $i++;
  $tr .= "
  <tr>
    <td>$i</td>
    <td>$d[nama_kurikulum]</td>
    <td>
      <a href='?manage_kurikulum&id_kurikulum=$d[id_kurikulum]' class='btn btn-primary btn-sm'>Manage MK Kurikulum</a>
    </td>
  </tr>
  ";
}

echo $tr=='' ? '' : "<div class=wadah><h3>Kurikulum pada Kalender ini:</h3><table class=table>$tr</table></div>";