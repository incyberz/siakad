<?php 
session_start();
# ================================================
# SESSION SECURITY
# ================================================
include "ajax_session_security.php";

# ================================================
# GET VARIABLES
# ================================================
$table = isset($_GET['table']) ? $_GET['table'] : die(erid("table"));
$fields = isset($_GET['fields']) ? $_GET['fields'] : die(erid("fields"));
$values = isset($_GET['values']) ? $_GET['values'] : die(erid("values"));
$pair_updates = isset($_GET['pair_updates']) ? $_GET['pair_updates'] : die(erid("pair_updates"));

if ($table=='' OR $fields=='' OR $values=='' OR $pair_updates=='') die("Error AJAX-global-insert-update. Salah satu index masih kosong.");


# ================================================
# MAIN HANDLE
# ================================================
$s = "INSERT INTO $table ($fields) VALUES ($values)
ON DUPLICATE KEY UPDATE $pair_updates
";
$q = mysqli_query($cn,$s) or die("Error @ajax. Tidak bisa insert/update data. SQL:$s. ".mysqli_error($cn));

die('sukses');
?>