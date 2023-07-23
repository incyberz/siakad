<h1>Manage Pembayaran</h1>
<p>Berikut adalah pembayaran yang harus Anda verifikasi:</p>
<style>.bukti_bayar{
  display:block;
  margin: 5px 0 10px 0;
  /* max-width: 150px; */
  max-height: 150px;
  border: solid 3px blue;
  border-radius: 5px;
}th{text-align:left}</style>
<?php
include '../include/include_rid_prodi.php';
$img_login_as = '<img src="../assets/img/icons/login_as.png" height=20px>';

$s = "SELECT 
a.*, 
a.nominal as nominal_bayar,
b.nama as nama_pembayar, 
b.nim, 
c.nominal_default, 
c.nama as nama_biaya,
b.angkatan, 
b.id_prodi, 
(SELECT nominal FROM tb_biaya_angkatan WHERE angkatan=b.angkatan and id_prodi=b.id_prodi and id_biaya=c.id) nominal_angkatan,
a.tanggal as tanggal_bayar 
FROM tb_bayar a 
JOIN tb_mhs b ON a.id_mhs=b.id 
JOIN tb_biaya c ON a.id_biaya=c.id  
WHERE verif_status is null 
";

echo "<pre class=debug>$s</pre>";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
$tr = '';
$i=0;
while ($d=mysqli_fetch_assoc($q)) {
  $i++;

  // $d['nominal_angkatan'] = ''; //zzz debug
  $prodi = $rprodi[$d['id_prodi']];
  $nominal_tagihan = $d['nominal_angkatan']=='' ? $d['nominal_default'] : $d['nominal_angkatan'];
  $nominal_angkatan_info = $d['nominal_angkatan']=='' ? "<span class=red>Perhatian! Masih memakai nominal default.</span>" : "<span class='green miring'>Nominal tagihan sudah sesuai dengan angkatan $d[angkatan] prodi $prodi.</span>";
  $nominal_tagihan_show = number_format($nominal_tagihan,0);
  $nominal_bayar_show = number_format($d['nominal_bayar'],0);

  $src_img = "../uploads/bukti_bayar/$d[id].jpg";
  // $img_bukti_bayar = "<img src='$src_img' class='bukti_bayar'>";
  $img_bukti_bayar = "<a class='link_bukti hideit' id=link_bukti__$d[id] href='$src_img' target=_blank>img_bukti_bayar::$d[id]</a>";
  $sisa_tagihan = $nominal_tagihan - $d['nominal_bayar'];
  $sisa_tagihan_show = number_format($sisa_tagihan,0);
  $sisa_tagihan_show = $sisa_tagihan==0 ? "<span class=green>$sisa_tagihan_show (lunas)</span>" : "<span class=red>$sisa_tagihan_show (belum lunas)</span>" ;

  $tr.= "
  <tr>
    <td>$i</td>
    <td>
      <div class=darkblue>
        $d[nama_pembayar] 
        <a href='?login_as&nim=$d[nim]' target=_blank>$img_login_as</a> 
      </div>
      <div class='kecil miring'>~ $d[tanggal_bayar]</div>
      <div class='kecil miring'>~ angkatan $d[angkatan]</div>
      <div class='kecil miring'>~ prodi $prodi</div>
    </td>
    <td>
      <span class='pointer darkblue show_bukti btn_aksi kecil' id=show_bukti__$d[id]>Show Bukti Bayar</span> 
      $img_bukti_bayar
      <span class=debug>src_img: <span id=src_img__$d[id]>$src_img</span></span>
    </td>
    <td class='kecil'>
      <div class=darkblue style=font-size:150%>$d[nama_biaya]</div>
      <div>Nominal Tagihan: <span class=darkblue>$nominal_tagihan_show</span></div>
      <div>$nominal_angkatan_info</div>
      <div><span class='biru tebal'>Nominal Bayar: <span style=font-size:150%>$nominal_bayar_show</span></span></div>
      <div>Sisa tagihan: $sisa_tagihan_show</div>
    </td>
    <td id=td_aksi__$d[id]>
      <button class='btn btn-success btn-sm mb1 mt1 btn_aksi' id=verif__$d[id]>Verifikasi</button>
      <button class='btn btn-danger btn-sm mb1 mt1 btn_aksi' id=reject__$d[id]>Reject</button>
    </td>
  </tr>
  ";
}

$thead = "
<thead>
  <th>No</th>
  <th>Dari</th>
  <th>Bukti</th>
  <th class=darkblue>Info Nominal</th>
  <th>Aksi</th>
</thead>
";

$table = $tr=='' ? div_alert('info',"Belum ada data pembayaran yang perlu diverifikasi | <a href='?history_pembayaran'>History Pembayaran</a>") 
: "<table class=table>$thead$tr</table>";

echo $table;

?>



<script>
  $(function(){
    $('.btn_aksi').click(function(){
      let tid = $(this).prop('id');
      let rid = tid.split('__');
      let aksi = rid[0];
      let id = rid[1];
      // console.log(aksi,id);

      if(aksi=='show_bukti'){
        $('.show_bukti').show();
        $('.link_bukti').hide();
        let src_img = $('#src_img__'+id).text();
        $('#link_bukti__'+id).html(`<img src='${src_img}' class=bukti_bayar>`);
        $('#link_bukti__'+id).slideDown();
        
        
        $(this).hide();
      }else if(aksi=='verif' || aksi=='reject'){
        let verif_status = aksi=='verif' ? 1 : -1;
        let alasan_reject = '';
        if(aksi=='verif'){
          let y = confirm('Apakah Bukti Bayar sudah sesuai valid?\n\nCek kembali Jenis Biaya, Nominal Tagihan, dan Nominal bayarnya! Jika sudah yakin tekan OK.');
          if(!y) return;
        }else if(aksi=='reject'){
          alasan_reject = prompt('Alasan reject:');
          if(!alasan_reject) return;
          alasan_reject = alasan_reject.trim();
          if(alasan_reject.length==0) return;
        }

        let link_ajax = `ajax_akademik/ajax_verif_bukti_bayar.php?aksi=${aksi}&verif_status=${verif_status}&alasan_reject=${alasan_reject}&id_bayar=${id}`;
        console.log(link_ajax);

        $.ajax({
          url:link_ajax,
          success:function(a){
            // alert(a);
            if(a.trim()=='sukses'){
              if(aksi=='verif'){
                $('#td_aksi__'+id).html('<span class="green">Verifikasi sukses</span>');
              }else if(aksi=='reject'){
                $('#td_aksi__'+id).html('<span class="green"><span class=red>Reject</span> sukses dg alasan: <i class=darkred>'+alasan_reject+'</i></span>');
              }else{
                alert('Perhatian! Sukses AJAX tanpa handler aksi apapun.');
              }
            }
          }
        })

      }else{
        alert(`Aksi : ${aksi} belum terdapat handler.`)
        return;
      }
    })
  })
</script>