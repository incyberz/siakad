<section id="my_docs" class="my_docs">
  <div class="container">

    <div class="section-title">
      <h2>Dokumen Saya</h2>
      <p>Berikut ini adalah dokumen-dokumen yang Anda upload.</p>
    </div>

    <?php 
    $no_docs = div_alert('info','Belum ada dokumen yang Anda upload');
    $path = "uploads/$folder_uploads";
    if(file_exists($path)){
      $files = scandir($path);

      $li = '';
      foreach ($files as $file) {
        if(strlen($file)>2){
          $li .= "<li><a href='$path/$file' target=_blank onclick='return confirm(\"Ingin membuka file ini di TAB baru?\")'>$file</a></li>";
        }
      }

      echo $li=='' ? $no_docs : "<ol>$li</ol>";


    }else{
      echo $no_docs;
    }

    ?>

    <div class="p-2 mt-4" style="border-top:solid 1px #ccc">
      <a href="?about">Kembali ke About</a>  
    </div>

  </div>
</section>