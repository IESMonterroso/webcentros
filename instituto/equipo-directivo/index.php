<?php
require_once("../../bootstrap.php");
require_once("../../config.php");

$pagina['titulo'] = 'Equipo directivo';

// SEO
//$pagina['meta']['robots'] = 0;
//$pagina['meta']['canonical'] = 0;
$pagina['meta']['meta_title'] = $pagina['titulo'];
$pagina['meta']['meta_description'] = "Organización del Equipo directivo del ".$config['centro_denominacion'];
$pagina['meta']['meta_type'] = "website";
$pagina['meta']['meta_locale'] = "es_ES";

include("../../inc_menu.php");
?>

    <div class="section">
        <div class="container">
            <?php $enUnaFila = (
                (!isset($config['eqdirectivo_vicedireccion']['nombre']) || empty($config['eqdirectivo_vicedireccion']['nombre'])) &&
                (!isset($config['eqdirectivo_jefatura_adjunta']['nombre']) || empty($config['eqdirectivo_jefatura_adjunta']['nombre'])) &&
                (!isset($config['eqdirectivo_jefatura_adjunta2']['nombre']) || empty($config['eqdirectivo_jefatura_adjunta2']['nombre'])) &&
                (!isset($config['eqdirectivo_jefatura_adjunta_adultos']['nombre']) || empty($config['eqdirectivo_jefatura_adjunta_adultos']['nombre'])) &&
                (!isset($config['eqdirectivo_administrador']['nombre']) || empty($config['eqdirectivo_administrador']['nombre']))
              ) ? 1 : 0; ?>

            <?php $cargos = array('direccion', 'vicedireccion', 'jefatura', 'jefatura_adjunta', 'jefatura_adjunta2', 'jefatura_adultos', 'jefatura_adjunta_adultos', 'secretaria', 'administrador'); ?>

            <?php if ($enUnaFila): ?>
            <div class="row justify-content-center">
            <?php endif; ?>
            <?php foreach ($cargos as $cargo): ?>
            <?php if (!$enUnaFila && ($cargo == 'direccion' || $cargo == 'jefatura' || $cargo == 'jefatura_adultos' || $cargo == 'secretaria' || $cargo == 'administrador')): ?>
            <div class="row justify-content-center">
            <?php endif; ?>

                <?php if (! empty($config['eqdirectivo_'.$cargo]['nombre'])): ?>

                <div class="col-md-4">

                    <div class="card card-plain text-center">
                        <div class="card-avatar text-muted">
                            <i class="far fa-user-circle fa-7x"></i>
                        </div>
                        <div class="card-block pad15">
                            <h5 class="card-title"><?php echo $config['eqdirectivo_'.$cargo]['nombre']; ?></h5>
                            <h6 class="category text-primary"><?php echo $config['eqdirectivo_'.$cargo]['cargo']; ?></h6>
                            <ul class="list-unstyled">
                                <li><a class="text-muted" href="call:+34<?php echo ! empty($config['eqdirectivo_'.$cargo]['telefono']) ? $config['eqdirectivo_'.$cargo]['telefono'] : $config['centro_telefono']; ?>"><span class="fas fa-phone fa-fw"></span> <?php echo ! empty($config['eqdirectivo_'.$cargo]['telefono']) ? $config['eqdirectivo_'.$cargo]['telefono'] : $config['centro_telefono']; ?></a></li>
                                <li><a class="text-muted" href="mailto:<?php echo ! empty($config['eqdirectivo_'.$cargo]['correoe']) ? ofuscarEmail($config['eqdirectivo_'.$cargo]['correoe']) : ofuscarEmail($config['centro_codigo'].'.edu@juntadeandalucia.es'); ?>"><span class="far fa-envelope fa-fw"></span> <?php echo ! empty($config['eqdirectivo_'.$cargo]['correoe']) ? ofuscarEmail($config['eqdirectivo_'.$cargo]['correoe']) : ofuscarEmail($config['centro_codigo'].'.edu@juntadeandalucia.es'); ?></a></li>
                            </ul>
                        </div>
                    </div>

                </div>
                <?php endif; ?>

            <?php if (!$enUnaFila && ($cargo == 'vicedireccion' || (! empty($config['eqdirectivo_jefatura_adjunta2']['nombre']) ? $cargo == 'jefatura_adjunta2' : $cargo ==  'jefatura_adjunta') || $cargo == 'jefatura_adjunta_adultos' || $cargo == 'secretaria')): ?>
            </div>
            <hr>
            <?php endif; ?>
            <?php endforeach; ?>
            <?php if ($enUnaFila): ?>
            </div>
            <?php endif; ?>

        </div>
    </div>

    <?php include("../../inc_pie.php"); ?>
