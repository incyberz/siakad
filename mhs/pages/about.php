<section id="about" class="about">
  <div class="container">

    <div class="section-title">
      <h2>Tentang Saya</h2>
      <div class="p-2 mt-4" style="border-top:solid 1px #ccc">
        <a href="?isi_biodata">Isi/Ubah Biodata</a> | 
        <a href="?my_docs">Dokumen Saya</a> | 
        <a href="?ubah_password">Ubah Password</a> 
      </div>

    </div>
    
    <p class="mt4 pt2" style="border-top:solid 1px #ccc">Berikut adalah biodata Anda:</p>
    <?php 
    // $s = "DESCRIBE tb_biodata";
    // $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
    // $kolom = [];
    // while ($d=mysqli_fetch_assoc($q)) {
    //   $kolom[$d['Field']] = '';
    // }

    $ragama = ['','ISLAM', 'KATOLIK', 'PROTESTAN','HINDU','BUDHA','LAINNYA'];
    $rstatus_menikah = ['','BELUM MENIKAH', 'MENIKAH', 'JANDA','DUDA'];
    $rwarga_negara = ['','WARGA NEGARA INDONESIA', 'WARGA NEGARA ASING'];

    $s = "SELECT b.nomor as semester, a.* FROM tb_biodata a 
    JOIN tb_semester b ON a.id_semester=b.id WHERE nim='$nim'";
    $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
    if(mysqli_num_rows($q)>0){
      $d=mysqli_fetch_assoc($q);

      foreach ($d as $key => $value) {
        if($key=='id_semester') continue;
        if($key=='agama' and $value!='') $value = $ragama[$value];
        if($key=='status_menikah' and $value!='') $value = $rstatus_menikah[$value];
        if($key=='warga_negara' and $value!='') $value = $rwarga_negara[$value];
        $kolom = strtoupper(str_replace('_',' ',$key));
        $value = $value=='' ? '-' : $value;
        echo "
        <div style='border-top:solid 1px #ccc;padding:5px'>
          <div class=row>
            <div class=col-lg-4>
              <span class='kecil abu'>$kolom :</span>
            </div>
            <div class=col-lg-8>
              $value
            </div>
          </div>
        </div>";
      }
    }else{
      echo div_alert('info','Anda belum mengisi biodata.');
    }
    

    ?>


  </div>
</section>