<?php
session_start();

$mk_s1 = [];
$mk_s2 = [];
$mk_s3 = [];
$mk_s4 = [];
$mk_s5 = [];
$mk_s6 = [];
$mk_s7 = [];
$mk_s8 = [];

array_push($mk_s1,['MK001','NAMA MATA KULIAH',3,0,'-']);
array_push($mk_s1,['MK002','NAMA MATA KULIAH',3,0,'-']);
array_push($mk_s1,['MK003','NAMA MATA KULIAH',3,0,'-']);
array_push($mk_s1,['MK004','NAMA MATA KULIAH',3,0,'-']);
array_push($mk_s1,['MK005','NAMA MATA KULIAH',3,0,'-']);
array_push($mk_s1,['MK006','NAMA MATA KULIAH',3,0,'-']);
array_push($mk_s1,['MK007','NAMA MATA KULIAH',3,0,'-']);
array_push($mk_s1,['MK008','NAMA MATA KULIAH',3,0,'-']);

array_push($mk_s2,['MK001','NAMA MATA KULIAH',3,0,'-']);
array_push($mk_s2,['MK002','NAMA MATA KULIAH',3,0,'-']);
array_push($mk_s2,['MK003','NAMA MATA KULIAH',3,0,'-']);
array_push($mk_s2,['MK004','NAMA MATA KULIAH',3,0,'-']);
array_push($mk_s2,['MK005','NAMA MATA KULIAH',3,0,'-']);

array_push($mk_s3,['MK001','NAMA MATA KULIAH',3,0,'-']);
array_push($mk_s3,['MK002','NAMA MATA KULIAH',3,0,'-']);
array_push($mk_s3,['MK003','NAMA MATA KULIAH',3,0,'-']);
array_push($mk_s3,['MK004','NAMA MATA KULIAH',3,0,'-']);

array_push($mk_s4,['MK001','NAMA MATA KULIAH',3,0,'-']);
array_push($mk_s4,['MK002','NAMA MATA KULIAH',3,0,'-']);
array_push($mk_s4,['MK003','NAMA MATA KULIAH',3,0,'-']);
array_push($mk_s4,['MK004','NAMA MATA KULIAH',3,0,'-']);
array_push($mk_s4,['MK005','NAMA MATA KULIAH',3,0,'-']);

array_push($mk_s5,['MK001','NAMA MATA KULIAH',3,0,'-']);
array_push($mk_s5,['MK002','NAMA MATA KULIAH',3,0,'-']);
array_push($mk_s5,['MK003','NAMA MATA KULIAH',3,0,'-']);
array_push($mk_s5,['MK004','NAMA MATA KULIAH',3,0,'-']);
array_push($mk_s5,['MK005','NAMA MATA KULIAH',3,0,'-']);
array_push($mk_s5,['MK006','NAMA MATA KULIAH',3,0,'-']);
array_push($mk_s5,['MK007','NAMA MATA KULIAH',3,0,'-']);
array_push($mk_s5,['MK008','NAMA MATA KULIAH',3,0,'-']);

array_push($mk_s6,['MK001','NAMA MATA KULIAH',3,0,'-']);
array_push($mk_s6,['MK002','NAMA MATA KULIAH',3,0,'-']);
array_push($mk_s6,['MK003','NAMA MATA KULIAH',3,0,'-']);
array_push($mk_s6,['MK004','NAMA MATA KULIAH',3,0,'-']);
array_push($mk_s6,['MK005','NAMA MATA KULIAH',3,0,'-']);

array_push($mk_s7,['MK001','NAMA MATA KULIAH',3,0,'-']);
array_push($mk_s7,['MK002','NAMA MATA KULIAH',3,0,'-']);
array_push($mk_s7,['MK003','NAMA MATA KULIAH',3,0,'-']);
array_push($mk_s7,['MK004','NAMA MATA KULIAH',3,0,'-']);
array_push($mk_s7,['MK005','NAMA MATA KULIAH',3,0,'-']);
array_push($mk_s7,['MK006','NAMA MATA KULIAH',3,0,'-']);
array_push($mk_s7,['MK007','NAMA MATA KULIAH',3,0,'-']);
array_push($mk_s7,['MK008','NAMA MATA KULIAH',3,0,'-']);

array_push($mk_s8,['MK001','NAMA MATA KULIAH',3,0,'-']);
array_push($mk_s8,['MK002','NAMA MATA KULIAH',3,0,'-']);
array_push($mk_s8,['MK003','NAMA MATA KULIAH',3,0,'-']);
array_push($mk_s8,['MK004','NAMA MATA KULIAH',3,0,'-']);
array_push($mk_s8,['MK005','NAMA MATA KULIAH',3,0,'-']);

$r_total_sks = [20,22,24,19,20,22,24,19];

ob_start();
require('fpdf/fpdf.php');

class PDF extends FPDF
{
    public function Header()
    {
        // $this->Image('kop.png', 10, 6, 190);
        // $this->Ln(20);

        $this->SetFont('Arial','B',10);
        $this->Cell(0,5,'KURIKULUM S1-DIGITAL BISNIS ANGKATAN 2020',0,1,'C');
        $this->Cell(0,5,'BASIS OBE DAN MBKM',0,1,'C');
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
# START
# ===========================================
// 3
$pdf->Cell(200, $lhhz, " ", 0, 1); // spacer hz
// 4 SEMESTER
$semester_ke=0;
for ($i=1; $i <=4 ; $i++) { 
  $total_sks_kiri = $r_total_sks[$semester_ke];
  $semester_ke++;
  $semester_ke_kiri = $semester_ke;
  
  $total_sks_kanan = $r_total_sks[$semester_ke];
  $semester_ke++;
  $semester_ke_kanan = $semester_ke;

  $pdf->SetFont('Arial', 'b', 9);
  $pdf->Cell(102, $lhs, "SEMESTER $semester_ke_kiri", 0, 0);
  $pdf->Cell(97, $lhs, "SEMESTER $semester_ke_kanan", 0, 1);
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
  $pdf->Cell(5, $lh, "20", 1, 0,'C');
  $pdf->Cell(5, $lh, "4", 1, 0,'C');
  $pdf->Cell(16, $lh, "= $total_sks_kiri", 1, 0,'');

  $pdf->Cell(4, $lh, ' ', 0, 0,'C'); // SPACER VERTICAL

  $pdf->Cell(72, $lh, "TOTAL SKS", 1, 0,'C');
  $pdf->Cell(5, $lh, "20", 1, 0,'C');
  $pdf->Cell(5, $lh, "4", 1, 0,'C');
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
$pdf->Cell(40, $lhs, "51 MK", $debug_cb, 0);

// QR CODE
$pdf->Cell(31, $lhs, "QR CODE", 0, 1);


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
$pdf->Cell(40, $lh, "132 SKS (85%)", $debug_cb, 1);


// 54 MK Pilihan 1 | Total Praktik
$pdf->SetFont('Arial', '', 8);

$pdf->Cell(6, $lh, '1', 1, 0,'C');
$pdf->Cell(16, $lh, 'MKP001', 1, 0,'');
$pdf->Cell(50, $lh, 'MATA KULIAH PILIHAN', 1, 0,'');
$pdf->Cell(5, $lh, '3', 1, 0,'C');
$pdf->Cell(5, $lh, '0', 1, 0,'C');
$pdf->Cell(16, $lh, '', 1, 0,'');

$pdf->Cell(4, $lh, ' ', $debug_cb, 0,'C'); // SPACER VERTIVAL

$pdf->SetFont('Arial', '', 8);
$pdf->Cell(22, $lh, "PRAKTIK", $debug_cb, 0);
$pdf->Cell(40, $lh, "34 SKS (15%)", $debug_cb, 1);


// 55 MK Pilihan 2 | Total SKS [BOLD]
$pdf->SetFont('Arial', '', 8);

$pdf->Cell(6, $lh, '2', 1, 0,'C');
$pdf->Cell(16, $lh, 'MKP002', 1, 0,'');
$pdf->Cell(50, $lh, 'MATA KULIAH PILIHAN', 1, 0,'');
$pdf->Cell(5, $lh, '3', 1, 0,'C');
$pdf->Cell(5, $lh, '0', 1, 0,'C');
$pdf->Cell(16, $lh, '', 1, 0,'');

$pdf->Cell(4, $lh, ' ', $debug_cb, 0,'C'); // SPACER VERTIVAL

$pdf->SetFont('Arial', 'b', 8);
$pdf->Cell(22, $lh, "TOTAL SKS", $debug_cb, 0);
$pdf->Cell(40, $lh, "157 SKS", $debug_cb, 1);


// 56 MK Pilihan 3
$pdf->SetFont('Arial', '', 8);

$pdf->Cell(6, $lh, '3', 1, 0,'C');
$pdf->Cell(16, $lh, 'MKP003', 1, 0,'');
$pdf->Cell(50, $lh, 'MATA KULIAH PILIHAN', 1, 0,'');
$pdf->Cell(5, $lh, '3', 1, 0,'C');
$pdf->Cell(5, $lh, '0', 1, 0,'C');
$pdf->Cell(16, $lh, '', 1, 1,'');

// $pdf->Cell(4, $lh, ' ', $debug_cb, 0,'C'); // SPACER VERTIVAL

// $pdf->SetFont('Arial', 'b', 8);
// $pdf->Cell(22, $lh, "TOTAL SKS", $debug_cb, 0);
// $pdf->Cell(45, $lh, "157 SKS", $debug_cb, 1);


// 57 MK Pilihan 4 | APPROVED BY [MIRING]
$pdf->SetFont('Arial', '', 8);

$pdf->Cell(6, $lh, '4', 1, 0,'C');
$pdf->Cell(16, $lh, 'MKP004', 1, 0,'');
$pdf->Cell(50, $lh, 'MATA KULIAH PILIHAN', 1, 0,'');
$pdf->Cell(5, $lh, '3', 1, 0,'C');
$pdf->Cell(5, $lh, '0', 1, 0,'C');
$pdf->Cell(16, $lh, '', 1, 0,'');

$pdf->Cell(4, $lh, ' ', $debug_cb, 0,'C'); // SPACER VERTIVAL

$pdf->SetFont('Arial', '', 8);
$pdf->Cell(62, $lh, "DISAHKAN OLEH:", $debug_cb, 1);


// 58 MK Pilihan 5 | NAMA KAPRODI [BOLD]
$pdf->SetFont('Arial', '', 8);

$pdf->Cell(6, $lh, '5', 1, 0,'C');
$pdf->Cell(16, $lh, 'MKP005', 1, 0,'');
$pdf->Cell(50, $lh, 'MATA KULIAH PILIHAN', 1, 0,'');
$pdf->Cell(5, $lh, '3', 1, 0,'C');
$pdf->Cell(5, $lh, '0', 1, 0,'C');
$pdf->Cell(16, $lh, '', 1, 0,'');

$pdf->Cell(4, $lh, ' ', $debug_cb, 0,'C'); // SPACER VERTIVAL

$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(62, $lh, "PROF. DR. KAPRODI SALAWASNA", $debug_cb, 1);


// 59 MK Pilihan 6 | KAPRODI $PRODI [BOLD]
$pdf->SetFont('Arial', '', 8);

$pdf->Cell(6, $lh, '6', 1, 0,'C');
$pdf->Cell(16, $lh, 'MKP006', 1, 0,'');
$pdf->Cell(50, $lh, 'MATA KULIAH PILIHAN', 1, 0,'');
$pdf->Cell(5, $lh, '2', 1, 0,'C');
$pdf->Cell(5, $lh, '1', 1, 0,'C');
$pdf->Cell(16, $lh, '', 1, 0,'');

$pdf->Cell(4, $lh, ' ', $debug_cb, 0,'C'); // SPACER VERTIVAL

$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(62, $lh, "KAPRODI BISNIS DIGITAL", $debug_cb, 1);


// 60 MK Pilihan 7 | NIDN
$pdf->SetFont('Arial', '', 8);

$pdf->Cell(6, $lh, '7', 1, 0,'C');
$pdf->Cell(16, $lh, 'MKP007', 1, 0,'');
$pdf->Cell(50, $lh, 'MATA KULIAH PILIHAN', 1, 0,'');
$pdf->Cell(5, $lh, '3', 1, 0,'C');
$pdf->Cell(5, $lh, '0', 1, 0,'C');
$pdf->Cell(16, $lh, '', 1, 0,'');

$pdf->Cell(4, $lh, ' ', $debug_cb, 0,'C'); // SPACER VERTIVAL

$pdf->SetFont('Arial', 'b', 8);
$pdf->Cell(62, $lh, "NIDN. 0411068706", $debug_cb, 1);


// 61 MK Pilihan 8 | DATE
$pdf->SetFont('Arial', '', 8);

$pdf->Cell(6, $lh, '8', 1, 0,'C');
$pdf->Cell(16, $lh, 'MKP008', 1, 0,'');
$pdf->Cell(50, $lh, 'MATA KULIAH PILIHAN', 1, 0,'');
$pdf->Cell(5, $lh, '3', 1, 0,'C');
$pdf->Cell(5, $lh, '0', 1, 0,'C');
$pdf->Cell(16, $lh, '', 1, 0,'');

$pdf->Cell(4, $lh, ' ', $debug_cb, 0,'C'); // SPACER VERTIVAL

$pdf->SetFont('Arial', '', 8);
$pdf->Cell(62, $lh, "PADA: 2 JUNI 1987 12:32:54", $debug_cb, 1);



// 62 MK Pilihan 9 | ACADEMIC SYSTEM
$pdf->SetFont('Arial', '', 8);

$pdf->Cell(6, $lh, '9', 1, 0,'C');
$pdf->Cell(16, $lh, 'MKP009', 1, 0,'');
$pdf->Cell(50, $lh, 'MATA KULIAH PILIHAN', 1, 0,'');
$pdf->Cell(5, $lh, '3', 1, 0,'C');
$pdf->Cell(5, $lh, '0', 1, 0,'C');
$pdf->Cell(16, $lh, '', 1, 0,'');

$pdf->Cell(4, $lh, ' ', $debug_cb, 0,'C'); // SPACER VERTIVAL

$pdf->SetFont('Arial', '', 8);
$pdf->Cell(98, $lh, "SISTEM AKADEMIK STMIK IKMI CIREBON", $debug_cb, 1);



$pdf->Output('D', "kurikulum.pdf");
ob_end_flush();
?>