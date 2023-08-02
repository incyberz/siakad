<style>
  .img_aksi{
    height: 20px;
    width: 20px;
    opacity: 60%;
    transition:.2s;
    cursor: pointer;
  }
  .img_aksi:hover{
    transform:scale(1.2);
    opacity: 100%;
  }
</style>
<?php
$aksi = [
'delete',
'edit',
'new',
'drop',
'assign',
'help',
'login_as',
'detail',
'check',
'wa',
'back',
'prev',
'next',
'pdf',
'csv',
'warning'
];

foreach ($aksi as $key => $value) {
  $img_aksi[$value] = "<img class='img_aksi' src='../assets/img/icons/$value.png'>";
}
