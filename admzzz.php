<?php
session_start();
$_SESSION['siakad_username'] = 'fatur';
echo "<script>location.replace('akademik')</script>";