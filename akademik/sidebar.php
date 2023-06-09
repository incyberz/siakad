<aside>
  <div id="sidebar" class="nav-collapse ">
    <ul class="sidebar-menu">
      <?php 
      // for ($i=0; $i < count($menu) ; $i++) { 
      //   if(isset($menu[$i]) and $menu[$i][0]==1)echo "<li><a href='?".$menu[$i][1]."' ".$menu[$i][5]."><i class='icon_".$menu[$i][4]."'></i><span>".$menu[$i][3]."</span></a></li>";
      // }
      ?>
      <li class=proper><a href="?"><i class="icon_easel_alt"></i>Dashboard</a></li>
      <li class=proper><a href="?master"><i class="icon_genius"></i>Master Data</a></li>
      <li class=proper><a href="?manage"><i class="icon_genius"></i>Manage</a></li>
      <!-- <li class=proper><a href="?master&p=kalender"><i class="icon_genius"></i>Manage Kalender</a></li>
      <li class=proper><a href="?master&p=kurikulum"><i class="icon_genius"></i>Manage Kurikulum</a></li>
      <li class=proper><a href="?manage_jadwal"><i class="icon_genius"></i>Manage Jadwal</a></li>
      <li class=proper><a href="?manage_kelas"><i class="icon_genius"></i>Manage Kelas</a></li>
      <li class=proper><a href="?manage_sesi"><i class="icon_genius"></i>Manage Sesi</a></li>
      <li class=proper><a href="?manage_peserta"><i class="icon_genius"></i>Manage peserta</a></li>
      <li class=proper><a href="?manage_mhs"><i class="icon_genius"></i>Manage Mhs</a></li> -->
      <li class=proper><a href="?dpnu"><i class="icon_genius"></i>DPNU</a></li>

      <li class=proper><a href="?pembayaran_home"><i class="icon_genius"></i>Pembayaran</a></li>
      <!-- <li class=proper><a onclick="belom()"><i class="icon_genius"></i><span class=merah>Pembayaran</span></a></li> -->
      <li class=proper><a onclick="belom()"><i class="icon_genius"></i><span class=merah>KRS</span></a></li>
      <!-- <li class=proper><a onclick="belom()"><i class="icon_genius"></i><span class=merah>KHS</span></a></li> -->
      <li class=proper><a href="?khs"><i class="icon_genius"></i>KHS</a></li>
      <li class=proper><a onclick="belom()"><i class="icon_genius"></i><span class=merah>Transkrip</span></a></li>

      <!-- <li class=proper><a href="?presensi"><i class="icon_genius"></i>Presensi</a></li> -->
      <li class=proper><a href="../tmp/relasi.png" target="_blank"><i class="icon_genius"></i>Relasi DB</a></li>

      


      <!-- <li><a href="../"><i class="icon_house_alt"></i>Siakad Home</a></li> -->
    </ul>
  </div>
</aside>

<script>
  function belom(){
    alert('Menu ini masih dalam tahap pengembangan. Terimakasih.')
  }
</script>