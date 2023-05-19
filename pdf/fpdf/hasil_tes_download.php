<?php
session_start();


require_once "../config.php";
require_once "../global_var.php";

$email_calon = $_SESSION['pendaftar_email'];
$id_calon = $_SESSION['pendaftar_id_calon'];
$id_daftar = $_SESSION['pendaftar_id_daftar'];
$nama_calon = $_SESSION['pendaftar_nama'];
$admin_level = $_SESSION['pendaftar_admin_level'];



# ===========================================
# DATA VARIABEL AWAL
# ===========================================
if (strlen($id_daftar<5)) {
    $nomor_surat = $id_daftar;
} else {
    $nomor_surat = substr($id_daftar, 2, 3);
}
$bulan_romawi=["","I","II","III","IV","V","VI","VII","VIII","IX","X","XI","XII"];
$bulan = $bulan_romawi[intval(date("m"))];
$tahun = date("Y");
$tahun_akademik = "$tahun/".($tahun+1);
$nomor_petugas = "0838-2165-1265 / 0823-1605-5422";
$link_grup_wa = "https://chat.whatsapp.com/INisQLmreivH56hTMbuo7H";
$link_wa_ubah_data_akun = "https://api.whatsapp.com/send?phone=62$no_wa_petugas&text=Yth. Petugas PMB STMIK IKMI Cirebon - Saya $nama_calon, email: $email_calon ingin mengubah Data Akun saya dengan perubahan sebagai berikut:";


# ===========================================
# GET ID JADWAL TES
# ===========================================
$id_jadwal_tes = "";
$s = "SELECT 
b.id_jadwal_tes,
b.titi_mangsa,
b.tanggal_deadline 

FROM tb_peserta_tes a 
JOIN tb_jadwal_tes b on a.id_jadwal_tes=b.id_jadwal_tes 
where a.id_daftar=$id_daftar";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
if (mysqli_num_rows($q)!=1) {
    die("id_jadwal_tes tidak ditemukan pada id_daftar: $id_daftar");
}
$d = mysqli_fetch_assoc($q);
$id_jadwal_tes = $d['id_jadwal_tes'];
$titi_mangsa = $d['titi_mangsa'];
$tanggal_deadline = $d['tanggal_deadline'];

if ($titi_mangsa=="") {
    die("Tanggal Surat belum ditentukan. 
    Silahkan hubungi Petugas PMB.
    <hr>
    id_daftar:$id_daftar<br>
    id_jadwal_tes:$id_jadwal_tes<br>
    titi_mangsa:$titi_mangsa<br>
    tanggal_deadline:$tanggal_deadline<br>");
}
if ($tanggal_deadline=="") {
    die("Tanggal Deadline belum ditentukan. 
    Silahkan hubungi Petugas PMB.
    <hr>
    id_daftar:$id_daftar<br>
    id_jadwal_tes:$id_jadwal_tes<br>
    titi_mangsa:$titi_mangsa<br>
    tanggal_deadline:$tanggal_deadline<br>");
}



# ===========================================
# MAIN SELECT
# ===========================================
$s = "SELECT 

a.*,
b.*,
c.no_wa,
c.status_no_wa,
c.status_email,
c.akun_created,
(select nama_kab from tb_kab where id_kab=a.id_kab_tempat_lahir) as tempat_lahir, 
(select nama_kec from tb_kec where id_kec=a.id_nama_kec_sekolah) as nama_kec_sekolah,
(select nama_kec from tb_kec where id_kec=a.id_nama_kec_ktp) as nama_kec_ktp,
(select nama_kec from tb_kec where id_kec=a.id_nama_kec_domisili) as nama_kec_domisili, 

(select x.nama_kab from tb_kab x join tb_kec y on x.id_kab=y.id_kab where y.id_kec=a.id_nama_kec_sekolah) as nama_kab_sekolah,
(select x.nama_kab from tb_kab x join tb_kec y on x.id_kab=y.id_kab where y.id_kec=a.id_nama_kec_ktp) as nama_kab_ktp,
(select x.nama_kab from tb_kab x join tb_kec y on x.id_kab=y.id_kab where y.id_kec=a.id_nama_kec_domisili) as nama_kab_domisili, 
(select nama_prodi from tb_prodi where id_prodi=b.id_prodi1) as nama_prodi1, 
(select nama_prodi from tb_prodi where id_prodi=b.id_prodi2) as nama_prodi2, 
(select singkatan_prodi from tb_prodi where id_prodi=b.id_prodi1) as singkatan_prodi1, 
(select singkatan_prodi from tb_prodi where id_prodi=b.id_prodi2) as singkatan_prodi2, 
(select jenjang from tb_prodi where id_prodi=b.id_prodi1) as jenjang1, 
(select jenjang from tb_prodi where id_prodi=b.id_prodi2) as jenjang2, 
(select nama_jalur from tb_jalur where id_jalur=b.id_jalur) as nama_jalur, 
(select singkatan_jalur from tb_jalur where id_jalur=b.id_jalur) as singkatan_jalur 

from tb_calon a 
join tb_akun c on a.email=c.email 
join tb_daftar b on a.email=b.email 
where c.email='$email_calon'";
$q = mysqli_query($cn, $s) or die("Global var error. ".mysqli_error($cn));
if (mysqli_num_rows($q)!=1) {
    die("Data calon tidak ada. email_calon: $email_calon");
}


$d = mysqli_fetch_assoc($q);

if (0) {
    echo "<pre>";
    echo var_dump($d);
    echo "</pre>";
    exit();
}
// $id_calon = $d['id_calon'];
// $id_daftar = $d['id_daftar'];

# ===========================================
# DATA AKUN
# ===========================================
$no_wa = $d['no_wa'];
$status_no_wa = $d['status_no_wa'];
$status_email = $d['status_email'];
$akun_created = $d['akun_created'];



# ===========================================
# DATA CALON
# ===========================================
$id_sekolah   = $d['id_sekolah'];
$tahun_lulus  = $d['tahun_lulus'];
$nisn   = $d['nisn'];
$prodi_asal  = $d['prodi_asal'];
$nik   = $d['nik'];
$tanggal_lahir  = $d['tanggal_lahir'];
$jenis_kelamin  = $d['jenis_kelamin'];
$status_menikah  = $d['status_menikah'];
$agama  = $d['agama'];
$warga_negara  = $d['warga_negara'];
$alamat_desa_ktp  = $d['alamat_desa_ktp'];
$alamat_desa_domisili  = $d['alamat_desa_domisili'];
$nama_ayah  = $d['nama_ayah'];
$nama_ibu  = $d['nama_ibu'];
$id_pekerjaan_ayah  = $d['id_pekerjaan_ayah'];
$id_pekerjaan_ibu  = $d['id_pekerjaan_ibu'];
$no_hp   = $d['no_hp'];
$no_ayah  = $d['no_ayah'];
$no_ibu  = $d['no_ibu'];
$no_saudara  = $d['no_saudara'];
$is_bekerja  = $d['is_bekerja'];
$is_wirausaha  = $d['is_wirausaha'];
$kode_pos_nama_kec_ktp  = $d['kode_pos_nama_kec_ktp'];
$kode_pos_nama_kec_domisili  = $d['kode_pos_nama_kec_domisili'];
$id_nama_kec_sekolah = $d['id_nama_kec_sekolah'];
$id_nama_kec_ktp = $d['id_nama_kec_ktp'];
$id_nama_kec_domisili = $d['id_nama_kec_domisili'];
$id_kab_tempat_lahir = $d['id_kab_tempat_lahir'];
$tempat_lahir = $d['tempat_lahir'];

# ===========================================
# TO PROPER NAMA ORANG
# ===========================================
$nama_calon = ucwords(strtolower($nama_calon));
$nama_ayah = ucwords(strtolower($nama_ayah));
$nama_ibu = ucwords(strtolower($nama_ibu));







# ===========================================
# DATA DAFTAR
# ===========================================
$id_gelombang = $d['id_gelombang'];
$id_prodi1 = $d['id_prodi1'];
$id_prodi2 = $d['id_prodi2'];
$id_jalur = $d['id_jalur'];
$id_kelas = $d['id_kelas'];
// $id_jadwal_tes = $d['id_jadwal_tes'];
$id_referal = $d['id_referal'];
$status_daftar = $d['status_daftar'];
$status_lulus = $d['status_lulus'];
$tanggal_daftar = $d['tanggal_daftar'];
$tanggal_submit_formulir = $d['tanggal_submit_formulir'];
$tanggal_tes_pmb = $d['tanggal_tes_pmb'];
$tanggal_lulus_tes = $d['tanggal_lulus_tes'];
$folder_uploads = $d['folder_uploads'];
$grade_lulus = $d['grade_lulus'];
if ($grade_lulus=="" and $id_jalur!=3) {
    die("grade_lulus is empty.<hr>Fitur ini tidak bisa diakses tanpa Grade Lulus yang benar.");
}
if (strlen($grade_lulus)>1) {
    die("grade_lulus length invalid.");
}

$nama_gel = substr($id_gelombang, 4, 1);



# ===========================================
# GET NAMA PRODI/SINGKATAN PRODI
# ===========================================
$nama_prodi1 = $d['nama_prodi1'];
$nama_prodi2 = $d['nama_prodi2'];
$singkatan_prodi1 = $d['singkatan_prodi1'];
$singkatan_prodi2 = $d['singkatan_prodi2'];
$jenjang1 = $d['jenjang1'];
$jenjang2 = $d['jenjang2'];

$nama_jalur = $d['nama_jalur'];
$singkatan_jalur = strtoupper($d['singkatan_jalur']);

# ===========================================
# PENGALIHAN PRODI UNTUK GRADE B/C
# ===========================================
$nama_prodi_show = $nama_prodi1;
$singkatan_prodi_show = $singkatan_prodi1;
$jenjang_show = $jenjang1;
// if($id_jalur==3 and ($grade_lulus=="B" or $grade_lulus=="C")){
//     $singkatan_prodi_show = $singkatan_prodi2;
//     $nama_prodi_show = $nama_prodi2;
//     $jenjang_show = $jenjang2;
// }



# ===========================================
# NAMA KECAMATAN/KAB
# ===========================================
$nama_kec_sekolah = $d['nama_kec_sekolah'];
$nama_kec_ktp = $d['nama_kec_ktp'];
$nama_kec_domisili = $d['nama_kec_domisili'];

$nama_kab_sekolah = $d['nama_kab_sekolah'];
$nama_kab_ktp = $d['nama_kab_ktp'];
$nama_kab_domisili = $d['nama_kab_domisili'];






# ===========================================
# SECURITY TOKEN BY MD5 ID JALUR
# ===========================================
if (!isset($_POST['download_formulir_token'])) {
    die("Page ini idak dapat diakses secara langsung. <hr>Download Token is missing.");
}

$download_formulir_token = $_POST['download_formulir_token'];
$token2 = md5(date("mydh").$id_jalur);
if ($download_formulir_token!=$token2) {
    die("Token invalid untuk mengakses fitur ini.");
}















































ob_start();
require('fpdf.php');

class PDF extends FPDF
{
    public function Header()
    {
        $this->Image('kop.png', 10, 6, 190);
        $this->Ln(20);

        // $this->SetFont('Arial','B',14);
        // $this->Cell(0,10,'FORMULIR PENDAFTARAN PMB',0,0,'C');
        // $this->Ln(10);
    }

    public function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Page '.$this->PageNo().'/{nb}', 0, 0, 'C');
    }
}

?>














































<?php
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();

$pdf->SetFont('Arial', '', 9);
$cb = 0;
$pdf->SetMargins(20, 10, 20);

# ===========================================
# NOMOR
# ===========================================
$pdf->Cell(23, 1, ' ', 0, 1);
// nomor
$singkatan_jalur_surat = "REG";
if ($id_jalur==3) {
    $singkatan_jalur_surat = "KIP";
}
$pdf->Cell(23, 5, 'Nomor', $cb, 0);
$pdf->Cell(2, 5, ':', $cb, 0);
$pdf->Cell(145, 5, "$nomor_surat/A/PMB/$singkatan_jalur_surat/STMIK-IKMI/$bulan/$tahun", $cb, 1);

// Lampiran
$pdf->Cell(23, 5, 'Lampiran', $cb, 0);
$pdf->Cell(2, 5, ':', $cb, 0);
$pdf->Cell(145, 5, "-", $cb, 1);

// Perihal
$pdf->Cell(23, 5, 'Perihal', $cb, 0);
$pdf->Cell(2, 5, ':', $cb, 0);
$pdf->SetFont('Arial', 'b', 9);
$pdf->Cell(145, 5, "Pemberitahuan Hasil Seleksi Calon Mahasiswa Baru TA. 2023/2024", $cb, 1);
$pdf->SetFont('Arial', '', 9);


# ===========================================
# KEPADA YTH
# ===========================================
//spacing after
$pdf->Cell(170, 3, ' ', "", 1);
// $pdf->SetFillColor(0,255,255);
$pdf->Cell(170, 5, "Kepada Yth", $cb, 1);
$pdf->SetFont('Arial', 'b', 9);
$pdf->Cell(170, 5, "Ananda $nama_calon", $cb, 1);
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(170, 5, "Di Tempat", $cb, 1);
// $pdf->Cell(170,5,"Tempat",$cb,1);


# ===========================================
# DENGAN HORMAT
# ===========================================
//spacing after
$pdf->Cell(170, 3, ' ', "", 1);
// dengan hormat
$pdf->Cell(170, 5, "Dengan hormat,", $cb, 1);
if ($id_jalur==3) {
    $pdf->Cell(170, 5, "Kami ucapkan selamat kepada saudara/i yang telah dinyatakan LULUS dan DITERIMA dalam seleksi Tes PMB", $cb, 1);
    $pdf->Cell(170, 5, "STMIK IKMI Cirebon TA. 2023/2024 UNTUK DIPROSES LEBIH LANJUT sebagai CALON MAHASISWA", $cb, 1);
    $pdf->Cell(170, 5, "PENERIMA BEASISWA KIP KULIAH STMIK IKMI CIREBON Tahun Akademik 2023/2024 pada :", $cb, 1);
} else {
    // jalur REGULER
    $pdf->Cell(170, 5, "Kami ucapkan selamat kepada saudara yang telah dinyatakan LULUS GELOMBANG $nama_gel GRADE $grade_lulus pada jalur ", $cb, 1);
    $pdf->Cell(170, 5, "Reguler STMIK IKMI Cirebon dalam seleksi PMB Tahun Akademik 2023/2024, pada :", $cb, 1);
}



# ===========================================
# PROGRAM STUDI
# ===========================================
//spacing after
$pdf->SetMargins(25, 10, 25);
$pdf->SetFont('Arial', 'b', 9);
$pdf->Cell(125, 2, ' ', "", 1);

$pdf->Cell(38, 5, 'Program Studi', $cb, 0);
$pdf->Cell(2, 5, ':', $cb, 0);
$pdf->Cell(125, 5, "$nama_prodi_show", $cb, 1);

$pdf->Cell(38, 5, 'Jenjang', $cb, 0);
$pdf->Cell(2, 5, ':', $cb, 0);
$pdf->Cell(125, 5, "$jenjang_show", $cb, 1);

$pdf->Cell(38, 5, 'Nomor Pendaftaran', $cb, 0);
$pdf->Cell(2, 5, ':', $cb, 0);
$pdf->Cell(125, 5, "$id_daftar", $cb, 1);
$pdf->SetFont('Arial', '', 9);
$pdf->SetMargins(20, 10, 20);
$pdf->Cell(2, 1, ' ', $cb, 1);


# ===========================================
# SELANJUTNYA
# ===========================================
if ($id_jalur==3) {
    # ===========================================
    # SELANJUTNYA SAUDARA KIP
    # ===========================================
    //spacing after
    $pdf->Cell(170, 2, ' ', "", 1);
    // dengan hormat
    $pdf->Cell(170, 5, "Setelah ditetapkan sebagai Penerima Beasiswa KIP Kuliah dan ketika biaya hidup semester 1 telah ", $cb, 1);
    $pdf->Cell(170, 5, "dicairkan dari Pemerintah, anda diwajibkan untuk melakukan proses Daftar Ulang (diluar biaya pendidikan) ", $cb, 1);
    $pdf->Cell(170, 5, "yang akan diinformasikan kemudian.", $cb, 1);

    $pdf->Cell(170, 5, ' ', $cb, 1);
    $pdf->Cell(170, 5, "Serta menyerahkan kelengkapan dokumen persyaratan di Sekretariat Informasi Pendaftaran STMIK IKMI", $cb, 1);
    $pdf->Cell(170, 5, "Cirebon pada hari kerja Senin sd Sabtu (Pukul 08.00 sd 16.00 WIB) sebagai berikut :", $cb, 1);

    # ===========================================
    # LIST PERSYARATAN KIP
    # ===========================================
    $pdf->SetMargins(25, 10, 25);
    $pdf->Cell(165, 1, " ", $cb, 1);
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(165, 4, "1. Surat Pernyataan kesanggupan melakukan Daftar Ulang (tersedia di Front Office)", $cb, 1);
    $pdf->Cell(165, 4, "2. Pas Foto Ukuran 4 x 6 background biru (2 lembar)", $cb, 1);
    $pdf->Cell(165, 4, "3. Fotocopy KTP Calon Mahasiswa / Surat Keterangan Disdukcapil (2 lembar)", $cb, 1);
    $pdf->Cell(165, 4, "4. Fotocopy KTP Orang Tua (2 lembar)", $cb, 1);
    $pdf->Cell(165, 4, "5. Fotocopy Ijazah Terakhir / Surat Keterangan Sebagai Siswa Kelas 12 (1 lembar)", $cb, 1);
    $pdf->Cell(165, 4, "6. Fotocopy Fotocopy Kartu Keluarga (2 lembar)", $cb, 1);
    $pdf->Cell(165, 4, "7. Fotocopy KIP Pelajar (jika ada) / KKS / PKH / SKTM dari desa / kelurahan (1 lembar)", $cb, 1);
    $pdf->Cell(165, 4, "8. Foto Bersama Keluarga termasuk Calon Mhs didalamnya dicetak kertas foto ukuran 4R (1 lembar)", $cb, 1);
    $pdf->Cell(165, 4, "9. Foto Tampak Depan Rumah dicetak kertas foto ukuran 4 R. (1 lembar)", $cb, 1);
    $pdf->Cell(165, 4, "10. Foto Ruang Keluarga dicetak kertas foto ukuran 4 R. (1 lembar)", $cb, 1);
    $pdf->SetMargins(20, 10, 20);
    $pdf->SetFont('Arial', '', 9);





    # ===========================================
    # DOKUMEN PERSYARATAN KIP
    # ===========================================
    $pdf->Cell(170, 2, " ", $cb, 1);
    $pdf->Cell(170, 5, "Dokumen persyaratan diatas dimasukkan kedalam amplop berwarna coklat, dikirimkan secara datang", $cb, 1);
    $pdf->Cell(170, 5, "langsung atau melalui POS / ekspedisi lain ditujukan kepada :", $cb, 1);


    $pdf->SetMargins(25, 10, 25);
    $pdf->SetFont('Arial', 'b', 10);
    $pdf->Cell(165, 1, " ", $cb, 1);
    $pdf->Cell(165, 5, "Panitia PMB, Kampus STMIK IKMI Cirebon, Jl. Perjuangan No. 10B Karyamulya Kecamatan", $cb, 1);
    $pdf->Cell(165, 5, "Kesambi Kota Cirebon 45131 atau dapat dititipkan melalui Security Kampus STMIK IKMI. ", $cb, 1);
    $pdf->Cell(165, 5, "Apabila melewati dari tanggal yang telah ditentukan, maka kami anggap anda telah ", $cb, 1);
    $pdf->Cell(165, 5, "mengundurkan diri dari Calon Mahasiswa STMIK IKMI Cirebon.", $cb, 1);

    $pdf->SetTextColor(255, 0, 0);
    $pdf->Cell(165, 5, "Pengiriman persyaratan paling lambat $tanggal_deadline.", $cb, 1);
    $pdf->SetTextColor(0, 0, 0);

    $pdf->SetFont('Arial', '', 10);
    $pdf->SetMargins(20, 10, 20);




    $pdf->Cell(170, 3, " ", $cb, 1);
    $pdf->Cell(170, 5, "Informasi lebih lanjut bisa menghubungi Chat Whatsapp di nomor $nomor_petugas ", $cb, 1);
    // $pdf->Cell(170,3," ",$cb,1);
    $pdf->Cell(170, 5, "Demikian surat ini kami sampaikan, atas perhatian dan kerjasamanya kami ucapkan terima kasih.", $cb, 1);




    $pdf->Cell(170, 3, " ", $cb, 1);
    $pdf->Cell(170, 5, "Cirebon, $titi_mangsa", $cb, 1);
    $pdf->Cell(170, 5, "Ketua STMIK IKMI Cirebon,", $cb, 1);
    $pdf->Image("qr_ketua.png", 25, null, 30);
    $pdf->SetFont('Arial', 'b', 10);
    $pdf->Cell(170, 5, "Dr. Dadang Sudrajat, S.Si, M.Kom", $cb, 1);
    $pdf->SetFont('Arial', '', 10);


// $pdf->AddPage();
// $pdf->Cell(170, 5, " ", $cb, 1);
// $pdf->SetFont('Arial', 'b', 12);
// $pdf->Cell(170, 3, "CONTOH KARTU PENDAFTARAN KIP KULIAH", $cb, 1);
// $pdf->Cell(170, 3, " ", $cb, 1);
// $pdf->Image("contoh_kartu_peserta_kip.png", 25);
} else {
    # ===========================================
    # SELANJUTNYA SAUDARA REGULER
    # ===========================================
    # Image(string file [, float x [, float y [, float w [, float h [, string type [, mixed link]]]]]])


    # ===========================================
    /* Selanjutnya saudara dipersilakan untuk melakukan Daftar Ulang (Registrasi) dengan cara menyelesaikan pembayaran kewajiban melalui Transfer atau M-Banking BANK BRI di Nomor Rekening 4149-01-000004-30-5 atas nama STMIK IKMI CIREBON dan apabila sudah melakukan pembayaran diwajibkan untuk melakukan konfirmasi Whatsapp ke Biro Administrasi Umum (BAU) di nomor 0823-1111-3070.

    Selanjutnya menyerahkan kelengkapan dokumen persyaratan di Sekretariat Informasi Pendaftaran STMIK IKMI Cirebon pada hari kerja Senin sd Sabtu (Pukul 08.00 sd 16.00 WIB) sebagai berikut : */
    # ===========================================
    $font_size_reg = 8;
    $pdf->SetFont('Arial', '', $font_size_reg);
    $line_spasi_reg = 4;
    $pdf->Cell(170, $line_spasi_reg, "Selanjutnya saudara dipersilakan untuk melakukan Daftar Ulang (Registrasi) dengan cara menyelesaikan pembayaran kewajiban melalui", $cb, 1);
    $pdf->Cell(170, $line_spasi_reg, "Transfer atau M-Banking BANK BRI di Nomor Rekening 4149-01-000004-30-5 atas nama STMIK IKMI CIREBON dan apabila sudah melakukan", $cb, 1);
    $pdf->Cell(170, $line_spasi_reg, "pembayaran diwajibkan untuk melakukan konfirmasi Whatsapp ke Biro Administrasi Umum (BAU) di nomor 0823-1111-3070.", $cb, 1);
    $pdf->Cell(170, $line_spasi_reg, " ", $cb, 1);

    # ===========================================
    /* Untuk selanjutnya menyerahkan kelengkapan dokumen persyaratan di Sekretariat Pendaftaran STMIK IKMI Cirebon pada hari kerja Senin sd Sabtu (Pukul 08.00 sd 16.00 WIB) sebagai berikut : */
    # ===========================================
    $pdf->Cell(170, $line_spasi_reg, "Selanjutnya menyerahkan kelengkapan dokumen persyaratan di Sekretariat Informasi Pendaftaran STMIK IKMI Cirebon pada hari kerja Senin", $cb, 1);
    $pdf->Cell(170, $line_spasi_reg, "sd Sabtu (Pukul 08.00 sd 16.00 WIB) sebagai berikut :", $cb, 1);
    $pdf->Cell(170, 2, "  ", $cb, 1);



    # ===========================================
    /*
    1. Bukti Pembayaran (biaya daftar, biaya awal, dan biaya kuliah semester 1) lihat di tabel kewajiban pembayaran.
    2. Pas Foto Ukuran 3x4 dan 4 x 6 background biru (masing-masing 2 lembar).
    3. Fotocopy KTP Calon Mahasiswa / Surat Keterangan Disdukcapil (2 lembar).
    4. Fotocopy KTP Orang Tua
    5. Fotocopy Ijazah Terakhir / Surat Keterangan Sebagai Siswa Kelas 12 (1 lembar).
    6. Fotocopy SKHUN (jika ada).
    7. Fotocopy Fotocopy Kartu Keluarga (2 lembar).
    */
    # ===========================================
    $pdf->Cell(170, $line_spasi_reg, "1. Bukti Pembayaran (biaya pendaftaran, dan biaya daftar ulang) lihat di tabel kewajiban pembayaran dibawah ini.", $cb, 1);
    $pdf->Cell(170, $line_spasi_reg, "2. Pas Foto Ukuran 3x4 dan 4 x 6 background biru (masing-masing 2 lembar).", $cb, 1);
    $pdf->Cell(170, $line_spasi_reg, "3. Fotocopy KTP Calon Mahasiswa / Surat Keterangan Disdukcapil (2 lembar).", $cb, 1);
    $pdf->Cell(170, $line_spasi_reg, "4. Fotocopy KTP Orang Tua", $cb, 1);
    $pdf->Cell(170, $line_spasi_reg, "5. Fotocopy Ijazah Terakhir / Surat Keterangan Sebagai Siswa Kelas 12 (1 lembar).", $cb, 1);
    $pdf->Cell(170, $line_spasi_reg, "6. Fotocopy Fotocopy Kartu Keluarga (2 lembar).", $cb, 1);
    $pdf->Cell(170, 2, " ", $cb, 1);





    # ===========================================
    /*
    Tabel Kewajiban Pembayaran :

    A. Biaya Pendaftaran
    -   Pendaftaran  Rp. 200.000 (Dua ratus ribu rupiah),
    -   dilunasi sebelum tanggal $tanggal_deadline
    */
    # ===========================================
    $pdf->Cell(170, $line_spasi_reg, "Tabel Kewajiban Pembayaran :", $cb, 1);
    $pdf->Cell(170, 2, " ", $cb, 1);

    $pdf->SetFont('Arial', 'B', $font_size_reg);
    $pdf->Cell(170, $line_spasi_reg, "A. Biaya Pendaftaran", $cb, 1);
    $pdf->SetFont('Arial', '', $font_size_reg);

    $pdf->Cell(170, $line_spasi_reg, "     - Pendaftaran  Rp. 200.000 (Dua ratus ribu rupiah)", $cb, 1);

    $pdf->SetTextColor(255, 0, 0);
    $pdf->Cell(170, $line_spasi_reg, "     - dilunasi sebelum tanggal $tanggal_deadline", $cb, 1);
    $pdf->SetTextColor(0, 0, 0);
    // $pdf->Cell(170, 2, " ", $cb, 1);





    # ===========================================
    /*
    B.  Biaya Daftar Ulang,
    No  Komponen Biaya  Reguler Pagi    Reguler Sore
    1   Jaket Almamater 200.000 200.000
    2   PKKMB/KU    250.000 250.000
    3   Perpustakaan + Asuransi 400.000 400.000
    4   KTM (Kartu Tanda Mahasiswa) 100.000 100.000
    5   Kemahasiswaan   200.000 200.000
    Total   1.150.000   1.150.000
        )* Wajib Di Lunasi Sebelum Tanggal $tanggal_deadline
    */
    # ===========================================
    $pdf->SetFont('Arial', 'B', $font_size_reg);
    $pdf->Cell(170, 5.5, "B.  Biaya Daftar Ulang", $cb, 1);
    // $pdf->Cell(170,1," ",$cb,1);

    # Cell(float w [, float h [, string txt [, mixed border [, int ln [, string align [, boolean fill [, mixed link]]]]]]])
    #              6,       4.5,      zzz    , border        ,     ln  ,
    $pdf->Cell(6, 5.5, " ", 0, 0);
    $pdf->Cell(7, 5.5, "No", 1, 0, "C");
    $pdf->Cell(70, 5.5, "Komponen Biaya", 1, 0, "C");
    $pdf->Cell(30, 5.5, "Reguler Pagi", 1, 0, "C");
    $pdf->Cell(30, 5.5, "Reguler Sore", 1, 1, "C");

    $pdf->SetFont('Arial', '', $font_size_reg);

    // 1   Jaket Almamater 200.000 200.000
    $pdf->Cell(6, $line_spasi_reg, " ", 0, 0);
    $pdf->Cell(7, $line_spasi_reg, "1", 1, 0, "C");
    $pdf->Cell(70, $line_spasi_reg, "Jaket Almamater", 1, 0);
    $pdf->Cell(30, $line_spasi_reg, "200.000", 1, 0, "R");
    $pdf->Cell(30, $line_spasi_reg, "200.000", 1, 1, "R");

    // 2   PKKMB/KU    250.000 250.000
    $pdf->Cell(6, $line_spasi_reg, " ", 0, 0);
    $pdf->Cell(7, $line_spasi_reg, "2", 1, 0, "C");
    $pdf->Cell(70, $line_spasi_reg, "PKKMB/KU ", 1, 0);
    $pdf->Cell(30, $line_spasi_reg, "250.000", 1, 0, "R");
    $pdf->Cell(30, $line_spasi_reg, "250.000", 1, 1, "R");

    // 3 Perpustakaan + Asuransi 400.000 400.000
    $pdf->Cell(6, $line_spasi_reg, " ", 0, 0);
    $pdf->Cell(7, $line_spasi_reg, "3", 1, 0, "C");
    $pdf->Cell(70, $line_spasi_reg, "Perpustakaan + Asuransi", 1, 0);
    $pdf->Cell(30, $line_spasi_reg, "400.000", 1, 0, "R");
    $pdf->Cell(30, $line_spasi_reg, "400.000", 1, 1, "R");

    // 4 KTM (Kartu Tanda Mahasiswa) 100.000 100.000
    $pdf->Cell(6, $line_spasi_reg, " ", 0, 0);
    $pdf->Cell(7, $line_spasi_reg, "4", 1, 0, "C");
    $pdf->Cell(70, $line_spasi_reg, "KTM (Kartu Tanda Mahasiswa)", 1, 0);
    $pdf->Cell(30, $line_spasi_reg, "100.000", 1, 0, "R");
    $pdf->Cell(30, $line_spasi_reg, "100.000", 1, 1, "R");

    // 5 Kemahasiswaan   200.000 200.000
    $pdf->Cell(6, $line_spasi_reg, " ", 0, 0);
    $pdf->Cell(7, $line_spasi_reg, "5", 1, 0, "C");
    $pdf->Cell(70, $line_spasi_reg, "Kemahasiswaan", 1, 0);
    $pdf->Cell(30, $line_spasi_reg, "200.000", 1, 0, "R");
    $pdf->Cell(30, $line_spasi_reg, "200.000", 1, 1, "R");

    // Total   1.150.000   1.150.000
    $pdf->SetFont('Arial', 'B', $font_size_reg);
    $pdf->Cell(6, $line_spasi_reg, " ", 0, 0);
    $pdf->Cell(77, $line_spasi_reg, "TOTAL", 1, 0, "C");
    $pdf->Cell(30, $line_spasi_reg, "1.150.000", 1, 0, "R");
    $pdf->Cell(30, $line_spasi_reg, "1.150.000", 1, 1, "R");

    // )* Wajib Di Lunasi Sebelum Tanggal $tanggal_deadline
    $pdf->Cell(6, $line_spasi_reg, " ", 0, 0);
    $pdf->SetTextColor(255, 0, 0);
    $pdf->Cell(164, $line_spasi_reg, ")* Wajib Di Lunasi Sebelum Tanggal $tanggal_deadline", 0, 1);
    $pdf->SetTextColor(0, 0, 0);


    $pdf->SetFont('Arial', '', $font_size_reg);
    $pdf->Cell(170, 1, " ", $cb, 1);





    # ===========================================
    # ADD PAGE
    # ===========================================
    // $pdf->AddPage();


    # ===========================================
    /*
    C.  Tabel Biaya Kuliah Per Semester yang Harus Dibayar Setelah Mendapatkan Program Relaksasi
    * pembayaran dilakukan sebelum tanggal $tanggal_deadline
    No  Program Studi                   GRADE A *)              GRADE B *)              GRADE C *)
                                        Pagi        Sore        Pagi        Sore        Pagi        Sore
    1   Sarjana (S1)                    3.200.000   3.450.000   3.800.000   4.050.000   4.200.000   4.400.000
    2   Diploma (D3)                    2.700.000   2.950.000   3.400.000   3.650.000   4.200.000   4.400.000

    */
    # ===========================================

    $pdf->SetFont('Arial', 'B', $font_size_reg);
    $pdf->Cell(170, 5.5, "C. Tabel Biaya Kuliah Per Semester yang Harus Dibayar Setelah Mendapatkan Program Relaksasi", $cb, 1);
    // $pdf->Cell(170,1," ",$cb,1);
    $cb = 1;

    // No  Program Studi                   GRADE A *)              GRADE B *)              GRADE C *)
    $pdf->Cell(7, 5, " ", $cb, 0, "C");
    $pdf->Cell(55, 5, " ", $cb, 0, "C");
    $pdf->Cell(36, 5, "Gel 1 (Grade A)", $cb, 0, "C");
    $pdf->Cell(36, 5, "Gel 2 (Grade B)", $cb, 0, "C");
    $pdf->Cell(36, 5, "Gel 3 (Grade C)", $cb, 1, "C");

    $pdf->Cell(7, 5, "No", $cb, 0, "C");
    $pdf->Cell(55, 5, "Program Studi", $cb, 0, "C");
    $pdf->Cell(18, 5, "Pagi", $cb, 0, "C");
    $pdf->Cell(18, 5, "Sore", $cb, 0, "C");
    $pdf->Cell(18, 5, "Pagi", $cb, 0, "C");
    $pdf->Cell(18, 5, "Sore", $cb, 0, "C");
    $pdf->Cell(18, 5, "Pagi", $cb, 0, "C");
    $pdf->Cell(18, 5, "Sore", $cb, 1, "C");


    $pdf->SetFont('Arial', '', $font_size_reg);



    // 1   Teknik Informatika (S1)         3.200.000   3.450.000   3.800.000   4.050.000   4.200.000   4.400.000
    $pdf->Cell(7, $line_spasi_reg, "1", $cb, 0, "C");
    $pdf->Cell(55, $line_spasi_reg, "Sarjana (S1)", $cb, 0);
    $pdf->Cell(18, $line_spasi_reg, "3.200.000", $cb, 0, "R");
    $pdf->Cell(18, $line_spasi_reg, "3.450.000", $cb, 0, "R");
    $pdf->Cell(18, $line_spasi_reg, "3.800.000", $cb, 0, "R");
    $pdf->Cell(18, $line_spasi_reg, "4.050.000", $cb, 0, "R");
    $pdf->Cell(18, $line_spasi_reg, "4.200.000", $cb, 0, "R");
    $pdf->Cell(18, $line_spasi_reg, "4.400.000", $cb, 1, "R");

    // 2   Diploma (D3)   2.700.000   2.950.000   3.400.000   3.650.000   4.200.000   4.400.000
    $pdf->Cell(7, $line_spasi_reg, "2", $cb, 0, "C");
    $pdf->Cell(55, $line_spasi_reg, "Diploma (D3)", $cb, 0);
    $pdf->Cell(18, $line_spasi_reg, "2.450.000", $cb, 0, "R");
    $pdf->Cell(18, $line_spasi_reg, "2.700.000", $cb, 0, "R");
    $pdf->Cell(18, $line_spasi_reg, "3.000.000", $cb, 0, "R");
    $pdf->Cell(18, $line_spasi_reg, "3.250.000", $cb, 0, "R");
    $pdf->Cell(18, $line_spasi_reg, "4.200.000", $cb, 0, "R");
    $pdf->Cell(18, $line_spasi_reg, "4.400.000", $cb, 1, "R");





    // $pdf->Cell(170, $line_spasi_reg, " ", 0, 1);





    # ===========================================
    /*
    Informasi teknis Pembayaran bisa menghubungi Chat Whatsapp di nomor :
    -   0838-2165-1265 / 0823-1605-5422 ( Front Office )
    -   0823-1111-3070 ( Biro Administrasi Umum )

    Demikian surat ini kami sampaikan, atas perhatian dan kerjasamanya yang baik kami ucapkan terima kasih.

    Cirebon, 12 April 2022
    Ketua STMIK IKMI Cirebon

    IMAGE QR-CODE PNG
    Dr. Dadang Sudrajat, S.Si, M.Kom
    */
    # ===========================================
    $cb=0;
    $pdf->Cell(170, $line_spasi_reg, "Info teknis Pembayaran 0823-1605-5422 (Front Office) / 0823-1111-3070 ( Biro Administrasi Umum )", $cb, 1);
    // $pdf->Cell(170, $line_spasi_reg, " ", $cb, 1);
    $pdf->Cell(170, $line_spasi_reg, "Demikian surat ini kami sampaikan, atas perhatian dan kerjasamanya yang baik kami ucapkan terima kasih.", $cb, 1);
    $pdf->Cell(170, $line_spasi_reg, " ", $cb, 1);

    $pdf->Cell(170, $line_spasi_reg, "Cirebon, $titi_mangsa", $cb, 1);
    $pdf->Cell(170, $line_spasi_reg, "Ketua STMIK IKMI Cirebon,", $cb, 1);
    $pdf->Image("qr_ketua.png", 25, null, 15);
    $pdf->SetFont('Arial', 'b', 10);
    $pdf->Cell(170, $line_spasi_reg, "Dr. Dadang Sudrajat, S.Si, M.Kom", $cb, 1);



    // if($id_jadwal_tes>=15){
    //     $pdf->Image("selanjutnya_$id_jadwal_tes"."a.png",null,null,170);
    //     $pdf->AddPage();
    //     $pdf->Cell(170,5," ",$cb,1);
    //     $pdf->Image("selanjutnya_$id_jadwal_tes"."b.png",null,null,170);
    // }else{
    //     $pdf->Image("selanjutnya_$id_jadwal_tes.png",null,null,null,175);
    // }
}

$pdf->Output('D', "Hasil-Tes-$id_daftar-$id_jalur - $nama_calon.pdf");
ob_end_flush();
?>