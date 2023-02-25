<h1 data-aos="fade-up">Welcome to SIAKAD</h1>
        <?php 

        if($url_parameter=="logout"){
          # =======================================================
          # LOGOUT
          # =======================================================
          if(!isset($_SESSION)) session_start();
          
          session_unset();

          echo "
          <div class='alerta alerta-success' data-aos='fade-up' data-aos-delay='400'>
            <h2>Goodbye $cnama_pegawai!</h2>
            Semoga hari Anda bermanfaat.
            <hr>
            <a href='?' class='btn btn-success'>Relogin</a>
          </div>

          <script>
            location.replace('index.php');
          </script>
          ";

        }elseif(isset($_POST['btn_login'])){

          if(!isset($cn)) die("Sorry, something happens with db-connections!");

          $username = filter_var($_POST['username']);
          $password = filter_var($_POST['password']);

          $s = "SELECT a.admin_level,a.nama_pegawai,b.jenis_user 
          from tb_pegawai a 
          join tb_admin_level b ON a.admin_level=b.admin_level 
          where a.username='$username' and a.password = '$password'";
          $q = mysqli_query($cn,$s) or die("Tidak dapat mengakses data login #1");

          if(mysqli_num_rows($q)==1){
            $d = mysqli_fetch_assoc($q);
            $is_login = 1;

            $admin_level = $d['admin_level'];
            $nama_pegawai = $d['nama_pegawai'];
            $jenis_user = $d['jenis_user'];
            $nama_pegawai = ucwords(strtolower($nama_pegawai));
            $jenis_user = ucwords(strtolower($jenis_user));

            if(!isset($_SESSION)) session_start();
            $_SESSION['siakad_username'] = $username;
            $_SESSION['cadmin_level'] = $admin_level;
            $_SESSION['cnama_pegawai'] = $nama_pegawai;
            $_SESSION['cjenis_user'] = $jenis_user;

            echo "
            <div class='alerta alerta-success' data-aos='fade-up' data-aos-delay='400'>
            <h2>Selamat $waktu $nama_pegawai!</h2>
            Hak Akses Anda sebagai <strong style='color:red'>$jenis_user</strong>.
            <hr>
            <a href='civitas/' class='btn btn-success'>Go to My Page</a>
            <a href='#fitur_siakad' class='btn btn-primary scrollto'>Akses Fitur SIAKAD</a>

            </div>
            <script>
              location.replace('index.php');
            </script>
            ";

          }else{
            echo "
            <div class='alert alert-danger' data-aos='fade-up' data-aos-delay='400'>
            Maaf, Username dan Password Anda tidak cocok.
            <hr>
            <a href='?username=$username' class='btn btn-primary' style='border-radius:10px'>Coba Lagi</a>
            </div>";

          }

        }else{ 
          # =======================================================
          # NOT POST BTN_LOGIN CLICK
          # =======================================================
          if($is_login){
            ?>
            <!-- ======================================================= -->
            <!-- LOGIN STATE -->
            <!-- ======================================================= -->
            <div data-aos="fade-up" data-aos-delay="400">
              <h2>Selamat <?=$waktu?> <?=$cnama_pegawai ?>!</h2>
              <p>Anda sedang login sebagai <strong style="color: red"><?=$cjenis_user ?></strong>.</p>
            </div>
            <div data-aos="fade-up" data-aos-delay="800" id="blok_tombol_login">
              <a href='civitas/' class='btn-get-started'>My Page</a>
              <a href='#fitur_siakad' class='btn-get-started scrollto' id='btn_goto_fitur'>Access Features</a>
            </div>

            <?php

          }else{

            ?>

            <!-- ======================================================= -->
            <!-- NOT LOGIN -->
            <!-- ======================================================= -->
            <h2 data-aos="fade-up" data-aos-delay="400">Please Login to access sub-features !</h2>
            <div data-aos="fade-up" data-aos-delay="800" id="blok_tombol_login">
            </div>

            <div class="row" id="blok_login" data-aos="fade-up" data-aos-delay="400">
              <div class="col-lg-12">
                <?php include "login.php"; ?>

              </div>
            </div>


          <?php }} ?>