<?php
# ========================================================
# MANAGE AKSI IF AKSI NOT NULL
# ========================================================
$id = $_GET['jenjang'] ?? $id;
$id = $_GET['angkatan'] ?? $id;
$id = $_GET['shift'] ?? $id; //new
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
    if($tabel_select=='pmb') $tabel_select='output_pmb';
    if($tabel_select=='is_publish') $tabel_select='status_mk';
    if($nama_kolom=='jenjang') {$kolom_acuan_select='jenjang';}
    if($nama_kolom=='kelas') {$kolom_acuan_select='kelas';}
    if($nama_kolom=='angkatan') {$kolom_acuan_select='angkatan';}
    if($nama_kolom=='shift') {$kolom_acuan_select='shift';}

    $s2 = "DESCRIBE tb_$tabel_select";
    // if($Field[$j]=='angkatan') die("zzz $s2");
    $q2 = mysqli_query($cn,$s2) or die(mysqli_error($cn));
    $k=0;
    while ($d2=mysqli_fetch_assoc($q2)) {
      $Field2[$k] = $d2['Field'];
      $k++;
    }
    $kolom_isi_select = in_array('nama',$Field2) ? 'nama' : 'id';
    $defid = $d[$nama_kolom];

    // exception
    if($nama_kolom=='jenjang') {$kolom_isi_select='jenjang';}
    if($nama_kolom=='kelas') {$kolom_isi_select='kelas';}
    if($nama_kolom=='angkatan') {$kolom_isi_select='angkatan';}
    if($nama_kolom=='shift') {$kolom_isi_select='shift';}
    if($nama_kolom=='status_mhs') {$kolom_isi_select='status_mhs';}

    echo "
    <script>
      $(function(){
        $.ajax({
          url:'ajax_akademik/ajax_get_option.php?tabel=$tabel_select&id=$kolom_acuan_select&val=$kolom_isi_select&defid=$defid&is_null=$Null[$j]',
          success: function(a){
            // console.log(a);
            $('#$nama_kolom').empty();
            $('#$nama_kolom').append(a);
            // $('#nama').val(a);
          }
        })
      })
    </script>
    ";

    $input = "<select class='form-control' $ATTR ></select>";
    $tabel_select_caption = str_replace('_',' ',$tabel_select);
    $opsi = "<div class='input-keterangan'>Opsi : <a href='?master&p=$tabel_select' class='proper'>manage $tabel_select_caption</a></div>";
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
      $text_length = 50; // zzz default
      if($text_length>100){
        // textarea
      }else{
        $input = "<input class='form-control' $ATTR value='$input_value' maxlength=$text_length>";
      }
    }elseif($Type[$j]=='date' || $Type[$j]=='timestamp'){
      # ===============================================
      # INPUT TANGGAL DAN TIMESTAMP
      # ===============================================
      // if($Type[$j]=='date') $input_value .= ' 00:00:00';
      // if($nama_kolom=='awal_krs') die('awal_krs, input_value:'.$input_value);

      $tg['d'] = date('d', strtotime($input_value));
      $tg['m'] = date('m', strtotime($input_value));
      $tg['y'] = date('Y', strtotime($input_value));
      $tg['h'] = date('H', strtotime($input_value));
      $tg['i'] = date('i', strtotime($input_value));
      $tg['s'] = date('s', strtotime($input_value));

      $min_tg = [1,1,(date('Y')-5),0,0,0];
      $max_tg = [31,12,(date('Y')+1),23,11,11]; // 11 kali 5 menit/detik = 60 detik

      $blok_datetime = '';
      $l=0;
      foreach($tg as $x=>$etg){
        $sel_id = $nama_kolom."__$x";
        $sel_class = $nama_kolom."__trigger";
        $hideit = ($Type[$j]=='date' && ($x=='h' || $x=='i' || $x=='s')) ? 'hideit' : '';
        // $hideit = '';
        $selop = "<select class='form-control $sel_class $hideit' id='$sel_id'>";
        for ($k=$min_tg[$l]; $k <= $max_tg[$l] ; $k++) {
          $n = $l>3 ? $k*5 : $k; // untuk menit/detik 
          $selected = $n==$etg ? 'selected' : '';
          // if($x=='h') echo "<p>n~etg : $n~$etg</p>";
          $selop.= "<option $selected>$n</option>";
        }
        $selop .= '</select>';

        $space = '<div style="margin:0 5px">-</div>';
        if($l==2)$space = '<div style="margin:0 15px 0 50px">&nbsp;</div>';
        if($l>2)$space = $Type[$j]=='date' ? '' : '<div style="margin:0 5px">:</div>';
        if($l>4)$space = '';
        $blok_datetime .= "$selop$space";
        $l++;
      }

      echo "
      <script>
        $(function(){
          $('.".$nama_kolom."__trigger').change(function(){
            let tgl_baru = $('#".$nama_kolom."__y').val() + '-' 
            + $('#".$nama_kolom."__m').val() + '-' 
            + $('#".$nama_kolom."__d').val() + ' ' 
            + $('#".$nama_kolom."__h').val() + ':' 
            + $('#".$nama_kolom."__i').val() + ':' 
            + $('#".$nama_kolom."__s').val();

            // console.log(tgl_baru);

            $('#$nama_kolom').val(tgl_baru);

          })
        })
      </script>
      ";


      $input = "<div class=blok_datetime style='display:flex'>$blok_datetime</div>";
      $input .= "<input class=debug id='$nama_kolom' name='$nama_kolom' value='$input_value'>";


    }else{
      die("Belum ada input handler untuk Type: $Type[$j]");
    }

  }

  $tr.="<tr><td class='proper'>$nama_kolom_upper $red_dot</td><td>$input</td></tr>";
} // end for

$btn_type = $aksi=='hapus' ? 'danger' : 'primary';
$tr.="<tr><td colspan=2><button class='btn btn-$btn_type btn-block upper' name=btn_$aksi>$aksi</button></td></tr>";
