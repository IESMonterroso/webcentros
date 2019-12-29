<?php
require_once("../bootstrap.php");
require_once("../config.php");

$pagina['titulo'] = 'Informes de tránsito <small>Curso '.$config['curso_actual'].'</small>';

// SEO
//$pagina['meta']['robots'] = 0;
//$pagina['meta']['canonical'] = 0;
$pagina['meta']['meta_title'] = $pagina['titulo'];
$pagina['meta']['meta_description'] = "Informes de tránsito ".$config['curso_actual']." del ".$config['centro_denominacion'];
$pagina['meta']['meta_type'] = "website";
$pagina['meta']['meta_locale'] = "es_ES";

include("../inc_menu.php");
?>
<div class="section">
<div class="container">

<div class="row justify-content-center">

<div class="col-sm-10">
<h3 align="center"><i class='fa fa-folder-open'> </i> 
Informe de Tránsito para Alumnos de Primaria.</h3>
<hr>
<?

// Se ha enviado la clave
$clave = limpiarInput($_POST['clave'],'alphanumericspecial');
$cole = limpiarInput($_POST['user'],'alphanumericspecial');
$colegi = limpiarInput($_POST['colegi'],'alphanumericspecial');
$unidad = limpiarInput($_POST['unidad'],'alphanumericspecial');

if (isset($_POST['alumno'])) {
	$alumno = limpiarInput($_POST['alumno'],'alphanumericspecial');
	$tr_al = explode(":",$_POST['alumno']);
	$claveal = $tr_al[0];
	$nombre_al = $tr_al[1];
}

$auth = limpiarInput($_POST['auth'], 'numeric');


$cod_sha = sha1($clave);

// Inserción de datos
if ($_POST['reset']=="Borrar datos del alumno") {
	mysqli_query($db_con,"delete from transito_datos where claveal='$claveal'");
	echo '<br /><div class="alert alert-success alert-block fade in">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
Los datos del alumno se han borrado correctamente.
</div>';
}	
// Inserción de datos
if ($_POST['submit0']=="Actualizar datos") {
	mysqli_query($db_con,"delete from transito_datos where claveal='$claveal'");
	foreach ($_POST as $clave=>$valor){
		if ($clave!=="auth" and $clave!=="colegi" and $clave!=="unidad" and $clave!=="alumno" and $clave!=="submit0") {
			if (is_array($valor)) {
				$valo="";
				foreach ($valor as $key=>$val){
					$valo.=$val;
					}
					$valor=$valo;
			}
			mysqli_query($db_con,"insert into transito_datos values ('','$claveal','$clave','$valor')");
		}
	}
	echo '<br /><div class="alert alert-success alert-block fade in">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
Los datos se han actualizado correctamente.
</div>';
}
elseif ($_POST['submit0']=="Enviar datos") {
	foreach ($_POST as $clave=>$valor){
		if ($clave!=="auth" and $clave!=="colegi" and $clave!=="unidad" and $clave!=="alumno" and $clave!=="submit0") {
			if (is_array($valor)) {
				$valo="";
				foreach ($valor as $key=>$val){
					$valo.=$val;
					}
					$valor=$valo;
			}
			mysqli_query($db_con,"insert into transito_datos values ('','$claveal','$clave','$valor')");
		}
	}
	echo '<br /><div class="alert alert-success alert-block fade in">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
Los datos se han guardado correctamente. Si necesitas cambiarlos, vuelve a seleccionar al alumno y modíficalos a tu gusto.
</div>';
}
// Control de entrada
$c_prim = mysqli_query($db_con,"SELECT * from transito_control WHERE transito_control.colegio = '$cole' and transito_control.pass = '$cod_sha'");
$num_cole=mysqli_num_rows($c_prim);

// Contraseña coincide: podemos entrar
if ($num_cole>0 or $auth=="1") {
	if (isset($cole)){$colegio=$cole;}else{$colegio=$colegi;}
	?>
<div class="hidden-print col-md-12 text-center">
<form class="form-inline justify-content-center" method="post">
	<input type="hidden" name="auth" value="1" /> 
	<input type="hidden" name="colegi"	value="<?php echo $colegio;?>" /> 

<div class="form-group text-center">
	<label for="unidad">
	Selecciona Grupo 
	</label> &nbsp;&nbsp; 
<select id="unidad" name="unidad" class="form-control" onchange="submit()" />
<option><?php echo $unidad;?></option>
	<?
	$al_primaria = "SELECT distinct unidad FROM alma_primaria where colegio = 'C.E.I.P. $colegio' order by unidad";
	$alum_primaria = mysqli_query($db_con,$al_primaria);
	while ($cole=mysqli_fetch_array($alum_primaria)) {
		echo "<option>$cole[0]</option>";
	}
	?> 
</select> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

	<label for="alumno">
	Selecciona Alumno 
	</label> &nbsp;&nbsp;
<select id="alumno" name="alumno" class="form-control" onchange="submit()" />
<option vlaue="<?php echo "$claveal:$nombre_al";?>"><?php echo $nombre_al;?></option>
	<?
	$al = "SELECT distinct claveal, apellidos, nombre FROM alma_primaria where colegio = 'C.E.I.P. $colegio' and unidad = '$unidad' order by apellidos, nombre";
	$alum = mysqli_query($db_con,$al);
	while ($alumn=mysqli_fetch_array($alum)) {
		echo "<option value='$alumn[0]:$alumn[1], $alumn[2]'>$alumn[1], $alumn[2]</option>";
	}
	?> 
</select>
</div>
</form>
</div>
<hr>
	<?
	$result = mysqli_query($db_con,"select distinct alma_primaria.claveal, alma_primaria.DNI, alma_primaria.fecha, alma_primaria.domicilio, alma_primaria.telefono, alma_primaria.padre, alma_primaria.matriculas, telefonourgencia, paisnacimiento, correo, nacionalidad, edad, curso, alma_primaria.unidad, numeroexpediente, apellidos, nombre from alma_primaria where alma_primaria.claveal= '$claveal' order BY alma_primaria.apellidos");
	if ($row = mysqli_fetch_array($result)):
	$nombre_alumn = $row['nombre']." ". $row['apellidos'];
	?>
<h3 class="text-center">C.E.I.P. <?php echo $colegi;?></h3>
<h4 class="text-center">Expediente académico del alumno/a <small> Curso académico: <?php echo $config['curso_actual']; ?></small></h4>
<legend class="text-danger"><?php echo $nombre_alumn; ?> </legend>
<br>
<div class="row">
<dl class="dl-horizontal col-md-6">
	<dt>DNI / Pasaporte</dt>
	<dd><?php echo ($row['DNI'] != "") ? $row['DNI']: '<span class="text-muted">Sin registrar</span>'; ?></dd>
	<dt>Fecha de nacimiento</dt>
	<dd><?php echo ($row['fecha'] != "") ? $row['fecha']: '<span class="text-muted">Sin registrar</span>'; ?></dd>
	<dt>Edad</dt>
	<dd><?php echo ($row['edad'] != "") ? $row['edad'].' años': '<span class="text-muted">Sin registrar</span>'; ?></dd>
	<dt>Domicilio</dt>
	<dd><?php echo ($row['domicilio'] != "") ? $row['domicilio']: '<span class="text-muted">Sin registrar</span>'; ?></dd>
	<dt>Nacionalidad</dt>
	<dd><?php echo ($row['nacionalidad'] != "") ? $row['nacionalidad']: '<span class="text-muted">Sin registrar</span>'; ?></dd>
	<dt>Teléfono</dt>
	<dd><?php echo ($row['telefono'] != "") ? '<a href="tel:'.$row['telefono'].'">'.$row['telefono'].'</a>': '<span class="text-muted">Sin registrar</span>'; ?></dd>
	<dt>Teléfono urgencias</dt>
	<dd><?php echo ($row['telefonourgencia'] != "") ? '<a href="tel:'.$row['telefonourgencia'].'">'.$row['telefonourgencia'].'</a>': '<span class="text-muted">Sin registrar</span>'; ?></dd>
</dl>


<dl class="dl-horizontal col-md-6">
	<dt><abbr data-bs="tooltip" title="Número de Identificación Escolar">N.I.E.</abbr></dt>
	<dd><?php echo ($row['claveal'] != "") ? $row['claveal']: '<span class="text-muted">Sin registrar</span>'; ?></dd>
	<dt>Nº Expediente</dt>
	<dd><?php echo ($row['numeroexpediente'] != "") ? $row['numeroexpediente']: '<span class="text-muted">Sin registrar</span>'; ?></dd>
	<dt>Curso</dt>
	<dd><?php echo ($row['curso'] != "") ? $row['curso']: '<span class="text-muted">Sin registrar</span>'; ?></dd>
	<dt>Unidad</dt>
	<dd><?php echo ($row['unidad'] != "") ? $row['unidad']: '<span class="text-muted">Sin registrar</span>'; ?></dd>
	<dt>Repetidor/a</dt>
	<dd><?php echo ($row['matriculas'] > 1) ? 'Sí': 'No'; ?></dd>
	<dt>Representante legal</dt>
	<dd><?php echo ($row['padre'] != "") ? $row['padre']: '<span class="text-muted">Sin registrar</span>'; ?></dd>
	<dt>Correo electrónico</dt>
	<dd><?php echo ($row['correo'] != "") ? '<a href="mailto:'.$row['correo'].'">'.$row['correo'].'</a>' : '<span class="text-muted">Sin registrar</span>'; ?></dd>
</dl>
</div>
	<?
	endif;
}

else  {
	echo '<div class="col-md-8 col-md-offset-2">
<div align="center">
<hr />';
	echo "<br /><div class='alert alert-danger' style='max-width:450px;margin:auto'><legend>Atenci&oacute;n:</legend><p>Debes introducir una <b>Clave del Centro</b> válida para entrar en estas páginas. Si eres alumno de este Centro, <em>debes conseguir tu clave de acceso a través del Tutor, Administración o Jefatura de Estudios del Centro</em> para poder entrar en estas páginas</p></div>";
	exit;
}

?> <br />
<?
if (isset($claveal)) {
	include 'form.php';
}
?>
</div>
</div>
</div>
</div>
<?php include("../inc_pie.php"); ?>


