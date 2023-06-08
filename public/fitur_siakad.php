<?php 
for ($i=0; $i < count($fitur); $i++) $link_fitur[$i] = $is_login ? $fitur[$i] : "#hero";
?>
<style>
  .fitur { transition: transform .2s;}
  .fitur:hover { transform: scale(1.5);}
  .fitur_siakad2 { transition: transform .2s;}
  .fitur_siakad2:hover { transform: scale(1.5);}
</style>

<section id="fitur_siakad" class="clients" style="margin-top: 150px">
  <div class="container">


    <div class="section-title" data-aos="zoom-in">
      <h2>Fitur</h2>
      <?php if($is_login){
        echo "<div>Selamat datang $nama_user! Anda login sebagai $login_as. <hr>Anda dapat mengakses fitur-fitur berikut:</div>";
      } else{
        echo "<p>Selamat datang Pengunjung! Untuk mengakses fitur SIAKAD silahkan login terlebih dahulu.</p>";
      }
      ?>

      <!-- <p><a href="?logout" onclick="return confirm('Ingin Logout?')">Logout</a> | Selamat <?=$waktu?> <span class="biru"><?=$nama_user?></span>! Anda login sebagai <span class="merah"><?=$login_as?></span> </p> -->
    </div>
    <hr>
    <nav class="">
      <div class="row">
        
        <?php for ($i=0; $i < count($fitur); $i++) { 
          $j = $i+1; 
          $nama_fitur_alt = $nama_fitur[$i];
          $clink_fitur = $link_fitur[$i];

          // echo "<h1>".$sub_domain."==".substr($fitur[$i],0,strlen($sub_domain))."</h1>";
          if($sub_domain==substr($fitur[$i],0,strlen($sub_domain))){
            $link_fitur[$i] = '';
          }else{
            continue;
          }
          
          

          echo "
          <div class='col-lg-2 col-md-4 col-6'>
            <a href='$clink_fitur' class='fitur'>
              <img src='assets/img/fitur-siakad/fitur-$j.png' class='img-fluid' alt='$nama_fitur_alt' data-aos='zoom-in'>
            </a>
          </div>
          ";

        } ?>



      </div>
    </nav>
    <hr>
    <h4 data-aos="zoom-in" style="margin-top: 20px">Links</h4>
    <hr>

    <nav>

      <div class="row">

        <div class="col-lg-2 col-md-4 col-6">
          <a href="https://pmb.ikmi.ac.id" class="fitur_siakad2" target="_blank">
            <img src="assets/img/fitur-siakad/fitur-pmb.png" class="img-fluid" alt="" data-aos="zoom-in">
          </a>
        </div>

        <div class="col-lg-2 col-md-4 col-6">
          <a href="https://pmb.ikmi.ac.id/pmb6/adm" class="fitur_siakad2" target="_blank">
            <img src="assets/img/fitur-siakad/fitur-admin-pmb.png" class="img-fluid" alt="" data-aos="zoom-in">
          </a>
        </div>

        <div class="col-lg-2 col-md-4 col-6">
          <a href="https://tracer.ikmi.ac.id" class="fitur_siakad2" target="_blank">
            <img src="assets/img/fitur-siakad/fitur-tracer.png" class="img-fluid" alt="" data-aos="zoom-in">
          </a>
        </div>

        <div class="col-lg-2 col-md-4 col-6">
          <a href="https://kuesioner.ikmi.ac.id" class="fitur_siakad2" target="_blank">
            <img src="assets/img/fitur-siakad/fitur-kuesioner.png" class="img-fluid" alt="" data-aos="zoom-in">
          </a>
        </div>

        <div class="col-lg-2 col-md-4 col-6">
          <a href="https://pmb.ikmi.ac.id/qwars" class="fitur_siakad2" target="_blank">
            <img src="assets/img/fitur-siakad/fitur-qwars.png" class="img-fluid" alt="" data-aos="zoom-in">
          </a>
        </div>

        <!-- <div class="col-lg-2 col-md-4 col-6">
          <a href="sidadu/" class="fitur_siakad2" target="_blank">
            <img src="assets/img/fitur-siakad/fitur-sidadu.png" class="img-fluid" alt="" data-aos="zoom-in">
          </a>
        </div> -->


      </div>
    </nav>

  </div>
</section>

<script type="text/javascript">
  $(document).ready(function(){
    // $(".fitur").click(function(){
    //   alert("Silahkan Login untuk mengakses Fitur SIAKAD.")
    // })
  })
</script>