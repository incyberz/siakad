<?php
$hari_ini = date('Y-m-d');
$w = date('w',strtotime($hari_ini));
$ahad_skg = date('Y-m-d',strtotime("-$w day",strtotime($hari_ini)));
$besok = date('Y-m-d H:i',strtotime('+1 day', strtotime('today')));
$lusa = date('Y-m-d H:i',strtotime('+2 day', strtotime('today')));

$senin_skg = date('Y-m-d',strtotime("+1 day",strtotime($ahad_skg)));
$selasa_skg = date('Y-m-d',strtotime("+2 day",strtotime($ahad_skg)));
$rabu_skg = date('Y-m-d',strtotime("+3 day",strtotime($ahad_skg)));
$kamis_skg = date('Y-m-d',strtotime("+4 day",strtotime($ahad_skg)));
$jumat_skg = date('Y-m-d',strtotime("+5 day",strtotime($ahad_skg)));
$sabtu_skg = date('Y-m-d',strtotime("+6 day",strtotime($ahad_skg)));
$ahad_depan = date('Y-m-d',strtotime("+7 day",strtotime($ahad_skg)));

$senin_skg_show = 'Senin, '.date('d M Y',strtotime($senin_skg));
$sabtu_skg_show = 'Sabtu, '.date('d M Y',strtotime($sabtu_skg));
$hari_ini_show = $nama_hari[date('w',strtotime('today'))].', '.date('d M Y H:i',strtotime('now'));
