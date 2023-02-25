<section id="cari_profil_dosen" class="team section-bg">
  <div class="container">

    <div class="section-title" data-aos="fade-up">
      <h2>Profil Dosen STMIK IKMI Cirebon</h2>
    </div>

    <div id="zzz" class="wadah text-center" data-aos="fade-up" data-aos-delay="100">
      <p>Silahkan Anda lakukan pencarian dengan keyword: Nama Dosen atau NIDN, minimal 3 huruf.</p>
      <div class="row"  style="margin-bottom:15px">
        <div class="col-lg-4"></div>
        <div class="col-lg-4">
          <input type="text" id="keyword" class="form-control text-center" maxlength="10">  
        </div>        
      </div>
      <hr>
      <div id="hasil_ajax" class="wadah"></div>   
    </div>
  </div>
</section>
<div style="margin-top: 500px">&nbsp;</div>













<script type="text/javascript">
  $(document).ready(function(){

    $("#keyword").keyup(function(){
      var keyword = $("#keyword").val();
      if(keyword.length<3){
        $("#hasil_ajax").html("");
        return;
      }

      var link_ajax = "ajax/ajax_cari_profil_dosen.php?keyword="+keyword;
      
      $.ajax({
        url:link_ajax,
        success:function(a){
          $("#hasil_ajax").html(a);
        }
      })

    })

  })
</script>