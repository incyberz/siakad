<?php
$s = "SELECT 
b.id as id_kelas_angkatan,
b.kelas,
b.tahun_ajar,
a.id as id_kelas_peserta,
(SELECT count(1) FROM tb_kelas_angkatan_detail WHERE id_kelas_angkatan=b.id) as jumlah_mhs    
FROM tb_kelas_peserta a 
JOIN tb_kelas_angkatan b on a.id_kelas_angkatan=b.id  
JOIN tb_kurikulum_mk c on c.id=a.id_kurikulum_mk 
JOIN tb_jadwal d on d.id_kurikulum_mk=c.id 
JOIN tb_dosen e on e.id=d.id_dosen 
where d.id=$id_jadwal";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));

$thead = '<thead>
  <th class="text-left">No</th>
  <th class="text-left">Kelas Peserta</th>
  <th class="text-left">Tahun Ajar</th>
  <th class="text-left">Aksi</th>
</thead>';
$tr = '';
$i = 0;
while ($d=mysqli_fetch_assoc($q)) {
  $i++;
  $red = $d['jumlah_mhs']==0 ? 'red':'';
  $tr .= "<tr id=tr__$d[id_kelas_peserta] class=$red>
    <td>$i</td>
    <td><span id=$d[kelas]>$d[kelas]</span> | $d[jumlah_mhs] | <a href='?manage_peserta&id_kelas_angkatan=$d[id_kelas_angkatan]' target=_blank>Manage</a></td>
    <td id=$d[tahun_ajar]>$d[tahun_ajar]</td>
    <td>
      <button class='btn btn-danger btn-sm btn_aksi' id='drop__$d[id_kelas_peserta]'>Drop</button>
    </td>
  </tr>";
}
$tb_ascl = $tr=='' ? "<div class='alert alert-danger'>Belum ada kelas peserta</div>" : "<div class=wadah><table class='table table-striped table-hover'>$thead$tr</table></div>";









?>
<div class='subsistem'>Assigned Classess</div>
<p>Berikut adalah kelas-kelas yang sudah dimasukan (assigned classess).</p>
<span class=debug><?=$s?></span>
<?=$tb_ascl?>















<script>
  $(function(){
    $(".btn_aksi").click(function(){
      let tid = $(this).prop('id');
      let rid = tid.split('__');
      let aksi = rid[0];
      let id_kelas_peserta = rid[1];
      let link_ajax;

      if(aksi=='drop'){
        link_ajax = `ajax_akademik/ajax_drop_kelas_peserta.php?id_kelas_peserta=${id_kelas_peserta}`;
        $.ajax({
          url: link_ajax,
          success: function(a){
            console.log(a,link_ajax);
            if(a.trim()=='sukses'){
              $("#tr__"+id_kelas_peserta).fadeOut();
            }else{
              alert(a);
            }
          }
        })

      }
    })
  })
</script>