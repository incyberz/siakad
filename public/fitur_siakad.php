<?php 
$rfitur = [
  [[3,4,6,7,8],'akademik/?','Data Mhs',1],
  [[3,4,6,7,8],'akademik/?krs','KRS Online',2],
  [[3,6,7,8],'akademik/?khs','KHS Online',3],
  [[3,6,7,8],'akademik/?manage_jadwal','Jadwal Kuliah',4],
  [[3,6,7,8],'akademik/?master&p=dosen','Data Dosen',8],
  [[3,6,7,8],'akademik/?master&p=user','Data Tendik',9],
  [[4,8],'akademik/?pembayaran_home','Keuangan',10],
];


//$sub_domain=''; // zzz old code
for ($i=0; $i < count($rfitur); $i++) {
  $link_fitur[$i] = $is_login ? $rfitur[$i][1] : "#hero";
}
?>

<section id="fitur_siakad" class="clients" style="margin-top: 150px">
  <div class="container">


    <div class="section-title" data-aos="zoom-in">
      <h2>Fitur</h2>
      <?php if($is_login){
        echo "<div>Selamat datang <b class=darkblue>$nama_user</b>! Anda login sebagai <b class=darkblue>$login_as</b>. <hr>Anda dapat mengakses fitur-fitur berikut:</div>";
      } else{
        echo "<p>Selamat datang Pengunjung! Untuk mengakses fitur SIAKAD silahkan login terlebih dahulu.</p>";
      }
      ?>

      <!-- <p><a href="?logout" onclick="return confirm('Ingin Logout?')">Logout</a> | Selamat <?=$waktu?> <span class="biru"><?=$nama_user?></span>! Anda login sebagai <span class="merah"><?=$login_as?></span> </p> -->
    </div>
    <hr>
    <nav class="">
      <div class="row">
        
        <?php 
        for ($i=0; $i < count($rfitur); $i++) { 
          $nama_fitur_alt = $rfitur[$i][2];
          $no_fitur = $rfitur[$i][3];

          $arr_level = $rfitur[$i][0];
          if(!in_array($admin_level,$arr_level)) continue;

          echo "
          <div class='col-md-4 col-6'>
            <div class='wadah gradasi-hijau' data-aos='fade-up'>
            <a href='$link_fitur[$i]' class='fitur'>
              <img src='assets/img/fitur-siakad/fitur-$no_fitur.png' class='img-fluid' alt='$nama_fitur_alt'>
            </a>
            </div>
          </div>
          ";

        } 
        ?>



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