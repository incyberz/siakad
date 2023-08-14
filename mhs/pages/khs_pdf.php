<?php
ob_start();
$dm=1;
require('../../pdf/fpdf/fpdf.php');

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
$lhs = 5.2; //line height SEMESTER XX
$lhhz = 3; //line height SPACER HZ






# ====================================================
# PROCESSING POST DATA
# ====================================================
$post_nim = isset($_POST['nim']) ? $_POST['nim'] : die("Index nim undefined.");
$dmks = isset($_POST['dmks']) ? $_POST['dmks'] : die("Index dmks undefined.");
$nama_mhs = isset($_POST['nama_mhs']) ? $_POST['nama_mhs'] : die("Index nama_mhs undefined.");
$nim = isset($_POST['nim']) ? $_POST['nim'] : die("Index nim undefined.");
$angkatan = isset($_POST['angkatan']) ? $_POST['angkatan'] : die("Index angkatan undefined.");
$jalur = isset($_POST['jalur']) ? $_POST['jalur'] : '';

$semester = 'all';
for ($i=1; $i < 8; $i++) { 
  if(isset($_POST['dw_'.$i])) $semester = $i;
}

$rprodi = [
  41=>'S1 - TEKNIK INFORMATIKA',
  42=>'S1 - REKAYASA PERANGKAT LUNAK',
  43=>'S1 - SISTEM INFORMASI',
  31=>'D3 - MANAJEMEN INFORMATIKA',
  32=>'D3 - KOMPUTERISASI AKUNTANSI',
];

$kode_prodi = substr($nim,0,2);
$prodi = $rprodi[$kode_prodi];

# ===========================================
# KOP SURAT
# ===========================================
// $pdf->Image("qr.png", x, y, w, h, );
$pdf->Image("../../assets/img/kop_surat.jpg", 5, 5, 200, );
$pdf->Cell(200, 30, " ", 0, 1); // spacer kop surat


// Nama :   Ahmad Firdaus   Program :   KIP
// 33	      67	            33	        67
# ===========================================
# HEADER IN PAGE
# ===========================================
$pdf->SetFont('Arial','B',10); // set-font
$pdf->Cell(0,5,'KARTU HASIL STUDI (KHS)',0,1,'C');
$pdf->Cell(0,5,'PROGRAM STUDI '.$prodi,0,1,'C');
if($semester!='all'){
  $pdf->Cell(0,5,'SEMESTER '.$semester,0,1,'C');
}
$pdf->Cell(200, 5, " ", 0, 1); // spacer


$pdf->SetFont('Arial','',9); // set-font
// $pdf->Cell(33, $lh, "Nama", $dm, 0);
// $pdf->Cell(67, $lh, "Ahmad Firdaus", $dm, 0);
// $pdf->Cell(33, $lh, "Program", $dm, 0);
// $pdf->Cell(67, $lh, "KIP", $dm, 1);

$pdf->Cell(33, $lh, 'NAMA', $dm, 0);
$pdf->SetFont('Arial','B',9); // set-font
$pdf->Cell(167, $lh, ": $nama_mhs", $dm, 1);
$pdf->SetFont('Arial','',9); // set-font
$pdf->Cell(33, $lh, 'NIM', $dm, 0);
$pdf->Cell(167, $lh, ": $nim", $dm, 1);
if($jalur!=''){
  $pdf->Cell(33, $lh, 'JALUR', $dm, 0);
  $pdf->Cell(167, $lh, ": $jalur", $dm, 1);
}

$pdf->Cell(200, 5, " ", 0, 1); // spacer


// NO KODE  MATA KULIAH   SKS HM  AM  NM
// 8	25	  115	          13	13	13	13
$ks = [8,25,115,13,13,13,13];
# ===========================================
# TABLE HEADER 
# ===========================================
$pdf->SetFillColor(80,80,80);
$pdf->SetTextColor(255,255,255);
$pdf->Cell($ks[0], $lhs, 'NO', 1, 0,'C',1);
$pdf->Cell($ks[1], $lhs, 'KODE', 1, 0,'C',1);
$pdf->Cell($ks[2], $lhs, 'MATA KULIAH', 1, 0,'C',1);
$pdf->Cell($ks[3], $lhs, 'SKS', 1, 0,'C',1);
$pdf->Cell($ks[4], $lhs, 'HM', 1, 0,'C',1);
$pdf->Cell($ks[5], $lhs, 'AM', 1, 0,'C',1);
$pdf->Cell($ks[6], $lhs, 'NM', 1, 1,'C',1);


# ====================================================
# SPLIT IPK
# ====================================================
// echo '<pre>';
// echo var_dump($dmks);
// echo '</pre>';
// exit;

$rd = explode('||',$dmks);
$ipk = $rd[1];
$d_smts = $rd[0];

// echo "<h1>IPK : $ipk</h1>";

# ====================================================
# SPLIT SETIAP SEMESTER
# ====================================================
$rd = explode('<hr>',$d_smts);
$jumlah_smt = count($rd)-1;
$loop_awal = $semester=='all'? 0 : $semester-1;
$loop_ahir = $semester=='all'? $jumlah_smt : $semester;
// $loop_ahir = $jumlah_smt;
for ($i=$loop_awal; $i < $loop_ahir ; $i++) { 

  $no_smt = $i+1;
  $pdf->Cell(200, 2, " ", 0, 1); // spacer
  $pdf->SetFillColor(217,241,245);
  $pdf->SetTextColor(0,0,200);
  $pdf->SetFont('Arial','I',9); // set-font
  $pdf->Cell(200, $lhs, 'SEMESTER '.$no_smt, 1, 1,'C',1);
  $pdf->SetFillColor(255,255,255);
  $pdf->SetTextColor(0,0,0);


  # ====================================================
  # SPLIT IP TIAP SEMESTER
  # ====================================================
  $rdd = explode('|',$rd[$i]);
  // echo "<h1>rd[$i]: $rd[$i]</h1>";
  $ip = isset($rdd[1]) ? $rdd[1] : 0;
  $ipks = isset($rdd[2]) ? $rdd[2] : 0;
  $total_sks = isset($rdd[3]) ? $rdd[3] : 0;
  $total_nm = isset($rdd[4]) ? $rdd[4] : 0;

  if($ip){
    // echo '<table class=table>'; 
    $rows = explode('<br>',$rdd[0]);
    for ($j=0; $j < count($rows); $j++) {
      // echo '<tr>'; 
      $td = explode(';',$rows[$j]);
      for ($k=0; $k < count($td)-1; $k++) { 
        // echo "<td>$td[$k]</td>";
        $is_break = $k>=(count($td)-2) ? 1 : 0;
        $is_center = $k==2 ? '' : 'C';
        $pdf->SetFont('Arial','',8); // set-font
        $pdf->Cell($ks[$k], $lh, $td[$k], 1, $is_break, $is_center);

      }
      // echo '</tr>'; 
    }
    // echo '</table>'; 


    // []   IPKS     3.4 TOTAL SKS   54
    $ksip = [$ks[0]+$ks[1],$ks[2],$ks[3],$ks[3]*2,$ks[3]];

    $pdf->SetFont('Arial','B',8); // set-font
    $pdf->Cell(200, 1, " ", 0, 1); // spacer
    $pdf->Cell($ksip[0], $lh, ' ', 0, 0, '');
    $pdf->Cell($ksip[1], $lh, 'Indeks Prestasi (IP)', 1, 0, 'R');
    $pdf->Cell($ksip[2], $lh, number_format($ip,2), 1, 0, 'C');
    $pdf->Cell($ksip[3], $lh, 'Total SKS', 1, 0, 'R');
    $pdf->Cell($ksip[4], $lh, $total_sks, 1, 1, 'C');

    $pdf->Cell($ksip[0], $lh, ' ', 0, 0, '');
    $pdf->Cell($ksip[1], $lh, 'Indeks Prestasi Kumulatif Semester (IPKS)', 1, 0, 'R');
    $pdf->Cell($ksip[2], $lh, number_format($ipks,2), 1, 0, 'C');
    $pdf->Cell($ksip[3], $lh, 'Total NM', 1, 0, 'R');
    $pdf->Cell($ksip[4], $lh, $total_nm, 1, 1, 'C');

  }else{
    $pdf->SetFillColor(255,225,225);
    $pdf->SetTextColor(255,0,0);
    $pdf->SetFont('Arial','I',8); // set-font
    $pdf->Cell(200, $lh, '-- NO DATA --', 1, 1,'C',1);
  }

}


# ===========================================
# QR CODE
# ===========================================
$pdf->Cell(200, $lh, ' ', 0, 1,'',0); //spacer
$print_from = 'Printed From: Academic Information System of STMIK IKMI Cirebon ';
$titimangsa = 'Cirebon, '.date('Y-m-d H:i:s').' WIB';
$pdf->Cell(200, $lh, $print_from, 0, 1,'',0);
$pdf->Cell(200, $lh, $titimangsa, 0, 1,'',0);
$pdf->Cell(200, $lh, ucwords(strtolower($prodi)), 0, 1,'',0);
$qr_nim = substr($nim,0,2).substr($angkatan,2,2);
// $pdf->Image("qr.png", x, y, w, h, );
$pdf->Image("../../assets/img/qr_prodi/khs_$qr_nim.png", null, null, 30, );



$pdf->Output('D', "KHS.pdf");
ob_end_flush();
?>