<h3>List Kurikulum MK</h3>
<?php
$default_option = '';
include 'include/option_angkatan.php';
include 'include/option_prodi.php';
?>
<style>.blok_filter{display:flex; flex-wrap:wrap; gap:15px} th{text-align:left}</style>
<div class="blok_filter mb2">
  <div>Angkatan</div>
  <div>
    <select class="filter form-control" id="angkatan"><?=$option_angkatan?></select>
  </div>
  <div>Prodi</div>
  <div>
    <select class="filter form-control" id="id_prodi"><?=$option_prodi?></select>
  </div>
  <div>MK <span class=debug id=last_keyword>last_keyword</span></div>
  <div>
    <input class="form-control" id=keyword placeholder="Kurikulum ...">
  </div>

</div>
<div id="blok_list_kurikulum_mk"></div>
<div class="debug" id=debug1></div>


<script>
  $(function(){
    $("#keyword").keyup(function(){
      let id_prodi = $("#id_prodi").val();
      let angkatan = $("#angkatan").val();
      let keyword = $("#keyword").val();
      let last_keyword = $("#last_keyword").text();

      // if(keyword==last_keyword) return;
      let link_ajax = `ajax_akademik/ajax_get_list_kurikulum_mk.php?keyword=${keyword}&id_prodi=${id_prodi}&angkatan=${angkatan}&`;
      $("#debug1").text(link_ajax);
      $.ajax({
        url:link_ajax,
        success:function(a){
          $("#blok_list_kurikulum_mk").html(a);
        }
      })
      $("#last_keyword").text(keyword);
    });
    $("#keyword").keyup();


    $(".filter").change(function(){
      $("#keyword").keyup();
    });

  })
</script>

<script>
  $(document).on("click",".dpnu_not_ready",function(){
    alert('MK ini belum dijadwalkan.\n\nSilahkan klik Manage Jadwal terlebih dahulu atau Silahkan Filter MK.');
  })
</script>