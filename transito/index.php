<?php 
require_once("../bootstrap.php");
require_once("../config.php");

$pagina['titulo'] = 'Informes de tránsito <small>Curso '.$config['curso_actual'].'</small>';

// SEO
//$pagina['meta']['robots'] = 0;
//$pagina['meta']['canonical'] = 0;
$pagina['meta']['meta_title'] = $pagina['titulo'];
$pagina['meta']['meta_description'] = "Informess de tránsito  ".$config['curso_actual']." del ".$config['centro_denominacion'];
$pagina['meta']['meta_type'] = "website";
$pagina['meta']['meta_locale'] = "es_ES";

include("../inc_menu.php");
?>

<br>
<div class="section">

<div class="container">

 <div class="row justify-content-md-center">

 <div class="col-md-4">   

<div class="well well-large">

<form action="control.php" method="post" align="center" class="form-horizontal" name="form1">

<div class="form-group">
<label for="user">Selecciona Colegio </label>
<select id="user" name="user" class="form-control" required />
<option></option>
<?
	$al_primaria = "SELECT distinct colegio FROM transito_control order by colegio";
	$alum_primaria = mysqli_query($db_con,$al_primaria);
	while ($cole=mysqli_fetch_array($alum_primaria)) {
		echo "<option>$cole[0]</option>";
	}
?>
</select>
<br>
<label for="clave">	Clave del Colegio </label>
<input id="clave" type="password" name="clave" class="form-control" required />
<br />
</div>

<button type="submit" name="submit" value="Entrar" class="btn btn-lg btn-primary btn-block"><i class="fa fa-signin fa fa-white fa fa-large"></i> &nbsp;Entrar</button>
</form>
</div>

<br />
</div>
</div> 
</div>
</div>
 <?php include("../pie.php"); ?>

