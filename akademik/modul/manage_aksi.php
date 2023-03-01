<?php
# ========================================================
# MANAGE AKSI IF AKSI NOT NULL
# ========================================================
$id = isset($_GET['jenjang']) ? $_GET['jenjang'] : $id;
$id = isset($_GET['angkatan']) ? $_GET['angkatan'] : $id;
$id = (trim($id)=='' and $aksi!='tambah') ? die('Aksi tanpa acuan id.') : $id;

if($aksi=='hapus'){
  $disabled = 'disabled';
}elseif($aksi=='update' || $aksi=='tambah'){
  $disabled = '';
}else{
  die("Aksi $aksi belum terdapat handler.");
}


for ($j=0; $j < count($Field); $j++) { 

  $nama_kolom = $Field[$j];
  $nama_kolom_upper = "<span class=upper>".str_replace('_',' ',$nama_kolom)."</span>";

  $required = $Null[$j]=='NO' ? 'required' : '';
  $red_dot = $Null[$j]=='NO' ? '<span class="red tebal">*</span>' : '';

  $ATTR = "name='$nama_kolom' id='$nama_kolom' $disabled $required";

  if($Key[$j]=='MUL' and $aksi!='hapus'){
    # ========================================================
    # HANDLER FOREIGN KEY
    # ========================================================

    // id_fakultas >> fakultas
    $tabel_select = str_replace('id_','',$nama_kolom);
    $kolom_acuan_select = 'id';

    # ========================================================
    # CUSTOM PRIMARY / FOREIGN KEY
    # ========================================================
    if($tabel_select=='rektor') $tabel_select='dosen';
    if($tabel_select=='dekan') $tabel_select='dosen';
    if($tabel_select=='kaprodi') $tabel_select='dosen';
    if($tabel_select=='homebase') $tabel_select='prodi';
    if($nama_kolom=='jenjang') {$kolom_acuan_select='jenjang';}

    $s2 = "DESCRIBE tb_$tabel_select";
    $q2 = mysqli_query($cn,$s2) or die(mysqli_error($cn));
    $k=0;
    while ($d2=mysqli_fetch_assoc($q2)) {
      $Field2[$k] = $d2['Field'];
      $k++;
    }
    $kolom_isi_select = in_array('nama',$Field2) ? 'nama' : 'id';
    $defid = $nama_kolom;

    echo "
    <script>
      $(function(){
        $.ajax({
          url:'ajax_akademik/ajax_get_option.php?tabel=$tabel_select&id=$kolom_acuan_select&val=$kolom_isi_select&defid=$defid&is_null=$Null[$j]',
          success: function(a){
            console.log(a);
            $('#$nama_kolom').empty();
            $('#$nama_kolom').append(a);
            // $('#nama').val(a);
          }
        })
      })
    </script>
    ";

    $input = "<select class='form-control' $ATTR ></select>";

    $opsi = "<div class='input-keterangan'>Opsi : <a href='?manage&p=$tabel_select' class='proper'>manage $tabel_select</a></div>";
    $input .= $opsi;

  }else{

    # ========================================================
    # HANDLER NON FOREIGN KEY
    # ========================================================
    $input_value = $aksi=='tambah'? '' : $d[$nama_kolom];

    if(substr($Type[$j],0,3)=='int' || substr($Type[$j],0,7)=='tinyint' || substr($Type[$j],0,8)=='smallint'){
      # ===============================================
      # INPUT NUMBER
      # ===============================================
      $input = "<input class='form-control' $ATTR value='$input_value'>";
    }elseif(substr($Type[$j],0,4)=='char' || substr($Type[$j],0,7)=='varchar'){
      # ===============================================
      # INPUT STRING
      # ===============================================
      $text_length = 50; // zzz here
      if($text_length>100){
        // textarea
      }else{
        $input = "<input class='form-control' $ATTR value='$input_value' maxlength=$text_length>";
      }
    }elseif($Type[$j]=='date' || $Type[$j]=='timestamp'){
      # ===============================================
      # INPUT TANGGAL DAN TIMESTAMP
      # ===============================================
      $input = "<input type=date class='form-control' $ATTR value='$input_value'>";
      $input .= "<input type=hiddena id='$nama_kolom"."_hidden' value='$input_value'>";
      ?>
      <script>
        $(function(){
          $('#tanggal_awal').val('2022-2-2');
        })
      </script>

      <?php
    }else{
      die("Belum ada input handler untuk Type: $Type[$j]");
    }

  }

  $tr.="<tr><td class='proper'>$nama_kolom_upper $red_dot</td><td>$input</td></tr>";
} // end for

$btn_type = $aksi=='hapus' ? 'danger' : 'primary';
$tr.="<tr><td colspan=2><button class='btn btn-$btn_type btn-block upper' name=btn_$aksi>$aksi</button></td></tr>";
