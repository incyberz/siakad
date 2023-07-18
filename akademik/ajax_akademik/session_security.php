<?php
session_start();
$username = $_SESSION['siakad_username'] ?? die('Anda sudah Auto Logout. Silahkan login kembali!');