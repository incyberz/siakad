<section id="contact" class="contact" data-aos="fade-left">
  <div class="container">

    <div class="section-title">
      <h2>Contact</h2>
      <p>Jika ingin bersilaturahim, silahkan menghubungi saya via whatsapp, email, atau via offline.</p>
    </div>

    <div class="row" data-aos="fade-in">

      <div class="col-lg-5 d-flex align-items-stretch">
        <div class="info">
          <div class="address">
            <i class="icofont-google-map"></i>
            <h4>Rumah saya:</h4>
            <p id="alamat_mhs"><?=$alamat_mhs?></p>
          </div>

          <div class="email">
            <i class="icofont-envelope"></i>
            <h4>Email:</h4>
            <p id="email_mhs"><?=$email_mhs?></p>
          </div>

          <div class="phone">
            <i class="icofont-phone"></i>
            <h4>Whatsapp:</h4>
            <p id="no_wa_mhs"><?=$no_wa_mhs?></p>
          </div>

          <!-- <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d12097.433213460943!2d-74.0062269!3d40.7101282!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0xb89d1fe6bc499443!2sDowntown+Conference+Center!5e0!3m2!1smk!2sbg!4v1539943755621" frameborder="0" style="border:0; width: 100%; height: 290px;" allowfullscreen></iframe> -->
        </div>

      </div>

      <div class="col-lg-7 mt-5 mt-lg-0 d-flex align-items-stretch">
        <form action="forms/contact.php" method="post" role="form" class="php-email-form">


          <div class="form-group">
            <label for="name">Pesan untuk saya:</label>
            <textarea class="form-control" id="pesan_untuk_saya" rows="5"></textarea>
          </div>
          <div class="mb-3">
            <div class="loading">Loading</div>
            <div class="error-message"></div>
            <div class="sent-message">Your message has been sent. Thank you!</div>
          </div>
          <div class="text-center">
            <a href="#" class="btn btn-primary" id="link_send_mail">Send Mail</a> 
            <a href="#" class="btn btn-primary" id="link_send_wa">Send WhatsApp</a> 
          </div>
        </form>
      </div>

    </div>

  </div>
</section>

<script type="text/javascript">
  $(document).ready(function(){
    $("#pesan_untuk_saya").keyup(function(){

      var pesan = $(this).val();
      var email_mhs = $("#email_mhs").text();
      var alamat_mhs = $("#alamat_mhs").text();
      var nama_mhs = $("#nama_mhs").text();
      var d = new Date();

      pesan+= ". [From: SIAKAD System - Sisfo Civitas - "+d+"]";

      var subject = "From Civitas STMIK IKMI Cirebon";
      var body = "Halo saya "+nama_mhs+", "+pesan;
      $("#link_send_mail").prop("href","mailto:"+email_mhs+"&subject="+subject+"&body="+body+"");

      var no_wa_mhs = $("#no_wa_mhs").text();
      var text = body;
      $("#link_send_wa").prop("href","https://api.whatsapp.com/send?phone=62"+no_wa_mhs+"&text="+text+"");
    })
    $("#pesan_untuk_saya").keyup();

  })
</script>