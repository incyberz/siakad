<?php
# ======================================================
# PROGRES MANAGE
# ======================================================
$s = "SELECT * FROM tb_unsetting ORDER BY no";
$q = mysqli_query($cn,$s) or die(mysqli_error($cn));
$div='';
while ($d=mysqli_fetch_assoc($q)) {
	$setinged = $d['total'] - $d['unsetting'];
	$persen = round($setinged/$d['total']*100,2);
	$green_color = intval($persen/100*155);
  $red_color = intval((100-$persen)/100*255);
  $rgb = "rgb($red_color,$green_color,50)";

	$div.="
		<div class=col-lg-4>
			<div class='kecil miring abu'>Manage $d[caption] ~ $persen% | $setinged of $d[total]</div>
			<div class=progress>
				<div class='progress-bar' style='width:$persen%; background:$rgb'></div>
			</div>
		</div>
	";
}
echo "
<div class='wadah gradasi-hijau'>
	<h4 class='darkblue'>Progress Manage:</h4>
	<div class=row>
		$div
	</div>
</div>";