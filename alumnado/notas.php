<?php defined('WEBCENTROS_DIRECTORY') OR exit('No direct script access allowed');

function obtener_calificacion_texto($nota) {
    switch ($nota) {
        case 10:
        case 9:
            $calificacion = 'SB';
            break;
        case 8:
        case 7:
            $calificacion = 'NT';
            break;
        case 6:
            $calificacion = 'BI';
            break;
        case 5:
            $calificacion = 'SU';
            break;
        default:
            $calificacion = 'IN';
            break;
    }

    return $calificacion;
}

// Evaluaciones
$notas1 = "select notas1, notas2, notas3, notas4, unidad, notas0 from alma, notas where alma.CLAVEAL1 = notas.claveal and alma.CLAVEAL = '$claveal'";
// echo $notas1;
$result1 = mysqli_query($db_con, $notas1);
$row1 = mysqli_fetch_array($result1);
$asignatura_1 = substr($row1[0], 0, strlen($row1[0])-1);
$asignatura_2 = substr($row1[1], 0, strlen($row1[1])-1);
$asignatura_3 = substr($row1[2], 0, strlen($row1[2])-1);
$inicial = substr($row1[5], 0, strlen($row1[5])-1);

if (strlen($asignatura_1) > 0) {
	$trozos = explode(";", $asignatura_1);
}
elseif (strlen($asignatura_2) > 0) {
	$trozos = explode(";", $asignatura_2);
}
elseif (strlen($asignatura_3) > 0) {
	$trozos = explode(";", $asignatura_3);
}
elseif(strlen($inicial) > 0) {
	$trozos = explode(";", $inicial);
}
?>

<?php
if (strlen($inicial) > 0) {
	$titulo_extra = '<th data-bs="tooltip" title="Evaluación inicial">EVI.</th>';
	$col_extra = 1;
}
?>
<h3>Evaluaciones</h3><br>
<div class='table-responsive'><table class='table table-bordered table-striped table-hover'>
		<thead>
			<tr>
				<th>Asignatura / Materia</th>
				<?php echo $titulo_extra; ?>
				<th data-bs="tooltip" title="1ª Evaluación">1Ev.</th>
				<th data-bs="tooltip" title="2ª Evaluación">2Ev.</th>
				<th data-bs="tooltip" title="Evaluación ordinaria">Ord.</th>
				<th data-bs="tooltip" title="Evaluación extraordinaria">Ext.</th>
			</tr>
		</thead>

<?php

$num = count($trozos);
 for ($i=0;$i<$num; $i++)
  {
$nombre_asig ="";
$bloque = explode(":", $trozos[$i]);
$nombreasig = "select NOMBRE, ABREV, CURSO, CODIGO from asignaturas where CODIGO = '" . $bloque[0] . "'  order by CURSO ASC";
$asig = mysqli_query($db_con, $nombreasig);
if(mysqli_num_rows($asig) < 1)	{$nombre_asig = "Asignatura sin código"; }
while($rowasig = mysqli_fetch_array($asig))	{
  if ($rowasig[3] == "")
  {$nombre_asig = "Asignatura sin código"; }
else{
$nombre_asig = $rowasig[0];
}
	if(strlen(strstr($rowasig[1],'_')) > 0)	{	}

	else 	{	$asig_pend = $rowasig[2];	}
	}


$asignatura1 = substr($row1[0], 0, strlen($row1[0])-1);
$trozos1 = explode(";", $asignatura1);
	if (strstr($row1[0],$bloque[0])) {
	foreach($trozos1 as $codi1)
	{
	$bloque1 = explode(":", $codi1);
	if($bloque1[0] == $bloque[0])
	{
$califica1 = "select abreviatura from calificaciones where codigo = '" . $bloque1[1]. "'";
$numero1 = mysqli_query($db_con, $califica1);
$rown1 = mysqli_fetch_array($numero1);
	}
	}
	}
	else{
		$rown1[0]=" ";
	}




$asignatura2 = substr($row1[1], 0, strlen($row1[1])-1);
$trozos2 = explode(";", $asignatura2);
	if (strstr($row1[1],$bloque[0])) {
	foreach($trozos2 as $codi2)
	{
	$bloque2 = explode(":", $codi2);
	if($bloque2[0] == $bloque[0])
	{
$califica2 = "select abreviatura from calificaciones where codigo = '" . $bloque2[1]. "'";
$numero2 = mysqli_query($db_con, $califica2);
$rown2 = mysqli_fetch_array($numero2);
	}
	}
	}
	else{
		$rown2[0]=" ";
	}



$asignatura3 = substr($row1[2], 0, strlen($row1[2])-1);
$trozos3 = explode(";", $asignatura3);
	if (strstr($row1[2],$bloque[0])) {
	foreach($trozos3 as $codi3)
	{
	$bloque3 = explode(":", $codi3);
	if($bloque3[0] == $bloque[0])
	{
$califica3 = "select abreviatura from calificaciones where codigo = '" . $bloque3[1]. "'";
$numero3 = mysqli_query($db_con, $califica3);
$rown3 = mysqli_fetch_array($numero3);
	}
	}
	}
	else{
		$rown3[0]=" ";
	}



$asignatura4 = substr($row1[3], 0, strlen($row1[3])-1);
$trozos4 = explode(";", $asignatura4);
	if (strstr($row1[3],$bloque[0])) {
		foreach($trozos4 as $codi4)
	{
	$bloque4 = explode(":", $codi4);

	if($bloque[0] == $bloque4[0])
	{
$califica4 = "select abreviatura from calificaciones where codigo = '" . $bloque4[1]. "'";
$numero4 = mysqli_query($db_con, $califica4);
$rown4 = mysqli_fetch_array($numero4);
	}
	}
	}
	else{
		$rown4[0]=" ";
	}



$asignatura5 = substr($row1[5], 0, strlen($row1[5])-1);
$trozos5 = explode(";", $asignatura5);
	if (strstr($row1[5],$bloque[0])) {
		foreach($trozos5 as $codi5)
	{
	$bloque5 = explode(":", $codi5);

	if($bloque[0] == $bloque5[0])
	{
$califica5 = "select abreviatura from calificaciones where codigo = '" . $bloque5[1]. "'";
$numero5 = mysqli_query($db_con, $califica5);
$rown5 = mysqli_fetch_array($numero5);
	}
	}
	}
	else{
		$rown5[0]=" ";
	}






if($rown1[0] == "" and $rown2[0] == "" and $rown3[0] == "" and $rown4[0] == "")
	{
			}
	else
		{
	echo "<tr><td>";
	if ($nombre_asig == "Asignatura sin código")  $asig_pend = "Consultar con Administración";
	$trozo_curso=explode("(",$asig_pend);
	$asig_curso=$trozo_curso[0];
	echo $nombre_asig . " <span  class='small'>(" . $asig_curso . ")</span></td>";

	if ($col_extra==1) {
		echo "<td>";
		if (intval($rown5[0])) {
			echo obtener_calificacion_texto($rown5[0]) . ' | ' . $rown5[0];
		} else {
			echo $rown5[0];
		}
		echo "</td>";
	}

	echo "<td>";
	if (intval($rown1[0])) {
		echo obtener_calificacion_texto($rown1[0]) . ' | ' . $rown1[0];
	} else {
		echo $rown1[0];
	}
	echo "</td>";

	echo "<td>";
	if (intval($rown2[0])) {
		echo obtener_calificacion_texto($rown2[0]) . ' | ' . $rown2[0];
	} else {
		echo $rown2[0];
	}
	echo "</td>";

	echo "<td>";
	if (intval($rown3[0])) {
		echo obtener_calificacion_texto($rown3[0]) . ' | ' . $rown3[0];
	} else {
		echo $rown3[0];
	}
	echo "</td>";

	echo "<td>";
	if (intval($rown4[0])) {
		echo obtener_calificacion_texto($rown4[0]) . ' | ' . $rown4[0];
	} else {
		echo $rown4[0];
	}
	echo "</td>";

	echo "</tr>";

			}
	}
	echo "</table></div>";

?>
