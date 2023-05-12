<section id="set_room" class="visitor">
  <div class="container">

<div class="row">
  <div class="col-lg-12">
    <h3 class="page-header"><i class="fa fa-laptop"></i>Ooppss... something wrong with page!!</h3>
  </div>
</div>

<?php 
$a = $_SERVER['REQUEST_URI'];
?>
<h1 class="merah tebal">404</h1>
<h3 class="merah tebal">Page Not Found!</h3>
<hr>
<p>Sepertinya Anda nyasar!!? Atau mungkin fiturnya belum ada.</p>
<p>Jangan khawatir, sistem telah mencatatnya... :)</p>
<hr>

<small>
  Broken-Link: <i><?=$a?></i> has been saved at <?=date("Y-m-d H:i:s")?>. Programmer will be soon fixed it!
  <hr><?=$btn_back?> 
</small>

</div>
</section>