<h1>Manage Peserta</h1>
<style>th{text-align:left}</style>
<?php
$id_kelas_ta = $_GET['id_kelas_ta'] ?? '';
$id_kurikulum = $_GET['id_kurikulum'] ?? '';
if($id_kelas_ta=='' || $id_kurikulum==''){
  echo div_alert('danger',"<h4>ID Kelas-TA atau ID Kurikulum belum terpilih.</h4> 
  Silahkan ikuti langkah berikut: 
  <ol>
    <li>Masuk <a href='?manage_kelas' target=_blank>Manage Kelas</a></li>
    <li>Pilih salah satu kurikulum</a></li>
    <li>Pilih salah satu manage (Kelas TA)</a></li>
    <li>Pilih salah satu Aksi : Manage Peserta Mhs</li>
  </ol> 
  ");
  exit;
}else{
  echo "<span class=debug>id_kelas_ta:<span id=id_kelas_ta>$id_kelas_ta</span> | id_kurikulum:<span id=id_kurikulum>$id_kurikulum</span> | </span>";
}



# ==============================================================
# GET DATA KELAS TA
# ==============================================================
$tr = '';
$s = "SELECT a.*,b.*,c.singkatan as prodi  
FROM tb_kelas_ta a 
JOIN tb_kelas b ON a.kelas=b.kelas 
JOIN tb_prodi c ON b.id_prodi=c.id  
WHERE a.id='$id_kelas_ta' 
";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
if(mysqli_num_rows($q)==0){
  die('Data kelas-TA tidak ditemukan.');
}else{
  $d=mysqli_fetch_assoc($q);
  $kelas = $d['kelas'];
  $angkatan = $d['angkatan'];
  $id_prodi = $d['id_prodi'];
  $prodi = $d['prodi'];
  $shift = $d['shift'];
  $id_jalur = $d['id_jalur'];
  $tahun_ajar = $d['tahun_ajar'];
  echo "<p>Proses assign Mahasiswa pada Grup Kelas-TA: <a href='?manage_kelas_ta&kelas=$kelas&id_kurikulum=$id_kurikulum'>$kelas ~ TA$tahun_ajar</a>.</p>";
}


# ==============================================================
# GET DATA MHS AKTIF
# ==============================================================
$s = "SELECT a.id as id_mhs, a.*,
(
  SELECT kelas FROM tb_kelas_ta p 
  JOIN tb_kelas_ta_detail q ON q.id_kelas_ta=p.id 
  WHERE q.nim=a.nim 
  AND tahun_ajar=$tahun_ajar 
  ) kelas_ta  
FROM tb_mhs a 
WHERE a.status_mhs=1
AND a.angkatan='$angkatan' 
AND a.id_prodi='$id_prodi' 
AND a.shift='$shift' 
ORDER BY a.nama  
";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
if(mysqli_num_rows($q)==0){
  $div_mhs = div_alert('danger',"Tidak ada mhs kelas $shift angkatan $angkatan prodi $prodi");
}else{
  $i=0;
  $tr = '';
  while ($d=mysqli_fetch_assoc($q)) {
    $i++;
    $id_mhs = $d['id_mhs'];
    $nim = $d['nim'];

    if($d['kelas_ta']==''){
      $td_add = "<td class='pointer blue btn_aksi' id=add__$nim>Add to $kelas</td>";
    }elseif($d['kelas_ta']==$kelas){
      $td_add = "<td class='abu kecil miring'>(on this class)</td>";
    }else{
      $td_add = "<td class='abu'>$d[kelas_ta]</td>";
    }

    $tr .= "
    <tr id=tr_mhs__$nim>
      <td>$d[nama] | $d[nim]</td>
      $td_add
    </tr>";
  }
  $div_mhs = "
  <table class='table table-striped'>
    <thead>
      <th>Mahasiswa</th>
      <th>Kelas / Aksi</th>
    </thead>
    $tr
  </table>";
}

# ==============================================================
# GET DATA PESERTA MHS
# ==============================================================
$s = "SELECT a.id as id_kelas_ta_detail, a.*,
b.nama as nama_mhs, 
b.nim 

FROM tb_kelas_ta_detail a 
JOIN tb_mhs b ON a.nim=b.nim 
WHERE a.id_kelas_ta='$id_kelas_ta' 
ORDER BY b.nama  
";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
if(mysqli_num_rows($q)==0){
  $div_peserta = div_alert('danger','Belum ada Peserta Mhs. Silahkan assign dari tabel sebelah kiri');
}else{
  $i=0;
  $tr = '';
  while ($d=mysqli_fetch_assoc($q)) {
    $i++;
    $id_kelas_ta_detail = $d['id_kelas_ta_detail'];
    $nim = $d['nim'];
    $tr .= "
    <tr id=tr_peserta__$nim>
      <td>$d[nama_mhs] | $d[nim]</td>
      <td class='pointer red btn_aksi' id=drop__$nim>Drop</td>
    </tr>";
  }
  $div_peserta = "
  <table class='table table-striped'>
    <thead>
      <th>Peserta Kelas</th>
      <th>Aksi</th>
    </thead>
    $tr
  </table>";
}



# =======================================================
# FINAL OUTPUT
# =======================================================
echo "
<div class='mygrid'>
  <div class='wadah bg-white'>
    <h4 class='tebal darkblue'>List Mhs Aktif</h4>
    <div class='kecil miring abu mb2 proper'>Prodi $prodi-$angkatan ~ Kelas $shift</div>
    $div_mhs
  </div>
  <div class='wadah bg-white'>
    <h4 class='tebal darkblue'>List Peserta</h4>
    <div class='kecil miring abu mb2 proper'>Pada kelas $kelas ~ TA$tahun_ajar</div>
    $div_peserta
  </div>
</div>
";  
?>
<style>.mygrid{
  display:grid;grid-template-columns:auto auto;grid-gap:10px
}.grid_mhs{
  display:grid;grid-template-columns:auto 30%;grid-gap:10px
}.btn_aksi:hover{
  background: linear-gradient(#fcf,#faf) !important;
}</style>






<script>
  $(function(){
    $('.btn_aksi').click(function(){
      let tid = $(this).prop('id');
      let rid = tid.split('__');
      let mode = rid[0];
      let nim = rid[1];
      let id_kelas_ta = $('#id_kelas_ta').text();
      
      let link_ajax = `ajax_akademik/ajax_assign_peserta_kelas.php?nim=${nim}&id_kelas_ta=${id_kelas_ta}&mode=${mode}`;
      console.log(link_ajax);
      $.ajax({
        url:link_ajax,
        success:function(a){
          // alert(a)
          if(a.trim()=='sukses'){
            if(mode=='add'){
              $('#add__'+nim).html('<span class="green italic kecil">success.</span>');
              $('#add__'+nim).removeClass('btn_aksi');
              $('#add__'+nim).removeClass('pointer');
            }else if(mode=='drop'){
              $('#tr_peserta__'+nim).fadeOut();
            }else{
              alert('Perhatian! AJAX sukses tanpa handler.');
            }
          }else{
            alert(a)
          }
        }
      })
    })
  })
</script>