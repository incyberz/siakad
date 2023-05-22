<div class="wadah">
  <h2>List Kelas Angkatan</h2>
  <style>th{text-align:left}</style>
  <p>Silahkan pilih Kelas Angkatan mana yang akan ditambah!</p>
  <?php
  $s = "SELECT 
  a.kelas,
  a.tahun_ajar,
  (SELECT count(1) FROM tb_kelas_angkatan_detail WHERE id_kelas_angkatan=a.id ) as jumlah_mhs    
  FROM tb_kelas_angkatan a 

  WHERE 1  
  ";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));

  $thead = '
  <thead>
    <th>No</th>
    <th>Kelas</th>
    <th>Tahun Ajar</th>
    <th>Jumlah Mhs</th>
    <th>Aksi</th>
  </thead>
  ';
  $tr = '';
  $i=0;
  while ($d = mysqli_fetch_assoc($q)) {
    $i++;
    $tr .= "<tr>
      <td>$i</td>
      <td>$d[kelas]</td>
      <td>$d[tahun_ajar]</td>
      <td>$d[jumlah_mhs] Mhs</td>
      <td>
        <a href='?input_khs&kelas=$d[kelas]' class='btn btn-info btn-sm proper'>Input KHS</a> 
      </td>
    </tr>";
  }

  $tb = $tr=='' ? '<div class="alert alert-info">Kelas tidak ditemukan. | <a href="?manage_kelas">Manage Kelas</a></div>' : "<table class=table>$thead$tr</table>";

  echo $tb;  
  ?>
</div>