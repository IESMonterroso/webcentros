<?php
require_once("../../bootstrap.php");
require_once("../../config.php");
require('../../plugins/calendar.class.php');

$curso = substr($config['curso_actual'], 0, 4);

$cal = new calendar();

$cal->enablePrettyHTML();
$cal->setStartDay(0);
$cal->disableNavigation();
$cal->disableNavigation();
$cal->enableNonMonthDays();
$cal->enableYear();

$cal->addEvent('Inicio curso ESO, Bachillerato y FP', substr($config['curso_inicio'],0,4), 9, substr($config['curso_inicio'],8,2), '#', "#FD9BB1");
$cal->addEvent('Fin días lectivos', substr($config['curso_fin'],0,4), 6, substr($config['curso_fin'],8,2), '#', "#FD9BB1");

// DIAS FESTIVOS
$result = mysqli_query($db_con, "SELECT `fecha`, `nombre`, `ambito` FROM `festivos` ORDER BY `fecha` ASC");

if (mysqli_num_rows($result)) {

    while ($row = mysqli_fetch_array($result)) {
        $fecha = explode('-', $row['fecha']);
        $fecha_anio = $fecha[0];
        substr($fecha[1],0,1)==0 ? $fecha_mes = substr($fecha[1],1,2) : $fecha_mes = $fecha[1];
        substr($fecha[2],0,1)==0 ? $fecha_dia = substr($fecha[2],1,2) : $fecha_dia = $fecha[2];

        switch ($row['ambito']) {
          case 'Andalucía': $color = "#8ECC5B"; break;
          case 'Localidad': $color = "#95E4FF"; break;
          case 'Provincial': $color = "#FEFB49"; break;
          default: $color = null; break;
        }

        $cal->addEvent($row['nombre'], $fecha_anio, $fecha_mes, $fecha_dia, '#', $color);
    }

}


$pagina['titulo'] = 'Calendario escolar <small>Curso '.$config['curso_actual'].'</small>';

// SEO
//$pagina['meta']['robots'] = 0;
//$pagina['meta']['canonical'] = 0;
$pagina['meta']['meta_title'] = $pagina['titulo'];
$pagina['meta']['meta_description'] = "Calendario escolar del curso ".$config['curso_actual']." de la provincia de ".$config['centro_provincia'];
$pagina['meta']['meta_type'] = "website";
$pagina['meta']['meta_locale'] = "es_ES";

include("../../inc_menu.php");
?>

    <div class="section">
        <div class="container">

            <div class="row">

              <div class="col-12">

                <div class="d-flex justify-content-center shadow-none p-3 mb-5 bg-light rounded">
                  <ul class="list-inline mb-0">
                    <li class="list-inline-item"><span class="fas fa-square fa-lg fa-fw" style="color: #8ECC5B;"></span> Fiesta autonómica</li>
                    <li class="list-inline-item"><span class="fas fa-square fa-lg fa-fw" style="color: #FEFB49;"></span> Fiesta provincial</li>
                    <li class="list-inline-item"><span class="fas fa-square fa-lg fa-fw" style="color: #95E4FF;"></span> Fiesta local</li>
                    <li class="list-inline-item"><span class="fas fa-square fa-lg fa-fw" style="color: #FD9BB1;"></span> Otras</li>
                  </ul>
                </div>

              </div>

            </div>

            <div class="row">

                <div class="col-lg-4">
                <?php $cal->display(9, $curso); ?>
                </div>

                <div class="col-lg-4">
                <?php $cal->display(10, $curso); ?>
                </div>

                <div class="col-lg-4">
                <?php $cal->display(11, $curso); ?>
                </div>

            </div><!-- ./row -->

            <div class="row">

                <div class="col-lg-4">
                <?php $cal->display(12, $curso); ?>
                </div>

                <div class="col-lg-4">
                <?php $cal->display(1, $curso+1); ?>
                </div>

                <div class="col-lg-4">
                <?php $cal->display(2, $curso+1); ?>
                </div>

            </div><!-- ./row -->

            <div class="row">

                <div class="col-lg-4">
                <?php $cal->display(3, $curso+1); ?>
                </div>

                <div class="col-lg-4">
                <?php $cal->display(4, $curso+1); ?>
                </div>

                <div class="col-lg-4">
                <?php $cal->display(5, $curso+1); ?>
                </div>

            </div><!-- ./row -->

            <div class="row">

                <div class="col-lg-4">
                <?php $cal->display(6, $curso+1); ?>
                </div>

                <div class="col-lg-4">
                <?php $cal->display(7, $curso+1); ?>
                </div>

                <div class="col-lg-4">
                <?php $cal->display(8, $curso+1); ?>
                </div>

            </div><!-- ./row -->

        </div>
    </div>

    <?php include("../../inc_pie.php"); ?>
