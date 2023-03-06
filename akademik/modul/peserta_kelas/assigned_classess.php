<?php
$s = "SELECT 
a.kelas,
e.nama as nama_dosen,  
b.id as id_peserta_kelas   
from tb_kelas a 
join tb_peserta_kelas b on a.kelas=b.kelas 
join tb_kurikulum_mk c on c.id=b.id_kurikulum_mk 
join tb_jadwal d on d.id_kurikulum_mk=c.id 
join tb_dosen e on e.id=b.id_dosen 
where d.id=$id_jadwal";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));

$thead = '<thead>
  <th class="text-left">No</th>
  <th class="text-left">Peserta Kelas</th>
  <th class="text-left">Pengampu</th>
  <th class="text-left">Aksi</th>
</thead>';
$tr = '';
$i = 0;
while ($d=mysqli_fetch_assoc($q)) {
  $i++;
  $tr .= "<tr id=tr__$d[id_peserta_kelas]>
    <td>$i</td>
    <td id=$d[kelas]>$d[kelas]</td>
    <td id=$d[nama_dosen]>$d[nama_dosen]</td>
    <td>
      <a class='btn btn-info btn-sm' href='?master&p=peserta_kelas&aksi=update&id=$d[id_peserta_kelas]' target='_blank'>Edit</a>
      <button class='btn btn-danger btn-sm btn_aksi' id='drop__$d[id_peserta_kelas]'>Drop</button>
    </td>
  </tr>";
}
$tb_ascl = $tr=='' ? "<div class='alert alert-danger'>Belum ada peserta kelas</div>" : "<div class=wadah><table class='table table-striped table-hover'>$thead$tr</table></div>";









?>
<p>Berikut adalah kelas-kelas yang sudah dimasukan (assigned classess).</p>
<?=$tb_ascl?>















<script>
  $(function(){
    $(".btn_aksi").click(function(){
      let tid = $(this).prop('id');
      let rid = tid.split('__');
      let aksi = rid[0];
      let id_peserta_kelas = rid[1];
      let link_ajax;

      if(aksi=='drop'){
        link_ajax = `ajax_akademik/ajax_drop_peserta_kelas.php?id_peserta_kelas=${id_peserta_kelas}`;
        $.ajax({
          url: link_ajax,
          success: function(a){
            if(a.trim()=='sukses'){
              $("#tr__"+id_peserta_kelas).fadeOut();
            }else{
              alert(a);
            }
          }
        })

      }
    })
  })
</script>