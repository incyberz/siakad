<?php
# =======================================================
# SEMESTER AKTIF
# =======================================================
$today = date('Y-m-d');
$s = "SELECT *,
(
	SELECT nomor FROM tb_semester s 
	JOIN tb_kalender k ON s.id_kalender=k.id 
	WHERE k.angkatan=a.angkatan 
	AND k.jenjang='S1' 
	AND s.tanggal_awal <= '$today' 
	AND s.tanggal_akhir >= '$today' 
	
	) semester  
FROM tb_angkatan a";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
$ul = div_alert('info','Belum ada angkatan.');
if(mysqli_num_rows($q)){
	$ul = '';
	while ($d=mysqli_fetch_assoc($q)) {
		if($d['semester']=='') continue;
		$ul.="
			<div>
				~ Angkatan $d[angkatan] ~ Semester $d[semester] 
					<span class=debug>lsa:$d[last_semester_aktif]</span>
			</div>
		";
		if($d['semester']!=$d['last_semester_aktif']){
			$s2 = "UPDATE tb_mhs SET semester_manual=$d[semester] WHERE angkatan=$d[angkatan]";
			$q2 = mysqli_query($cn,$s2) or die(mysqli_error($cn));
			$ul.= "<div class='consolas green small ml4'>Updating semester tiap mahasiswa angkatan $d[angkatan] success.</div>";
			$s2 = "UPDATE tb_angkatan SET last_semester_aktif=$d[semester] WHERE angkatan=$d[angkatan]";
			$q2 = mysqli_query($cn,$s2) or die(mysqli_error($cn));
			$ul.= "<div class='consolas green small ml4'>Updating last_semester_aktif angkatan $d[angkatan] success.</div>";
		}
	}
}

echo "
<div class='wadah gradasi-biru'>
	<div class='tebal'>Semester Aktif:</div>
	$ul
</div>
";