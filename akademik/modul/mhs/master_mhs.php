<h1>Master Mahasiswa</h1>
<style>.blok_filter{
  display: flex; gap:5px
}.show_records{
  font-size:small;
  border:solid 1px #ccc; 
  display:flex; 
  gap:15px; 
  background:white; 
  border-radius:5px; 
  padding:5px 10px; 
  margin-bottom:5px
}.bg_green{ background: #5f5;
}.bg_red{ background: #fcc;
}
</style>
<?php
# =============================================================
# OPTIONAL GET VARIABLE
# =============================================================
$keyword = $_GET['keyword'] ?? '';
$angkatan = $_GET['angkatan'] ?? '';
$status_mhs = $_GET['status_mhs'] ?? '';
$id_prodi = $_GET['id_prodi'] ?? '';
$id_jalur = $_GET['id_jalur'] ?? '';
$shift = $_GET['shift'] ?? '';

$bg_green_keyword = $keyword=='' ? '' : 'bg_green';
$bg_green_angkatan = $angkatan=='' ? '' : 'bg_green';
$bg_green_id_prodi = $id_prodi=='' ? '' : 'bg_green';
$bg_green_id_jalur = $id_jalur=='' ? '' : 'bg_green';
$bg_green_shift = $shift=='' ? '' : 'bg_green';

$selected_status[0] = $status_mhs==0 ? 'selected' : '';
$selected_status[1] = $status_mhs==1 ? 'selected' : '';

$selected_shift['pagi'] = $shift=='pagi' ? 'selected' : '';
$selected_shift['sore'] = $shift=='sore' ? 'selected' : '';

# =============================================================
# INCLUDES
# =============================================================
include '../include/include_rid_prodi.php';
include '../include/include_rid_jalur.php';
include '../include/include_rangkatan.php';

# =============================================================
# GLOBAL VARIABEL
# =============================================================
$null = '<span class="red miring kecil">null</span>';

# =============================================================
# GET VARIABEL
# =============================================================
$keyword = $_GET['keyword'] ?? '';
$status_mhs = $_GET['status_mhs'] ?? '';
$id_prodi = $_GET['id_prodi'] ?? '';
$id_jalur = $_GET['id_jalur'] ?? '';

# =============================================================
# BLOK FILTER
# =============================================================
?>
<div class="blok_filter mb2">
  <div>
    <input class="<?=$bg_green_keyword?> form-control input-sm " placeholder='nim atau nama' id=keyword style='width:100px' value='<?=$keyword?>'>
    <span id=keyword_tmp class=debug><?=$keyword?></span>
  </div>

  <div>
    <select class="form-control input-sm filter filter_select" id="status_mhs_filter">
      <option value=1 <?=$selected_status[1]?>>Aktif</option>
      <option value=0 <?=$selected_status[0]?>>Non-Aktif</option>
    </select>
  </div>

  <div>
    <select class="<?=$bg_green_angkatan?> form-control input-sm filter filter_select filter_green" id="angkatan_filter">
      <option value=all>All</option>
      <?php 
      for ($i=0; $i < count($rangkatan) ; $i++) { 
        $selected = $rangkatan[$i] == $angkatan ? 'selected' : '';
        echo "<option value='$rangkatan[$i]' $selected>$rangkatan[$i]</option>";
      }
      ?>
    </select>
  </div>

  <div>
    <select class="<?=$bg_green_id_jalur?> form-control input-sm filter filter_select filter_green" id="id_jalur_filter">
      <option value=all>All Jalur</option>
      <?php 
      for ($i=0; $i < count($rjalur) ; $i++) { 
        $jalur = $rjalur[$rid_jalur[$i]];
        $selected = $rid_jalur[$i] == $id_jalur ? 'selected' : '';
        echo "<option value='$rid_jalur[$i]' $selected>$jalur</option>";
      }
      ?>
    </select>
  </div>

  <div>
    <select class="<?=$bg_green_id_prodi?> form-control input-sm filter filter_select filter_green" id="id_prodi_filter">
      <option value=all>All Prodi</option>
      <?php 
      for ($i=0; $i < count($rprodi) ; $i++) { 
        $prodi = $rprodi[$rid_prodi[$i]];
        $selected = $rid_prodi[$i] == $id_prodi ? 'selected' : '';
        echo "<option value='$rid_prodi[$i]' $selected>$prodi</option>";
      }
      ?>
    </select>
  </div>
  
  <div>
    <select class="<?=$bg_green_shift?> form-control input-sm filter filter_select filter_green" id="shift_filter">
      <option value=all>All Shift</option>
      <option value='pagi' <?=$selected_shift['pagi']?>>Kelas Pagi</option>
      <option value='sore' <?=$selected_shift['sore']?>>Kelas Sore</option>
    </select>
  </div>

  <div>
    <select class="form-control input-sm filter filter_select" id="limit">
      <option value=10>Show 10</option>
      <option value=25>Show 25</option>
      <option value=50>Show 50</option>
      <option value=100>Show 100</option>
      <option value=500>Show 500</option>
      <option value=9999>Show All</option>
    </select>
  </div>

  <div>
    <select class="form-control input-sm filter filter_select" id="order_by">
      <option value="a.nama">By Nama</option>
      <option value="a.nim">By NIM</option>
    </select>
  </div>

  <div>
    <button class="btn btn-success btn-sm" id="btn_get_csv">Get CSV</button>
  </div>

  <!-- <div>
    <label><input type="checkbox" id="show_foto"> <small>Show Foto</small></label>
  </div> -->

</div>

<div id=hasil_ajax></div>

<script>
  $(function(){
    $('.filter_select').change(function(){
      let keyword = $('#keyword').val().replace("'", "`").trim();
      let status_mhs = $('#status_mhs_filter').val();
      let angkatan = $('#angkatan_filter').val();
      let id_prodi = $('#id_prodi_filter').val();
      let id_jalur = $('#id_jalur_filter').val();
      let shift = $('#shift_filter').val();
      let limit = $('#limit').val();
      let order_by = $('#order_by').val();
      let link_ajax = `modul/mhs/master_mhs_fetch.php?angkatan=${angkatan}&id_prodi=${id_prodi}&id_jalur=${id_jalur}&shift=${shift}&status_mhs=${status_mhs}&order_by=${order_by}&limit=${limit}&keyword=${keyword}`;
      $.ajax({
        url:link_ajax,
        success:function(a){
          $('#hasil_ajax').html(a);
        }
      })
    });

    $('.filter_select').change();

    $('.filter_green').change(function(){
      if($(this).val()=='all'){
        $(this).removeClass('bg_green');
      }else{
        $(this).addClass('bg_green');
      }
    })

    $('#status_mhs_filter').change(function(){
      if($(this).val()==1){
        $(this).removeClass('bg_red');
      }else{
        $(this).addClass('bg_red');
      }
    })
  })

  $('#keyword').on('keypress',function(e) {
    if(e.which == 13) {

      if($(this).val()!=$('#keyword_tmp').text()){
        $('.filter_select').change();
        $(this).addClass('bg_green');
        $('#keyword_tmp').text($(this).val());
      }
    }
  });
</script>