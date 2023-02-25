<section id="progres_siakad" class="team section-bg">
  <div class="container">

    <div class="section-title" data-aos="fade-up">
      <h2>Progres</h2>
      <p>Progres Pengembangan SIAKAD STMIK IKMI Cirebon!</p>
    </div>

    <div id="zzz" class="text-center" data-aos="fade-up" data-aos-delay="100">

      <style>
        .flexy{
          display: flex;
          flex-wrap: wrap;
          margin: auto;
          /*border: solid 1px yellow;*/
          align-items: center;
          justify-content: center;
        }
        .bola{
          border: solid 1px #ccf;
          background: linear-gradient(#ffe,#aff);
          border-radius: 50%;
          text-align: center;
          height: 100px;
          width: 100px;
          padding-top: 30px;
          margin: 10px;
        }

        .bola .nama_fitur{ 
          font-size: 8pt;
          margin-bottom: 5px;
        }


      </style>

      <div class="flexy">

        <?php 
        $fitur = [
          'Data Center Mhs',
          'Portal Civitas',
          'KRS Online',
          'KHS Online',
          'KuliahKu Apps'
        ];

        $is_sudah = [1,1,0,1,0];

        for ($i=0; $i < count($fitur); $i++) { 
          $icon = $is_sudah[$i]==1?'check_small':'warning';
          echo "
          <div class='bola'>
            <div class='nama_fitur'>$fitur[$i]</div>
            <img width='20px' src='assets/img/icons/$icon.png'>
          </div>
          ";
          if($i!=(count($fitur)-1)) echo "
          <div class='panah'>
            <img src='assets/img/icons/next.png'>
          </div>
          ";
        }

        ?>


      </div>

      <!-- <a href="progres/" class="btn btn-success btn-sm">Go to Progres SIAKAD</a> -->
    </div>

  </div>
</section>