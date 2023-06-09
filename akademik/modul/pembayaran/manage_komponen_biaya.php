<h1>Manage Komponen Biaya</h1>
<?php
$s = "SELECT a.* FROM tb_biaya a ";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));

$tr_biaya="
<thead>
  <th>No</th>
  <th>Nama Biaya</th>
  <th>Jenis</th>
  <th>Nominal Default</th>
  <th>Keterangan</th>
  <th>Aksi</th>
</thead>
";
$i=0;
$null = '<code class=miring>null</code>';
while ($d=mysqli_fetch_assoc($q)) {
  $i++;

  $jenis = $d['jenis']=='' ? $null : $d['jenis'];
  $ket = $d['ket']=='' ? $null : $d['ket'];
  $nominal_default = $d['nominal_default']=='' ? $null : $d['nominal_default'];
  $id = $d['id'];

  $tr_biaya.="
  <tr>
    <td class=editable id=no__$id>$d[no]<span class=debug>$d[id]</span></td>
    <td class=editable id=nama__$id>$d[nama]</td>
    <td class=editable id=jenis__$id>$jenis</td>
    <td class=editable id=nominal_default__$id>$nominal_default</td>
    <td class=editable id=ket__$id>$ket</td>
    <td class=deletable>Hapus</td>
  </tr>";
}

$div_biaya = '';
$s = "SELECT * FROM tb_angkatan ORDER BY angkatan DESC";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
while ($d=mysqli_fetch_assoc($q)) {
  $div_biaya.="<div class=wadah><a href='?manage_biaya_angkatan&angkatan=$d[angkatan]'>Manage Biaya Angkatan $d[angkatan]</a></div>";
}
?>
<p>Berikut adalah jenis-jenis biaya untuk semua angkatan (tanpa nominal).</p>
<table class="table">
  <?=$tr_biaya?>
</table>

<div class="wadah">
  <h3>Manage Biaya Angkatan</h3>
  <?=$div_biaya?>
</div>











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