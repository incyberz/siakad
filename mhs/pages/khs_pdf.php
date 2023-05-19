<?php
ob_start();
$dm=0;
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
$post_nim = isset($_POST['nim']) ? $_POST['nim'] : die(div_alert('danger','Page ini tidak bisa diakses secara langsung. Silahkan menuju <a href="?khs">Menu KHS</a>'));
$dmks = isset($_POST['dmks']) ? $_POST['dmks'] : die(div_alert('danger','Belum ada Data KHS. Silahkan menuju <a href="?khs">Menu KHS</a>'));
$prodi = 'TEKNIK INFORMATIKA (S1)'; //ZZZ
$nama_mhs = 'AHMAD FIRDASU'; //ZZZ
$nim = '41414114'; //ZZZ
$jalur = 'KIP KULIAH'; //ZZZ

// Nama :   Ahmad Firdaus   Program :   KIP
// 33	      67	            33	        67
# ===========================================
# HEADER IN PAGE
# ===========================================
$pdf->SetFont('Arial','B',10); // set-font
$pdf->Cell(0,5,'KARTU HASIL STUDI (KHS)',0,1,'C');
$pdf->Cell(0,5,'PROGRAM STUDI '.$prodi,0,1,'C');
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
$pdf->Cell(33, $lh, 'JALUR', $dm, 0);
$pdf->Cell(167, $lh, ": $jalur", $dm, 1);

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
$rd = explode('||',$dmks);
$ipk = $rd[1];
$d_smts = $rd[0];
// echo "<h1>IPK : $ipk</h1>";

# ====================================================
# SPLIT SETIAP SEMESTER
# ====================================================
$rd = explode('<hr>',$d_smts);
$jumlah_smt = count($rd)-1;
for ($i=0; $i < $jumlah_smt ; $i++) { 

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
    $pdf->Cell($ksip[2], $lh, $ip, 1, 0, 'C');
    $pdf->Cell($ksip[3], $lh, 'Total SKS', 1, 0, 'R');
    $pdf->Cell($ksip[4], $lh, $total_sks, 1, 1, 'C');

    $pdf->Cell($ksip[0], $lh, ' ', 0, 0, '');
    $pdf->Cell($ksip[1], $lh, 'Indeks Prestasi Kumulatif Semester (IPKS)', 1, 0, 'R');
    $pdf->Cell($ksip[2], $lh, $ipks, 1, 0, 'C');
    $pdf->Cell($ksip[3], $lh, 'Total NM', 1, 0, 'R');
    $pdf->Cell($ksip[4], $lh, $total_nm, 1, 1, 'C');

  }else{
    $pdf->SetFillColor(255,225,225);
    $pdf->SetTextColor(255,0,0);
    $pdf->SetFont('Arial','I',8); // set-font
    $pdf->Cell(200, $lh, '-- NO DATA --', 1, 1,'C',1);
  }

}


/*
// 4 SEMESTER
$semester_ke=0;
for ($i=1; $i <=4 ; $i++) { 
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
*/


$pdf->Output('D', "KHS.pdf");
ob_end_flush();
?>