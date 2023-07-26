<?php
// session_start();
$id_kurikulum = isset($_POST['id_kurikulum']) ? $_POST['id_kurikulum'] : die('Page ini tidak dapat diakses secara langsung.');
include "../conn.php";


# ==============================================================
# GET KURIKULUM DATA
# ==============================================================
$s = "SELECT 
b.nama as nama_prodi, 
CONCAT('Kurikulum ',c.jenjang,'-',c.angkatan) as nama_kurikulum, 
c.angkatan,
d.nama as jenjang,
a.basis, 
c.jumlah_semester,
a.is_publish, 
a.tanggal_penetapan, 
a.ditetapkan_oleh,
c.jumlah_bulan_per_semester,
b.id as id_prodi, 
c.id as id_kalender, 
a.id as id_kurikulum 

FROM tb_kurikulum a 
JOIN tb_prodi b ON b.id=a.id_prodi 
JOIN tb_kalender c ON c.id=a.id_kalender  
JOIN tb_jenjang d ON d.jenjang=c.jenjang  
WHERE a.id='$id_kurikulum'";
$q = mysqli_query($cn, $s)or die(mysqli_error($cn));
if(!mysqli_num_rows($q)) die('Data kurikulum tidak ditemukan.');
$d = mysqli_fetch_assoc($q);
$jumlah_semester = $d['jumlah_semester'];
$nama_kurikulum = strtoupper($d['nama_kurikulum']);
$basis = strtoupper($d['basis']);
$id_kalender = $d['id_kalender'];
$id_prodi = $d['id_prodi'];
$nama_prodi = strtoupper($d['nama_prodi']);
$disahkan_oleh = ''; //zzz
$nidn_kaprodi = '-'; //zzz

$mk_s1 = [];
$mk_s2 = [];
$mk_s3 = [];
$mk_s4 = [];
$mk_s5 = [];
$mk_s6 = [];
$mk_s7 = [];
$mk_s8 = [];

$mk_pilihan = [];

$r_total_teori = [];
$r_total_praktik = [];
$r_total_sks = [];




# ==============================================================
# TAMPIL SEMESTERS
# ==============================================================
$s = "SELECT 
a.id as id_semester,
a.nomor as no_semester 
FROM tb_semester a 
JOIN tb_kalender b ON b.id=a.id_kalender 
JOIN tb_kurikulum c ON c.id_kalender=b.id  

WHERE c.id='$id_kurikulum' 
ORDER BY a.nomor 
";
$q = mysqli_query($cn, $s)or die(mysqli_error($cn));

$jumlah_semester_real = mysqli_num_rows($q);

$semesters = '';
$rnomor_semester = [];
$total_mk = 0;
$total_teori = 0;
$total_praktik = 0;
$i=0;
while ($d=mysqli_fetch_assoc($q)) {
  $i++; 
  array_push($rnomor_semester,$d['no_semester']);

  # ==============================================================
  # LIST MATA KULIAH
  # ==============================================================
  $s2 = "SELECT 
  a.kode as kode_mk,
  a.nama as nama_mk,
  a.bobot_teori,
  a.bobot_praktik,
  a.prasyarat

  FROM tb_mk a 
  JOIN tb_kurikulum_mk b ON a.id=b.id_mk 
  JOIN tb_semester c ON b.id_semester=c.id  
  JOIN tb_kurikulum d ON b.id_kurikulum=d.id  
  WHERE c.id='$d[id_semester]' 
  AND d.id_prodi=$id_prodi
  ";
  $q2 = mysqli_query($cn, $s2)or die(mysqli_error($cn));
  $jumlah_mk = mysqli_num_rows($q2);

  $tr = '';
  $jumlah_teori[$d['id_semester']] = 0;
  $jumlah_praktik[$d['id_semester']] = 0;
  $j=0;
  // list MK looping
  while ($d2=mysqli_fetch_assoc($q2)) { 
    $j++;
    $total_mk++;
    $jumlah_teori[$d['id_semester']] += $d2['bobot_teori'];
    $jumlah_praktik[$d['id_semester']] += $d2['bobot_praktik'];

    $d2['prasyarat'] = $d2['prasyarat']=='' ? '-' : $d2['prasyarat'];

    $arr = [
      $d2['kode_mk'],
      $d2['nama_mk'],
      $d2['bobot_teori'],
      $d2['bobot_praktik'],
      $d2['prasyarat']
    ];

    switch ($d['no_semester']) {
      case 1: array_push($mk_s1,$arr); break;
      case 2: array_push($mk_s2,$arr); break;
      case 3: array_push($mk_s3,$arr); break;
      case 4: array_push($mk_s4,$arr); break;
      case 5: array_push($mk_s5,$arr); break;
      case 6: array_push($mk_s6,$arr); break;
      case 7: array_push($mk_s7,$arr); break;
      case 8: array_push($mk_s8,$arr); break;
      default: die('Invalid nomor semester.');
    }


  } //end while list MK

  $total_teori +=   $jumlah_teori[$d['id_semester']];
  $total_praktik +=   $jumlah_praktik[$d['id_semester']];

  array_push($r_total_teori,$jumlah_teori[$d['id_semester']]);
  array_push($r_total_praktik,$jumlah_praktik[$d['id_semester']]);
  array_push($r_total_sks,($jumlah_teori[$d['id_semester']]+$jumlah_praktik[$d['id_semester']]));

} // end while semesters

$total_sks = $total_praktik + $total_teori;
$persen_teori = round($total_teori/$total_sks*100,0);
$persen_praktik = 100-$persen_teori;
// echo "<pre>";
// var_dump($r_total_sks);
// echo "</pre>";
// exit;


ob_start();
require('fpdf/fpdf.php');

class PDF extends FPDF
{
    public function Header()
    {
        // $this->Image('kop.png', 10, 6, 190);
        // $this->Ln(20);

        // $this->SetFont('Arial','B',10);
        // $this->Cell(0,5,$nama_kurikulum,0,1,'C');
        // $this->Cell(0,5,"BASIS $basis",0,1,'C');
        // $this->Ln(10);
    }

    public function Footer()
    {
        // $this->SetY(-15);
        // $this->SetFont('Arial', 'I', 8);
        // $this->Cell(0, 10, 'Page '.$this->PageNo().'/{nb}', 0, 0, 'C');
    }
}

?>





<?php
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();


# ===========================================
# SETTINGS
# ===========================================
$pdf->SetFont('Arial', '', 8);
$cb = 0; //cell border
$pdf->SetMargins(5, 10, 5);
$lh = 4.3; //line height
$lhs = 5; //line height SEMESTER XX
$lhhz = 3; //line height SPACER HZ


# ===========================================
# HEADER
# ===========================================
$pdf->SetFont('Arial','B',10);
$pdf->Cell(0,5,$nama_kurikulum,0,1,'C');
$pdf->Cell(0,5,"BASIS $basis",0,1,'C');
# ===========================================
# START
# ===========================================
// 3
$pdf->Cell(200, $lhhz, " ", 0, 1); // spacer hz
// 4 SEMESTER
$semester_ke=0;
$jumlah_rows_kiri_kanan = round($jumlah_semester_real/2,0,PHP_ROUND_HALF_UP);

for ($i=1; $i <=$jumlah_rows_kiri_kanan ; $i++) { 
  $total_teori_kiri = $r_total_teori[$semester_ke];
  $total_praktik_kiri = $r_total_praktik[$semester_ke];
  $total_sks_kiri = $r_total_sks[$semester_ke];
  $semester_ke++;
  $semester_ke_kiri = $semester_ke;
  
  $total_teori_kanan = $r_total_teori[$semester_ke];
  $total_praktik_kanan = $r_total_praktik[$semester_ke];
  $total_sks_kanan = $r_total_sks[$semester_ke];
  $semester_ke++;
  $semester_ke_kanan = $semester_ke;

  $pdf->SetFont('Arial', 'b', 9);
  $pdf->Cell(102, $lhs, "SEMESTER $semester_ke_kiri", 0, 0);
  $pdf->Cell(98, $lhs, "SEMESTER $semester_ke_kanan", 0, 1);
  // 5 TABLE HEADER 
  $pdf->SetFont('Arial', 'b', 8);

  $pdf->Cell(6, $lh, 'NO', 1, 0,'C');
  $pdf->Cell(16, $lh, 'KODE', 1, 0,'C');
  $pdf->Cell(50, $lh, 'MATA KULIAH', 1, 0,'C');
  $pdf->Cell(5, $lh, 'T', 1, 0,'C');
  $pdf->Cell(5, $lh, 'P', 1, 0,'C');
  $pdf->Cell(16, $lh, 'SYARAT', 1, 0,'C');

  $pdf->Cell(4, $lh, ' ', 0, 0,'C'); // SPACER VERTIVAL

  $pdf->Cell(6, $lh, 'NO', 1, 0,'C');
  $pdf->Cell(16, $lh, 'KODE', 1, 0,'C');
  $pdf->Cell(50, $lh, 'MATA KULIAH', 1, 0,'C');
  $pdf->Cell(5, $lh, 'T', 1, 0,'C');
  $pdf->Cell(5, $lh, 'P', 1, 0,'C');
  $pdf->Cell(16, $lh, 'SYARAT', 1, 1,'C');

  // 6
  // 7
  // 8
  // 9
  // 10
  // 11
  // 12
  // 13 list MK
  $pdf->SetFont('Arial', '', 8);

  switch ($i) {
    case 1: $sm_kiri = $mk_s1; $sm_kanan = $mk_s2; break;
    case 2: $sm_kiri = $mk_s3; $sm_kanan = $mk_s4; break;
    case 3: $sm_kiri = $mk_s5; $sm_kanan = $mk_s6; break;
    case 4: $sm_kiri = $mk_s7; $sm_kanan = $mk_s8; break;
  }
  $max_row = count($sm_kiri)>count($sm_kanan) ? count($sm_kiri) : count($sm_kanan);
  
  for ($j=0; $j < $max_row ; $j++) { 
    if(isset($sm_kiri[$j][0])){
      $pdf->Cell(6, $lh, $j+1, 1, 0,'C');
      $pdf->Cell(16, $lh, $sm_kiri[$j][0], 1, 0,'');
      $pdf->Cell(50, $lh, $sm_kiri[$j][1], 1, 0,'');
      $pdf->Cell(5, $lh, $sm_kiri[$j][2], 1, 0,'C');
      $pdf->Cell(5, $lh, $sm_kiri[$j][3], 1, 0,'C');
      $pdf->Cell(16, $lh, $sm_kiri[$j][4], 1, 0,'');
    }else{
      if($j==0){
        // tidak ada data semester
        $pdf->Cell(98, $lh, 'Belum ada data pada Semester ini.', 1, 0,'');

      }else{
        // data semester lebih sedikit dari max_row
        $pdf->Cell(6, $lh, '-', 1, 0,'C');
        $pdf->Cell(16, $lh, '-', 1, 0,'');
        $pdf->Cell(50, $lh, '-', 1, 0,'');
        $pdf->Cell(5, $lh, '-', 1, 0,'C');
        $pdf->Cell(5, $lh, '-', 1, 0,'C');
        $pdf->Cell(16, $lh, '-', 1, 0,'');

      }
    }    
    
    # ======================================
    # SPACER VERTICAL PADA LOOP
    # ======================================
    $pdf->Cell(4, $lh, ' ', 0, 0,'C');
    # ======================================
    

    if(isset($sm_kanan[$j][0])){
      $pdf->Cell(6, $lh, $j+1, 1, 0,'C');
      $pdf->Cell(16, $lh, $sm_kanan[$j][0], 1, 0,'');
      $pdf->Cell(50, $lh, $sm_kanan[$j][1], 1, 0,'');
      $pdf->Cell(5, $lh, $sm_kanan[$j][2], 1, 0,'C');
      $pdf->Cell(5, $lh, $sm_kanan[$j][3], 1, 0,'C');
      $pdf->Cell(16, $lh, $sm_kanan[$j][4], 1, 1,'');
    }else{
      if($j==0){
        // tidak ada data semester
        $pdf->Cell(98, $lh, 'Belum ada data pada Semester ini.', 1, 1,'');

      }else{
        // data semester lebih sedikit dari max_row
        $pdf->Cell(6, $lh, '-', 1, 0,'C');
        $pdf->Cell(16, $lh, '-', 1, 0,'');
        $pdf->Cell(50, $lh, '-', 1, 0,'');
        $pdf->Cell(5, $lh, '-', 1, 0,'C');
        $pdf->Cell(5, $lh, '-', 1, 0,'C');
        $pdf->Cell(16, $lh, '-', 1, 1,'');

      }
    }
  }

  // 14 total sks
  $pdf->SetFont('Arial', 'b', 8);

  $pdf->Cell(72, $lh, "TOTAL SKS", 1, 0,'C');
  $pdf->Cell(5, $lh, $total_teori_kiri, 1, 0,'C');
  $pdf->Cell(5, $lh, $total_praktik_kiri, 1, 0,'C');
  $pdf->Cell(16, $lh, "= $total_sks_kiri", 1, 0,'');

  $pdf->Cell(4, $lh, ' ', 0, 0,'C'); // SPACER VERTICAL

  $pdf->Cell(72, $lh, "TOTAL SKS", 1, 0,'C');
  $pdf->Cell(5, $lh, $total_teori_kanan, 1, 0,'C');
  $pdf->Cell(5, $lh, $total_praktik_kanan, 1, 0,'C');
  $pdf->Cell(16, $lh, "= $total_sks_kanan", 1, 1,'');


  // 15
  $pdf->Cell(200, $lhhz, " ", 0, 1); // spacer hz

}










# ==========================================================
# MK PILIHAN
# ==========================================================
$debug_cb = 0;
// 52 mk pilihan | Total MK
$pdf->SetFont('Arial', 'b', 9);
$pdf->Cell(102, $lhs, "MK PILIHAN", $debug_cb, 0);

$pdf->SetFont('Arial', '', 8);
$pdf->Cell(22, $lhs, "TOTAL MK", $debug_cb, 0);
$pdf->Cell(40, $lhs, "$total_mk MK", $debug_cb, 0);

// QR CODE
// $pdf->Image("qr.png", x, y, w, h, );
// $pdf->Image("qr.png", null, null, 30);
$pdf->Cell(31, $lhs, "QR CODE", 0, 1);
// $pdf->Cell(31, $lhs, $pdf->Image("qr.png", null, null, 30), 0, 0);
// $pdf->Cell(6, $lh, 'NO', 1, 1,'C');


// 53 table header | Total Teori
$pdf->SetFont('Arial', 'b', 8);

$pdf->Cell(6, $lh, 'NO', 1, 0,'C');
$pdf->Cell(16, $lh, 'KODE', 1, 0,'C');
$pdf->Cell(50, $lh, 'MATA KULIAH', 1, 0,'C');
$pdf->Cell(5, $lh, 'T', 1, 0,'C');
$pdf->Cell(5, $lh, 'P', 1, 0,'C');
$pdf->Cell(16, $lh, 'SYARAT', 1, 0,'C');

$pdf->Cell(4, $lh, ' ', $debug_cb, 0,'C'); // SPACER VERTIVAL

$pdf->SetFont('Arial', '', 8);
$pdf->Cell(22, $lh, "TEORI", $debug_cb, 0);
$pdf->Cell(40, $lh, "$total_teori SKS ($persen_teori%)", $debug_cb, 1);


// 54 MK Pilihan 1 | Total Praktik
$pdf->SetFont('Arial', '', 8);

$mp = 0;
if(isset($mk_pilihan[$mp])){
  $pdf->Cell(6, $lh, $mp+1, 1, 0,'C');
  $pdf->Cell(16, $lh, $mk_pilihan[$mp][0], 1, 0,'');
  $pdf->Cell(50, $lh, $mk_pilihan[$mp][1], 1, 0,'');
  $pdf->Cell(5, $lh, $mk_pilihan[$mp][2], 1, 0,'C');
  $pdf->Cell(5, $lh, $mk_pilihan[$mp][3], 1, 0,'C');
  $pdf->Cell(16, $lh, $mk_pilihan[$mp][4], 1, 0,'');
}else{
  $pdf->Cell(98, $lh, '(BELUM ADA MK PILIHAN)', 1, 0,'c');
}

$pdf->Cell(4, $lh, ' ', $debug_cb, 0,'C'); // SPACER VERTIVAL

$pdf->SetFont('Arial', '', 8);
$pdf->Cell(22, $lh, "PRAKTIK", $debug_cb, 0);
$pdf->Cell(40, $lh, "$total_praktik SKS ($persen_praktik%)", $debug_cb, 1);


// 55 MK Pilihan 2 | Total SKS [BOLD]
$pdf->SetFont('Arial', '', 8);

$mp++;
if(isset($mk_pilihan[$mp])){
  $pdf->Cell(6, $lh, $mp+1, 1, 0,'C');
  $pdf->Cell(16, $lh, $mk_pilihan[$mp][0], 1, 0,'');
  $pdf->Cell(50, $lh, $mk_pilihan[$mp][1], 1, 0,'');
  $pdf->Cell(5, $lh, $mk_pilihan[$mp][2], 1, 0,'C');
  $pdf->Cell(5, $lh, $mk_pilihan[$mp][3], 1, 0,'C');
  $pdf->Cell(16, $lh, $mk_pilihan[$mp][4], 1, 0,'');
}else{
  $pdf->Cell(98, $lh, ' ', 0, 0,'');
}

$pdf->Cell(4, $lh, ' ', $debug_cb, 0,'C'); // SPACER VERTIVAL

$pdf->SetFont('Arial', 'b', 8);
$pdf->Cell(22, $lh, "TOTAL SKS", $debug_cb, 0);
$pdf->Cell(40, $lh, "$total_sks SKS", $debug_cb, 1);


// 56 MK Pilihan 3
$pdf->SetFont('Arial', '', 8);

$mp++;
if(isset($mk_pilihan[$mp])){
  $pdf->Cell(6, $lh, $mp+1, 1, 0,'C');
  $pdf->Cell(16, $lh, $mk_pilihan[$mp][0], 1, 0,'');
  $pdf->Cell(50, $lh, $mk_pilihan[$mp][1], 1, 0,'');
  $pdf->Cell(5, $lh, $mk_pilihan[$mp][2], 1, 0,'C');
  $pdf->Cell(5, $lh, $mk_pilihan[$mp][3], 1, 0,'C');
  $pdf->Cell(16, $lh, $mk_pilihan[$mp][4], 1, 0,'');
}else{
  $pdf->Cell(98, $lh, ' ', 0, 0,'');
}

$pdf->Cell(4, $lh, ' ', $debug_cb, 0,'C'); // SPACER VERTIVAL

$pdf->Cell(98, $lh, ' ', 0, 1,'');

// $pdf->SetFont('Arial', 'b', 8);
// $pdf->Cell(22, $lh, "TOTAL SKS", $debug_cb, 0);
// $pdf->Cell(45, $lh, "157 SKS", $debug_cb, 1);


// 57 MK Pilihan 4 | APPROVED BY [MIRING]
$pdf->SetFont('Arial', '', 8);

$mp++;
if(isset($mk_pilihan[$mp])){
  $pdf->Cell(6, $lh, $mp+1, 1, 0,'C');
  $pdf->Cell(16, $lh, $mk_pilihan[$mp][0], 1, 0,'');
  $pdf->Cell(50, $lh, $mk_pilihan[$mp][1], 1, 0,'');
  $pdf->Cell(5, $lh, $mk_pilihan[$mp][2], 1, 0,'C');
  $pdf->Cell(5, $lh, $mk_pilihan[$mp][3], 1, 0,'C');
  $pdf->Cell(16, $lh, $mk_pilihan[$mp][4], 1, 0,'');
}else{
  $pdf->Cell(98, $lh, ' ', 0, 0,'');
}

$pdf->Cell(4, $lh, ' ', $debug_cb, 0,'C'); // SPACER VERTIVAL

$pdf->SetFont('Arial', '', 8);
$pdf->Cell(62, $lh, "DISAHKAN OLEH:", $debug_cb, 1);


// 58 MK Pilihan 5 | NAMA KAPRODI [BOLD]
$pdf->SetFont('Arial', '', 8);

$mp++;
if(isset($mk_pilihan[$mp])){
  $pdf->Cell(6, $lh, $mp+1, 1, 0,'C');
  $pdf->Cell(16, $lh, $mk_pilihan[$mp][0], 1, 0,'');
  $pdf->Cell(50, $lh, $mk_pilihan[$mp][1], 1, 0,'');
  $pdf->Cell(5, $lh, $mk_pilihan[$mp][2], 1, 0,'C');
  $pdf->Cell(5, $lh, $mk_pilihan[$mp][3], 1, 0,'C');
  $pdf->Cell(16, $lh, $mk_pilihan[$mp][4], 1, 0,'');
}else{
  $pdf->Cell(98, $lh, ' ', 0, 0,'');
}

$pdf->Cell(4, $lh, ' ', $debug_cb, 0,'C'); // SPACER VERTIVAL

$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(62, $lh, ($disahkan_oleh==''?'(BELUM DISAHKAN)':$disahkan_oleh), $debug_cb, 1);


// 59 MK Pilihan 6 | KAPRODI $PRODI [BOLD]
$pdf->SetFont('Arial', '', 8);

$mp++;
if(isset($mk_pilihan[$mp])){
  $pdf->Cell(6, $lh, $mp+1, 1, 0,'C');
  $pdf->Cell(16, $lh, $mk_pilihan[$mp][0], 1, 0,'');
  $pdf->Cell(50, $lh, $mk_pilihan[$mp][1], 1, 0,'');
  $pdf->Cell(5, $lh, $mk_pilihan[$mp][2], 1, 0,'C');
  $pdf->Cell(5, $lh, $mk_pilihan[$mp][3], 1, 0,'C');
  $pdf->Cell(16, $lh, $mk_pilihan[$mp][4], 1, 0,'');
}else{
  $pdf->Cell(98, $lh, ' ', 0, 0,'');
}

$pdf->Cell(4, $lh, ' ', $debug_cb, 0,'C'); // SPACER VERTIVAL

$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(62, $lh, "KAPRODI $nama_prodi", $debug_cb, 1);


// 60 MK Pilihan 7 | NIDN
$pdf->SetFont('Arial', '', 8);

$mp++;
if(isset($mk_pilihan[$mp])){
  $pdf->Cell(6, $lh, $mp+1, 1, 0,'C');
  $pdf->Cell(16, $lh, $mk_pilihan[$mp][0], 1, 0,'');
  $pdf->Cell(50, $lh, $mk_pilihan[$mp][1], 1, 0,'');
  $pdf->Cell(5, $lh, $mk_pilihan[$mp][2], 1, 0,'C');
  $pdf->Cell(5, $lh, $mk_pilihan[$mp][3], 1, 0,'C');
  $pdf->Cell(16, $lh, $mk_pilihan[$mp][4], 1, 0,'');
}else{
  $pdf->Cell(98, $lh, ' ', 0, 0,'');
}

$pdf->Cell(4, $lh, ' ', $debug_cb, 0,'C'); // SPACER VERTIVAL

$pdf->SetFont('Arial', 'b', 8);
$pdf->Cell(62, $lh, "NIDN. $nidn_kaprodi", $debug_cb, 1);


// 61 MK Pilihan 8 | DATE
$pdf->SetFont('Arial', '', 8);

$mp++;
if(isset($mk_pilihan[$mp])){
  $pdf->Cell(6, $lh, $mp+1, 1, 0,'C');
  $pdf->Cell(16, $lh, $mk_pilihan[$mp][0], 1, 0,'');
  $pdf->Cell(50, $lh, $mk_pilihan[$mp][1], 1, 0,'');
  $pdf->Cell(5, $lh, $mk_pilihan[$mp][2], 1, 0,'C');
  $pdf->Cell(5, $lh, $mk_pilihan[$mp][3], 1, 0,'C');
  $pdf->Cell(16, $lh, $mk_pilihan[$mp][4], 1, 0,'');
}else{
  $pdf->Cell(98, $lh, ' ', 0, 0,'');
}

$pdf->Cell(4, $lh, ' ', $debug_cb, 0,'C'); // SPACER VERTIVAL

$pdf->SetFont('Arial', '', 8);
$pdf->Cell(62, $lh, "DICETAK PADA: ".date('F d, Y, H:i:s'), $debug_cb, 1);



// 62 MK Pilihan 9 | ACADEMIC SYSTEM
$pdf->SetFont('Arial', '', 8);

$mp++;
if(isset($mk_pilihan[$mp])){
  $pdf->Cell(6, $lh, $mp+1, 1, 0,'C');
  $pdf->Cell(16, $lh, $mk_pilihan[$mp][0], 1, 0,'');
  $pdf->Cell(50, $lh, $mk_pilihan[$mp][1], 1, 0,'');
  $pdf->Cell(5, $lh, $mk_pilihan[$mp][2], 1, 0,'C');
  $pdf->Cell(5, $lh, $mk_pilihan[$mp][3], 1, 0,'C');
  $pdf->Cell(16, $lh, $mk_pilihan[$mp][4], 1, 0,'');
}else{
  $pdf->Cell(98, $lh, ' ', 0, 0,'');
}

$pdf->Cell(4, $lh, ' ', $debug_cb, 0,'C'); // SPACER VERTIVAL

$pdf->SetFont('Arial', '', 8);
$pdf->Cell(98, $lh, "SISTEM AKADEMIK STMIK IKMI CIREBON", $debug_cb, 1);



$pdf->Output('D', "kurikulum.pdf");
ob_end_flush();
?>