<h1>MANAGE KURIKULUM</h1>
<style>
  .kurikulum {}
  .semester-ke {font-size:24px !important; color:darkblue !important; margin-bottom:10px}
  
</style>
<?php
$id = isset($_GET['id']) ? $_GET['id'] : '';
if($id<1) die('<script>location.replace("?master&p=kurikulum")</script>');

$s = "DESCRIBE tb_kurikulum";







$jumlah_semester = 8; //zzz

$semesters = '';
for ($i=0; $i < $jumlah_semester; $i++) {
  $j = $i+1; 
  $semesters .= "
  <div class='col-lg-6'>
  <div class=wadah>
  <div class='semester-ke'>Semester $j</div>
  <table class=table>
    <thead>
      <th>No</th>
      <th>Kode</th>
      <th>Mata Kuliah</th>
      <th>SKS</th>
      <th>Prasyarat</th>
    </thead>
    
    <tr>
      <td>1</td>
      <td>MK-001</td>
      <td>Pemrograman Web</td>
      <td>2</td>
      <td>--null--</td>
    </tr>

  </table>
  </div>
  </div>
  ";
}

$kurikulum = $semesters=='' ? 'Belum ada semester' : "<div class='row kurikulum'>$semesters</div>";
echo $semesters;