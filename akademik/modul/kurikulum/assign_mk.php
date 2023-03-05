<style>
  .pilihan_mk {cursor: pointer; padding: 5px}
  .pilihan_mk:hover{
    background: #cfc;
    font-weight: bold;
  }
</style>
<?php
if(isset($_POST['btn_assign'])){
  // die(var_dump($_POST));
  $s = "INSERT INTO tb_kurikulum_mk (id_semester,id_mk) VALUES ($_POST[id_semester],$_POST[id_mk])";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  echo "<div class='alert alert-success'>Assign MK Sukses<hr><a class='btn btn-success' href='?kurikulum&id=$_POST[id_kurikulum]'>kembali ke Kurikulum</a></div>";
  exit;
}

$id_kurikulum = isset($_GET['id_kurikulum']) ? $_GET['id_kurikulum'] : die("<script>location.replace('?master&p=kurikulum')</script>");
$nama_kurikulum = isset($_GET['nama_kurikulum']) ? $_GET['nama_kurikulum'] : die("<script>location.replace('?master&p=kurikulum')</script>");
$id_semester = isset($_GET['id_semester']) ? $_GET['id_semester'] : die("<script>location.replace('?kurikulum&id=$id_kurikulum')</script>");
$no_semester = isset($_GET['no_semester']) ? $_GET['no_semester'] : die("<script>location.replace('?kurikulum&id=$id_kurikulum')</script>");

$select_mk = "<select class='form-control' name='id_mk' id='id_mk' >";

for ($i=0; $i < 7; $i++) { 
  $select_mk .= "<option>Pilihan $i</option>";
}

$select_mk .= "</select>";








?>
<h1>Assign MK pada Kurikulum</h1>
<?=$btn_back?>
<form method=post>
  <table class='table table-hover table-dark'>
    <tr>
      <td>Nama Kurikulum</td>
      <td>
        <?=$nama_kurikulum?>
        <input class=debug name=id_semester value=<?=$id_semester?>>
        <input class=debug name=id_kurikulum  id=id_kurikulum value=<?=$id_kurikulum?>>
      </td>
    </tr>

    <tr>
      <td>Semester</td>
      <td><?=$no_semester ?></td>
    </tr>

    <tr class=debug>
      <td>ID MK</td>
      <td>
        <input name='id_mk' id='id_mk'>
      </td>
    </tr>

    <tr>
      <td>Mata Kuliah</td>
      <td>
        <input class='form-control' id='nama_mk' name='nama_mk' placeholder='Silahkan ketik dan pilih' minlength=3 maxlength=100 required>
        <input class='debug' id='nama_mk2'>
        <div id='blok_list_mk'></div>
      </td>
    </tr>

    <tr>
      <td>&nbsp;</td>
      <td>
        <button class='btn btn-primary btn-block' id=btn_assign name=btn_assign>Assign</button>
      </td>
    </tr>

  </table>
</form>




<script>
  $(function(){
    $('#nama_mk').keyup(function(){
      let t = $(this).val();
      let t2 = $("#nama_mk2").val();
      if(t==t2) return;
      $('#btn_assign').prop("disabled",true);
      $('#id_mk').val("");
      if(t.length<3){
        $('#blok_list_mk').html("<ul><li><i class='abu'>-- ketik minimal 3 karakter --</i></li></ul>")
      }else{

        let id_kurikulum = $("#id_kurikulum").val();
        $.ajax({
          url: `ajax_akademik/get_list_mk_for_kurikulum.php?id_kurikulum=${id_kurikulum}&keyword=${t}&`,
          success:function(a){
            $("#blok_list_mk").fadeIn();
            $('#blok_list_mk').html(a);
          }
        })

      }
      $("#nama_mk2").val(t);
    });
    $('#nama_mk').keyup();

    $(document).on("click",".pilihan_mk",function(){
      $("#blok_list_mk").fadeOut();
      $("#blok_list_mk").html("");

      let rt = $(this).text().split(" ~ ");
      $("#nama_mk").val(rt[0]);
      $("#id_mk").val(rt[1]);
      $('#btn_assign').prop("disabled",false);

    });
    $(".pilihan_mk").click(function(){
      

    });
  })
</script>