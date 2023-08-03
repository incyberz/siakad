<header id="header" class="fixed-top d-flex align-items-center">
  <div class="container d-flex align-items-center">

    <div class="logo mr-auto">
      <h1 class="text-light"><a href="#hero">
      	<img src="assets/img/siakad-logo.png">
      </a></h1>
      <!-- Uncomment below if you prefer to use an image logo -->
      <!-- <a href="index.html"><img src="assets/img/logo.png" alt="" class="img-fluid"></a>-->
    </div>

    <nav class="nav-menu d-none d-lg-block">
      <ul>
        <li class="active"><a href="#hero" class="">Home</a></li>
        <!-- <li><a href="#about">About</a></li> -->
        <li><a href="#fitur_siakad" class="">Fitur</a></li>
        <!-- <li><a href="#info_siakad" id="link_informations" class="">Informasi</a></li> -->
        <!-- <li><a href="#progres_siakad" id="link_progres" class="">Progres</a></li> -->
        <!-- <li><a href="#cari_profil_dosen" id="link_cari_profil_dosen" class="">Profil Dosen</a></li> -->
        <li><a href="#team" id="link_team" class="">Tim</a></li>

        <?php if($is_login) echo "
        <li class=' drop-down'><a href=''>$nama_user</a>
          <ul>
            <li><a href='?logout' onclick='return confirm(\"Yakin untuk Logout?\")'>Logout</a></li>
            <li><a href='?ubah_password'>Ubah Password</a></li>
          </ul>
        </li>"; ?>

      </ul>
    </nav>
    
  </div>
</header>
