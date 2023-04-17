<?php
$s = "SELECT 
a.id as id_mhs,
a.nim,
a.nama as nama_mhs
FROM tb_mhs a 
JOIN tb_kelas_angkatan_detail b ON a.id=b.id_mhs 
JOIN tb_kelas_angkatan c ON c.id=b.id_kelas_angkatan 
WHERE c.kelas='$kelas'
ORDER BY a.nama 
";
// echo "<pre>$s</pre>";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
if(mysqli_num_rows($q)==0) die(div_alert('danger',"Jadwal ini belum punya Sesi Kuliah. | <a href='?manage_sesi&id_jadwal=$id_jadwal' target=_blank>Manage Sesi</a>"));

$thead = "
  <thead>
    <th class='text-left'>No</th>
    <th class='text-left'>NIM</th>
    <th class='text-left'>Nama</th>
    <th class='text-left'>Kehadiran</th>
  </thead>
";
$tr = '';
$jumlah_mhs=0;
while ($d=mysqli_fetch_assoc($q)) {
  $jumlah_mhs++;
  $d['status_presensi']=''; //zzz

  $btn_active_hadir = $d['status_presensi']=='h' ? 'btn_active' : '';
  $btn_active_s = $d['status_presensi']=='s' ? 'btn_active' : '';
  $btn_active_i = $d['status_presensi']=='i' ? 'btn_active' : '';
  $btn_active_a = $d['status_presensi']=='a' ? 'btn_active' : '';
  $btn_active_null = $d['status_presensi']=='' ? 'btn_active' : '';

  $btn_set_hadir = "<button class='btn btn-info btn-sm btn_status_presensi $btn_active_hadir' id=status__h>Hadir</button>";
  $btn_s = "<button class='btn btn-warning btn-sm btn_status_presensi $btn_active_s' id=status__s>S</button>";
  $btn_i = "<button class='btn btn-warning btn-sm btn_status_presensi $btn_active_i' id=status__i>I</button>";
  $btn_a = "<button class='btn btn-danger btn-sm btn_status_presensi $btn_active_a' id=status__a>A</button>";
  $btn_null = "<button class='btn btn-danger btn-sm btn_status_presensi $btn_active_null' id=status__null>Null</button>";


  $tr .= "
  <tr>
    <td>$jumlah_mhs</td>
    <td>$d[nim]</td>
    <td>$d[nama_mhs]</td>
    <td>
      $btn_set_hadir
      $btn_s
      $btn_i
      $btn_a
      $btn_null
    </td>
  </tr>
  ";
}
echo "<div class=wadah><table class=table>$thead$tr</table></div>";
