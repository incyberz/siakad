<h1>Manage Komponen Biaya</h1>
<?php
$s = "SELECT a.* FROM tb_biaya a ORDER BY a.no";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));

$tr_biaya="
<thead>
  <th>No</th>
  <th>Nama Biaya</th>
  <th>Jenis</th>
  <th>Nominal Default</th>
  <th>Untuk Semester</th>
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
    <td class=editable id=no__$id>$d[no]</td>
    <td class=editable id=nama__$id>$d[nama]</td>
    <td class=editable id=jenis__$id>$jenis</td>
    <td class=editable id=nominal_default__$id>$nominal_default</td>
    <td class=editable id=untuk_semester__$id>$d[untuk_semester]</td>
    <td class=editable id=ket__$id>$ket</td>
    <td class='deletable btn_aksi' id=hapus__$id>Hapus<span class=debug>$d[id]</span></td>
  </tr>";
}

?>
<!-- <p>Berikut adalah jenis-jenis biaya untuk semua angkatan.</p> -->
<p class="kecil miring abu"><code>Nominal Default</code> artinya semua angkatan dan semua prodi mengacu pada nominal tersebut jika belum ada spesifikasi biaya per angkatan dan per prodi.</p>
<div class="text-right mb1"><button class='btn btn-success btn-sm btn_aksi' id=tambah__new>Tambah Komponen Biaya</button></div>
<table class="table">
  <?=$tr_biaya?>
</table>

<div class="wadah">
  <b>Notes</b>:
  <ul class="kecil miring abu">
    <li>
      Urutkan kolom <code>No</code> agar tampilan ke mhs juga terurut!
    </li>
    <li>Dikarenakan biaya tiap angkatan dan tiap prodi <u>dapat berbeda</u>, disarankan Anda melakukan <a href="?manage_biaya_angkatan">Manage Biaya setiap Angkatan</a>.</li>
    <li>Untuk melihat hasil perubahan Anda boleh <code>Login As</code> sebagai Mahasiswa pada <a href="?list_mhs_aktif">List Mahasiswa Aktif</a>, kemudian cek pada Menu Pembayaran</li>
  </ul>
</div>











<script>
  $(function(){
    $(".editable").click(function(){
      let tid = $(this).prop('id');
      let rid = tid.split('__');
      let kolom = rid[0];
      let id_biaya = rid[1];
      let isi = $(this).text();

      let isi_baru = prompt(`Data ${kolom} baru:`,isi);
      if(isi_baru===null) return;
      if(isi_baru.trim()==isi) return;

      isi_baru = isi_baru.trim()==='' ? 'NULL' : isi_baru.trim();
      
      // VALIDASI UPDATE DATA
      let kolom_acuan = 'id';
      let link_ajax = `ajax_akademik/ajax_set_komponen_biaya.php?isi_baru=${isi_baru}&kolom=${kolom}&id_biaya=${id_biaya}`;

      $.ajax({
        url:link_ajax,
        success:function(a){
          if(a.trim()=='sukses'){
            $("#"+tid).text(isi_baru);
            $("#"+tid).addClass('biru tebal');
          }else{
            console.log(a);
            if(a.toLowerCase().search('cannot delete or update a parent row')>0){
              alert('Gagal edit data. \n\nData ini dibutuhkan untuk relasi data ke tabel lain.\n\n'+a);
            }else if(a.toLowerCase().search('duplicate entry')>0){
              alert(`Kode ${isi_baru} telah dipakai pada data lain.\n\nSilahkan masukan kode unik lainnya.`)
            }else{
              alert('Gagal edit data.');
            }

          }
        }
      })


    });

    $(".btn_aksi").click(function(){
      let tid = $(this).prop('id');
      let rid = tid.split('__');
      let aksi = rid[0];
      let id_biaya = rid[1];

      if(aksi=='tambah'){
        let y = confirm('Yakin untuk tambah?\n\nSetelah tekan OK, akan muncul rows baru paling atas (dengan nomor urut 0) yang siap Anda edit.');
        if(!y) return;
      }else if(aksi=='hapus'){
        let y = confirm('Yakin untuk hapus?\n\nPerhatian!! Menghapus komponen biaya mengakibatkan seluruh transaksi yang mengacu kepadanya akan invalid. Pastikan komponen biaya yang ingin dihapus belum ditagihkan ke mahasiswa!');
        if(!y) return;
      }else{
        alert('aksi undefined');
        return;
      }

      let link_ajax = `ajax_akademik/ajax_crud_biaya.php?aksi=${aksi}&id_biaya=${id_biaya}`;
      // alert('ajax time: '+link_ajax);
      $.ajax({
        url: link_ajax,
        success:function(a){
          // alert(a)
          if(a.trim()=='sukses'){
            location.reload();
          }else{
            alert(a);
          }
        }
      })

    });
    
  })
</script>