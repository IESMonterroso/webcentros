<?php
require_once("../bootstrap.php");
require_once('../config.php');


if (isset($config['alumnado']['pasen']) && $config['alumnado']['pasen'] == true) {
	header('Location:https://www.juntadeandalucia.es/educacion/portalseneca/web/pasen/inicio');
	exit();
}

if (file_exists('../intranet/config.php')) {
	include('../config.php');
}

if (file_exists('../intranet/admin/fechorias/config.php')) {
	include('../intranet/admin/fechorias/config.php');
}

// COMPROBAMOS LA SESION
if ($_SESSION['alumno_autenticado'] != 1) {
	$_SESSION = array();
	session_destroy();

	header('Location:'.WEBCENTROS_DOMINIO.'alumnado/logout.php');
	exit();
}

// FORZAMOS EL CAMBIO DE CONTRASEÑA
if(isset($_SESSION['cambiar_clave_alumno']) && $_SESSION['cambiar_clave_alumno']) {
	header('Location:'.WEBCENTROS_DOMINIO.'alumnado/clave.php');
	exit();
}
$claveal = $_SESSION['claveal'];
$c_escolar = (date('n') > 6) ?  date('Y').'/'.(date('y')+1) : (date('Y')-1).'/'.date('y');

if (isset($_SESSION['tabla_bd'])) {
	$bd_alma = $_SESSION['tabla_bd'];
}
else {
	$bd_alma = "alma";
}

if (isset($_SESSION['tabla_bd_control'])) {
	$bd_control = $_SESSION['tabla_control'];
}
else {
	$bd_control = "control";
}

if ($claveal) {
	if ($bd_alma == "alma") {
		$result1 = mysqli_query($db_con, "SELECT DISTINCT apellidos, nombre, unidad, curso, claveal, claveal1, numeroexpediente, dnitutor, combasi, estadomatricula FROM $bd_alma WHERE claveal = '$claveal' ORDER BY apellidos");
	}
	else{
		$result1 = mysqli_query($db_con, "SELECT DISTINCT apellidos, nombre, unidad, curso, claveal, numeroexpediente, dnitutor FROM $bd_alma WHERE claveal = '$claveal' ORDER BY apellidos");
	}

	if ($row1 = mysqli_fetch_array($result1)) {
		$unidad = $row1['unidad'];
		$_SESSION['unidad'] = $row1['unidad'];
		$curso = $row1['curso'];
		$claveal1 = $row1['claveal1'];
		$apellido = $row1['apellidos'];
		$nombrepil = $row1['nombre'];
		$dni_responsable_legal = $row1['dnitutor'];
		$_SESSION['promociona'] = $row1['estadomatricula'];
		if (isset($row1['combasi'])) {
			$combasi = $row1['combasi'];
		}
		if (! isset($_SESSION['alumno'])) {
			$_SESSION['alumno'] = $nombrepil;
		}

		if ($_SERVER['SERVER_NAME'] == "iesmonterroso.org") {
			$nombre_o = str_replace("Á", "A", $row1['nombre']);
			$apellidos_o = str_replace("Á", "A", $row1['apellidos']);
			$iniciales = strtolower(substr($nombre_o, 0,1).substr($apellidos_o, 0,1));
			$iniciales = str_ireplace($caracteres_no_permitidos, $caracteres_permitidos, $iniciales);
			$nombre = str_ireplace($caracteres_no_permitidos, $caracteres_permitidos, $nombre_o);
			$apellidos = str_ireplace($caracteres_no_permitidos, $caracteres_permitidos, $apellidos_o);

			$correo_gsuite = "al.".$row1['claveal'].'@'.$config['dominio'];
			$pass_gsuite = $iniciales.".".$row1['claveal'];

			$usuario_moodle = $row1['claveal'];
			$pass_moodle = substr(sha1($row1['claveal']),0,8);
		}
		else {
			$correo_gsuite = $row1['claveal'].'.alumno@'.$config['dominio'];
			$pass_gsuite = substr(sha1($row1['claveal']),0,8);

			$usuario_moodle = $row1['claveal'];
			$pass_moodle = substr(sha1($row1['claveal']),0,8);
		}
	}

}

// Informes para la evaluación extraordinaria
$informe_extraordinaria="";
$inf_extra = mysqli_query($db_con,"select * from informe_pendientes_alumnos where claveal = '".$claveal."'");
if (mysqli_num_rows($inf_extra)>0) {
	$informe_extraordinaria=1;
}

// Módulo de matriculación
if (isset($config['mod_matriculacion']) && $config['mod_matriculacion']) {
	if (@file_exists("../intranet/admin/matriculas/config.php")) require_once("../intranet/admin/matriculas/config.php");
	if (@file_exists("../../intranet/admin/matriculas/config.php")) require_once("../../intranet/admin/matriculas/config.php");

	$_form_action = "";
	$_form_descripcion = "";

	$dia_matricula_ini = strftime('%d %B', strtotime($config['matriculas']['fecha_inicio']));
	$dia_matricula_fin = strftime('%d %B', strtotime($config['matriculas']['fecha_fin']));
	$dia_audiencia_ini = strftime('%d %B', strtotime(substr($config['curso_fin'],0,4)."-06-08"));
	$dia_audiencia_fin = strftime('%d %B', strtotime(substr($config['curso_fin'],0,4)."-06-12"));
	

	// Comprobamos si el centro ofrece estudios de Bachillerato
	$result = mysqli_query($db_con, "SELECT nomcurso FROM cursos WHERE nomcurso LIKE '%Bachillerato%' LIMIT 1");
	if (mysqli_num_rows($result)) $ofertaBachillerato = 1;
	else $ofertaBachillerato = 0;

	if (isset($_SESSION['alumno_primaria']) && $_SESSION['alumno_primaria'] == 1) {
		$_curso_matricula = "1 ESO";
		$_form_action = "matriculas.php";
		$_form_descripcion = "1º de Educación Secundaria Obligatoria";
	}
	elseif (stristr($curso, '4º de E.S.O.') == true || stristr($curso, 'Bachillerato') == true) {
		$result_matricula_bach = mysqli_query($db_con, "SELECT claveal FROM matriculas_bach WHERE claveal = '$claveal' LIMIT 1");
		if (mysqli_num_rows($result_matricula_bach)) $estaMatriculadoBachillerato = 1;
		else $estaMatriculadoBachillerato = 0;

		if (stristr($curso, '4º de E.S.O.') == true) {
			$estaMatriculadoESO = 1;
			$_curso_matricula = "1 BACH";
			$_form_action = "matriculas_bach.php";
			$_form_descripcion = "1º Bachillerato";
		}
		elseif (stristr($curso, 'Bachillerato') == true) {
			$_curso_matricula = "2 BACH";
			$_form_action = "matriculas_bach.php";
			$_form_descripcion = "2º Bachillerato";
		}
	}
	else {
		$result_matricula_eso = mysqli_query($db_con, "SELECT claveal FROM matriculas WHERE claveal = '$claveal' LIMIT 1");
		if (mysqli_num_rows($result_matricula_eso)) $estaMatriculadoESO = 1;
		else $estaMatriculadoESO = 0;

		$curso_siguiente = substr($curso, 0, 1) + 1;
		$_curso_matricula = $curso_siguiente . " ESO";
		$_form_action = "matriculas.php";
		$_form_descripcion = $curso_siguiente ."º de Educación Secundaria Obligatoria";
	}
}

$_SESSION['ya_matricula_eso'] = $estaMatriculadoESO;
$_SESSION['ya_matricula_bach'] = $estaMatriculadoBachillerato;

if (isset($_POST['subirFotografia'])) {

	$fotografia = $_FILES['foto']['tmp_name'];

	if (empty($claveal) || empty($fotografia)) {
		$msg_error = "Todos los campos del formulario son obligatorios.";
	}
	else {

		if ($_FILES['foto']['type'] != 'image/jpeg') {
			$msg_error = "El formato del archivo no es válido.";
		}
		else {
			require_once('../plugins/class.Images.php');
			$image = new Image($fotografia);
			$image->resize(240,320,'crop');
			$image->save($claveal, '../intranet/xml/fotos/', 'jpg');

			$file_content = mysqli_real_escape_string($db_con, file_get_contents('../intranet/xml/fotos/'.$claveal.'.jpg'));
			$file_size = filesize('../intranet/xml/fotos/'.$claveal.'.jpg');

			// Eliminamos posibles imagenes que hayan en la tabla
			mysqli_query($db_con, "DELETE FROM fotos WHERE nombre='".$claveal.".jpg'");

			// Insertamos la foto en la tabla, esto es útil para la página externa
			mysqli_query($db_con, "INSERT fotos (nombre, datos, fecha, tamaño) VALUES ('".$claveal.".jpg', '$file_content', '".date('Y-m-d H:i:s')."', '".$file_size."')");

			$msg_success = "La fotografía se ha actualizado.";
		}

	}
}

// Comprobamos si el alumno tiene actividades evaluables registradas
$muestra_evaluables = 0;
$query_evaluables0 = mysqli_query($db_con, "SELECT DISTINCT notas_cuaderno.profesor AS nomprofesor, asignaturas.NOMBRE AS nomasignatura, notas_cuaderno.id AS idactividad, notas_cuaderno.nombre AS nomactividad, notas_cuaderno.fecha AS fecactividad FROM notas_cuaderno JOIN asignaturas ON notas_cuaderno.asignatura = asignaturas.CODIGO WHERE notas_cuaderno.curso LIKE '%$unidad%' AND notas_cuaderno.visible_nota=1");
if (mysqli_num_rows($query_evaluables0)>0) $muestra_evaluables = 1;

$query_evaluables1 = mysqli_query($db_con,"SELECT id, fechaini, unidades, nombre, asignaturas FROM calendario WHERE unidades like '%".$unidad."%' and date(fechaini)>'".$config['curso_inicio']."' and categoria > '2' order by fechaini");
if (mysqli_num_rows($query_evaluables1)>0)  $muestra_evaluables = 1;

// Comprobamos mensajes recibidos
$query_mensajes = mysqli_query($db_con, "SELECT mens_texto.id, ahora, asunto, texto, c_profes.profesor, (SELECT recibidoprofe FROM mens_profes WHERE id_texto = mens_texto.id AND profesor LIKE '%$apellido, $nombrepil%' OR profesor LIKE '%".$_SESSION['claveal']."%' LIMIT 1) AS recibidoprofe FROM mens_texto JOIN c_profes ON mens_texto.origen = c_profes.idea WHERE ahora BETWEEN '".$config['curso_inicio']."' AND '".$config['curso_fin']."' AND (destino LIKE '%$apellido, $nombrepil%' OR destino LIKE '%".$_SESSION['claveal']."%' AND asunto NOT LIKE 'Mensaje de confirmación') ORDER BY ahora DESC");
$numeroMensajesRecibidos = mysqli_num_rows($query_mensajes);

$query_mensajes2 = mysqli_query($db_con, "SELECT mens_texto.id, ahora, asunto, texto, c_profes.profesor, (SELECT recibidoprofe FROM mens_profes WHERE id_texto = mens_texto.id AND profesor LIKE '%$apellido, $nombrepil%' OR profesor LIKE '%".$_SESSION['claveal']."%' LIMIT 1) AS recibidoprofe FROM mens_texto JOIN c_profes ON mens_texto.origen = c_profes.idea WHERE ahora BETWEEN '".$config['curso_inicio']."' AND '".$config['curso_fin']."' AND (destino LIKE '%$apellido, $nombrepil%' OR destino LIKE '%".$_SESSION['claveal']."%' AND asunto NOT LIKE 'Mensaje de confirmación') ORDER BY ahora DESC");

while ($n_mens = mysqli_fetch_array($query_mensajes2)) {
	$c1  = mysqli_query($db_con,"select recibidoprofe from mens_profes where id_texto = '$n_mens[0]' and recibidoprofe='0'");
		if (mysqli_num_rows($c1)>0) {
		$numeroMensajesRecibidos2++;
		}
}

$query_enviados = mysqli_query($db_con, "SELECT id, ahora, asunto, texto, recibidotutor FROM mensajes WHERE ahora BETWEEN '".$config['curso_inicio']."' AND '".$config['curso_fin']."' AND (claveal = '".$_SESSION['claveal']."' AND asunto NOT LIKE 'Mensaje de confirmación')");
$numeroMensajesEnviados = mysqli_num_rows($query_enviados);


$pagina['titulo'] = 'Expediente académico';

$pagina['meta']['robots'] = 0;
$pagina['meta']['canonical'] = 0;

include('../inc_menu.php');
?>

	<div class="section">
		<div class="container-fluid">

			<?php if (isset($_SESSION['dnitutor'])): ?>
			<div class="alert alert-info">
				<?php if($_SESSION['esTutor'] == 1) $extra_tutorLegal = " tutor/a legal de $nombrepil ";?>
				Ha iniciado sesión como <?php echo $_SESSION['nombretutor']; ?>,<?php echo $extra_tutorLegal; ?> <span style="border-bottom: 1px dotted #fff; cursor: help;" data-toggle="popover" data-placement="bottom" data-content="Los tutores legales registrados en la matrícula del alumno/a en este centro tienen acceso al expediente académico utilizando su respectivo DNI como contraseña. Los accesos que realicen a este informe quedarán registrados, incluyendo fecha y dirección IP de su ordenador actual. Puede solicitar un documento con los accesos realizados a este informe en Jefatura de Estudios.">¿qué significa esto?</span>
			</div>
			<?php endif; ?>

			<?php $result = mysqli_query($db_con, "SELECT correo FROM $bd_control WHERE claveal='$claveal' LIMIT 1"); ?>
			<?php $row2 = mysqli_fetch_array($result); ?>
			<?php mysqli_free_result($result); ?>

			<?php $result = mysqli_query($db_con, "SELECT claveal, apellidos, nombre, DNI, fecha, domicilio, telefono, padre, dnitutor, matriculas, telefonourgencia, paisnacimiento, correo, nacionalidad, edad, curso, unidad, numeroexpediente FROM $bd_alma WHERE claveal= '$claveal'"); ?>

			<?php if ($row = mysqli_fetch_array($result)): $grupo_al = $row['unidad'];?>
			<?php $result_tutor = mysqli_query($db_con, "SELECT tutor FROM FTUTORES WHERE unidad = '".$row['unidad']."' LIMIT 1"); ?>
			<?php $row_tutor = mysqli_fetch_array($result_tutor); ?>
			<?php $exp_tutor = explode(", ",$row_tutor['tutor']); ?>
			<?php $tutor = trim($exp_tutor[1]." ".$exp_tutor[0]); ?>
			<!-- SCAFFOLDING -->
			<div class="card-box border-primary pb-0">
			<div class="row">

				<!-- COLUMNA IZQUIERDA -->
				<div class="col-sm-2 text-center hidden-xs">
					<?php $foto = '../intranet/xml/fotos/'.$claveal.'.jpg'; ?>
					<?php if (file_exists($foto)): ?>
					<?php $foto_usuario = 'data:image/png;base64,'.base64_encode(file_get_contents($foto)); ?>
					<img class="img-thumbnail" src="<?php echo $foto_usuario; ?>" alt="<?php echo $apellido.', '.$nombrepil; ?>">
					<?php else: ?>
					<h2><span class="far fa-user fa-fw fa-4x"></span></h2>
					<?php endif; ?>

					<a href="#" class="btn btn-default btn-sm" data-toggle="modal" data-target="#subirFotografia"><small>Subir o cambiar foto</small></a>
				</div><!-- /.col-sm-2 -->


				<!-- COLUMNA DERECHA -->
				<div class="col-sm-10">

					<div class="row">

						<div class="col-sm-6">

							<dl class="row">
								<dt class="col-sm-5">Nombre</dt>
								<dd class="col-sm-7"><?php echo ($row['nombre'] != "") ? $row['nombre']: '<span class="text-muted">Sin registrar</span>'; ?></dd>

								<dt class="col-sm-5">Apellidos</dt>
								<dd class="col-sm-7"><?php echo ($row['apellidos'] != "") ? $row['apellidos']: '<span class="text-muted">Sin registrar</span>'; ?></dd>

								<dt class="col-sm-5">DNI / Pasaporte</dt>
								<dd class="col-sm-7"><?php echo ($row['DNI'] != "") ? $row['DNI']: '<span class="text-muted">Sin registrar</span>'; ?></dd>

								<dt class="col-sm-5">Fecha de nacimiento</dt>
								<dd class="col-sm-7"><?php echo ($row['fecha'] != "") ? $row['fecha']: '<span class="text-muted">Sin registrar</span>'; ?></dd>

								<dt class="col-sm-5">Edad</dt>
								<dd class="col-sm-7"><?php echo ($row['edad'] != "") ? $row['edad'].' años': '<span class="text-muted">Sin registrar</span>'; ?></dd>

								<dt class="col-sm-5">Domicilio</dt>
								<dd class="col-sm-7"><?php echo ($row['domicilio'] != "") ? $row['domicilio']: '<span class="text-muted">Sin registrar</span>'; ?></dd>

								<dt class="col-sm-5">Nacionalidad</dt>
								<dd class="col-sm-7"><?php echo ($row['nacionalidad'] != "") ? $row['nacionalidad']: '<span class="text-muted">Sin registrar</span>'; ?></dd>

								<dt class="col-sm-5">Teléfono</dt>
								<dd class="col-sm-7"><?php echo ($row['telefono'] != "") ? $row['telefono']: '<span class="text-muted">Sin registrar</span>'; ?></dd>

								<dt class="col-sm-5">Teléfono urgencias</dt>
								<dd class="col-sm-7"><?php echo ($row['telefonourgencia'] != "") ? $row['telefonourgencia']: '<span class="text-muted">Sin registrar</span>'; ?></dd>

							</dl>

						</div><!-- /.col-sm-6 -->

						<div class="col-sm-6">

							<dl class="row">
								<dt class="col-sm-5"><abbr data-bs="tooltip" title="Número de Identificación Escolar">N.I.E.</abbr></dt>
								<dd class="col-sm-7"><?php echo ($row['claveal'] != "") ? $row['claveal']: '<span class="text-muted">Sin registrar</span>'; ?></dd>

								<dt class="col-sm-5">Nº Expediente</dt>
								<dd class="col-sm-7"><?php echo ($row['numeroexpediente'] != "") ? $row['numeroexpediente']: '<span class="text-muted">Sin registrar</span>'; ?></dd>

								<dt class="col-sm-5">Año académico</dt>
								<dd class="col-sm-7"><?php echo $c_escolar; ?></dd>

								<dt class="col-sm-5">Curso</dt>
								<dd class="col-sm-7"><?php echo ($row['curso'] != "") ? $row['curso']: '<span class="text-muted">Sin registrar</span>'; ?></dd>

								<dt class="col-sm-5">Unidad</dt>
								<dd class="col-sm-7"><?php echo ($row['unidad'] != "") ? $row['unidad']: '<span class="text-muted">Sin registrar</span>'; ?></dd>

								<dt class="col-sm-5">Tutor</dt>
								<dd class="col-sm-7"><?php echo ($tutor != "") ? mb_convert_case($tutor, MB_CASE_TITLE, 'UTF-8'): '<span class="text-muted">Sin registrar</span>'; ?></dd>

								<dt class="col-sm-5">Repetidor/a</dt>
								<dd class="col-sm-7"><?php echo ($row['matriculas'] > 1) ? 'Sí': 'No'; ?></dd>

								<dt class="col-sm-5">Representante legal</dt>
								<dd class="col-sm-7"><?php echo ($row['padre'] != "") ? $row['padre']: '<span class="text-muted">Sin registrar</span>'; ?></dd>

								<dt class="col-sm-5">Correo electrónico</dt>
									<?php
									if ($row['correo'] != "") {
										$correo = $row['correo'];
									}
									elseif($row2['correo'] != "") {
										$correo = $row2['correo'];
									}
									else {
										$correo = '<span class="text-muted">Sin registrar</span>';
									}
									?>
								<dd class="col-sm-7"><?php echo $correo ?></dd>

								<?php if (isset($config['convivencia']['puntos']['habilitado']) && $config['convivencia']['puntos']['habilitado']): ?>
								<dt class="col-sm-5">Puntos</dt>
								<dd class="col-sm-7"><?php echo sistemaPuntos($row['claveal']); ?></dd>
								<?php endif; ?>
							</dl>

						</div><!-- /.col-sm-6 -->

					</div><!-- /.row -->

					<button class="btn btn-link btn-block" id="collapseButtonCredenciales" type="button" data-toggle="collapse" data-target="#collapseCredenciales" aria-expanded="false" aria-controls="collapseCredenciales"><span class="h6 mb-0 pb-0">Mostrar más <i class="fas fa-chevron-down fa-fw"></i></span></button>

					<div class="collapse pb-3" id="collapseCredenciales">
						<hr>

						<div class="row">
							<div class="col-sm-6">
								<h6 class="mb-3">
									Acceso a plataforma Moodle del Centro <a href="http://www.juntadeandalucia.es/averroes/centros-tic/<?php echo $config['centro_codigo']; ?>/moodle2/" target="_blank"><i class="fas fa-external-link-alt ml-1"></i></a>
								</h6>

								<dl class="row">
									<dt class="col-sm-5">Usuario</dt>
									<dd class="col-sm-7"><?php echo $usuario_moodle; ?></dd>

									<dt class="col-sm-5">Contraseña</dt>
									<dd class="col-sm-7"><?php echo $pass_moodle; ?></dd>
								</dl>
							</div>

							<?php if (isset($config['mod_centrotic_gsuite']) && $config['mod_centrotic_gsuite'] || isset($config['mod_centrotic_office365']) && $config['mod_centrotic_office365']): ?>
							<div class="col-sm-6">
								<h6 class="mb-3">
									<?php if (isset($config['mod_centrotic_gsuite']) && $config['mod_centrotic_gsuite']): ?>
									Acceso a Gmail / Classroom <a href="https://classroom.google.com/a/<?php echo $_SERVER['SERVER_NAME']; ?>" target="_blank"><i class="fas fa-external-link-alt ml-1"></i></a> 
									<?php endif; ?>
									<?php if (isset($config['mod_centrotic_office365']) && $config['mod_centrotic_office365']): ?>
									Microsoft 365 <a href="https://login.microsoftonline.com/?whr=<?php echo $_SERVER['SERVER_NAME']; ?>" target="_blank"><i class="fas fa-external-link-alt ml-1"></i></a>
									<?php endif; ?>
								</h6>

								<dl class="row">
									<dt class="col-sm-5">Usuario</dt>
									<dd class="col-sm-7"><?php echo $correo_gsuite; ?></dd>

									<dt class="col-sm-5">Contraseña</dt>
									<dd class="col-sm-7"><?php echo $pass_gsuite; ?></dd>
								</dl>
							</div>
							<?php endif; ?>

							<div class="col-sm-12">
								<small class="text-muted">Las credenciales que aparecen en esta página son de carácter informativo. Es posible que el Centro educativo no le haya dado de alta en todas las plataformas.</small>
							</div>

						</div>
					</div>

					<br>

					<?php if (($_SESSION['alumno_primaria'] == 1 OR $_SESSION['alumno_secundaria'] == 1) AND date('Y-m-d')>$config['matriculas']['fecha_fin'] AND date('m')<'09'){ 

						if($_SESSION['alumno_primaria'] == 1){ $_form_action="matriculas.php";}
						if($_SESSION['alumno_secundaria'] == 1){ $_form_action="matriculas_bach.php";}
					?>

					<form class="" action="./matriculas/<?php echo $_form_action; ?>" method="post" target="_blank">
						<input type="hidden" name="curso" value="<?php echo $_curso_matricula; ?>">
						<table class="table table-bordered">
							<tbody>
								<tr class="d-flex">
									<td class="col-md-2 align-middle bg-secondary text-white"><strong><?php echo $dia_matricula_ini; ?> - <?php echo $dia_matricula_fin; ?></strong></td>
									<td class="col-md-7 align-middle"><button type="submit" name="rellenarMatricula" class="btn btn-link btn-sm m-0">Solicitud de matrícula en <?php echo $_form_descripcion; ?></button></td>
									<td class="d-none d-md-table-cell col-md-3 text-center align-middle"><button type="submit" name="rellenarMatricula" class="btn btn-secondary btn-sm m-0">Imprimir documento</button></td>
								</tr>
							</tbody>
						</table>
					</form>
					<br>

					<?php } ?>

				</div><!-- /.col-sm-10 -->

			</div><!-- /.row -->
			</div><!-- /.well -->

			<?php if ((isset($config['mod_matriculacion']) && $config['mod_matriculacion']) && (date('Y-m-d') >= $config['matriculas']['fecha_inicio'] && date('Y-m-d') <= $config['matriculas']['fecha_fin'] && (stristr($curso, "Bachillerato") || stristr($curso, "E.S.O") || stristr($curso, "Educ. Prima.")))): $_SESSION['pasa_matricula']=1; ?>

			<br>
			<div class="row mb-3">
				<div class="col-12">

					<h3>Trámites disponibles</h3>

					<form class="" action="./matriculas/<?php echo $_form_action; ?>" method="post" target="_blank">
						<input type="hidden" name="curso" value="<?php echo $_curso_matricula; ?>">
						<table class="table table-bordered">
							<tbody>
								<tr class="d-flex">
									<td class="col-md-2 align-middle bg-secondary text-white"><strong><?php echo $dia_matricula_ini; ?> - <?php echo $dia_matricula_fin; ?></strong></td>
									<td class="col-md-8 align-middle"><button type="submit" name="rellenarMatricula" class="btn btn-link btn-sm m-0">Solicitud de matrícula en <?php echo $_form_descripcion; ?></button></td>
									<td class="d-none d-md-table-cell col-md-2 text-center align-middle"><button type="submit" name="rellenarMatricula" class="btn btn-secondary btn-sm m-0">Rellenar</button></td>
								</tr>
							</tbody>
						</table>
					</form>
					<br>
					<table class="table table-bordered">
							<tbody>
								<tr class="d-flex">
									<td class="col-md-2 align-middle bg-secondary text-white"><strong><?php echo $dia_matricula_ini; ?> - <?php echo $dia_matricula_fin; ?></strong></td>
									<td class="col-md-8 align-middle"><button name="rellenarMatricula" class="btn btn-link btn-sm m-0">Pago del Seguro escolar del curso 2020-2021 (<u>obligatorio</u>)</button>
																		<!-- Button trigger modal -->
								    	<a href="#" class="btn btn-warning btn-sm pull-right hidden-print" data-toggle="modal" data-target="#modalAyuda">
								    		<span class="fas fa-question fa-sm"></span>
								    	</a>

								  		<!-- Modal -->
								  		<div class="modal fade" id="modalAyuda" tabindex="-1" role="dialog" aria-labelledby="modal_ayuda_titulo" aria-hidden="true">
								  			<div class="modal-dialog modal-lg">
								  				<div class="modal-content">
								  					<div class="modal-header">
								  						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
								  						<h4 class="modal-title" id="modal_ayuda_titulo">Pago del seguro escolar del curso 2020-21</h4>
								  					</div>
								  					<div class="modal-body">
								  						<p class="text-left">
								  							La tasa obligatoria del seguro escolar (para el alumnado de todos los niveles <u><em>excepto 1º y 2º de ESO</em></u>), se abonará durante el periodo de matriculación establecido para cada caso.  La cuantía por alumno es de 1,12 euros  y se realizará por pasarela bancaria accediendo a la Secretaría Virtual en el siguiente enlace: <a href="http://lajunta.es/seguroescolar" target="_blank">http://lajunta.es/seguroescolar</a>. El código del IES Monterroso es 29002885.</p>
								  							<p>En el caso de que el alumno/a esté <u><em>actualmente cursando 2º ESO</em></u>, el sistema de pago telemático no permite pagar porque aún no está matriculado en 3º ESO. Por lo tanto, estos casos realizarán el pago durante el mes de <u><em>septiembre</em></u>, una vez que comience el periodo lectivo en 3º de ESO.
								  						</p>
								  					</div>
								  					<div class="modal-footer">
								  						<button type="button" class="btn btn-default" data-dismiss="modal">Entendido</button>
								  					</div>
								  				</div>
								  			</div>
								  		</div>
									</td>
									<td class="d-none d-md-table-cell col-md-2 text-center align-middle"><a href="http://lajunta.es/seguroescolar/" target="_blank" class="btn btn-secondary btn-sm m-0">Pagar</a></td>
								</tr>
							</tbody>
						</table>


				</div>
			</div>
			<?php endif; ?>

			<?php if ($_SESSION['alumno_primaria'] <> 1 AND $_SESSION['alumno_secundaria'] <> 1 AND date('m')=='06' AND date('d')>='08' AND date('d')<='12'): $_SESSION['pasa_audiencia']=1; ?>

			<div class="row mb-3">
				<div class="col-12">

					<form class="" action="./audiencia.php" method="post" target="_blank">
						<table class="table table-bordered">
							<tbody>
								<tr class="d-flex">
									<td class="col-md-2 align-middle bg-secondary text-white"><strong><?php echo $dia_audiencia_ini; ?> - <?php echo $dia_audiencia_fin; ?></strong></td>
									<td class="col-md-8 align-middle"><button type="submit" name="rellenarAudiencia" class="btn btn-link btn-sm m-0">Trámite de audiencia para la evaluación ordinaria del alumno</button></td>
									<td class="d-none d-md-table-cell col-md-2 text-center align-middle"><button type="submit" name="rellenarAudiencia" class="btn btn-secondary btn-sm m-0">Rellenar</button></td>
								</tr>
							</tbody>
						</table>
					</form>

				</div>
			</div>
			<br>
			<?php endif; ?>

			<?php if($numeroMensajesRecibidos2>0): ?>
			<div class="row mb-3">
				<div class="col-12">

						<table class="table table-bordered">
							<tbody>
								<tr class="d-flex">
									<td class="col-md-12 align-middle bg-info text-white text-center"><h6><span class="fas fa-exclamation-triangle fa-lg"> </span> Tiene mensajes sin leer.</h6></td>
								</tr>
							</tbody>
						</table>

				</div>
			</div>
		<?php endif; ?>


			<?php 
			$matr_contr = mysqli_fetch_array(mysqli_query($db_con,"select curso, grupo_actual from matriculas where claveal = '$claveal'"));
			$matr_bach_contr = mysqli_fetch_array(mysqli_query($db_con,"select curso, grupo_actual from matriculas_bach where claveal = '$claveal'"));
			if (!empty($matr_contr['curso'])) {
				$curso_matr = $matr_contr['curso'];
				$unidad_matr = $matr_contr['curso']." - ".$matr_contr['grupo_actual'];
			}
			elseif(!empty($matr_bach_contr['curso'])) {
				$curso_matr = $matr_bach_contr['curso'];
				$unidad_matr = $matr_bach_contr['curso']." - ".$matr_contr['grupo_actual'];
			}
			else{
				$unidad_matr = "";
			}

			?>

			<?php if ($bd_alma == "alma"): ?>
			<div class="row">

				<div class="col-sm-12">

					<style class="text/css">
					@media print {
						.body {
							margin: 0;
							padding: 0;
						}
						#intro {
							display: none;
						}
						.tab-content>.tab-pane {
							display: block;
							visibility: inherit;
						}
					}
					</style>

					<?php
					if (isset($_GET['anio']) AND isset($_GET['mes'])) {
						$link_active_faltas = "";
						$link_active_calendario = "active";
					}
					else{
						$link_active_faltas = "active";
						$link_active_calendario = "";
					}
					?>
					<ul id="nav_alumno" class="nav nav-tabs nav-tabs-neutral justify-content-center bg-milanored" role="tablist">
						<?php $tab1 = 1; ?>
						<li class="nav-item"><a class="nav-link <?php echo $link_active_faltas; ?>" href="#asistencia" role="tab" data-toggle="tab">Asistencia</a></li>
						<li class="nav-item"><a class="nav-link" href="#convivencia" role="tab" data-toggle="tab">Convivencia</a></li>
						<li class="nav-item"><a class="nav-link" href="#evaluaciones" role="tab" data-toggle="tab">Evaluaciones</a></li>
						<?php if ($muestra_evaluables == 1): ?><li class="nav-item"><a class="nav-link <?php echo $link_active_calendario; ?>" href="#evaluables" role="tab" data-toggle="tab">Actividades</a></li><?php endif; ?>
						<li class="nav-item"><a class="nav-link" href="#horario" role="tab" data-toggle="tab">Horario</a></li>
						<?php if (isset($config['alumnado']['ver_informes_tutoria']) && $config['alumnado']['ver_informes_tutoria']): ?>
						<li class="nav-item"><a class="nav-link" href="#tutoria" role="tab" data-toggle="tab">Tutoría</a></li>
						<?php endif; ?>
						<li class="nav-item"><a class="nav-link" href="#mensajes" role="tab" data-toggle="tab">Mensajes<?php echo ($numeroMensajesRecibidos2) ? ' <span class="badge">'.$numeroMensajesRecibidos2.'</span>' : ''; ?></a></li>
						<?php $rutaRecursos = $config['mod_documentos_dir'] . "/Recursos/" . $row['unidad']; ?>
						<?php if (file_exists($rutaRecursos)): ?>
						<li class="nav-item"><a class="nav-link" href="<?php echo WEBCENTROS_DOMINIO; ?>/documentos/index.php?dir=/Recursos/<?php echo $row['unidad']; ?>">Recursos</a></li>
						<?php endif; ?>
						<?php if ((isset($config['mod_matriculacion']) && $config['mod_matriculacion']) && date('m') >= 6 && date('m') <= 10): ?>
						<?php if (isset($estaMatriculadoBachillerato) && $estaMatriculadoBachillerato == 1): ?>
						<li class="nav-item"><a class="nav-link" href="./matriculas/matriculas_bach.php?curso=<?php echo $curso; ?>" target="_blank">Matrícula</a></li>
						<?php elseif (isset($estaMatriculadoESO) && $estaMatriculadoESO == 1): ?>
						<li class="nav-item"><a class="nav-link" href="./matriculas/matriculas.php?curso=<?php echo $curso; ?>" target="_blank">Matrícula</a></li>
						<?php endif; ?>
						<?php endif; ?>
					</ul>

					<br>
					

					<div class="tab-content">
						<div class="tab-pane <?php echo $link_active_faltas; ?>" id="asistencia">
						<?php include("faltas.php"); ?>
						<?php include("faltasd.php"); ?>
						</div>
						<div class="tab-pane" id="convivencia">
						<?php include("fechorias.php"); ?>
						</div>
						<div class="tab-pane" id="evaluaciones">
						<?php include("notas.php"); ?>
						</div>
						<?php if ($muestra_evaluables == 1): ?>
						<div class="tab-pane <?php echo $link_active_calendario; ?>" id="evaluables">
						<?php include("evaluables.php"); ?>
						</div>
						<?php endif; ?>
						<div class="tab-pane" id="horario">
						<?php include("horarios.php"); ?>
						</div>
						<?php if (isset($config['alumnado']['ver_informes_tutoria']) && $config['alumnado']['ver_informes_tutoria']): ?>
						<div class="tab-pane" id="tutoria">
						<?php include("tutoria.php"); ?>
						</div>
						<?php endif; ?>
						<div class="tab-pane" id="mensajes">
						<?php include("mensajes.php"); ?>
						</div>
						<div class="tab-pane" id="recursos">
						
						</div>
					</div>

				</div>

			</div><!-- /.row -->
			<?php endif; ?>

			<!-- MODAL SUBIDA FOTOGRAFIA -->
			<div class="modal fade" id="subirFotografia" tabindex="-1" role="dialog">
				<div class="modal-dialog" role="document">
					<form action="" method="post" enctype="multipart/form-data">
						<div class="modal-content">
							<div class="modal-header justify-content-center">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<h4 class="title title-up">Subir o cambiar fotografía</h4>
							</div>
							<div class="modal-body">
								<div class="bg-clouds p-3 rounded">
									<div class="form-group">
										<label for="foto">Suba o actualice la fotografía. El formato debe ser JPEG.</label>
										<input type="file" class="form-control" id="foto" name="foto" accept="image/jpg">
									</div>
								</div>

								<hr>

								<div class="help-block">
									<p>La foto debe cumplir la norma especificada:</p>
									<ul>
										<li>Tener el fondo de un único color, liso y claro.</li>
										<li>La foto ha de ser reciente y tener menos de 6 meses de antigüedad.</li>
										<li>Foto tipo carnet, la imagen no puede estar inclinada, tiene que mostrar la cara claramente de frente.</li>
										<li>Fotografía de cerca que incluya la cabeza y parte superior de los hombros, la cara ocuparía un 70-80% de la fotografía.</li>
										<li>Fotografía perfectamente enfocada y clara.</li>
									</ul>
								</div>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
								<button type="submit" name="subirFotografia" class="btn btn-primary">Subir fotografía</button>
							</div>
						</div><!-- /.modal-content -->
					</form>
				</div><!-- /.modal-dialog -->
			</div><!-- /.modal -->

			<?php else: ?>

			<div class="justify-content-center">
				<p class="lead text-muted text-center p-5">No hay información de este alumno/a</p>
			</div>

			<?php endif; ?>

		</div><!-- /.container -->
	</div>

	<?php include("../inc_pie.php"); ?>

	<script>
		<?php if(isset($_GET['mod']) && $_GET['mod'] == 'recursos'): ?>
		$('#nav_alumno a[href="#recursos"]').tab('show');
		<?php elseif (isset($_GET['mod']) && $_GET['mod'] == 'mensajes'): ?>
		$('#nav_alumno a[href="#mensajes"]').tab('show');
		<?php endif; ?>

		$(document).ready(function() {
			$('#collapseCredenciales').on('show.bs.collapse', function () {
			  $('#collapseButtonCredenciales').html('<span class="h6 mb-0 pb-0">Mostrar menos <i class="fas fa-chevron-up fa-fw"></i></span>');
			});
			$('#collapseCredenciales').on('hidden.bs.collapse', function () {
			  $('#collapseButtonCredenciales').html('<span class="h6 mb-0 pb-0">Mostrar más <i class="fas fa-chevron-down fa-fw"></i></span>');
			});
		});
	</script>

</body>
</html>
