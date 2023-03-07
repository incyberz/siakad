<h1>Manage Peserta Kelas</h1>
<?php
if(isset($_POST['btn_buat_sesi_default'])){
  $id_dosen = $_POST['id_dosen'];
  
  $s = "INSERT INTO tb_sesi_kuliah (
    kelas,
    pertemuan_ke,
    id_dosen,
    nama
    ) VALUES $values";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));

  echo div_alert('success',"Membuat $jumlah_sesi Sesi Kuliah Default berhasil.<hr><a href='?manage_sesi&kelas=$kelas'>Lanjutkan Proses</a>");
  exit;
  
}


$kelas = isset($_GET['kelas']) ? $_GET['kelas'] : '';

if($kelas==''){
  include 'modul/kelas/list_kelas.php';
  exit;
}
echo "<div class=wadah >Kelas : <span id=kelas>$kelas</span> | <a href='?manage_peserta'>Change</a></div>";
$s = "";
// $q = mysqli_query($cn,$s) or die(mysqli_error($cn));















?>
<style>.manage_peserta h3{margin:0 0 15px 0} th{text-align:left}</style>
<div class="row manage_peserta">
  <div class="col-lg-6">
    <div class="wadah">
      <h3>List Mahasiswa</h3>

      <style>.blok_filter{display:flex; flex-wrap:wrap; gap:15px}</style>
      <div class='blok_filter mb2'>
        <div>Search:</div>
        <div>
          <input id=keyword class="form-control">
        </div>
        <span id=last_keyword class="debug">last_keyword</span>
      </div>

      <div id="blok_list_mhs"></div> 

    </div>
  </div>

  <div class="col-lg-6">
    <div class="wadah">
      <?php include 'modul/peserta/list_peserta.php'; ?>
    </div>
  </div>
</div>













<script>
  $(function(){
    $("#keyword").keyup(function(){
      let keyword = $("#keyword").val();
      let last_keyword = $("#last_keyword").text();

      if(keyword==last_keyword) return;
      let link_ajax = `ajax_akademik/ajax_get_list_mhs.php?keyword=${keyword}&`;

      $.ajax({
        url:link_ajax,
        success:function(a){
          $("#blok_list_mhs").html(a);
          $("#last_keyword").text(keyword);
        }
      })
    });
    $("#keyword").keyup();


    $(document).on("click",".btn_aksi",function(){
      let tid = $(this).prop('id');
      let rid = tid.split('__');
      let aksi = rid[0];
      let id_mhs = rid[1];
      let link_ajax;
      alert(aksi);

      if(aksi=='drop'){
        link_ajax = `ajax_akademik/ajax_drop_kelas_mhs.php?id_mhs=${id_mhs}`;
        $.ajax({
          url: link_ajax,
          success: function(a){
            if(a.trim()=='sukses'){
              $("#tr__"+id_mhs).fadeOut();
            }else{
              alert(a);
            }
          }
        })

      }
    })
  })
</script>