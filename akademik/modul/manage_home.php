<?php
include 'include/rmaster.php';

?>
<style>
  .manage-home{
    display:flex;
    flex-wrap: wrap;
  }

  .item-master{
    width: 200px;
    height: 150px;
    background: linear-gradient(#afa,#fff,#afa);
    margin: 0 15px 15px 0;
    border-radius: 10px;
    text-align: center;
    padding: 15px;
    text-transform: uppercase;
    font-size: 24px;
  }


</style>
<div class="manage-home">
  <?php

  for ($i=0; $i < count($rmaster); $i++) { 
    $text = str_replace('_',' ',$rmaster[$i]);
    echo "

    <div class='item-master'>
      <a href='?manage&p=$rmaster[$i]'>manage<br> $text</a>

    </div>


    ";
  }

  ?>

</div>