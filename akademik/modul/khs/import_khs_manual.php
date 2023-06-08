<h1>Import KHS Manual</h1>
<style>th{text-align:left}</style>
<?php




?>
<div class="wadah">
  Langkah Import:
  <ol>
    <li>Download <a href="#">Template CSV</a>, buka dengan Ms.Excel</li>
    <li>Copy Data lalu Paste Values pada Template CSV</li>
    <li>Simpan lalu klik Browse</li>
    <li>Akan muncul Preview Perubahan data, klik Next jika setuju.</li>
  </ol>
</div>

<form method=post enctype='multipart/form-data'>
  <div class="form-group">
    <label for="csv_nilai">File CSV:</label>
    <input type="file" name="csv_nilai" id="csv_nilai" class=form-control required accept='.csv'>
  </div>
  <div class="form-group">
    <button class='btn btn-primary btn-block'>Upload</button>
  </div>
</form>