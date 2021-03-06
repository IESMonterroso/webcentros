<?php defined('WEBCENTROS_DIRECTORY') OR exit('No direct script access allowed'); ?>

<!-- MODULO HORARIO Y PROFESORES -->
<?php
// Asignaturas
mysqli_query($db_con,"CREATE TABLE IF NOT EXISTS `asig_tmp` (
  `claveal` varchar(12) collate latin1_spanish_ci NOT NULL,
  `codigo` int(6) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci");
mysqli_query($db_con,"TRUNCATE TABLE asig_tmp");

$comb = mysqli_query($db_con,"select combasi from alma where claveal = '$claveal'");
$combasi = mysqli_fetch_array($comb);
$tr_combasi = explode(":",$combasi[0]);
foreach ($tr_combasi as $codigo){
	 mysqli_query($db_con,"insert into asig_tmp(claveal, codigo) VALUES ('$claveal','$codigo')");
}
?>
<a name="horario"></a>
<br>
<h3>Horario de la unidad</h3>
<br>

<div class="table-responsive">
	<table class="table table-bordered table-striped">
		<thead>
			<tr>
				<th>&nbsp;</th>
				<th>Lunes</th>
				<th>Martes</th>
				<th>Miércoles</th>
				<th>Jueves</th>
				<th>Viernes</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$hr = mysqli_query($db_con,"select hora_inicio, hora_fin, hora from tramos ORDER BY idjornada ASC, horini ASC");
				while ($hor = mysqli_fetch_array($hr)):
					$desc = substr($hor[0],0,5)." - ".substr($hor[1],0,5);
					$hora = $hor[2];
			?>
			<tr>
				<th nowrap><?php echo $desc; ?></th>
				<?php for($i = 1; $i < 6; $i++): ?>
				<td width="20%">
					<?php $result = mysqli_query($db_con, "SELECT DISTINCT asig, c_asig, a_aula, n_aula FROM horw WHERE (a_grupo=(select unidad from alma where claveal = '$claveal')) AND dia='$i' AND hora='$hora' and c_asig in (select codigo from asig_tmp)");?>
					<?php while($row = mysqli_fetch_array($result)): ?>
          <?php if ($pos = strpos($combasi, $row[0]) !== false): ?>
          <?php echo $row[0]."<div class='text-success' data-bs='tooltip' title='".$row[3]."'>".$row[2]."</div>"; ?>
          <?php endif; ?>
					<?php endwhile; ?>
				</td>
				<?php endfor; ?>
			</tr>
			<?php endwhile; ?>
		</tbody>
	</table>
</div>
<?php
mysqli_query($db_con,"DROP TABLE asig_tmp");
?>
<br>
<h3>Equipo educativo</h3>
<br>

<div class="table-responsive">
	<table class="table table-striped">
		<thead>
			<tr>
				<th>Asignatura</th>
				<th>Profesor/a</th>
			</tr>
		</thead>
		<tbody>
		<?php $result = mysqli_query($db_con, "SELECT combasi, unidad, curso FROM alma WHERE claveal='$claveal' LIMIT 1"); ?>
		<?php $combasi = mysqli_fetch_array($result); ?>
		<?php $exp_combasi = explode(":", $combasi['combasi']); ?>
		<?php foreach($exp_combasi as $codigo): ?>
		<?php $result = mysqli_query($db_con, "SELECT DISTINCT `profesores`.`materia`, `profesores`.`profesor`, `c_profes`.`correo` FROM `profesores`, `asignaturas`, `c_profes` WHERE `profesores`.`profesor` = `c_profes`.`profesor` AND `profesores`.`materia`= `asignaturas`.`nombre` AND `profesores`.`grupo` = '".$combasi['unidad']."' AND `asignaturas`.`codigo` = '$codigo' AND `asignaturas`.`abrev` NOT LIKE '%\_%' AND `asignaturas`.`curso` = '".$combasi['curso']."' ORDER BY `profesores`.`materia` ASC"); ?>
			<?php while($row = mysqli_fetch_array($result)): ?>
			<tr>
				<td><?php echo $row['materia']; ?></td>
				<td class="text-info">
					<?php echo $row['profesor']; ?>
					<?php if (strpos($row['correo'], '@'.'juntadeandalucia.es') !== false || strpos($row['correo'], '@'.$_SERVER['SERVER_NAME']) !== false): ?>
					<div class="text-muted mt-2" style="font-size: 0.8rem;">
						<a href="mailto:<?php echo $row['correo']; ?>"><i class="fas fa-envelope fa-fw"></i> <?php echo $row['correo']; ?></a>
					</div>
					<?php endif; ?>
				</td>
			</tr>
			<?php endwhile; ?>
		<?php endforeach; ?>
		</tbody>
	</table>
</div>
<!-- FIN MODULO HORARIO Y PROFESORES -->
