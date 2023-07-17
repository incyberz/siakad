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
$page = isset($_GET['p'])?$_GET['p']:'';
$aksi = isset($_GET['aksi'])?$_GET['aksi']:'';
$id = isset($_GET['id'])?$_GET['id']:'';
$keyword = isset($_POST['keyword'])?$_POST['keyword']:'';

if($page==''){
  # ==============================================================
  # SHOW MANAGE MASTER PAGE
  # ==============================================================
  // include 'master_home.php';
  // new patch :: redirect to manage
  die('<script>location.replace("?manage")</script>'); 

}else{

  # ==============================================================
  # MANAGE SINGLE MASTER
  # ==============================================================
  include 'master_single_page.php';
}
