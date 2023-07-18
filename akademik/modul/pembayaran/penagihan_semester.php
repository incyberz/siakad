<h1>Penagihan Semester</h1>



<?php
include '../include/include_rprodi.php';

$angkatan = isset($_GET['angkatan']) ? $_GET['angkatan'] : '';
$id_prodi = isset($_GET['id_prodi']) ? $_GET['id_prodi'] : '';
$id_biaya = isset($_GET['id_biaya']) ? $_GET['id_biaya'] : '';
$untuk_semester = isset($_GET['untuk_semester']) ? $_GET['untuk_semester'] : '';

$s = "SELECT a.*, b.id as id_kurikulum, c.nama as nama_prodi 
FROM tb_kalender a 
JOIN tb_kurikulum b ON a.id=b.id_kalender 
JOIN tb_prodi c ON b.id_prodi=c.id 
WHERE a.angkatan =$angkatan 
AND c.id = $id_prodi 
ORDER BY a.angkatan DESC, a.jenjang
";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));

if(mysqli_num_rows($q)==0){
  $tb = div_alert('danger', "Belum ada Kurikulum untuk angkatan $angkatan prodi $rnama_prodi[$id_prodi] pada SIAKAD. Jika ingin manage_penagihan secara manual silahkan menuju Penagihan Semester Manual <hr> <a class='btn btn-primary' href='?penagihan_biaya&angkatan=$angkatan&id_prodi=$id_prodi&id_biaya=$id_biaya'>Penagihan Semester Manual</a>");
}else{
  $tb="
  <p>Berikut adalah Kurikulum Akademik yang masih aktif.</p>
  <table class='table'>
  <thead>
    <th>No</th>
    <th>Angkatan</th>
    <th>Prodi</th>
    <th>Semester</th>
  </thead>
  ";
  $i=0;
  $tnow = strtotime('now');
  while ($d=mysqli_fetch_assoc($q)) {
    $i++;
    $id = $d['id'];
    $nama_prodi = ucwords(strtolower($d['nama_prodi']));
    $semesters = '';

    $s2 = "SELECT * 
    FROM tb_semester WHERE id_kalender=$id 
    ";
    $q2 = mysqli_query($cn,$s2) or die(mysqli_error($cn));
    $td_no_smt = '';
    $td_tgl = '';
    while ($d2=mysqli_fetch_assoc($q2)) {
      $id_semester = $d2['id'];
      $awal = date('d/m/y',strtotime($d2['tanggal_awal']));
      $akhir = date('d/m/y',strtotime($d2['tanggal_akhir']));
      $sudah_masuk_smt = strtotime($d2['tanggal_awal']) <= $tnow ? 1 : 0;
      $sudah_lewat_smt = strtotime($d2['tanggal_akhir']) < $tnow ? 1 : 0;
      $bg = $sudah_masuk_smt ? 'gradasi-hijau' : 'bg-white';
      $bg = $sudah_lewat_smt ? 'gradasi-kuning' : $bg;
      
      $border = ($sudah_masuk_smt and !$sudah_lewat_smt) ? 'style="border: solid 3px blue"' : '';
      
      $input_tagihkan = "
      <div>
      <input id=tagihkan__$id_semester type=checkbox> 
      <label for=tagihkan__$id_semester>tagihkan</label>
      </div>
      ";
      
      $td_no_smt.= "<td class='text-center $bg' $border>$d2[nomor]<span class=debug>$id_semester</span></td>";
      $td_tgl.= "
      <td class='kecil abu text-center $bg' $border>
        <div>$awal</div> 
        <div>s.d</div> 
        <div>$akhir</div>
        <div>$input_tagihkan</div>
      </td>";
    }
    $semesters.="
    <table class='table-bordered' width=100%>
      <tr>$td_no_smt</tr>
      <tr>$td_tgl</tr>
    </table>";


    $tb.="
    <tr>
      <td>$i</td>
      <td>$d[angkatan]</td>
      <td>
        $d[jenjang]-$nama_prodi
        <span class=debug>
          <br>id_kalender: $d[id]
          <br>id_kurikulum: $d[id_kurikulum]
        </span>
      </td>
      <td>$semesters</td>
    </tr>";
  }
  $tb .= '</table>';
}


?>
<?=$tb?>
<!-- <div class='kecil miring abu'>*) Penagihan <code>lampau</code> artinya tidak bisa ditagihkan karena melewati zzz.</div> -->












<script>
  $(function(){
    $(".editable").click(function(){
      let tid = $(this).prop('id');
      let rid = tid.split('__');
      let kolom = rid[0];
      let id = rid[1];
      let isi = $(this).text();

      let isi_baru = prompt(`Data ${kolom} baru:`,isi);
      if(isi_baru===null) return;
      if(isi_baru.trim()==isi) return;

      isi_baru = isi_baru.trim()==='' ? 'NULL' : isi_baru.trim();
      
      // VALIDASI UPDATE DATA
      let kolom_acuan = 'id';
      let link_ajax = `../ajax_global/ajax_global_update.php?tabel=${tabel}&kolom_target=${kolom}&isi_baru=${isi_baru}&acuan=${acuan}&kolom_acuan=${kolom_acuan}`;

      $.ajax({
        url:link_ajax,
        success:function(a){
          if(a.trim()=='sukses'){
            $("#"+kolom+"__"+tabel+"__"+acuan).text(isi_baru)
          }else{
            console.log(a);
            if(a.toLowerCase().search('cannot delete or update a parent row')>0){
              alert('Gagal menghapus data. \n\nData ini dibutuhkan untuk relasi data ke tabel lain.\n\n'+a);
            }else if(a.toLowerCase().search('duplicate entry')>0){
              alert(`Kode ${isi_baru} telah dipakai pada data lain.\n\nSilahkan masukan kode unik lainnya.`)
            }else{
              alert('Gagal menghapus data.');
            }

          }
        }
      })


    });
    
  })
</script>