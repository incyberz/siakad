<nav class="nav-menu d-none d-lg-block">
  <ul>
    <li class="active"><a href="#hero" class="link_header">Home</a></li>
    <!-- <li><a href="#about">About</a></li> -->
    <li><a href="#fitur_siakad" class="link_header">Fitur</a></li>
    <li><a href="#info_siakad" id="link_informations" class="link_header">Informasi</a></li>
    <li><a href="#progres_siakad" id="link_progres" class="link_header">Progres</a></li>
    <!-- <li><a href="#cari_profil_dosen" id="link_cari_profil_dosen" class="link_header">Profil Dosen</a></li> -->
    <li><a href="#team" id="link_team" class="link_header">Tim</a></li>
    <!-- <li><a href="#pricing">Pricing</a></li> -->
    <!-- <li class="drop-down"><a href="">Drop Down</a>
      <ul>
        <li><a href="#">Drop Down 1</a></li>
        <li class="drop-down"><a href="#">Drop Down 2</a>
          <ul>
            <li><a href="#">Deep Drop Down 1</a></li>
            <li><a href="#">Deep Drop Down 2</a></li>
            <li><a href="#">Deep Drop Down 3</a></li>
            <li><a href="#">Deep Drop Down 4</a></li>
            <li><a href="#">Deep Drop Down 5</a></li>
          </ul>
        </li>
        <li><a href="#">Drop Down 3</a></li>
        <li><a href="#">Drop Down 4</a></li>
        <li><a href="#">Drop Down 5</a></li>
      </ul>
    </li>
    <li><a href="#contact">Contact</a></li> -->

    <?php if($is_login) echo "<li class='get-started'><a href='?logout' id='link_header__logout' onclick='return confirm(\"Yakin untuk Logout?\")'>Logout</a></li>"; ?>

  </ul>
</nav>




<script type="text/javascript">
  $(document).ready(function(){
    $(".link_header").click(function(){
      var id = $(this).prop("id");

      if(id=="link_cari_profil_dosen"){
        // $("section").hide();
        // $("#hero").hide();
        // $("#cari_profil_dosen").fadeIn();

      }
    })
  })
</script>