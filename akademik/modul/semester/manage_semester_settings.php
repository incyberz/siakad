<?php
$w = date('w',strtotime($batas_awal));
$add_days = $w<=1 ? (1-$w) : (8-$w);

$tanggal_senin_pertama = date('Y-m-d',strtotime("+$add_days day",strtotime($batas_awal)));
$batas_awal_show = date('D, d M Y',strtotime($batas_awal));
?>
<div class="wadah">
  <form action="" method="post">
    <h3 class='m0 mb2'>Seting Pembayaran dan KRS</h3>
    <div class="form-group">
      <div>
        <label for="radio_senin_pertama">
          <input type="radio" id="radio_senin_pertama" name="radio_senin_pertama" checked> 
          <small>Awal Pembayaran mengacu ke Senin Pertama</small>
        </label>      
      </div>
      <div>
        <label for="radio_senin_pertama2">
          <input type="radio" id="radio_senin_pertama2" name="radio_senin_pertama"> 
          <small>Awal Pembayaran mengacu ke Batas Awal Semester</small>
        </label>      
      </div>

      <div class="wadah">
        <label for="senin_pertama_show">Senin Pertama <br><small><i>Hari Senin Pertama pada Batas Semester yaitu tanggal: </i></small></label>
        <input class="form-control mb2" type="date" name="senin_pertama_show" id="senin_pertama_show" value=<?=$tanggal_senin_pertama?> disabled>
        <input class=debug id="senin_pertama" name="senin_pertama" value=<?=$tanggal_senin_pertama?>>
      </div>
    </div>
    <div class="form-group">
      <label for="durasi_pembayaran">Durasi Pembayaran <small><i>(hari)</i></small></label>
      <select name="durasi_pembayaran" id="durasi_pembayaran" class="form-control">
        <?php for ($i=1; $i <= 50 ; $i++) { 
          $selected = $i==14 ? 'selected' : '';
          echo "<option $selected>$i</option>";
        } ?>
      </select>
    </div>
    <div class="form-group">
      <div>
        <label for="jeda_krs">
          <input type="radio" id="jeda_krs" name="jeda_krs" checked> 
          Tanggal Awal KRS adalah sesudah Jatuh Tempo Pembayaran
        </label>      
      </div>
      <div>
        <label for="jeda_krs2">
          <input type="radio" id="jeda_krs2" name="jeda_krs"> 
          Tanggal Awal KRS adalah sama dengan Tanggal Awal Pembayaran
        </label>      
      </div>
      <div class='flexy'>
        <div>
          <label for="jeda_krs3">
            <input type="radio" id="jeda_krs3" name="jeda_krs"> 
            Tanggal Awal KRS bergeser selama:
          </label>      
        </div>
        <div>
          <select name="geser_pembayaran" id="geser_pembayaran" class="form-control">
            <?php for ($i=-14; $i <= 7 ; $i++) { 
              $selected = $i==0 ? 'selected' : '';
              echo "<option $selected>$i</option>";
            } ?>
          </select>
        </div>
        <div>
          <label for="jeda_krs3">hari setelah Jatuh Tempo Pembayaran</label>
        </div>

      </div><!-- End of Flexy -->
    </div><!-- End of Form-Group -->
    <div class="form-group">
      <label for="durasi_krs">Durasi KRS <small><i>(hari)</i></small></label>
      <select name="durasi_krs" id="durasi_krs" class="form-control">
        <?php for ($i=1; $i <= 21 ; $i++) { 
          $selected = $i==5 ? 'selected' : '';
          echo "<option $selected>$i</option>";
        } ?>
      </select>
    </div>

    <div class="form-group">
      <label for="minggu_tenang_uts">durasi minggu tenang UTS</label>
      <select name="minggu_tenang_uts" id="minggu_tenang_uts" class="form-control">
        <option>0</option>
        <option>1</option>
        <option>2</option>
      </select>
    </div>

    <div class="form-group">
      <label for="minggu_tenang_uas">durasi minggu tenang UAS</label>
      <select name="minggu_tenang_uas" id="minggu_tenang_uas" class="form-control">
        <option>0</option>
        <option>1</option>
        <option>2</option>
      </select>
    </div>

    <div class="form-group">
      <label for="durasi_uts">durasi UTS</label>
      <select name="durasi_uts" id="durasi_uts" class="form-control">
        <option>1</option>
        <option>2</option>
        <option>3</option>
      </select>
    </div>

    <div class="form-group">
      <label for="durasi_uas">durasi UAS</label>
      <select name="durasi_uas" id="durasi_uas" class="form-control">
        <option>1</option>
        <option>2</option>
        <option>3</option>
      </select>
    </div>

    <div class="form-group">
      <div for="awal_kuliah">Awal Perkuliahan</div>
      <input type="checkbox" checked id="cek_awal_kuliah">
      <label for="cek_awal_kuliah">Automatic pada Senin Pertama setelah Minggu KRS</label>
      <input type=date id="awal_kuliah_show" class="form-control" disabled>
      <input type=date class='debug form-control' name="awal_kuliah" id="awal_kuliah">
    </div>

    <div class="form-group">
      <button class="btn btn-primary btn-block" name="btn_apply_setting">Apply Setting</button>
      <small><i>Setelah Apply Setting Anda dapat menyimpan Aturan Tanggal pada Semester</i></small>
    </div>    
  </form>

</div>
