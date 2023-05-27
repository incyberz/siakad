<?php
$no_wa='';
$is_verified_no_wa=0;
echo "<span class=debug>no_wa: $no_wa</span>";
if($no_wa=='' OR !$is_verified_no_wa){
  $link_ver_my_wa = '$link_ver_my_wa';

  $text_wa = "Yth. Petugas Akademik ... zzz";

  $form = "
    <label for=no_wa>Berapa No Whatsapp kamu?</label>
    <input class='form-control text-center consolas' id=no_wa name=no_wa minlength=10 maxlength=14 required>
    <div id=blok_link_submit class=hideit>
      <a id=link_submit href='https://api.whatsapp.com/send?phone=6287878787&text=$text_wa' class='btn btn-primary btn-block mt-2 ' target=_blank>Submit Nomor Whatsapp</a>
    </div>
  ";

  $form = $no_wa=='' 
  ? "
  <div class='wadah gradasi-merah'>
    <div class=red>
      Kamu belum mendaftarkan Nomor Whatsapp.
    </div>
    <hr>
    $form
  </div>" 
  : "<div class=darkred>Nomor Whatsapp kamu belum diverifikasi oleh Petugas.</div>";
}else{
  $form = "
    <div class='wadah'>
      <div class='form-group'>
        <label for='perihal'>Perihal</label>
        <input type='text' class='form-control' value='Verifikasi Bukti Bayar Biaya Formulir PMB' disabled>
      </div>
      <div class='form-group'>
        <label for='perihal'>Kepada</label>
        <input type='text' class='form-control' value='Bagian Keuangan' disabled>
      </div>
      <div class='form-group'>
        <label for='perihal'>Tanggal Pengajuan</label>
        <input type='text' class='form-control' value='2023-05-26 11:34:12' disabled>
      </div>
      <div class='form-group'>
        <label for='perihal'>Link Akses</label>
        <input type='text' class='form-control' value='https://siakad.ikmi.ac.id/akademik/?verifikasi_bukti_bayar&id_biaya=1&id_mhs=1' disabled>
      </div>
    </div>
  ";
}
?>
<section id="" class="" data-aos="fade-left">
  <div class="container">

    <div class="section-title">
      <h2>Whatsapp Petugas</h2>
      <p>Berikut adalah Fitur Whatsapp Gateway untuk mahasiswa:</p>
    </div>

    <?=$form?>

  </div>
</section>


<script>

  $(function () {

    (function($) {
      $.fn.inputFilter = function(inputFilter) {
        return this.on("input keydown keyup mousedown mouseup select contextmenu drop", function() {
          if (inputFilter(this.value)) {
            this.oldValue = this.value;
            this.oldSelectionStart = this.selectionStart;
            this.oldSelectionEnd = this.selectionEnd;
          } else if (this.hasOwnProperty("oldValue")) {
            this.value = this.oldValue;
            this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
          } else {
            this.value = "";
          }
        });
      };
    }(jQuery));
    //=======================================================================
    // INPUT NUMBER ONLY
    //=======================================================================
    $("#no_wa").inputFilter(function(value) {return /^\d*$/.test(value); });
          
    $("#no_wa").keyup(function () {
      let no_wa = $(this).val();      
      console.log(no_wa.length);
      if (no_wa.length > 9) {
        $('#blok_link_submit').slideDown();
      }else{
        $('#blok_link_submit').fadeOut();
      }
    });
  })
</script>