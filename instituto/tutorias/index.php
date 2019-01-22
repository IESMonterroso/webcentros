<?php
require_once("../../bootstrap.php");
require_once("../../config.php");

$tutorias = array();
$result = mysqli_query($db_con, "SELECT DISTINCT SUBSTRING(`cursos`.`nomcurso`, 3, 12) AS `subcurso`, `unidades`.`nomunidad`, `FTUTORES`.`tutor` FROM `unidades` JOIN `cursos` ON `cursos`.`idcurso` = `unidades`.`idcurso` JOIN `FTUTORES` ON `unidades`.`nomunidad` = `FTUTORES`.`unidad` ORDER BY `subcurso` ASC, `unidades`.`nomunidad` ASC");
while ($row = mysqli_fetch_array($result)) {

  $result_horario = mysqli_query($db_con, "SELECT `dia`, `hora` FROM `horw` WHERE (`c_asig` = '117' OR `c_asig` = '279') AND (`a_grupo` = '".$row['nomunidad']."' OR `prof` = '".$row['tutor']."') LIMIT 1");
  $row_horario = mysqli_fetch_array($result_horario);
  $horario = obtenerHoraTutoria($row_horario['dia'], $row_horario['hora']);
  if (empty($horario)) $horario = '<i>Sin definir</i>';
  mysqli_free_result($result_horario);

  $cursos = array();
  $result_cursos = mysqli_query($db_con, "SELECT `cursos`.`nomcurso` FROM `cursos` JOIN `unidades` ON `cursos`.`idcurso` = `unidades`.`idcurso` WHERE `unidades`.`nomunidad` = '".$row['nomunidad']."' ORDER BY `cursos`.`nomcurso` ASC");
  while ($row_cursos = mysqli_fetch_array($result_cursos)) {
    array_push($cursos, $row_cursos['nomcurso']);
  }

  $nomtutor = rgpdNombreProfesor(nombreProfesor($row['tutor']));
  if (stristr($nomtutor, ', ') == true) {
    $exp_nomtutor = explode(', ', $nomtutor);
    $nomtutor = trim($exp_nomtutor[1]).' '.trim($exp_nomtutor[0]);
  }

  $tutoria = array(
  		'unidad'	=> $row['nomunidad'],
      'cursos'  => $cursos,
      'tutor'		=> $nomtutor,
      'horario'	=> $horario
  );

  array_push($tutorias, $tutoria);
  unset($row_horario);
  unset($horario);
}
mysqli_free_result($result);
unset($tutoria);

$pagina['titulo'] = 'Tutorías';

// SEO
//$pagina['meta']['robots'] = 0;
//$pagina['meta']['canonical'] = 0;
$pagina['meta']['meta_title'] = $pagina['titulo'];
$pagina['meta']['meta_description'] = "Tutorías y horarios de atención a padres del ".$config['centro_denominacion'];
$pagina['meta']['meta_type'] = "website";
$pagina['meta']['meta_locale'] = "es_ES";

include("../../inc_menu.php");
?>

    <div class="section">
        <div class="container">

            <div class="row">

                <div class="col-sm-12">

									<table class="table table-bordered table-striped">
										<thead>
											<tr>
												<th class="d-none d-md-table-cell">Unidad</th>
												<th class="d-none d-md-table-cell">Tutor/a</th>
												<th class="d-none d-md-table-cell">Horario de atención</th>
											</tr>
										</thead>
										<tbody>
											<?php foreach ($tutorias as $tutoria): ?>
											<tr>
												<td>
													<h6><?php echo $tutoria['unidad']; ?></h6>
                          <?php foreach ($tutoria['cursos'] as $curso): ?>
                          <small class="text-muted"><?php echo $curso; ?></small><br>
                          <?php endforeach; ?>
                          <hr class="d-block d-md-none">
                          <p class="d-block d-md-none mb-1"><strong>Tutor/a:</strong> <?php echo $tutoria['tutor']; ?></p>
                          <p class="d-block d-md-none mb-1"><strong>Horario:</strong> <?php echo $tutoria['horario']; ?></p>
												</td>
												<td class="d-none d-md-table-cell"><?php echo $tutoria['tutor']; ?></td>
												<td class="d-none d-md-table-cell"><?php echo $tutoria['horario']; ?></td>
											</tr>
											<?php endforeach; ?>
										</tbody>
									</table>

                </div>

            </div>
        </div>
    </div>

    <?php include("../../inc_pie.php"); ?>
