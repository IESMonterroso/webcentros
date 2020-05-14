<?php
require_once("../bootstrap.php");
require_once("../config.php");

// COMPROBAMOS LA SESION
if ($_SESSION['alumno_autenticado'] != 1) {
	$_SESSION = array();
	session_destroy();

	header('Location:'.WEBCENTROS_DOMINIO.'alumnado/logout.php');
	exit();
}

if (file_exists('../intranet/config.php')) {
	include('../config.php');
}

$claveal = $_SESSION['claveal'];

$pagina['titulo'] = 'Trámite de audiencia para la evaluación ordinaria';
$pagina['meta']['meta_title'] = $pagina['titulo'];
$pagina['meta']['meta_description'] = "Trámite de audiencia de la evaluación ordinaria";
$pagina['meta']['meta_type'] = "website";
$pagina['meta']['meta_locale'] = "es_ES";

include("../inc_menu.php");

$datos_al = mysqli_query($db_con,"select apellidos, nombre, unidad, curso from alma where claveal='$claveal'");
$datos = mysqli_fetch_array($datos_al);

// Comprobamos que la tabla se ha creado
mysqli_query($db_con,"CREATE TABLE IF NOT EXISTS `audiencia` (
  `claveal` varchar(12) NOT NULL,
  `texto` text NOT NULL,
  `fecha` date NOT NULL,
  PRIMARY KEY (`claveal`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

?>

<?php
if (isset($_POST['enviar'])) {
	mysqli_query($db_con,"delete from audiencia where claveal = '$claveal'");
	$texto_tramite = trim($_POST['texto']);
	$fecha_tramite = date('Y-m-d');
	$in_tramite = mysqli_query($db_con, "insert into audiencia VALUES ('$claveal', '$texto_tramite', '$fecha_tramite')");
	if($in_tramite){
	$reg_tramite = 1;
	$mens_alerta = "
	<div class='alert alert-info'>
	<p>La solicitud ha sido registrada correctamente. Asimismo, se ha enviado un mensaje al tutor del alumno con la exposición presentada para que pueda comunicarla al Equipo educativo del grupo durante la evaluación ordinaria. </p><p>El tutor o el jefe de estudios se pondrá en comunicación con usted para informarle de las resoluciones tomadas por la junta de evaluación en relación a su solicitud.</p>
	</div>
	<br>";
	}

	$direccionIP = getRealIP();
	$dni_responsable_legal = $_SESSION['dnitutor'];
	$asunto_audiencia = attributeContextCleaner(limpiarInput(trim("Trámite de audiencia solicitado por el tutor legal del alumno ".$datos['nombre']." ".$datos['apellidos']), 'alphanumericspecial'));
	$mensaje_audiencia = attributeContextCleaner(limpiarInput(trim($_POST['texto']), 'alphanumericspecial'));

	$result = mysqli_query($db_con, "INSERT INTO mensajes (dni, claveal, asunto, texto, ip, correo, unidad) VALUES ('$dni_responsable_legal', '".$_SESSION['claveal']."', '$asunto_audiencia', '$mensaje_audiencia', '".$direccionIP."', '".$_SESSION['correo']."', '".$datos['unidad']."')");

			if(! $result) {
				$msg_error = "Ha ocurrido un error al enviar el mensaje.";
			}
			else {
				$msg_success = "El mensaje ha sido enviado correctamente.";
			}
}
?>

<?php
	

	$ya_hay = mysqli_query($db_con, "select * from audiencia where claveal='$claveal'");
	if (mysqli_num_rows($ya_hay)>0) {
		$audien = mysqli_fetch_array($ya_hay);
		$texto_a = $audien['texto'];
	}
	else{
		$texto_a = "D/Dª ------------------------------------, como padre/madre/tutor-a legal del alumno/a ".$datos['nombre']." ".$datos['apellidos']." que cursa ".$datos['curso']." en el grupo ".$datos['unidad'].", de cara a la sesión de evaluación ordinaria del curso ".$config['curso_actual']." y según aparece en el punto 3.2. de nuestro Reglamento de Organización y Funcionamiento que establece los criterios y procedimientos que garanticen el rigor y la transparencia en la toma de decisiones en los procesos de evaluación que habla sobre la posibilidad  de trámite de audiencia previo a la sesión de evaluación ordinaria y a la toma de decisión de la promoción y/o titulación, \n\nCOMUNICA:";
	}
?>
    <div class="section">
        <div class="container">

            <div class="row justify-content-md-center">

                <div class="col-md-8">

					<div class="card">
					  <div class="card-header">
					    <h6>Trámite de audiencia previo a la toma de decisión sobre la promoción o titulación</h6>
					  </div>
					  <div class="card-body">
					    <p class="card-text text-justify">Atendiendo a lo dispuesto en las Órdenes de 14 de julio de 2016 del currículo en ESO (artículo 22.5) y Bachillerato (25.2), así como en el reglamento de organización y funcionamiento del Centro, se abre el plazo para que las familias puedan trasladar al tutor/a y equipo docente con anterioridad a la evaluación Ordinaria, las circunstancias que consideren relevantes de cara a la toma de decisiones relacionadas con la promoción/titulación de sus hijos e hijas. </p><p class="card-text text-jleft"><em>Para el cumplimiento de este trámite, dispondrán del lunes 8 al viernes 12 de junio.</em></p>
					  </div>
					  <div class="card-footer text-muted">
					    <a href="https://iesmonterroso.org/plan-de-centro/ROF/32_transparencia.pdf" target = "_blank" class="btn btn-primary">Ver R.O.F.</a>
					  </div>
					</div>

					<hr>

                	<?php if (isset($_POST['enviar']) AND $reg_tramite==1){ echo $mens_alerta; }?>

                	<form method="post" action="audiencia.php" enctype="multipart/form-data">

						<fieldset>
							<legend class="text-muted">
								Alumno/a: <strong><?php echo $datos['nombre']." ".$datos['apellidos']; ?></strong> 
							<br>
								Grupo: <strong><?php echo $datos['unidad']; ?></strong>
							<br>	
								Curso: <strong><?php echo $datos['curso']; ?></strong>
							</legend>
							<br>

							<div class="form-group">
								<h5>Exposición:</h5>
								<textarea class="form-control" id="texto" name="texto" rows="12" style="border: 1px solid #888;"><?php echo $texto_a; ?></textarea>							
							</div>

							<button type="submit" class="btn btn-primary" name="enviar">Enviar texto</button>

						</fieldset>

					</form>

                </div>
            </div>
        </div>
    </div>

    <?php include("../inc_pie.php"); ?>



