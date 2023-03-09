<h3>List Jadwal</h3>
<?php
$default_option = '';
include 'include/option_angkatan.php';
include 'include/option_prodi.php';
?>
<style>.blok_filter{display:flex; flex-wrap:wrap; gap:15px}</style>
<div class="blok_filter mb2">
  <div>Angkatan</div>
  <div>
    <select class="filter form-control" id="angkatan"><?=$option_angkatan?></select>
  </div>
  <div>Prodi</div>
  <div>
    <select class="filter form-control" id="id_prodi"><?=$option_prodi?></select>
  </div>
  <div>Cari</div>
  <div>
    <input class="form-control" id=keyword placeholder="Jadwal ...">
  </div>

</div>
<div id="blok_list_jadwal"></div>
<div class="debug" id=debug1></div>


<script>
  $(function(){
    $("#keyword").keyup(function(){
      let id_prodi = $("#id_prodi").val();
      let angkatan = $("#angkatan").val();
      let keyword = $("#keyword").val();

      // if(keyword.length==0) return;
      let link_ajax = `ajax_akademik/ajax_get_list_jadwal.php?keyword=${keyword}&id_prodi=${id_prodi}&angkatan=${angkatan}&`;
      $("#debug1").text(link_ajax);
      $.ajax({
        url:link_ajax,
        success:function(a){
          $("#blok_list_jadwal").html(a);
        }
      })
    });
    
    $(".filter").change(function(){
      $("#keyword").keyup();
    });

  })
</script>