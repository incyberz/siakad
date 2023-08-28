<?php
# ==============================================================
# MASTER DATA HANDLER v.1.0.1
# ==============================================================

# ==============================================================
# FORM ACTION HANDLER
# ==============================================================
include 'master_form_handler.php';
include 'master_pesan_handler.php';


# ==============================================================
# GET PARAMS
# ==============================================================
$page = $_GET['p']??'';
$aksi = $_GET['aksi']??'';
$id = $_GET['id']??'';
$keyword = $_POST['keyword']??'';

if($page==''){
  # ==============================================================
  # SHOW MANAGE MASTER PAGE
  # ==============================================================
  // include 'master_home.php';
  // new patch :: redirect to manage
  die('<script>location.replace("?manage")</script>'); 

}elseif($page=='mhs'){
  die('<script>location.replace("?master_mhs")</script>'); 
}else{

  # ==============================================================
  # MANAGE SINGLE MASTER
  # ==============================================================
  include 'master_single_page.php';
}
