<h1>Monitoring SKS Dosen</h1>
<?php
// if(isset($_POST['btn_buat_sesi_default'])){
//   $id_dosen = $_POST['id_dosen'];
  
//   $s = "INSERT INTO tb_sesi_kuliah (
//     kelas,
//     pertemuan_ke,
//     id_dosen,
//     nama
//     ) VALUES $values";
//   $q = mysqli_query($cn,$s) or die(mysqli_error($cn));

//   echo div_alert('success',"Membuat $jumlah_sesi Sesi Kuliah Default berhasil.<hr><a href='?manage_sesi&kelas=$kelas'>Lanjutkan Proses</a>");
//   exit;
  
// }


$opt_bulan = '';
for ($i=0; $i < count($nama_bulan); $i++) {
  $j = $i+1; 
  $selected = $j==date('m') ? 'selected' : '';
  $skg = $j==date('m') ? ' (skg)' : '';
  $opt_bulan.= "<option value='$j' $selected>$nama_bulan[$i]$skg</option>";
}

?>
<style>.monitoring_sks_dosen h3{margin:0 0 15px 0} th{text-align:left}</style>

<div class="monitoring_sks_dosen wadah">
  <h3>List Dosen</h3>

  <style>.blok_filter{display:flex; flex-wrap:wrap; gap:15px}</style>
  <div class="debug">
  </div>
  <div class='blok_filter mb2'>
    <div>Search:</div>
    <div>
      <input id=keyword class="form-control">
    </div>
    <div>Bulan:</div>
    <div>
      <select class="form-control" id='id_bulan'>
        <?=$opt_bulan?>
      </select>
    </div>
    <span id=last_keyword class="debug">last_keyword</span>
  </div>
  <div id="blok_list_mhs"></div> 
</div>













<script>
  $(function(){
    $("#keyword").keyup(function(){
      let keyword = $("#keyword").val();
      let last_keyword = $("#last_keyword").text();
      let kelas = $("#kelas").text();
      let id_bulan = $("#id_bulan").text();

      if(keyword==last_keyword) return;
      let link_ajax = `ajax_akademik/ajax_get_list_dosen.php?keyword=${keyword}&id_bulan=${id_bulan}&`;
      

      $.ajax({
        url:link_ajax,
        success:function(a){
          $("#blok_list_mhs").html(a);
          $("#last_keyword").text(keyword);
        }
      })
    });
    $("#id_bulan").change(function(){
      $("#keyword").keyup();
    })
    
    // at form-load
    $("#keyword").keyup();


    $(document).on("click",".btn_aksi",function(){
      let tid = $(this).prop('id');
      let rid = tid.split('__');
      let aksi = rid[0];
      let id_mhs = rid[1];

      let kelas_asal = $("#kelas_asal__"+id_mhs).text()
      let kelas = $("#kelas").text()

      if(aksi=='move'){
        let y = confirm(`Yakin untuk memindahkan kelas?\n\nDari: ${kelas_asal}\nKe: ${kelas}\n\nPerhatian! Pemindahan Kelas akan berdampak pada proses KRS, KHS, dan Pembayaran.`);
        if(!y) return;
      }

      let kelas_sent = aksi=='drop' ? '' : kelas;
      let link_ajax = `ajax_akademik/ajax_set_kelas_mhs.php?id_mhs=${id_mhs}&kelas=${kelas_sent}&`;
      // alert(link_ajax); return;
      $.ajax({
        url: link_ajax,
        success: function(a){
          if(a.trim()=='sukses'){
            if(aksi=='drop'){
              $("#tr2__"+id_mhs).fadeOut();
            }else{
              $("#tr__"+id_mhs).fadeOut();
            }
          }else{
            alert(a);
          }
        }
      })

    })
  })
</script>