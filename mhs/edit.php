<?php 
if(!isset($id_mhs)) die("ID Pegawai is not set");
if(!isset($link_edit)) die("Link edit is not set");



# ==========================================================================
// $s = "DESCRIBE tb_mhs_detail";
// $q = mysqli_query($cn,$s) or die("Tidak dapat mendeskripsikan data pegawai.");
// $i=0;
// while ($d=mysqli_fetch_assoc($q)) {
//   $tb_mhs_detail_fields[$i] = $d['Field'];
//   $tb_mhs_detail_field_types[$i] = $d['Type'];
//   $tb_mhs_detail_field_ket[$d['Field']] = '';

//   $i++;
// }

$img_profile = "uploads/profile_na.jpg";
$user_profile = "uploads/$folder_uploads/img_profile_$id_mhs.jpg";
$user_bg = "uploads/$folder_uploads/img_bg_$id_mhs.jpg";
if(file_exists($user_profile))$img_profile = $user_profile;

$img_bg = "uploads/bg_na.jpg";
if(file_exists($user_bg))$img_bg = $user_bg;
?>

<div class="container" style="background-color: #eff; border: solid 1px #ddd; padding: 15px">

  <div class="section-title">
    <h2>Edit Data Civitas</h2>
    <p style="color: red;font-weight: bold">Perhatian!! Masih dala tahap coding!</p>
    <p>Perhatian!! Setiap perubahan akan tersimpan ke database.</p>
  </div>
  <a href="?" class="btn btn-primary btn-sm">Back to Civitas Page</a>
  <hr>

  <form method="post" enctype="multipart/form-data" action="uplod.php">
    <!-- ============================================ -->
    <!-- FORM UPLOAD - REQUIRED INDEX -->
    <!-- ============================================ -->
    <input type="hidden" name="folder_uploads" value="<?=$folder_uploads?>">
    <input type="hidden" name="id_mhs" value="<?=$id_mhs?>">


    <style type="text/css">#tbiopeg th, #tedit th{text-align: center;padding: 10px;background-color: #dfd}</style>
    <style type="text/css">#tbiopeg td,#tedit td{padding: 5px}</style>

    <table class="table-bordered table-striped table-hover" width="100%" id="tbiopeg">
      <tr><td>Profile Picture</td><td><img src="<?=$img_profile?>" height='200px' style='border: solid 5px white;margin: 5px'></td></tr>
      <tr><td>&nbsp;</td><td>
        <div class="row" style="padding: 15px">
          <div class="col-lg-6">
            <input type="file" name="file_img_profile" accept="image/jpeg"><br>
            
          </div>
          <div class="col-lg-6">
            <button name="btn_upload_img_profile" class="btn btn-primary btn-sm">Upload Profile</button>
          </div>
        </div>
        <small>~ disarankan 200x200 pixel atau ukuran panjang dan lebarnya sama</small>
      </td></tr>
      <tr><td>Background Image</td><td>
        <a href="<?=$img_bg?>" target="_blank"><img src="<?=$img_bg?>" height='200px'  style='border: solid 5px white;margin: 5px'></a>
      </td></tr>
      <tr><td>&nbsp;</td><td>

        <div class="row" style="padding: 15px">
          <div class="col-lg-6">
            <input type="file" name="file_img_bg" accept="image/jpeg"><br>
          </div>
          <div class="col-lg-6">
            <button name="btn_upload_img_bg" class="btn btn-primary btn-sm">Upload Background</button>
          </div>
        </div>


        <small>~ disarankan Landscape Image (Wallpaper) dengan ukuran 1024 x 768 pixel</small>
      </td></tr>
    </table>
  </form>
  <hr>
  <div class="row">
    <div class="col-lg-6">
      <table class="table-bordered table-striped table-hover" width="100%" id="tbiopeg">
        <tr><td>Nama Pegawai</td><td>
          <!-- ============================================================ -->
          <!-- AUTO SAVED -->
          <!-- ============================================================ -->
          <input type="hidden" id="tb_mhs__id_mhs" value="<?=$id_mhs?>">
          <!-- ============================================================ -->
          <input type="text" id="tb_mhs__nama_mhs" class="form-control inputan" maxlength="30" value="<?=$nama_mhs?>">
          <input type="hidden" id="ztb_mhs__nama_mhs" value="<?=$nama_mhs?>">
          <small id="nama_mhs_ket"></small>
        </td></tr>
        <tr><td>Gelar</td><td>
          <input type="text" id="tb_mhs__gelar_mhs" class="form-control inputan" maxlength="20" value="<?=$gelar_mhs?>">
          <input type="hidden" id="ztb_mhs__gelar_mhs" value="<?=$gelar_mhs?>">
          <small id="gelar_mhs_ket"></small>
        </td></tr>
        <tr><td>N.I.Kepeg</td><td>
          <input type="text" id="tb_mhs__nik_mhs" class="form-control inputan" maxlength="11" value="<?=$nik_mhs?>">
          <input type="hidden" id="ztb_mhs__nik_mhs" value="<?=$nik_mhs?>">
          <small id="nik_mhs_ket">Lihat Kartu Pegawai, 11 digit</small>
        </td></tr>
        <tr><td>Tempat Lahir</td><td>
          <input type="text" id="tb_mhs__tempat_lahir_mhs" class="form-control inputan" maxlength="11" value="<?=$tempat_lahir_mhs?>">
          <input type="hidden" id="ztb_mhs__tempat_lahir_mhs" value="<?=$tempat_lahir_mhs?>">
          <small id="tempat_lahir_mhs_ket"></small>
        </td></tr>
        <tr><td>Tanggal Lahir</td><td>
          <input type="text" id="tb_mhs__tanggal_lahir_mhs" class="form-control inputan" maxlength="11" value="<?=$tanggal_lahir_mhs?>" placeholder="yyyy-mm-dd">
          <input type="hidden" id="ztb_mhs__tanggal_lahir_mhs" value="<?=$tanggal_lahir_mhs?>">
          <small id="tanggal_lahir_mhs_ket">format yyyy-mm-dd, contoh 2001-02-29</small>
        </td></tr>
        <tr><td>Status</td><td>
          <input type="hidden" id="ztb_mhs__status_pernikahan" size="4" value="<?=$status_pernikahan?>">
          <select id="tb_mhs__status_pernikahan" class="form-control pilihan">
            <option value="0">--Pilih--</option>
            <option value="1">Belum Menikah</option>
            <option value="2">Menikah</option>
            <option value="3">Janda/Duda</option>
          </select>
          <small id="status_pernikahan_ket"></small>
        </td></tr>
        <tr><td>Jumlah Anak</td><td>
          <input type="hidden" id="ztb_mhs__jumlah_anak" size="4" value="<?=$jumlah_anak?>">
          <select id="tb_mhs__jumlah_anak" class="form-control pilihan">
            <option value="null">--Pilih--</option>
            <option>0</option>
            <option>1 orang</option>
            <option value="2">lebih dari 1 orang</option>
          </select>
          <small id="jumlah_anak_ket"></small>
        </td></tr>
        <tr><td>Pendidikan Terakhir</td><td>
          <input type="hidden" id="ztb_mhs__pendidikan_mhs" size="4" value="<?=$pendidikan_mhs?>">
          <select id="tb_mhs__pendidikan_mhs" class="form-control pilihan">
            <option value="0">--Pilih--</option>
            <option>SMA</option>
            <option>D1</option>
            <option>D3</option>
            <option>D4</option>
            <option>S1</option>
            <option>S2</option>
            <option>S3</option>
          </select>
          <small id="pendidikan_mhs_ket"></small>
        </td></tr>
        <tr><td>Asal Sekolah/Perti</td><td>
          <input type="text" id="tb_mhs__lulusan_mhs" class="form-control inputan" maxlength="50" value="<?=$lulusan_mhs?>">
          <input type="hidden" id="ztb_mhs__lulusan_mhs" value="<?=$lulusan_mhs?>">
          <small id="lulusan_mhs_ket"></small>
        </td></tr>
        <tr><td>Jabatan</td><td>
          <input type="text" id="tb_mhs__jabatan_mhs" class="form-control inputan" maxlength="50" value="<?=$jabatan_mhs?>">
          <input type="hidden" id="ztb_mhs__jabatan_mhs" value="<?=$jabatan_mhs?>">
          <small id="jabatan_mhs_ket">)* jabatan di STMIK IKMI Cirebon, misal: Staf Akademik, Staf Front Office, Kepala Lab, dll </small>
        </td></tr>
        <tr><td>Divisi</td><td>
          <input type="text" id="tb_mhs__divisi_mhs" class="form-control inputan" maxlength="50" value="<?=$divisi_mhs?>">
          <input type="hidden" id="ztb_mhs__divisi_mhs" value="<?=$divisi_mhs?>">
          <small id="divisi_mhs_ket">)* divisi di STMIK IKMI Cirebon, misal: BAK, BAU, FO, Perpus, dll</small>
        </td></tr>
        <tr><td>Email</td><td>
          <input type="email" id="tb_mhs__email_mhs" class="form-control inputan" maxlength="50" value="<?=$email_mhs?>">
          <input type="hidden" id="ztb_mhs__email_mhs" value="<?=$email_mhs?>">
          <small id="email_mhs_ket">Email dan Nomor whatsapp tidak akan dipublikasikan kecuali kepada sesama Civitas STMIK IKMI Cirebon</small>
        </td></tr>
        <tr><td>No Whatsapp</td><td>
          <input type="text" id="tb_mhs__no_wa_mhs" class="form-control inputan" maxlength="13" value="<?=$no_wa_mhs?>">
          <input type="hidden" id="ztb_mhs__no_wa_mhs" value="<?=$no_wa_mhs?>">
          <small id="no_wa_mhs_ket">Silahkan gunakan nomor whatsapp yang aktif agar Anda dapat menggunakan fitur whatsApp Gateway pada sistem SIAKAD</small>
        </td></tr>
        <tr><td>Alamat</td><td>
          <input type="text" id="tb_mhs__alamat_mhs" class="form-control inputan" maxlength="100" value="<?=$alamat_mhs?>">
          <input type="hidden" id="ztb_mhs__alamat_mhs" value="<?=$alamat_mhs?>">
          <small id="alamat_mhs_ket"></small>
        </td></tr>
        <tr><td>Kecamatan</td><td>
          <input type="text" id="tb_mhs__nama_kec" class="form-control" value="<?=$nama_kec?> - <?=$nama_kab?>">
          <input type="hidden" id="id_kec" value="<?=$id_kec?>">
          <input type="hidden" id="zid_kec" value="<?=$id_kec?>">
          <div id="list_nama_kec" style="font-size: small;background-color: #3ff;cursor: pointer;"></div>
          <small id="id_kec_ket">Silahkan ketik dan Pilih Kecamatan</small>
        </td></tr>
      </table>      
    </div>
    <div class="col-lg-6">
      <table class="table-bordered table-striped table-hover" width="100%" id="tbiopeg">
        <tr><td>Username</td><td>
          <input type="text" id="tb_mhs__username" class="form-control inputan" value="<?=$nim?>">
          <input type="hidden" id="ztb_mhs__username" value="<?=$nim?>">
          <small id="username_ket"></small>
        </td></tr>
        <tr><td>&nbsp;</td><td><button class="btn btn-danger btn-sm">Ubah Username</button></td></tr>
        <tr><td>Password Lama</td><td><input type="password" id="password_lama" class="form-control"></td></tr>
        <tr><td>Password Baru</td><td><input type="password" id="password_baru" class="form-control"></td></tr>
        <tr><td>Konfirmasi Password</td><td>
          <input type="password" id="cpassword_baru" class="form-control">
          <small id="password_ket"></small>
        </td></tr>
        <tr><td>&nbsp;</td><td><button class="btn btn-danger btn-sm">Ubah Password</button></td></tr>
      </table>
    </div>
  </div>
  <hr>
  <a href="?" class="btn btn-primary btn-sm">Back to Civitas Page</a>



  <!-- ================================================================== -->
  <!-- BIODATA DETAIL -->
  <!-- ================================================================== -->
  <hr>
  <h2>Biodata Detail</h2>

  <table class="table-bordered table-striped table-hover" width="100%" id="tedit">
    <thead>
      <th>No</th>
      <th>Nama Field</th>
      <th>Detail Pegawai</th>
    </thead>

    <?php 
    $s = "SELECT a.* from tb_mhs_detail a  
    join tb_mhs b on a.id_mhs_detail=b.id_mhs_detail where b.id_mhs = $id_mhs";
    $q = mysqli_query($cn,$s) or die("Tidak dapat mengakses tabel pegawai. ".mysqli_error($cn));
    $d = mysqli_fetch_assoc($q);

    $tb_mhs_detail_field_ket['saya_sebagai'] = "Pisahkan dg koma, contoh: Web Developer, Operator, Admin, Cooker, Teknisi, dll. Akan muncul sebagai animasi di Content Page Civitas paling atas";

    $i = 0;
    foreach ($tb_mhs_detail_fields as $field) {
      $i++;
      $j=$i-1;
      $field_value = $d[$field];
      $field_type = $tb_mhs_detail_field_types[$j];
      $field_ket = $tb_mhs_detail_field_ket[$field];
      if($field_ket!="")$field_ket="$field_ket - ";

      $disable_input = '';

      if($field=="id_mhs") $disable_input = "disabled";

      $field_length_ket = '';

      $pos_a = strpos($field_type, "(");
      $pos_b = strpos($field_type, ")");
      $field_length = intval(substr($field_type, $pos_a+1, $pos_b-$pos_a-1));
      if($field_length>11)$field_length_ket = "Max: $field_length karakter";

      if($i==1) $disable_input = "disabled";

      if($field_length>100){
        $inputs = "
            <textarea rows=4 class='form-control inputan' id='tb_mhs_detail__$field' $disable_input>$field_value</textarea>
            <textarea id='ztb_mhs_detail__$field' style='display:none'>$field_value</textarea>
        ";

      }else{

        $inputs = "
            <input type='text' value='$field_value' class='form-control inputan' id='tb_mhs_detail__$field' $disable_input>
            <input type='hidden' value='$field_value' id='ztb_mhs_detail__$field'>
        ";
      }

      $field_ket_id = "$field"."_ket";

      echo "
      <tr>
        <td align=center>$i</td>
        <td>$field</td>
        <td>$inputs
          <small id='$field_ket_id'>$field_ket$field_length_ket</small>
        </td>
      </tr>
      ";


    }
    ?>
  </table>

  <a href="?" class="btn btn-primary m-2">Back to Civitas Page</a>
</div>

<script type="text/javascript">
  $(document).ready(function(){

    $(".pilihan").change(function(){
      var id = $(this).prop("id");
      var isi = $(this).val();
      var zisi = $("#z"+id).val();
      var rid = id.split("__");
      var nama_tabel = rid[0];
      var field = rid[1];

      var field_acuan = "id_mhs";
      if(nama_tabel=="tb_mhs_detail") field_acuan="id_mhs_detail";

      var isi_field_acuan = $("#"+nama_tabel+"__"+field_acuan).val();
      console.log("#"+nama_tabel+"__"+field_acuan);

      if(isi==zisi) return;
      if(isi.trim()==""){
        var x = confirm("Anda yakin mengosongkan nilai untuk field: "+field+"?");
        if(!x) {$(this).val(zisi); return;}
      }

      var link_ajax = "ajax_civitas/ajax_update_mhs.php"
      +"?nama_tabel="+nama_tabel
      +"&field="+field
      +"&isi="+isi
      +"&field_acuan="+field_acuan
      +"&isi_field_acuan="+isi_field_acuan
      +'';


      $.ajax({
        url:link_ajax,
        success:function(a){
          if(a.substring(0,3)=="1__"){
            var ra = a.split("__");
            var pesan_sukses = ra[1];
            $("#z"+id).val(isi);
            // alert(pesan_sukses);
            $("#"+field+"_ket").html("<span style='color:green;font-weight:bold'>Update Success</span>");
          }else{
            alert(a)
            $("#"+field+"_ket").html("<span style='color:red;font-weight:bold'>Update Failed</span>");
          }
        }
      })
    })

    $(".inputan").focusout(function(){
      var id = $(this).prop("id");
      var isi = $(this).val();
      var zisi = $("#z"+id).val();
      var rid = id.split("__");
      var nama_tabel = rid[0];
      var field = rid[1];

      var field_acuan = "id_mhs";
      if(nama_tabel=="tb_mhs_detail") field_acuan="id_mhs_detail";

      var isi_field_acuan = $("#"+nama_tabel+"__"+field_acuan).val();
      console.log("#"+nama_tabel+"__"+field_acuan);

      if(isi==zisi) return;
      if(isi.trim()==""){
        var x = confirm("Anda yakin mengosongkan nilai untuk field: "+field+"?");
        if(!x) {$(this).val(zisi); return;}
      }

      var link_ajax = "ajax_civitas/ajax_update_mhs.php"
      +"?nama_tabel="+nama_tabel
      +"&field="+field
      +"&isi="+isi
      +"&field_acuan="+field_acuan
      +"&isi_field_acuan="+isi_field_acuan
      +'';


      $.ajax({
        url:link_ajax,
        success:function(a){
          if(a.substring(0,3)=="1__"){
            var ra = a.split("__");
            var pesan_sukses = ra[1];
            $("#z"+id).val(isi);
            // alert(pesan_sukses);
            $("#"+field+"_ket").html("<span style='color:green;font-weight:bold'>Update Success</span>");
          }else{
            alert(a)
            $("#"+field+"_ket").html("<span style='color:red;font-weight:bold'>Update Failed</span>");
          }
        }
      })
    })

    $("#tb_mhs__nama_kec").keyup(function(){
      var val = $(this).val();
      $("#list_nama_kec").html("");
      if(val.length<3) return;

      var link_ajax = "ajax_civitas/get_list_nama_kec.php?q="+val;

      $.ajax({
        url:link_ajax,
        success:function(a){
          $("#list_nama_kec").html(a)
        }
      })
    })

  })

  $(document).on("click",".list_nama_kec",function(){
    var id = $(this).prop("id");
    var rid = id.split("__");
    var id_kec = rid[1];
    var nama_kec = $(this).text();
    $("#id_kec").val(id_kec);
    $("#tb_mhs__nama_kec").val(nama_kec);
    $("#list_nama_kec").html("")

    var field = "id_kec";
    var field_acuan = "id_mhs";
    var nama_tabel = "tb_mhs";
    var isi_field_acuan = $("#tb_mhs__id_mhs").val();
    var link_ajax = "ajax_civitas/ajax_update_mhs.php"
    +"?nama_tabel="+nama_tabel
    +"&field="+field
    +"&isi="+id_kec
    +"&field_acuan="+field_acuan 
    +"&isi_field_acuan="+isi_field_acuan
    +'';


    $.ajax({
      url:link_ajax,
      success:function(a){
        if(a.substring(0,3)=="1__"){
          var ra = a.split("__");
          var pesan_sukses = ra[1];
          $("#z"+id).val(id_kec);
          // alert(pesan_sukses);
          $("#"+field+"_ket").html("<span style='color:green;font-weight:bold'>Update Success</span>");
        }else{
          alert(a)
          $("#"+field+"_ket").html("<span style='color:red;font-weight:bold'>Update Failed</span>");
        }
      }
    })
  })
</script>