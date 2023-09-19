<?php
// $judul = 'Pengisian KRS';
// $undef = '<span style="color:#f77; font-style:italic">undefined</span>';
// $null = '<code class=miring>null</code>';
// $belum_ada = '<code class=miring>belum ada</code>';
// $jumlah_mk = $null;
echo "<span class=debug>id_semester<span id=id_semester>$id_semester</span></span>";
if(!isset($id_semester)||$id_semester=='') die(div_alert('danger','Index id_semester tidak valid.'));




if (isset($_POST['btn_submit_biodata'])) {

  echo '<pre>';
  var_dump($_POST);
  echo '</pre>';
  exit;


  $angkatan = $_POST['angkatan'];
  $id_prodi = $_POST['id_prodi'];
  $s = "SELECT id,nominal_default FROM tb_krs_manual";
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  $values = '';
  
  while ($d=mysqli_fetch_assoc($q)) {
    $id = $d['id'];
    $nominal = $d['nominal_default'];
    $values .= "('$id','$angkatan','$id_prodi','$nominal'),";
    
  }
  $s = "INSERT INTO tb_krs_mk_manual (id_krs,angkatan,id_prodi,nominal) VALUES $values".'__';
  $s = str_replace(',__','',$s);
  $q = mysqli_query($cn,$s) or die(mysqli_error($cn));
  echo div_alert('success', 'Set Nominal Default success. Redirecting ...');
  echo "<script>location.replace('?manage_krs&angkatan=$angkatan&id_prodi=$id_prodi')</script>";
  exit;

}

$s = "SELECT * FROM tb_biodata WHERE nim='$nim' AND id_semester=$id_semester";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
if(mysqli_num_rows($q)>1){
  die(div_alert('danger','Duplicate biodata detected.'));
}elseif(mysqli_num_rows($q)==0){

  $nik = '';
  $nama = '';
  $tempat_lahir = '';
  $tanggal_lahir = '';
  
  $gender_L_checked = '';
  $gender_P_checked = '';
  
  $golongan_darah = '';
  $alamat_blok = '';
  $alamat_rt = '';
  $alamat_rw = '';
  $alamat_desa = '';
  $alamat_kecamatan = '';
  
  $agama_1_checked = '';
  $agama_2_checked = '';
  $agama_3_checked = '';
  $agama_4_checked = '';
  $agama_5_checked = '';
  $agama_6_checked = '';
  
  $suku = '';
  
  $status_menikah_1_checked = '';
  $status_menikah_2_checked = '';
  $status_menikah_3_checked = '';
  $status_menikah_4_checked = '';
  
  $warga_negara_1_checked = '';
  $warga_negara_2_checked = '';
  
  $no_wa = '';
  $email = '';
  $facebook = '';
  $instagram = '';
  
  $alamat_blok_domisili = '';
  $alamat_rt_domisili = '';
  $alamat_rw_domisili = '';
  $alamat_desa_domisili = '';
  $alamat_kecamatan_domisili = '';
  
  
  $sd_nama = '';
  $sd_alamat = '';
  $sd_provinsi = '';
  $sd_tahun = '';
  
  $sltp_nama = '';
  $sltp_alamat = '';
  $sltp_provinsi = '';
  $sltp_tahun = '';
  
  $slta_nama = '';
  $slta_alamat = '';
  $slta_provinsi = '';
  $slta_tahun = '';
  
  $program_ikmi = '';
  $tahun_ikmi = '';
  $jurusan_ikmi = '';
  
  $univ_lain = '';
  $tahun_univ_lain = '';
  $fakultas_univ_lain = '';
  $jurusan_univ_lain = '';
  $nim_asal = '';
  
  $bekerja_sebagai = '';
  $jabatan_bekerja = '';
  $instansi_bekerja = '';
  $alamat_bekerja = '';
  
  $di_cirebon_sejak = '';
  $hubungan_tinggal = '';
  
  $nama_ayah = '';
  $pekerjaan_ayah = '';
  $no_wa_ayah = '';
  $tempat_kerja_ayah = '';
  $pendidikan_ayah = '';
  
  $nama_ibu = '';
  $pekerjaan_ibu = '';
  $no_wa_ibu = '';
  $tempat_kerja_ibu = '';
  $pendidikan_ibu = '';
  
  $nama_wali = '';
  $pekerjaan_wali = '';
  $no_wa_wali = '';
  $tempat_kerja_wali = '';
  $pendidikan_wali = '';
  $hubungan_wali = '';
  
  $anak_ke = '';
  $jumlah_saudara= '';
  
  $nama_donatur = '';
  $alamat_donatur = '';
  $no_wa_donatur = '';
  $hubungan_donatur = '';
  $pekerjaan_donatur = '';
  $jabatan_donatur = '';
  $tempat_kerja_donatur = '';
  $alamat_kerja_donatur = '';
  $telepon_kerja_donatur = '';

}else{ // sudah isi biodata
  $d=mysqli_fetch_assoc($q);

  $nik = $d['nik'];
  $nama = $d['nama'];
  $tempat_lahir = $d['tempat_lahir'];
  $tanggal_lahir = $d['tanggal_lahir'];
  $gender = $d['gender'];
  $golongan_darah = $d['golongan_darah'];
  $alamat_blok = $d['alamat_blok'];
  $alamat_rt = $d['alamat_rt'];
  $alamat_rw = $d['alamat_rw'];
  $alamat_desa = $d['alamat_desa'];
  $alamat_kecamatan = $d['alamat_kecamatan'];
  $agama = $d['agama'];
  $suku = $d['suku'];
  $status_menikah = $d['status_menikah'];
  $warga_negara = $d['warga_negara'];
  $no_wa = $d['no_wa'];
  $email = $d['email'];
  $facebook = $d['facebook'];
  $instagram = $d['instagram'];
  $alamat_blok_domisili = $d['alamat_blok_domisili'];
  $alamat_rt_domisili = $d['alamat_rt_domisili'];
  $alamat_rw_domisili = $d['alamat_rw_domisili'];
  $alamat_desa_domisili = $d['alamat_desa_domisili'];
  $alamat_kecamatan_domisili = $d['alamat_kecamatan_domisili'];
  $sd_nama = $d['sd_nama'];
  $sd_alamat = $d['sd_alamat'];
  $sd_provinsi = $d['sd_provinsi'];
  $sd_tahun = $d['sd_tahun'];
  $sltp_nama = $d['sltp_nama'];
  $sltp_alamat = $d['sltp_alamat'];
  $sltp_provinsi = $d['sltp_provinsi'];
  $sltp_tahun = $d['sltp_tahun'];
  $slta_nama = $d['slta_nama'];
  $slta_alamat = $d['slta_alamat'];
  $slta_provinsi = $d['slta_provinsi'];
  $slta_tahun = $d['slta_tahun'];
  $program_ikmi = $d['program_ikmi'];
  $tahun_ikmi = $d['tahun_ikmi'];
  $jurusan_ikmi = $d['jurusan_ikmi'];
  $univ_lain = $d['univ_lain'];
  $tahun_univ_lain = $d['tahun_univ_lain'];
  $fakultas_univ_lain = $d['fakultas_univ_lain'];
  $nim_asal = $d['nim_asal'];
  $jurusan_univ_lain = $d['jurusan_univ_lain'];
  $bekerja_sebagai = $d['bekerja_sebagai'];
  $jabatan_bekerja = $d['jabatan_bekerja'];
  $instansi_bekerja = $d['instansi_bekerja'];
  $alamat_bekerja = $d['alamat_bekerja'];
  $di_cirebon_sejak = $d['di_cirebon_sejak'];
  $hubungan_tinggal = $d['hubungan_tinggal'];
  $nama_ayah = $d['nama_ayah'];
  $pekerjaan_ayah = $d['pekerjaan_ayah'];
  $no_wa_ayah = $d['no_wa_ayah'];
  $tempat_kerja_ayah = $d['tempat_kerja_ayah'];
  $pendidikan_ayah = $d['pendidikan_ayah'];
  $nama_ibu = $d['nama_ibu'];
  $pekerjaan_ibu = $d['pekerjaan_ibu'];
  $no_wa_ibu = $d['no_wa_ibu'];
  $tempat_kerja_ibu = $d['tempat_kerja_ibu'];
  $pendidikan_ibu = $d['pendidikan_ibu'];
  $hubungan_wali = $d['hubungan_wali'];
  $nama_wali = $d['nama_wali'];
  $pekerjaan_wali = $d['pekerjaan_wali'];
  $no_wa_wali = $d['no_wa_wali'];
  $tempat_kerja_wali = $d['tempat_kerja_wali'];
  $pendidikan_wali = $d['pendidikan_wali'];
  $anak_ke = $d['anak_ke'];
  $jumlah_saudara = $d['jumlah_saudara'];
  $yang_membiayai = $d['yang_membiayai'];
  $nama_donatur = $d['nama_donatur'];
  $alamat_donatur = $d['alamat_donatur'];
  $no_wa_donatur = $d['no_wa_donatur'];
  $hubungan_donatur = $d['hubungan_donatur'];
  $pekerjaan_donatur = $d['pekerjaan_donatur'];
  $jabatan_donatur = $d['jabatan_donatur'];
  $tempat_kerja_donatur = $d['tempat_kerja_donatur'];
  $alamat_kerja_donatur = $d['alamat_kerja_donatur'];
  $telepon_kerja_donatur = $d['telepon_kerja_donatur'];
  
  $nik = '1111222233334444';
  $nama = 'Iin Sholihin';
  $tempat_lahir = 'Sumedang';
  $tanggal_lahir = '1987-06-11';
  
  $gender_L_checked = 'checked';
  $gender_P_checked = '';
  
  $golongan_darah = '-';
  $alamat_blok = 'Blok Utara';
  $alamat_rt = 2;
  $alamat_rw = 6;
  $alamat_desa = 'Babakan';
  $alamat_kecamatan = 'Ciwaringin';
  
  $agama_1_checked = 'checked';
  $agama_2_checked = '';
  $agama_3_checked = '';
  $agama_4_checked = '';
  $agama_5_checked = '';
  $agama_6_checked = '';
  
  $suku = 'Sunda';
  
  $status_menikah_1_checked = 'checked';
  $status_menikah_2_checked = '';
  $status_menikah_3_checked = '';
  $status_menikah_4_checked = '';
  
  $warga_negara_1_checked = 'checked';
  $warga_negara_2_checked = '';
  
  $no_wa = '087729007318';
  $email = 'iin@gmail.com';
  $facebook = '-';
  $instagram = '-';
  
  $alamat_blok_domisili = 'Blok Utara';
  $alamat_rt_domisili = 2;
  $alamat_rw_domisili = 6;
  $alamat_desa_domisili = 'Babakan';
  $alamat_kecamatan_domisili = 'Ciwaringin';
  
  
  $sd_nama = 'SDN 1';
  $sd_alamat = 'Tanjungsari';
  $sd_provinsi = 'jabar';
  $sd_tahun = 1999;
  
  $sltp_nama = 'SLTPN 1';
  $sltp_alamat = 'Tanjungsari';
  $sltp_provinsi = 'jabar';
  $sltp_tahun = 2002;
  
  $slta_nama = 'SMAN 1';
  $slta_alamat = 'Tanjungsari';
  $slta_provinsi = 'jabar';
  $slta_tahun = 2005;
  
  $program_ikmi = 'D3-MI';
  $tahun_ikmi = 2001;
  $jurusan_ikmi = 'komputer';
  
  $univ_lain = '-';
  $tahun_univ_lain = '-';
  $fakultas_univ_lain = '-';
  $jurusan_univ_lain = '-';
  $nim_asal = '-';
  
  $bekerja_sebagai = '-';
  $jabatan_bekerja = '-';
  $instansi_bekerja = '-';
  $alamat_bekerja = '-';
  
  $di_cirebon_sejak = '2020-01-12';
  $hubungan_tinggal = '-';
  
  $nama_ayah = 'Koswara';
  $pekerjaan_ayah = 'petani';
  $no_wa_ayah = '-';
  $tempat_kerja_ayah = '-';
  $pendidikan_ayah = 'SD';
  
  $nama_ibu = 'Koswara';
  $pekerjaan_ibu = 'petani';
  $no_wa_ibu = '-';
  $tempat_kerja_ibu = '-';
  $pendidikan_ibu = 'SD';
  
  $nama_wali = 'Koswara';
  $pekerjaan_wali = 'petani';
  $no_wa_wali = '-';
  $tempat_kerja_wali = '-';
  $pendidikan_wali = 'SD';
  $hubungan_wali = '-';
  
  $anak_ke = 1;
  $jumlah_saudara=1;
  
  $nama_donatur = 'Donatur';
  $alamat_donatur = 'alamat_donatur';
  $no_wa_donatur = 'no_wa_donatur';
  $hubungan_donatur = 'hubungan_donatur';
  $pekerjaan_donatur = 'pekerjaan_donatur';
  $jabatan_donatur = 'jabatan_donatur';
  $tempat_kerja_donatur = 'tempat_kerja_donatur';
  $alamat_kerja_donatur = 'alamat_kerja_donatur';
  $telepon_kerja_donatur = 'telepon_kerja_donatur';  
}


?>
<section>
  <div class="container">
    <form method=post>

      <h1>FORMULIR REGISTRASI</h1>
      <div>MAHASISWA STMIK IKMI CIREBON</div>  
      <div>TAHUN AJAR <?=$tahun_ajar?></div>
      <div>SEMESTER <?=$semester?></div>

      <style>h1{font-size:24px}h2{color:#4444ff; font-weight: 600;font-size:20px}</style>
      <div class="wadah mt-3">
        <h2>DATA KEPENDUDUKAN</h2>
      
        <div class="form-group">
          <label for="nik">NIK</label>
          <input required minlength=16 maxlength=16 type="text" class="form-control" id=nik name=nik value='<?=$nik?>'>
        </div>

        <div class="form-group">
          <label for="nama">Nama Lengkap</label>
          <input required minlength=3 maxlength=50 type="text" class="form-control" id=nama name=nama value='<?=$nama?>'>
        </div>

        <div class="form-group">
          <label for="tempat_lahir">Tempat Lahir</label>
          <input required minlength=3 maxlength=50 type="text" class="form-control" id=tempat_lahir name=tempat_lahir value='<?=$tempat_lahir?>'>
        </div>

        <div class="form-group">
          <label for="tanggal_lahir">Tanggal Lahir</label>
          <input required type="date" class="form-control" id=tanggal_lahir name=tanggal_lahir value='<?=$tanggal_lahir?>'>
        </div>

        <div class="form-group">
          <label><input required type="radio" name=gender value='L' <?=$gender_L_checked?>> Laki-laki</label>
          <label><input required type="radio" name=gender value='P' <?=$gender_P_checked?>> Perempuan</label>
        </div>

        <div class="form-group">
          <label for="golongan_darah">Golongan Darah</label>
          <input required minlength=1 maxlength=3 type="text" class="form-control" id=golongan_darah name=golongan_darah value='<?=$golongan_darah?>'>
          <small>strip (-) jika tidak tahu</small>
        </div>

        <div class="form-group">
          <label for="alamat_blok">Alamat KTP Blok/Dusun</label>
          <input required minlength=10 maxlength=50 type="text" class="form-control" id=alamat_blok name=alamat_blok value='<?=$alamat_blok?>'>
        </div>

        <div class="form-group">
          <label for="alamat_rt">RT</label>
          <input required minlength=1 maxlength=3 type="text" class="form-control" id=alamat_rt name=alamat_rt value='<?=$alamat_rt?>'>
        </div>

        <div class="form-group">
          <label for="alamat_rw">RW</label>
          <input required minlength=1 maxlength=3 type="text" class="form-control" id=alamat_rw name=alamat_rw value='<?=$alamat_rw?>'>
        </div>

        <div class="form-group">
          <label for="alamat_desa">Desa</label>
          <input required minlength=3 maxlength=50 type="text" class="form-control" id=alamat_desa name=alamat_desa value='<?=$alamat_desa?>'>
        </div>

        <div class="form-group">
          <label for="alamat_kecamatan">Kecamatan</label>
          <input required minlength=3 maxlength=50 type="text" class="form-control" id=alamat_kecamatan name=alamat_kecamatan value='<?=$alamat_kecamatan?>'>
        </div>

        <div class="form-group">
          <label><input required type="radio" name=agama value='1' <?=$agama_1_checked?>> Islam</label>
          <label><input required type="radio" name=agama value='2' <?=$agama_2_checked?>> Katolik</label>
          <label><input required type="radio" name=agama value='3' <?=$agama_3_checked?>> Protestan</label>
          <label><input required type="radio" name=agama value='4' <?=$agama_4_checked?>> Hindu</label>
          <label><input required type="radio" name=agama value='5' <?=$agama_5_checked?>> Budha</label>
          <label><input required type="radio" name=agama value='6' <?=$agama_6_checked?>> Lainnya</label>
        </div>

        <div class="form-group">
          <label for="suku">Asal Lingkungan Kebudayaan</label>
          <input required minlength=1 maxlength=50 type="text" class="form-control" id=suku name=suku value='<?=$suku?>'>
          <small>Sunda, Jawa, dll</small>
        </div>

        <div class="form-group">
          <label><input required type="radio" name=status_menikah value='1' <?=$status_menikah_1_checked?>> Belum Menikah</label>
          <label><input required type="radio" name=status_menikah value='2' <?=$status_menikah_2_checked?>> Menikah</label>
          <label><input required type="radio" name=status_menikah value='3' <?=$status_menikah_3_checked?>> Janda</label>
          <label><input required type="radio" name=status_menikah value='4' <?=$status_menikah_4_checked?>> Duda</label>
        </div>

        <div class="form-group">
          <label><input required type="radio" name=warga_negara value='1' <?=$warga_negara_1_checked?>> WNI</label>
          <label><input required type="radio" name=warga_negara value='2' <?=$warga_negara_2_checked?>> WNA</label>
        </div>

      </div>


      <div class="wadah mt-3">
        <h2>KONTAK DAN DOMISILI</h2>

        <div class="form-group">
          <label for="no_wa">Nomor Whatsapp yang aktif</label>
          <input required minlength=10 maxlength=13 type="text" class="form-control" id=no_wa name=no_wa value='<?=$no_wa?>'>
        </div>

        <div class="form-group">
          <label for="email">Email</label>
          <input required maxlength=100 type="email" class="form-control" id=email name=email value='<?=$email?>'>
        </div>

        <div class="form-group">
          <label for="facebook">Facebook</label>
          <input required minlength=1 maxlength=50 type="text" class="form-control" id=facebook name=facebook value='<?=$facebook?>'>
          <small>strip (-) jika tidak punya</small>
        </div>
        
        <div class="form-group">
          <label for="instagram">Instagram</label>
          <input required minlength=1 maxlength=50 type="text" class="form-control" id=instagram name=instagram value='<?=$instagram?>'>
          <small>strip (-) jika tidak punya</small>
        </div>

        <div class="wadah">
          <label><input type="checkbox" name=is_domisili_as_ktp id=is_domisili_as_ktp> Alamat domisili saya sama dengan alamat di KTP</label>
          <div class="form-group">
            <script>
              $(function(){
                $("#is_domisili_as_ktp").click(function(){
                  if($(this).prop("checked")){
                    $("#alamat_blok_domisili").val($("#alamat_blok").val());
                    $("#alamat_rt_domisili").val($("#alamat_rt").val());
                    $("#alamat_rw_domisili").val($("#alamat_rw").val());
                    $("#alamat_desa_domisili").val($("#alamat_desa").val());
                    $("#alamat_kecamatan_domisili").val($("#alamat_kecamatan").val());
                    // $("#alamat_blok_domisili").prop("disabled",true);
                    $("#blok_domisili").fadeOut();
                  }else{
                    $("#blok_domisili").fadeIn();
                    $("#alamat_blok_domisili").val("");
                    $("#alamat_rt_domisili").val("");
                    $("#alamat_rw_domisili").val("");
                    $("#alamat_desa_domisili").val("");
                    $("#alamat_kecamatan_domisili").val("");
                  }
                })
              })
            </script>
          </div>
          <div id="blok_domisili">
            <div class="form-group">
              <label for="alamat_blok">Alamat Domisili Blok/Dusun</label>
              <!-- _domisili -->
              <input required minlength=3 maxlength=50 type="text" class="form-control" id=alamat_blok_domisili name=alamat_blok_domisili value='<?=$alamat_blok_domisili?>'>
            </div>

            <div class="form-group">
              <label for="alamat_rt">Domisili - RT</label>
              <input required minlength=1 maxlength=3 type="text" class="form-control" id=alamat_rt_domisili name=alamat_rt_domisili value='<?=$alamat_rt_domisili?>'>
            </div>

            <div class="form-group">
              <label for="alamat_rw">Domisili - RW</label>
              <input required minlength=1 maxlength=3 type="text" class="form-control" id=alamat_rw_domisili name=alamat_rw_domisili value='<?=$alamat_rw_domisili?>'>
            </div>

            <div class="form-group">
              <label for="alamat_desa">Domisili - Desa</label>
              <input required minlength=3 maxlength=50 type="text" class="form-control" id=alamat_desa_domisili name=alamat_desa_domisili value='<?=$alamat_desa_domisili?>'>
            </div>

            <div class="form-group">
              <label for="alamat_kecamatan">Domisili - Kecamatan</label>
              <input required minlength=3 maxlength=50 type="text" class="form-control" id=alamat_kecamatan_domisili name=alamat_kecamatan_domisili value='<?=$alamat_kecamatan_domisili?>'>
            </div>

          </div>
        </div>


      </div>


      <div class="wadah mt-3 bg-white">
        <h2>DATA PENDIDIKAN</h2>

        <div class="wadah gradasi-hijau mt-2">
          SEKOLAH DASAR
          <div class="form-group">
            <label for="sd_nama">Sekolah Dasar</label>
            <input required minlength=3 maxlength=50 type="text" class="form-control" id=sd_nama name=sd_nama value='<?=$sd_nama?>'>
          </div>

          <div class="form-group">
            <label for="sd_alamat">Di</label>
            <input required minlength=3 maxlength=50 type="text" class="form-control" id=sd_alamat name=sd_alamat value='<?=$sd_alamat?>'>
          </div>

          <div class="form-group">
            <label for="sd_provinsi">Provinsi</label>
            <input required minlength=3 maxlength=50 type="text" class="form-control" id=sd_provinsi name=sd_provinsi value='<?=$sd_provinsi?>'>
          </div>

          <div class="form-group">
            <label for="sd_tahun">Tahun</label>
            <input required minlength=4 maxlength=4 type="text" class="form-control" id=sd_tahun name=sd_tahun value='<?=$sd_tahun?>'>
          </div>

        </div>

        <div class="wadah gradasi-hijau mt-2">
          SEKOLAH LANJUTAN TINGKAT PERTAMA
          <div class="form-group">
            <label for="sltp_nama">SLTP</label>
            <input required minlength=3 maxlength=50 type="text" class="form-control" id=sltp_nama name=sltp_nama value='<?=$sltp_nama?>'>
          </div>

          <div class="form-group">
            <label for="sltp_alamat">Di</label>
            <input required minlength=3 maxlength=50 type="text" class="form-control" id=sltp_alamat name=sltp_alamat value='<?=$sltp_alamat?>'>
          </div>

          <div class="form-group">
            <label for="sltp_provinsi">Provinsi</label>
            <input required minlength=3 maxlength=50 type="text" class="form-control" id=sltp_provinsi name=sltp_provinsi value='<?=$sltp_provinsi?>'>
          </div>

          <div class="form-group">
            <label for="sltp_tahun">Tahun</label>
            <input required minlength=4 maxlength=4 type="text" class="form-control" id=sltp_tahun name=sltp_tahun value='<?=$sltp_tahun?>'>
          </div>

        </div>

        <div class="wadah gradasi-hijau mt-2">
          SEKOLAH LANJUTAN TINGKAT ATAS
          <div class="form-group">
            <label for="slta_nama">SLTA</label>
            <input required minlength=3 maxlength=50 type="text" class="form-control" id=slta_nama name=slta_nama value='<?=$slta_nama?>'>
          </div>

          <div class="form-group">
            <label for="slta_alamat">Di</label>
            <input required minlength=3 maxlength=50 type="text" class="form-control" id=slta_alamat name=slta_alamat value='<?=$slta_alamat?>'>
          </div>

          <div class="form-group">
            <label for="slta_provinsi">Provinsi</label>
            <input required minlength=3 maxlength=50 type="text" class="form-control" id=slta_provinsi name=slta_provinsi value='<?=$slta_provinsi?>'>
          </div>

          <div class="form-group">
            <label for="slta_tahun">Tahun</label>
            <input required minlength=4 maxlength=4 type="text" class="form-control" id=slta_tahun name=slta_tahun value='<?=$slta_tahun?>'>
          </div>
        </div>

        <div class="wadah mt-4">
          <h2>DAFTAR PERTAMA KALI</h2>
          <div class="wadah mt-2 gradasi-hijau">
            Di STMIK IKMI Cirebon 

            <div class="form-group">
              <label for="program_ikmi">Program D3/S1</label>
              <input required minlength=3 maxlength=50 type="text" class="form-control" id=program_ikmi name=program_ikmi value='<?=$program_ikmi?>'>
              <small>isi dengan D3 atau S1</small>
            </div>

            <div class="form-group">
              <label for="tahun_ikmi">Tahun</label>
              <input required minlength=4 maxlength=4 type="text" class="form-control" id=tahun_ikmi name=tahun_ikmi value='<?=$tahun_ikmi?>'>
            </div>

            <div class="form-group">
              <label for="jurusan_ikmi">Jurusan</label>
              <input required minlength=3 maxlength=50 type="text" class="form-control" id=jurusan_ikmi name=jurusan_ikmi value='<?=$jurusan_ikmi?>'>
              <small>isi dg: TI, RPL, SI, MI, KA</small>
            </div>

          </div>

          <div class="wadah mt-2 gradasi-hijau">
            <div class="form-group">
              <input type="checkbox" id="cek_pernah_kuliah" class="toggle">
              <label for="cek_pernah_kuliah">Saya pernah kuliah di Perguruan Tinggi lain</label>
            </div>

            <div id="cek_pernah_kuliah__details" class="hideit">
              <div class="form-group">
                <label for="univ_lain">Univ/Akademik/Diploma</label>
                <input required minlength=1 maxlength=50 type="text" class="form-control" id=univ_lain name=univ_lain value='<?=$univ_lain?>'>
              </div>

              <div class="form-group">
                <label for="tahun_univ_lain">Tahun</label>
                <input required minlength=1 maxlength=50 type="text" class="form-control" id=tahun_univ_lain name=tahun_univ_lain value='<?=$tahun_univ_lain?>'>
              </div>

              <div class="form-group">
                <label for="fakultas_univ_lain">Fakultas</label>
                <input required minlength=1 maxlength=50 type="text" class="form-control" id=fakultas_univ_lain name=fakultas_univ_lain value='<?=$fakultas_univ_lain?>'>
              </div>

              <div class="form-group">
                <label for="nim_asal">NIM Asal</label>
                <input required minlength=1 maxlength=50 type="text" class="form-control" id=nim_asal name=nim_asal value='<?=$nim_asal?>'>
              </div>

              <div class="form-group">
                <label for="jurusan_univ_lain">Jurusan</label>
                <input required minlength=1 maxlength=50 type="text" class="form-control" id=jurusan_univ_lain name=jurusan_univ_lain value='<?=$jurusan_univ_lain?>'>
              </div>            
            </div>

          </div>
        </div> 
      </div>
      <!-- end data pendidikan -->

      <div class="wadah">
        <h2>DATA PEKERJAAN</h2>
        <div class="form-group">
          <input type="checkbox" id="cek_sudah_bekerja" class="toggle">
          <label for="cek_sudah_bekerja">Saya sudah bekerja</label>
        </div>

        <div class="hideit" id=cek_sudah_bekerja__details>

          <div class="form-group">
            <label for="bekerja_sebagai">Sebagai</label>
            <input required minlength=1 maxlength=50 type="text" class="form-control" id=bekerja_sebagai name=bekerja_sebagai value='<?=$bekerja_sebagai?>'>
          </div>

          <div class="form-group">
            <label for="jabatan_bekerja">Jabatan</label>
            <input required minlength=1 maxlength=50 type="text" class="form-control" id=jabatan_bekerja name=jabatan_bekerja value='<?=$jabatan_bekerja?>'>
          </div>
          
          <div class="form-group">
            <label for="instansi_bekerja">Instansi/Perusahaan</label>
            <input required minlength=1 maxlength=50 type="text" class="form-control" id=instansi_bekerja name=instansi_bekerja value='<?=$instansi_bekerja?>'>
          </div>

          <div class="form-group">
            <label for="alamat_bekerja">Alamat Perusahaan</label>
            <input required minlength=1 maxlength=500 type="text" class="form-control" id=alamat_bekerja name=alamat_bekerja value='<?=$alamat_bekerja?>'>
          </div>        
        </div>


      </div>
      <!-- end data pekerjaan -->


      <div class="wadah mt-3">
        <h2>AKOMODASI</h2>
        <div class="form-group">
          <label for="di_cirebon_sejak">Tinggal di Cirebon sejak</label>
          <input required type="date" class="form-control" id=di_cirebon_sejak name=di_cirebon_sejak value='<?=$di_cirebon_sejak?>'>
        </div>
  
        <div class="form-group">
          <label for="hubungan_tinggal">Hubungan dengan yang ditinggali</label>
          <input required minlength=1 maxlength=50 type="text" class="form-control" id=hubungan_tinggal name=hubungan_tinggal value='<?=$hubungan_tinggal?>'>
          <small>Orangtua / kakak / Kost / Asrama / ...</small>
        </div>

      </div>


      <div class="wadah mt-3 bg-white">
        <h2>DATA ORANGTUA / WALI</h2>

        <div class="wadah mt-2 gradasi-hijau">
          DATA AYAH 
          <div class="form-group">
            <label for="nama_ayah">Nama Ayah</label>
            <input required minlength=3 maxlength=50 type="text" class="form-control" id=nama_ayah name=nama_ayah value='<?=$nama_ayah?>'>
          </div>
          
          <div class="form-group">
            <label for="pekerjaan_ayah">Pekerjaan Ayah</label>
            <input required minlength=3 maxlength=50 type="text" class="form-control" id=pekerjaan_ayah name=pekerjaan_ayah value='<?=$pekerjaan_ayah?>'>
          </div>
          
          <div class="form-group">
            <label for="no_wa_ayah">WhatsApp Ayah</label>
            <input required minlength=3 maxlength=50 type="text" class="form-control" id=no_wa_ayah name=no_wa_ayah value='<?=$no_wa_ayah?>'>
          </div>
          
          <div class="form-group">
            <label for="tempat_kerja_ayah">Instansi Pekerjaan Ayah</label>
            <input required minlength=3 maxlength=50 type="text" class="form-control" id=tempat_kerja_ayah name=tempat_kerja_ayah value='<?=$tempat_kerja_ayah?>'>
          </div>
          
          <div class="form-group">
            <label for="pendidikan_ayah">Pendidikan Terakhir Ayah</label>
            <input required minlength=2 maxlength=50 type="text" class="form-control" id=pendidikan_ayah name=pendidikan_ayah value='<?=$pendidikan_ayah?>'>
          </div>
          
        </div>
  
        <div class="wadah mt-2 gradasi-hijau">
          DATA IBU 
          <div class="form-group">
            <label for="nama_ibu">Nama Ibu</label>
            <input required minlength=3 maxlength=50 type="text" class="form-control" id=nama_ibu name=nama_ibu value='<?=$nama_ibu?>'>
          </div>
          
          <div class="form-group">
            <label for="pekerjaan_ibu">Pekerjaan Ibu</label>
            <input required minlength=3 maxlength=50 type="text" class="form-control" id=pekerjaan_ibu name=pekerjaan_ibu value='<?=$pekerjaan_ibu?>'>
          </div>
          
          <div class="form-group">
            <label for="no_wa_ibu">WhatsApp Ibu</label>
            <input required minlength=3 maxlength=50 type="text" class="form-control" id=no_wa_ibu name=no_wa_ibu value='<?=$no_wa_ibu?>'>
          </div>
          
          <div class="form-group">
            <label for="tempat_kerja_ibu">Instansi Pekerjaan Ibu</label>
            <input required minlength=3 maxlength=50 type="text" class="form-control" id=tempat_kerja_ibu name=tempat_kerja_ibu value='<?=$tempat_kerja_ibu?>'>
          </div>
          
          <div class="form-group">
            <label for="pendidikan_ibu">Pendidikan Terakhir Ibu</label>
            <input required minlength=2 maxlength=50 type="text" class="form-control" id=pendidikan_ibu name=pendidikan_ibu value='<?=$pendidikan_ibu?>'>
          </div>
          
        </div>
  
        

  
        <div class="wadah mt-2 gradasi-hijau">
          <div class="form-group">
            <input type="checkbox" id="cek_punya_wali" class="toggle">
            <label for="cek_punya_wali">Saya punya Wali (selain Ayah/Ibu)</label>
          </div>

          <div class="hideit" id="cek_punya_wali__details">
            DATA WALI 
            <div class="form-group">
              <label for="hubungan_wali">Hubungan dengan Wali</label>
              <input required minlength=1 maxlength=50 type="text" class="form-control" id=hubungan_wali name=hubungan_wali value='<?=$hubungan_wali?>'>
            </div>
            
            <div class="form-group">
              <label for="nama_wali">Nama Wali</label>
              <input required minlength=1 maxlength=50 type="text" class="form-control" id=nama_wali name=nama_wali value='<?=$nama_wali?>'>
            </div>
            
            <div class="form-group">
              <label for="pekerjaan_wali">Pekerjaan Wali</label>
              <input required minlength=1 maxlength=50 type="text" class="form-control" id=pekerjaan_wali name=pekerjaan_wali value='<?=$pekerjaan_wali?>'>
            </div>

            <div class="form-group">
              <label for="no_wa_wali">WhatsApp Wali</label>
              <input required minlength=1 maxlength=50 type="text" class="form-control" id=no_wa_wali name=no_wa_wali value='<?=$no_wa_wali?>'>
            </div>
            
            
            <div class="form-group">
              <label for="tempat_kerja_wali">Instansi Pekerjaan Wali</label>
              <input required minlength=1 maxlength=50 type="text" class="form-control" id=tempat_kerja_wali name=tempat_kerja_wali value='<?=$tempat_kerja_wali?>'>
            </div>
            
            <div class="form-group">
              <label for="pendidikan_wali">Pendidikan Terakhir Wali</label>
              <input required minlength=1 maxlength=50 type="text" class="form-control" id=pendidikan_wali name=pendidikan_wali value='<?=$pendidikan_wali?>'>
            </div>
          </div>
        </div>
  
        <div class="wadah mt-2 gradasi-hijau">
          
          <div class="form-group">
            <label for="anak_ke">Saya anak ke:</label>
            <input required minlength=1 maxlength=2 type="text" class="form-control" id=anak_ke name=anak_ke value='<?=$anak_ke?>'>
          </div>
          
          <div class="form-group">
            <label for="jumlah_saudara">Jumlah saudara kandung:</label>
            <input required minlength=1 maxlength=2 type="text" class="form-control" id=jumlah_saudara name=jumlah_saudara value='<?=$jumlah_saudara?>'>
          </div>
          
        </div>
      </div>



      <div class="wadah mt-3">
        <h2>PENDANAAN KULIAH</h2>
        <div class="form-group">
          <label for="yang_membiayai">Selain dana Beasiswa (jika ada), saya dibiayai oleh:</label>
          <select class="form-control" name="yang_membiayai" id="yang_membiayai">
            <option value="1">Biaya Dari Ayah/Ibu</option>
            <option value="2">Biaya Sendiri</option>
            <option value="3">Biaya Dari Ibu</option>
            <option value="4">Biaya Dari Saudara Lainnya</option>
          </select>
        </div>
        
        <div class="wadah hideit" id=yang_membiayai__details>
          SAUDARA LAINNYA YANG MEMBIAYAI PENDIDIKAN
          <div class="form-group">
            <label for="nama_donatur">Nama Saudara</label>
            <input required minlength=1 maxlength=50 type="text" class="form-control" id=nama_donatur name=nama_donatur value='<?=$nama_donatur?>'>
          </div>
          
          <div class="form-group">
            <label for="alamat_donatur">Alamat Rumah</label>
            <input required minlength=1 maxlength=50 type="text" class="form-control" id=alamat_donatur name=alamat_donatur value='<?=$alamat_donatur?>'>
          </div>
          
          <div class="form-group">
            <label for="no_wa_donatur">WhatsApp/No.HP</label>
            <input required minlength=1 maxlength=50 type="text" class="form-control" id=no_wa_donatur name=no_wa_donatur value='<?=$no_wa_donatur?>'>
          </div>
          
          <div class="form-group">
            <label for="hubungan_donatur">Hubungan</label>
            <input required minlength=1 maxlength=50 type="text" class="form-control" id=hubungan_donatur name=hubungan_donatur value='<?=$hubungan_donatur?>'>
          </div>
          
          <div class="form-group">
            <label for="pekerjaan_donatur">Pekerjaan</label>
            <input required minlength=1 maxlength=50 type="text" class="form-control" id=pekerjaan_donatur name=pekerjaan_donatur value='<?=$pekerjaan_donatur?>'>
          </div>
          
          <div class="form-group">
            <label for="jabatan_donatur">Jabatan</label>
            <input required minlength=1 maxlength=50 type="text" class="form-control" id=jabatan_donatur name=jabatan_donatur value='<?=$jabatan_donatur?>'>
          </div>
          
          <div class="form-group">
            <label for="tempat_kerja_donatur">Instansi/Perusahaan</label>
            <input required minlength=1 maxlength=50 type="text" class="form-control" id=tempat_kerja_donatur name=tempat_kerja_donatur value='<?=$tempat_kerja_donatur?>'>
          </div>
          
          <div class="form-group">
            <label for="alamat_kerja_donatur">Alamat Instansi/Perusahaan</label>
            <input required minlength=1 maxlength=50 type="text" class="form-control" id=alamat_kerja_donatur name=alamat_kerja_donatur value='<?=$alamat_kerja_donatur?>'>
          </div>
          
          <div class="form-group">
            <label for="telepon_kerja_donatur">No Telp. Instansi/Perusahaan</label>
            <input required minlength=1 maxlength=50 type="text" class="form-control" id=telepon_kerja_donatur name=telepon_kerja_donatur value='<?=$telepon_kerja_donatur?>'>
          </div>
          

          
        </div>

      </div>

      <div class="wadah">
        <label>
          <input type="checkbox" name="" id=""> 
          Demikian biodata ini saya isi dengan sebenar-benarnya. 
        </label>

        <div class="wadah">
          <div>Last update: none</div>
          <label>
            <input type="checkbox" name="" id=""> 
            Saya sudah melakukan perubahan biodata dengan data terbaru saya saat ini. 
          </label>

        </div>
        

        <div class="form-group" style="">
          <button class="btn btn-primary btn-block" name=btn_submit_biodata>Submit Biodata</button>
        </div>
      </div>



          
    </form>
  </div>
</section>





<script>
  $(function(){
    $(".toggle").click(function(){
      let tid = $(this).prop('id');
      let c = $(this).prop('checked');
      console.log(tid, typeof c, c)

      $("#"+tid+"__details").fadeToggle();
    })

    $("#yang_membiayai").change(function(){
      if($(this).val()==4){
        $("#yang_membiayai__details").fadeIn()
      }else{
        $("#yang_membiayai__details").fadeOut()
      }
    })

    $("input[type=text]").keyup(function(){
      let nim = $('#nim').text();
      let id_semester = $('#id_semester').text();
      //zzz here
      // console.log($(this).prop("id"),nim,id_semester);
      let id = $(this).prop("id");

      let link_ajax = `pages/isi_biodata_autosave.php?id=${id}&nim=${nim}&id_semester=${id_semester}&`;
      $.ajax({
        url:link_ajax,
        success:function(a){
          console.log(a);
        }
      })
    })
  })

</script>