<?php
# ==============================================================
# MASTER DATA HANDLER v.1.0.1
# ==============================================================

# ==============================================================
# FORM ACTION HANDLER
# ==============================================================
include 'manage_form_handler.php';


# ==============================================================
# GET PARAMS
# ==============================================================
$page = isset($_GET['p'])?$_GET['p']:'';
$aksi = isset($_GET['aksi'])?$_GET['aksi']:'';
$id = isset($_GET['id'])?$_GET['id']:'';

if($page==''){
  # ==============================================================
  # SHOW MANAGE MASTER PAGE
  # ==============================================================
  include 'manage_home.php';

}else{

  # ==============================================================
  # MANAGE SINGLE MASTER
  # ==============================================================

  # ==============================================================
  # DESCRIBING COLUMNS
  # ==============================================================
  $s = "DESCRIBE tb_$page";
  $q = mysqli_query($cn, $s)or die(mysqli_error($cn));
  $Field = [];
  $Type = [];
  $Null = [];
  $Key = [];
  $Default = [];
  $i=0;
  while ($d=mysqli_fetch_assoc($q)) {
    if($d['Extra']=='auto_increment') continue;
    if($d['Field']=='folder_uploads') continue;
    $Field[$i] = $d['Field'];
    $Type[$i] = $d['Type'];
    $Null[$i] = $d['Null'];
    $Key[$i] = $d['Key'];
    // $Default[$i] = $d['Default'];
    $i++;
  }

  # ==============================================================
  # PAGE TITLE
  # ==============================================================
  $param = $aksi=='' ? '' : "&p=$page";
  $btn_back = "<a href='?manage$param'><i class=icon_house_alt></i></a> | ";
  $aksi_title = $aksi=='' ? 'MANAGE' : $aksi; 
  $page_title = "<h1 class='judul-page upper'>$btn_back $aksi_title ". strtoupper($page). '</h1>';


  # ===============================================
  # CUSTOM PARAM FOR FOREIGN KEY LINK
  # ===============================================
  $param_id = $page=='jenjang' ? 'jenjang' : 'id';
  $param_id = $page=='angkatan' ? 'angkatan' : $param_id;

  $s = "SELECT * from tb_$page";

  # ==============================================================
  # CUSTOM PRIMARY / FOREIGN KEY
  # ==============================================================
  $kolom_acuan = $page=='jenjang' ? 'jenjang' : 'id';
  $s = !isset($_GET[$kolom_acuan]) ? $s : "SELECT * from tb_$page where $kolom_acuan='$_GET[$kolom_acuan]'";

  # ==============================================================
  # SQL LIMIT
  # ==============================================================
  $limit = 10;
  $s = $aksi=='tambah' ? "$s limit 1" : "$s limit $limit";

  # ==============================================================
  # EXECUTE QUERY
  # ==============================================================
  echo "sql: $s";
  $q = mysqli_query($cn, $s)or die(mysqli_error($cn));

  # ==============================================================
  # HEADER FOR LIST MASTER
  # ==============================================================
  $th='';
  if($aksi==''){
    $th='<th class=text-left>No</th>';
    for ($i=0; $i < count($Field); $i++) { 
      $kolom_formatted = str_replace('_',' ',strtoupper($Field[$i]));
      $th.="<th class=text-left>$kolom_formatted</th>";
    }
  }


  $tr = '';
  $i=0;
  while ($d = mysqli_fetch_assoc($q)) {

    if($aksi==''){
      # ===============================================
      # SHOW LIST MASTER DATA
      # ===============================================
      $i++;
      $td='';
      for ($j=0; $j < count($Field); $j++) { 
        $td.='<td>'.$d[$Field[$j]].'</td>';
      }


      # ===============================================
      # TABEL ROW OUTPUT
      # ===============================================
      $tr.= "
        <tr>
          <td>$i</td>
          $td
          <td>
            <a class='btn btn-success btn-sm btn-block mb-2 upper' href='?manage&p=$page&aksi=update&$param_id=$d[$param_id]'>update</a>
            <a class='btn btn-danger btn-sm btn-block mb-2 upper' href='?manage&p=$page&aksi=hapus&$param_id=$d[$param_id]'>hapus</a>
          </td>
        </tr>
      ";

    }else{

      # ===============================================
      # SHOW FORM UPDATE / HAPUS / TAMBAH
      # ===============================================
      $id = isset($_GET['jenjang']) ? $_GET['jenjang'] : $id;
      $id = isset($_GET['angkatan']) ? $_GET['angkatan'] : $id;
      $id = (trim($id)=='' and $aksi!='tambah') ? die('Aksi tanpa acuan id.') : $id;

      if($aksi=='hapus'){
        $disabled = 'disabled';
      }elseif($aksi=='update' || $aksi=='tambah'){
        $disabled = '';
      }else{
        die("Aksi $aksi belum terdapat handler.");
      } //end if $aksi type


      for ($j=0; $j < count($Field); $j++) { 

        $nama_kolom = $Field[$j];
        $nama_kolom_upper = "<span class=upper>".str_replace('_',' ',$nama_kolom)."</span>";

        $required = $Null[$j]=='NO' ? 'required' : '';
        $red_dot = $Null[$j]=='NO' ? '<span class="red tebal">*</span>' : '';

        $ATTR = "name='$nama_kolom' id='$nama_kolom' $disabled $required";

        if($Key[$j]=='MUL'){
          // echo "<hr>Key[$j] = $Key[$j] = $nama_kolom <hr>";
          # ========================================================
          # HANDLER FOREIGN KEY
          # ========================================================

          // id_fakultas >> fakultas
          $tabel_select = str_replace('id_','',$nama_kolom);
          $kolom_acuan_select = 'id';

          # ========================================================
          # CUSTOM PRIMARY / FOREIGN KEY
          # ========================================================
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

          $defid = $d[$nama_kolom];


          $script = "
          <script>
            $(function(){
              $.ajax({
                url:'ajax_akademik/ajax_get_option.php?tabel=$tabel_select&id=$kolom_acuan_select&val=$kolom_isi_select&defid=$defid',
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

          echo $script;
          $input = "<select class='form-control' $ATTR ></select>";
          $input .= "<div class='input-keterangan'>Opsi : <a href='?manage&p=$tabel_select' class='proper'>manage $tabel_select</a></div>";
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

    } // end else $aksi == ''
  }

  $tr = $tr=='' ? "<tr><td class='red'>Belum ada data $page.</td></tr>" : $tr;
  $tb = "<table class='table table-striped'><thead>$th</thead>$tr</table>";

  $btn_tambah = $aksi=='tambah'?'':"<div style='padding:10px'><a href='?manage&p=$page&aksi=tambah' class='btn btn-primary'>Tambah</a></div>";

  echo "
  <form method=post>
    <div class=debug>
      <input name=tabel value=$page>
      <input name=kolom_acuan value=$param_id>
      <input name=id value=$id>
    </div>
    $page_title $btn_tambah $tb
  </form>
  ";
}
