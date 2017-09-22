<?php defined('INTRANET_DIRECTORY') OR exit('No direct script access allowed'); 

if(isset($_POST['enviar'])) {
	
	$asunto = trim(mysqli_real_escape_string($db_con,	$_POST['asunto']));
	$mensaje = trim(mysqli_real_escape_string($db_con, $_POST['mensaje']));
	
	if (! empty($asunto) && ! empty($mensaje)) {
		
		$result = mysqli_query($db_con, "INSERT INTO mensajes (dni, claveal, asunto, texto, ip, correo, unidad) VALUES ('$dni_responsable_legal', '".$_SESSION['claveal']."', '$asunto', '$mensaje', '".$_SESSION['direccion_ip']."', '".$_SESSION['correo']."', '$unidad')");
		
		if(! $result) {
			$msg_error = "Los campos del formulario son obligatorios.";
		}
		else {
			$msg_success = "El mensaje ha sido enviado correctamente.";
		}
		
	}
	else {
		$msg_error = "Los campos del formulario son obligatorios.";
	}
	
}

if(isset($_POST['leido'])){
	$verifica = $_POST['verifica'];
	
	mysqli_query($db_con, "UPDATE mens_profes SET recibidoprofe = '1' WHERE id_profe = '".$_POST['verifica']."'");
	
	$asunto = "Mensaje de confirmación";
	$mensaje = "El mensaje enviado a los padres del alumno/a $nombrepil $apellido ha sido recibido y leído por estos en la web del centro.";
	
	mysqli_query($db_con, "INSERT INTO mensajes (dni, claveal, asunto, texto, ip, correo, unidad) VALUES ('$dni_responsable_legal', '".$_SESSION['claveal']."', '$asunto', '$mensaje', '".$_SESSION['direccion_ip']."', '".$_SESSION['correo']."', '$unidad')");
}  

?>

<a name="mensajes"></a>
<h3>Mensajes</h3>

<br>

<?php if(isset($msg_error)): ?>
<div class="alert alert-danger">
	<?php echo $msg_error; ?>
</div>
<?php endif; ?>

<?php if(isset($msg_success)): ?>
<div class="alert alert-success">
	<?php echo $msg_success; ?>
</div>
<?php endif; ?>

<div class="row">
	
	<div class="col-sm-5">
		
		<legend>Mensajes recibidos</legend>
		
		<?php $query_mensajes = mysqli_query($db_con, "SELECT mens_texto.id, ahora, asunto, texto, c_profes.profesor, (SELECT recibidoprofe FROM mens_profes WHERE id_texto = mens_texto.id AND profesor LIKE '%$apellido, $nombrepil%' OR profesor LIKE '%".$_SESSION['claveal']."%' LIMIT 1) AS recibidoprofe FROM mens_texto JOIN c_profes ON mens_texto.origen = c_profes.idea WHERE destino LIKE '%$apellido, $nombrepil%' OR destino LIKE '%".$_SESSION['claveal']."%' AND asunto NOT LIKE 'Mensaje de confirmación' ORDER BY ahora DESC"); ?>
		<?php if(mysqli_num_rows($query_mensajes)): ?>
		<div class="table-responsive">
			<table class="table table-bordered table-striped">
			  <tbody>
			    <?php while ($mensajes_recibidos = mysqli_fetch_array($query_mensajes)): ?>
			    <tr<?php echo (! $mensajes_recibidos['recibidoprofe']) ? ' class="success"' : ''; ?>>
			      <td><a href="#" data-toggle="modal" data-target="#recibidos_<?php echo $mensajes_recibidos['id']; ?>" style="display: block;"><?php echo $mensajes_recibidos['asunto'];  ?><br /><small class="text-muted"><?php echo $mensajes_recibidos['profesor']; ?> - <?php echo $mensajes_recibidos['ahora']; ?></small></a></td>
			    </tr>
			    <?php endwhile; ?>
			  </tbody>
			</table>
		</div>
		
		<?php else: ?>
		
		<h3 class="text-muted">No ha recibido ningún mensaje.</h3>
		<br>
		
		<?php endif; ?>
		
		<br>
		
		<legend>Mensajes enviados</legend>
		
		<?php $query_enviados = mysqli_query($db_con, "SELECT id, ahora, asunto, texto, recibidotutor FROM mensajes WHERE claveal = '".$_SESSION['claveal']."' AND asunto NOT LIKE 'Mensaje de confirmaci�n'"); ?>
		<?php if(mysqli_num_rows($query_enviados)): ?>
		<div class="table-responsive">
			<table class="table table-bordered table-striped">
			  <tbody>
			    <?php while ($mensajes_enviados = mysqli_fetch_array($query_enviados)): ?>
			    <tr>
			      <td>
			      	<a href="#" data-toggle="modal" data-target="#enviados_<?php echo $mensajes_enviados['id']; ?>" style="display: block;">
			      		<?php echo $mensajes_enviados['asunto'];  ?><br />
			      		<small class="text-muted"><?php echo $apellido.', '.$nombrepil; ?> - <?php echo $mensajes_enviados['ahora']; ?></small>
			      		
			      		<?php
			      		if ($mensajes_enviados['recibidotutor']) {
			      			$leido_class = "text-success";
			      			$leido = "El tutor ha leido el mensaje";
			      			
			      			mysqli_query($db_con, "UPDATE mensajes SET recibidopadre = '1' WHERE id='".$mensajes_enviados['id']."' LIMIT 1");
			      		} 
			      		else {
			      			$leido_class = "text-muted";
			      			$leido = "El tutor aún no ha leido el mensaje";
			      		}
			      		?>
			      		<span class="fa fa-check fa-fw pull-right <?php echo $leido_class; ?>" data-toggle="tooltip" title="<?php echo $leido; ?>"></span>
			      	</a>
			      </td>
			    </tr>
			    <?php endwhile; ?>
			  </tbody>
			</table>
		</div>
		
		<?php else: ?>
		
		<h3 class="text-muted">No ha enviado ningún mensaje.</h3>
		<br>
		
		<?php endif; ?>
		
		
	</div>
	
	<div class="col-sm-6 col-sm-offset-1">
		
		<form method="post" action="index.php?mod=mensajes">
			
			<fieldset>
				<legend>Contactar con el tutor del alumno/a</legend>
				
				<div class="form-group">
					<label for="asunto">Asunto</label>
					<input type="text" class="form-control" id="asunto" name="asunto">
				</div>
				
				<div class="form-group">
					<label for="mensaje">Mensaje</label>
					<textarea type="text" class="form-control" id="mensaje" name="mensaje" rows="5"></textarea>
				</div>
				
				<button type="submit" class="btn btn-primary" name="enviar">Enviar mensaje</button>
				
			</fieldset>
			
		</form>
		
	</div>

</div>

<?php $query_mensajes = mysqli_query($db_con, "SELECT mens_texto.id, ahora, asunto, texto, c_profes.profesor, (SELECT recibidoprofe FROM mens_profes WHERE id_texto = mens_texto.id AND profesor LIKE '%$apellido, $nombrepil%' OR profesor LIKE '%".$_SESSION['claveal']."%' LIMIT 1) AS recibidoprofe, (SELECT id_profe FROM mens_profes WHERE id_texto = mens_texto.id AND profesor LIKE '%$apellido, $nombrepil%' OR profesor LIKE '%".$_SESSION['claveal']."%' LIMIT 1) AS id_profe FROM mens_texto JOIN c_profes ON mens_texto.origen = c_profes.idea WHERE destino LIKE '%$apellido, $nombrepil%' OR destino LIKE '%".$_SESSION['claveal']."%' AND asunto NOT LIKE 'Mensaje de confirmaci�n' ORDER BY ahora DESC"); ?>
<?php if(mysqli_num_rows($query_mensajes)): ?>
<?php while ($mensajes_recibidos = mysqli_fetch_array($query_mensajes)): ?>
<div id="recibidos_<?php echo $mensajes_recibidos['id']; ?>" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><?php echo $mensajes_recibidos['asunto']; ?><br><small>Enviado por <?php echo $mensajes_recibidos['profesor']; ?> el <?php echo $mensajes_recibidos['ahora']; ?></small></h4>
      </div>
      <div class="modal-body">
        <?php echo stripslashes($mensajes_recibidos['texto']); ?>
      </div>
      <div class="modal-footer">
      	<?php if(! $mensajes_recibidos['recibidoprofe']): ?>
      	<form method="post" action="index.php?mod=mensajes">
	      	<input type="hidden" name="verifica" value="<?php echo $mensajes_recibidos['id_profe']; ?>">
	        <button type="submit" class="btn btn-default" name="leido">Cerrar</button>
	      </form>
	      <?php else: ?>
	      <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
	      <?php endif; ?>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<?php endwhile; ?>
<?php endif; ?>

<?php $query_enviados = mysqli_query($db_con, "SELECT id, ahora, asunto, texto, recibidotutor FROM mensajes WHERE claveal = '".$_SESSION['claveal']."' AND asunto NOT LIKE 'Mensaje de confirmaci�n'"); ?>
<?php if(mysqli_num_rows($query_enviados)): ?>
<?php while ($mensajes_enviados = mysqli_fetch_array($query_enviados)): ?>
<div id="enviados_<?php echo $mensajes_enviados['id']; ?>" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><?php echo $mensajes_enviados['asunto']; ?><br><small>Enviado por <?php echo $apellido.', '.$nombrepil; ?> el <?php echo $mensajes_enviados['ahora']; ?></small></h4>
      </div>
      <div class="modal-body">
        <?php echo stripslashes($mensajes_enviados['texto']); ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<?php endwhile; ?>
<?php endif; ?>