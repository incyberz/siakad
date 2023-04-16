<?php
$s = "SELECT id,nama FROM tb_prodi WHERE jenjang='$jenjang'";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$option_prodi = '';
while ($dopt=mysqli_fetch_assoc($q)) {
  if(in_array($dopt['id'],$arr_id_prodi)) continue;
  $option_prodi .= "<option value='$dopt[id]'>$dopt[nama]</option>";
}
echo $option_prodi=='' ? div_alert('info',"Semua Prodi sudah terpasang pada Kurikulum.<hr><a href='?master&p=prodi&aksi=tambah' target=_blank>Tambah Prodi</a>") : "

<div class='wadah'>
  <h3>Buat Kurikulum Baru</h3>
  <p>Kalender yang telah lengkap dapat Anda buat menjadi Kurikulum berdasarkan Prodi yang ada.</p>

  <form method='post'>
    <div class='form-group'>
      <div class='debug'>
        id_kalender:
        <input name='id_kalender' id='id_kalender' value='$id_kalender'>

      </div>
    </div>
    <div class='form-group'>
      <select name='id_prodi' id='id_prodi' class='form-control'>
        <option value='0'>-- Pilih Prodi --</option>
        $option_prodi
      </select>

    </div>
    <div class='form-group'>
      <button name='btn_buat_kurikulum' id='btn_buat_kurikulum' disabled class='btn btn-primary btn-block'>Buat Kurikulum Baru</button>
    </div>
  </form>  
</div>


<script>
  $(function(){
    $('#id_prodi').click(function(){
      let val = $(this).val();
      if(val=='0'){
        $('#btn_buat_kurikulum').prop('disabled',true);
        $('#btn_buat_kurikulum').text('Buat Kurikulum Baru');
      }else{
        $('#btn_buat_kurikulum').prop('disabled',false);
        $('#btn_buat_kurikulum').text('Buat Kurikulum Baru untuk Prodi ini.');
      }
    })

  })
</script>

";
?>




