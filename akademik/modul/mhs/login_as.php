<?php
$nim = isset($_GET['nim']) ? $_GET['nim'] : die(erid('nim'));
$_SESSION['siakad_mhs'] = $nim;

echo div_alert('success','Set SESSION sukses. <a href="../mhs/">silahkan menuju Laman Mahasiswa</a>');
exit;