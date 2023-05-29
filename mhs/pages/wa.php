<?php
$last_use_wa_gateway = $d_mhs['last_use_wa_gateway'];
$last_use_second = 600-(strtotime('now')-strtotime($last_use_wa_gateway));

if($dm) {$last_use_second = 0; } //zzz debug

$script_timer_selisih = "
<script>
  $(function(){
    let selisih = parseInt($('#selisih').text());
    setInterval(() => {
      selisih--;
      if(selisih==0){
        location.reload();
      }else{
        $('#selisih').text(selisih);
      }
    }, 1000);
  })
</script>
";

if($last_use_second>1){
  $form = "<div class='wadah gradasi-merah'>Maaf, mohon antri! Kamu baru saja menggunakan Whatsapp Gateway. Dapat kembali menggunakan dalam <span id=selisih>$last_use_second</span> detik.</div>$script_timer_selisih";
}else{

  if(isset($_POST['btn_submit_no_wa'])){
    $no_wa = $_POST['no_wa'];
    $s = "UPDATE tb_mhs SET no_wa='$no_wa' WHERE id=$id_mhs";
    $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
    echo '<script>location.replace("?wa")</script>';
    exit;
  }


  if(isset($_POST['btn_kirim_wa'])){
    $perihal = $_POST['perihal'];
    $kepada = $_POST['kepada'];
    $info = $_POST['info'];
    $tanggal_pengajuan = $_POST['tanggal_pengajuan'];
    $link_akses = urlencode($_POST['link_akses']);



    $text_wa = "*Perihal: $perihal*%0a%0aYth. Petugas Keuangan%0a%0aSaya mengajuakan request verifikasi pembayaran: %0a~ Tanggal pengajuan : $tanggal_pengajuan%0a~ Informasi : $info%0a%0aMohon segera diverifikasi. Terimakasih.%0aDari: $nama_mhs - $nim%0a%0a";
    $text_wa_html = str_replace('%0a','<br>',$text_wa);
    $preview = "<div class='wadah mb-2 bg-white'>$text_wa_html</div>";
    $no_wa_tujuan = $no_bau;
    
    $s = "UPDATE tb_mhs SET last_use_wa_gateway=CURRENT_TIMESTAMP WHERE id=$id_mhs";
    $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
    $form = "$preview<a href='https://api.whatsapp.com/send?phone=$no_wa_tujuan&text=$text_wa$link_akses' class='btn btn-primary btn-block mt-2 '>Kirim via Whatsapp</a><div class='mt-2 kecil miring abu'>Jika kamu memakai laptop, maka kamu memerlukan Whatsapp Web atau Whatsapp Desktop.</div>";

    }else if(isset($_POST['btn_kirim_verif'])){
    $text_wa = $_POST['text_wa'];
    $link_verif = urlencode($_POST['link_verif']);
    $s = "UPDATE tb_mhs SET last_use_wa_gateway=CURRENT_TIMESTAMP WHERE id=$id_mhs";
    $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
    $form = "<a href='https://api.whatsapp.com/send?phone=$no_wa_tujuan&text=$text_wa$link_verif' class='btn btn-primary btn-block mt-2 '>Verifikasi via Whatsapp</a><div class='mt-2 kecil miring abu'>Jika kamu memakai laptop, maka kamu memerlukan Whatsapp Web atau Whatsapp Desktop.</div>";
    // exit;
  }else{
    // normal flow
    // $no_wa=''; // zzz debug
    // $is_verified_no_wa=0; //zzz debug
    echo "<span class=debug>no_wa: $no_wa</span>";
    if($no_wa=='' OR !$is_verified_no_wa){
      $link_ver_my_wa = '$link_ver_my_wa';
      
      // $text_wa = "Yth. Petugas Akademik ... zzz";
      
      $no_wa_tujuan = $no_baak;
      $link_verif = "https://siakad.ikmi.ac.id/akademik/?verifikasi_nomor_wa_mhs&no_wa=${no_wa}&nim=${nim}";
      $text_wa = "Yth. Petugas Akademik%0a%0aBerikut adalah nomor WhatsApp saya yang aktif:%0a~ Nomor: $no_wa%0a~ Atas nama: $nama_mhs%0a%0aMohon segera diverifikasi. Terimakasih.%0a%0a";

      $api_wa = "https://api.whatsapp.com/send?phone=$no_wa_tujuan&text=$text_wa";

      if($no_wa==''){
        $pesan = '<div class=red>Kamu belum mendaftarkan Nomor Whatsapp.</div>' ;
        $form = "
          <form method=post>
            <label for=no_wa>Berapa No Whatsapp kamu?</label>
            <input class='form-control text-center consolas' id=no_wa name=no_wa minlength=10 maxlength=14 required>
            <table>
              <tr>
                <td><input class='form-control consolas text-center' id=no_wa1 disabled></td>
                <td><input class='form-control consolas text-center' id=no_wa2 disabled></td>
                <td><input class='form-control consolas text-center' id=no_wa3 disabled></td>
                <td><input class='form-control consolas text-center' id=no_wa4 disabled></td>
              </tr>
            </table>
            <div id=blok_link_submit class=hideit>
              <button id=link_submit class='btn btn-primary btn-block mt-2 ' name=btn_submit_no_wa>Submit Nomor Whatsapp</button>
            </div>
          </post>
        ";
      }else{
        $pesan = '<div class=darkred>Nomor Whatsapp kamu belum diverifikasi oleh Petugas.</div>';

        $form = "
          <form method=post>
            <span class=debug>selisih: $selisih</span>
            <input class=debug name=text_wa value='$text_wa'>
            <input class=debug name=link_verif value='$link_verif'>
            <button class='btn btn-primary btn-block' name=btn_kirim_verif>Kirim Verifikasi</button>
          </form>
        ";    
      } // end wa belum diverifikasi

      $form = "<div class='wadah gradasi-merah'>$pesan<hr>$form</div>"; 

    }else{

      # =======================================================
      # REGISTERED AND VERIFIED WHATSAPP
      # =======================================================

      $perihal = isset($_POST['perihal']) ? $_POST['perihal'] : die(erid('perihal'));
      $kepada = isset($_POST['kepada']) ? $_POST['kepada'] : die(erid('kepada'));
      $no_tujuan = isset($_POST['no_tujuan']) ? $_POST['no_tujuan'] : die(erid('no_tujuan'));
      $tanggal_pengajuan = isset($_POST['tanggal_pengajuan']) ? $_POST['tanggal_pengajuan'] : die(erid('tanggal_pengajuan'));
      $info = isset($_POST['info']) ? $_POST['info'] : die(erid('info'));
      $link_akses = isset($_POST['link_akses']) ? $_POST['link_akses'] : die(erid('link_akses'));

      $form = "
      <form method=post>
        <input class=debug name=perihal value='$perihal'>
        <input class=debug name=kepada value='$kepada'>
        <input class=debug name=no_tujuan value='$no_tujuan'>
        <input class=debug name=tanggal_pengajuan value='$tanggal_pengajuan'>
        <input class=debug name=link_akses value='$link_akses'>
        <input class=debug name=info value='$info'>
        <div class='wadah'>
          <div class='form-group'>
            <label for='perihal'>Perihal</label>
            <input type='text' class='form-control' value='$perihal' disabled>
          </div>
          <div class='form-group'>
            <label for='perihal'>Kepada</label>
            <input type='text' class='form-control' value='$kepada' disabled>
          </div>
          <div class='form-group'>
            <label for='perihal'>Tanggal Pengajuan</label>
            <input type='text' class='form-control' value='$tanggal_pengajuan' disabled>
          </div>
          <div class='form-group'>
            <label for='perihal'>Informasi</label>
            <input type='text' class='form-control' value='$info' disabled>
          </div>
          <div class='form-group debug'>
            <label for='perihal'>Link Akses</label>
            <input type='text' class='form-control' value='$link_akses' disabled>
          </div>
          <div class='form-group'>
            <button class='btn btn-primary btn-block' name=btn_kirim_wa>Kirim Whatsapp</button>
          </div>
        </div>
      </form>
      ";
    } // end registered & verified wa  
  } // end normal flow
} // end last use is ready



?>
<section id="" class="" data-aos="fade-left">
  <div class="container">

    <div class="section-title">
      <h2>Whatsapp Petugas</h2>
      <p>Berikut adalah Fitur Whatsapp Gateway untuk mahasiswa:</p>
      <div class="kecil miring abu consolas">Last use: <?=$last_use_wa_gateway?></div>
    </div>

    <?=$form?>
    <span id="debug"></span>
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
      let awalan = no_wa.substring(0,2); 

      if (no_wa.length > 2) {
        if(awalan=='08'){
          let akhiran = no_wa.substring(2,14);
          $(this).val('628'+akhiran);
        }else if(awalan!='62'){
          alert('Silahkan awali nomor whatsapp dengan 08... atau 62...');
          $(this).val('');
          return;
        }
      }
      
      $('#no_wa1').val(no_wa.substring(0,4));     
      $('#no_wa2').val(no_wa.substring(4,7));     
      $('#no_wa3').val(no_wa.substring(7,10));     
      $('#no_wa4').val(no_wa.substring(10,14));     
      if (no_wa.length > 10) {
        $('#blok_link_submit').slideDown();
        // let nama_mhs=$('#nama_mhs').text();
        // let nim=$('#nim').text();
        // let link_verif = `https://siakad.ikmi.ac.id/akademik/?verifikasi_nomor_wa_mhs&no_wa=${no_wa}&nim=${nim}`;
        // let text_wa = `Yth. Petugas Akademik%0a%0aBerikut adalah nomor WhatsApp saya yang aktif:%0a~ Nomor: ${no_wa}%0a~ Atas nama: ${nama_mhs}%0a%0aMohon segera diverifikasi. Terimakasih.%0a%0a${link_verif}`;
        // let link_wa = encodeURI(`https://api.whatsapp.com/send?phone=${no_wa}&text=${text_wa}`);
        // $('#link_submit').prop('href',link_wa);
      }else{
        $('#blok_link_submit').fadeOut();
        $('#link_submit').prop('href','#');
      }
    });
    // $('#link_submit').prop('href',`https://api.whatsapp.com/send?phone=${no_wa}&text=${encodeURI(text_wa)}`);
  })
</script>

