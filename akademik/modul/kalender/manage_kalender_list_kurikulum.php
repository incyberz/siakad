<?php
$tr='';
$s = "SELECT 
a.id_prodi, 
a.id as id_kurikulum, 
CONCAT('Kurikulum ',c.jenjang,'-',b.singkatan,'-',c.angkatan) as nama_kurikulum 
FROM tb_kurikulum a 
JOIN tb_prodi b ON a.id_prodi=b.id   
JOIN tb_kalender c ON a.id_kalender=c.id   
WHERE a.id_kalender=$id_kalender ";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$arr_id_prodi = [];
$i=0;
while ($d=mysqli_fetch_assoc($q)) {
  $arr_id_prodi[$i] = $d['id_prodi']; $i++;
  $tr .= "
  <tr>
    <td>$i</td>
    <td>$d[nama_kurikulum] <span class=debug>id:$d[id_kurikulum]</span></td>
    <td>
      <a href='?manage_kurikulum&id_kurikulum=$d[id_kurikulum]' class='btn btn-primary btn-sm'>Manage MK Kurikulum</a>
    </td>
  </tr>
  ";
}

echo $tr=='' ? '' : "<div class=wadah><h3>Kurikulum pada Kalender ini:</h3><table class=table>$tr</table></div>";