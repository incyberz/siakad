<h1>MANAGE KURIKULUM</h1>
<?php
$id = isset($_GET['id']) ? $_GET['id'] : '';
if($id<1) die('<script>location.replace("?master&p=kurikulum")</script>');

