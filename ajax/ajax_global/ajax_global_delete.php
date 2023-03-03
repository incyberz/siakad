<?php 
session_start();
# ================================================
# SESSION SECURITY
# ================================================
include "ajax_session_security.php";

# ================================================
# GET VARIABLES
# ================================================
// include "ajax_global_getting_variables.php";
$table = isset($_GET['table']) ? $_GET['table'] : die(erjx("table"));
$field_acuan = isset($_GET['field_acuan']) ? $_GET['field_acuan'] : die(erjx("field_acuan"));
$acuan_val = isset($_GET['acuan_val']) ? $_GET['acuan_val'] : die(erjx("acuan_val"));

if ($table=='' OR $field_acuan=='' OR $acuan_val=='') die("Error AJAX-global-delete. Salah satu index masih kosong.");

# ================================================
# MAIN HANDLE
# ================================================
$s = "DELETE FROM $table WHERE $field_acuan = '$acuan_val' ";
$q = mysqli_query($cn,$s) or die("Error @ajax. Tidak bisa menghapus data. \n\nSQL: $s\n\n".mysqli_error($cn));
die('sukses');
?>