<?php
require_once("../../bootstrap.php");
require_once("../../config.php");

$profesores = array();
$result = mysqli_query($db_con, "SELECT `nombre` FROM `departamentos` WHERE `cargo` LIKE '%b%' ORDER BY `nombre` ASC");
while ($row = mysqli_fetch_array($result)) {
    array_push($profesores, rgpdNombreProfesor($row['nombre']));
}
mysqli_free_result($result);

$pagina['titulo'] = 'Aula de Convivencia';

// SEO
//$pagina['meta']['robots'] = 0;
//$pagina['meta']['canonical'] = 0;
$pagina['meta']['meta_title'] = $pagina['titulo'];
$pagina['meta']['meta_description'] = "Aula de Convivencia";
$pagina['meta']['meta_type'] = "website";
$pagina['meta']['meta_locale'] = "es_ES";

include("../../inc_menu.php");
?>

    <div class="section">
        <div class="container">

          <div class="row justify-content-md-center">

            <div class="col-md-9">

              <p>La creación de un Aula de Convivencia surge porque estamos convencidos de la necesidad de atender al alumnado que presenta problemas relacionados con la convivencia escolar, en un espacio que permitiera una atención personal desde un modelo de aula integradora y reflexiva con un tiempo destinado a ello y con la participación de todos los sectores educativos. Sobre todo porque creemos en la prevención y modificación del comportamiento disruptivo.</p>

              <h4>Objetivos</h4>

              <p>Trabajamos con unos objetivos generales en torno a los cuales desarrollamos nuestras actuaciones:</p>

              <ul>
                <li><strong>Prevenir</strong> posibles conductas que interfieran en el clima del Centro.</li>
                <li><strong>Actuar</strong> directamente sobre situaciones conflictivas ya existentes con la participación de todos los sectores educativos.</li>
                <li><strong>Conseguir la implicación de las familias</strong> del alumnado atendido, desde el convencimiento de que debemos actuar conjuntamente, con criterios y expectativas comunes.</li>
                <li><strong>Trabajar de forma multidisciplinar</strong> con la colaboración de entidades externas.</li>
              </ul>

              <h4>¿Cuándo se deriva a un alumno/a al Aula de Convivencia?</h4>

              <p>El alumnado atendido podrá ser derivado al aula por diferentes cauces: Orientación, jefatura de estudios, tutores/as y/o equipo educativo, familia y /o el propio alumnado, una vez que el tutor/a de su clase haya agotado los recursos propios de la función tutorial.</p>

              <p>Una vez hecho esto, al alumno/a se le asignará un Tutor/a del Aula de Convivencia (TAC) que realizará la intervención con el alumnado de forma directa, en ningún caso supliendo al tutor de clase, pero siempre coordinando las actuaciones.</p>

              <h4>¿Qué se hace?</h4>

              <p>Realizar entrevistas con el alumnado para reflexionar sobre sus propias conductas, analizándolas, contemplando las causas, situaciones y sentimientos que se ponen en juego. Intentamos que sean capaces de prever las consecuencias para sí mismos y los demás, así como proponer soluciones desde la resolución pacífica de conflictos.</p>

              <p>Al alumnado se le hace un seguimiento temporal que dependerá de cada situación y de la evolución.</p>

              <h4>Equipo de Convivencia</h4>

              <?php if (isset($config['convivencia']['mostrar_profesores']) && $config['convivencia']['mostrar_profesores'] == 1): ?>
              <p>El Aula de Convivencia es atendida por profesorado voluntario del Centro y lo componen:</p>

              <ul>
                <?php foreach ($profesores as $profesor): ?>
                <li><?php echo $profesor; ?></li>
                <?php endforeach; ?>
              </ul>
              <?php else: ?>
              <p>El Aula de Convivencia es atendida por profesorado voluntario del Centro.</p>
              <?php endif; ?>

            </div>

          </div>

        </div>
    </div>

    <?php include("../../inc_pie.php"); ?>
