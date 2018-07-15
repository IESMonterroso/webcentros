<?php
require_once("bootstrap.php");
require_once("config.php");

error_reporting(E_ALL);

$noticias_destacadas = array();
$result = mysqli_query($db_con, "SELECT id, titulo, contenido, autor, fechapub, categoria from noticias where pagina like '%2%' and fechafin >= '".date('Y-m-d H:i:s')."' ORDER BY fechapub DESC");
while ($row = mysqli_fetch_array($result)) {

    $exp_autor = explode(', ', $row['autor']);
    $autor = trim($exp_autor[1]);

    $noticia_destacada = array(
        'id'                => $row['id'],
        'titulo'            => $row['titulo'],
        'categoria'         => $row['categoria'],
        'alias_categoria'   => mb_strtolower(str_replace($acentos, $no_acentos, $row['categoria'])),
        'autor'             => $autor,
        'alias_autor'       => mb_strtolower(str_replace($acentos, $no_acentos, $row['autor'])),
        'contenido'         => $row['contenido'],
        'fechapub'          => $row['fechapub'],
        'alias'             => mb_strtolower(str_replace($acentos, $no_acentos, $row['titulo']))
    );

    array_push($noticias_destacadas, $noticia_destacada);
}
mysqli_free_result($result);
unset($noticia_destacada);


// 5 ULTIMAS NOTICIAS
$noticias = array();
$result = mysqli_query($db_con, "SELECT id, titulo, contenido, autor, fechapub, categoria FROM noticias WHERE fechapub <= '".date('Y-m-d H:i:s')."' AND pagina LIKE '%2%' AND id NOT IN (SELECT id FROM noticias WHERE pagina LIKE '%2%' AND fechafin >= '".date('Y-m-d H:i:s')."' ORDER BY fechapub DESC) ORDER BY fechapub DESC LIMIT 5");
while ($row = mysqli_fetch_array($result)) {

    $exp_autor = explode(', ', $row['autor']);
    $autor = trim($exp_autor[1]);

    $noticia = array(
        'id'                => $row['id'],
        'titulo'            => $row['titulo'],
        'categoria'         => $row['categoria'],
        'alias_categoria'   => mb_strtolower(str_replace($acentos, $no_acentos, $row['categoria'])),
        'autor'             => $autor,
        'alias_autor'       => mb_strtolower(str_replace($acentos, $no_acentos, $row['autor'])),
        'contenido'         => $row['contenido'],
        'fechapub'          => $row['fechapub'],
        'alias'             => mb_strtolower(str_replace($acentos, $no_acentos, $row['titulo']))
    );

    array_push($noticias, $noticia);
}
mysqli_free_result($result);
unset($noticia);

// 7 ULTIMOS EVENTOS
$ultima_fecha = "0000-00-00";
$eventos = array();
$result = mysqli_query($db_con, "SELECT c.nombre, c.fechaini, c.horaini, c.fechafin, c.horafin, c.lugar, c.categoria, c.confirmado FROM calendario AS c JOIN calendario_categorias AS cc ON c.categoria = cc.id WHERE cc.espublico = 1 AND fechaini BETWEEN NOW() AND ADDDATE(NOW(), INTERVAL 7 DAY) ORDER BY fechaini ASC");
while ($row = mysqli_fetch_array($result)) {

    if (($row['categoria'] == 2 && $row['confirmado'] == 1) || $row['categoria'] != 2) {
        $evento = array(
            'nombre'        => $row['nombre'],
            'lugar'         => $row['lugar'],
            'fechaini'      => $row['fechaini'].' '.$row['horaini'],
            'fechafin'      => $row['fechafin'].' '.$row['horafin'],
            'festivo'       => 0
        );

        array_push($eventos, $evento);
        $ultima_fecha = $row['fechaini'];
    }

}
mysqli_free_result($result);
unset($evento);

// DIAS FESTIVOS
$result = mysqli_query($db_con, "SELECT fecha, nombre, ambito FROM festivos WHERE fecha BETWEEN NOW() AND ADDDATE(NOW(), INTERVAL 7 DAY) ORDER BY fecha ASC");
while ($row = mysqli_fetch_array($result)) {

    $evento = array(
        'nombre'        => $row['nombre'],
        'lugar'         => $row['ambito'],
        'fechaini'      => $row['fecha'].' 00:00:00',
        'fechafin'      => $row['fecha'].' 00:00:00',
        'festivo'       => 1
    );

    array_push($eventos, $evento);
}
mysqli_free_result($result);
unset($evento);

// ORDENAMOS EVENTOS
usort($eventos, 'sort_by_orden');
function sort_by_orden ($a, $b) {
    return strcmp($a['fechaini'], $b['fechaini']);
}


// SEO
//$pagina['meta']['robots'] = 0;
$pagina['meta']['canonical'] = 0;
$pagina['meta']['meta_title'] = $config['centro_denominacion'];
$pagina['meta']['meta_description'] = $config['centro_denominacion']." - Instituto de Educación Secundaria de ".$config['centro_localidad'].' ('.$config['centro_provincia'].')';
$pagina['meta']['meta_type'] = "website";
$pagina['meta']['meta_locale'] = "es_ES";

$pagina['titulo'] = '';
include("inc_menu.php");
?>

    <div class="section section-news">
        <div class="container">

            <div class="row">

                <div class="col-md-8">

                    <h2 class="h3 title">Novedades en el instituto</h2>

                    <?php if (isset($config['content_html']['top']) && count($config['content_html']['top'])): ?>
                    <?php foreach ($config['content_html']['top'] as $content_html_top): ?>
                    <?php if (isset($content_html_top['titulo']) && ! empty($content_html_top['titulo'])): ?>
                    <h4><?php echo $content_html_top['titulo']; ?></h4>
                    <?php endif; ?>

                    <?php echo $content_html_top['html']; ?>
                    <?php endforeach; ?>
                    <?php endif; ?>

                    <?php if (count($noticias_destacadas) || count($noticias)): ?>
                    <?php foreach ($noticias_destacadas as $noticia): ?>
                    <?php $url_noticia = WEBCENTROS_DOMINIO."noticias/".$noticia['id']."/".$noticia['alias']; ?>
                    <article>
                        <div class="media bg-secondary">
                            <div class="media-body" style="margin: 20px;">
                                <h5 class="mt-0"><a href="<?php echo $url_noticia; ?>"><?php echo $noticia['titulo']; ?></a></h5>
                                <h6 class="text-muted">
                                    <a href="<?php echo WEBCENTROS_DOMINIO."noticias/autor/".$noticia['alias_autor']; ?>" class="text-muted"><?php echo $noticia['autor']; ?></a>
                                    &nbsp;&nbsp;/&nbsp;&nbsp;
                                    <a href="<?php echo WEBCENTROS_DOMINIO."noticias/categoria/".$noticia['alias_categoria']; ?>" class="text-muted"><?php echo $noticia['categoria']; ?></a>
                                    &nbsp;&nbsp;/&nbsp;&nbsp;
                                    <?php echo strftime('%e %b %Y', strtotime($noticia['fechapub'])); ?>
                                </h6>
                                <?php
                                 $noticia['contenido'] = str_ireplace("&nbsp;", "", $noticia['contenido']);
                                ?>
                                <p class="text-wrap"><?php echo trim(cortarTexto(strip_tags($noticia['contenido']), 300).'...<br><a href="'.$url_noticia.'">[Leer más]</a>'); ?></p>

                                <div class="" style="margin-top: 10px;">
                                    <a href="#" onclick="javascript:popup('http://www.facebook.com/share.php?u=<?php echo $url_noticia; ?>',550,350)" class="btn btn-default btn-sm btn-icon btn-round btn-pad5" data-toggle="tooltip" title="Compartir en Facebook"><i class="fab fa-facebook"></i></a>
                                    <a href="#" onclick="javascript:popup('http://twitter.com/home?status=<?php echo $url_noticia; ?>',550,350)" class="btn btn-default btn-sm btn-icon btn-round btn-pad5" data-toggle="tooltip" title="Compartir en Twitter"><i class="fab fa-twitter"></i></a>
                                    <a href="#" onclick="javascript:popup('https://plus.google.com/share?url=<?php echo $url_noticia; ?>',550,550)" class="btn btn-default btn-sm btn-icon btn-round btn-pad5" data-toggle="tooltip" title="Compartir en Google+"><i class="fab fa-google-plus-g"></i></a>
                                    <a href="#" onclick="javascript:popup('https://www.linkedin.com/shareArticle?title=<?php echo urlencode($noticia['titulo']); ?>&url=<?php echo $url_noticia; ?>&mini=true&share_to=http%3A%2F%2Fwww.linkedin.com%2FshareArticle',550,550)" class="btn btn-default btn-sm btn-icon btn-round btn-pad5" data-toggle="tooltip" title="Compartir en LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                                    <a href="whatsapp://send?text=<?php echo $url_noticia; ?>" class="btn btn-default btn-sm btn-icon btn-round btn-pad5" data-toggle="tooltip" title="Compartir en WhatsApp"><i class="fab fa-whatsapp"></i></a>
                                    <a href="tg://msg_url?url=<?php echo $url_noticia; ?>&amp;text=<?php echo $noticia['titulo']; ?>" class="btn btn-default btn-sm btn-icon btn-round btn-pad5" data-toggle="tooltip" title="Compartir en Telegram"><i class="fab fa-telegram-plane"></i></a>
                                    <a href="mailto:?subject=<?php echo $noticia['titulo']; ?>&amp;body=<?php echo $url_noticia; ?>" class="btn btn-default btn-sm btn-icon btn-round btn-pad5" data-toggle="tooltip" title="Compartir por correo electrónico"><i class="far fa-envelope"></i></a>
                                </div>
                            </div>
                        </div>
                    </article>
                    <?php endforeach; ?>
                    <?php foreach ($noticias as $noticia): ?>
                    <?php $url_noticia = WEBCENTROS_DOMINIO."noticias/".$noticia['id']."/".$noticia['alias']; ?>
                    <article>
                        <div class="media">
                            <div class="media-body" style="margin: 20px;">
                                <h5 class="mt-0"><a href="<?php echo $url_noticia; ?>"><?php echo $noticia['titulo']; ?></a></h5>
                                <h6 class="text-muted">
                                    <a href="<?php echo WEBCENTROS_DOMINIO."noticias/autor/".$noticia['alias_autor']; ?>" class="text-muted"><?php echo $noticia['autor']; ?></a>
                                    &nbsp;&nbsp;/&nbsp;&nbsp;
                                    <a href="<?php echo WEBCENTROS_DOMINIO."noticias/categoria/".$noticia['alias_categoria']; ?>" class="text-muted"><?php echo $noticia['categoria']; ?></a>
                                    &nbsp;&nbsp;/&nbsp;&nbsp;
                                    <?php echo strftime('%e %b %Y', strtotime($noticia['fechapub'])); ?>
                                </h6>
                                <?php
                                $noticia['contenido'] = str_ireplace(". ", ".", $noticia['contenido']);
                                $noticia['contenido'] = str_ireplace(".", ". ", $noticia['contenido']);
                                $noticia['contenido'] = str_ireplace("&nbsp;", "", $noticia['contenido']);
                                ?>
                                <p class="text-wrap"><?php echo trim(cortarTexto(strip_tags($noticia['contenido']), 300).'...<br><a href="'.$url_noticia.'">[Leer más]</a>'); ?></p>

                                <div class="pad10">
                                    <a href="#" onclick="javascript:popup('http://www.facebook.com/share.php?u=<?php echo $url_noticia; ?>',550,350)" class="btn btn-default btn-sm btn-icon btn-round btn-pad5" data-toggle="tooltip" title="Compartir en Facebook"><i class="fab fa-facebook"></i></a>
                                    <a href="#" onclick="javascript:popup('http://twitter.com/home?status=<?php echo $url_noticia; ?>',550,350)" class="btn btn-default btn-sm btn-icon btn-round btn-pad5" data-toggle="tooltip" title="Compartir en Twitter"><i class="fab fa-twitter"></i></a>
                                    <a href="#" onclick="javascript:popup('https://plus.google.com/share?url=<?php echo $url_noticia; ?>',550,550)" class="btn btn-default btn-sm btn-icon btn-round btn-pad5" data-toggle="tooltip" title="Compartir en Google+"><i class="fab fa-google-plus-g"></i></a>
                                    <a href="#" onclick="javascript:popup('https://www.linkedin.com/shareArticle?title=<?php echo urlencode($noticia['titulo']); ?>&url=<?php echo $url_noticia; ?>&mini=true&share_to=http%3A%2F%2Fwww.linkedin.com%2FshareArticle',550,550)" class="btn btn-default btn-sm btn-icon btn-round btn-pad5" data-toggle="tooltip" title="Compartir en LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                                    <a href="whatsapp://send?text=<?php echo $url_noticia; ?>" class="btn btn-default btn-sm btn-icon btn-round btn-pad5" data-toggle="tooltip" title="Compartir en WhatsApp"><i class="fab fa-whatsapp"></i></a>
                                    <a href="tg://msg_url?url=<?php echo $url_noticia; ?>&amp;text=<?php echo $noticia['titulo']; ?>" class="btn btn-default btn-sm btn-icon btn-round btn-pad5" data-toggle="tooltip" title="Compartir en Telegram"><i class="fab fa-telegram-plane"></i></a>
                                    <a href="mailto:?subject=<?php echo $noticia['titulo']; ?>&amp;body=<?php echo $url_noticia; ?>" class="btn btn-default btn-sm btn-icon btn-round btn-pad5" data-toggle="tooltip" title="Compartir por correo electrónico"><i class="far fa-envelope"></i></a>
                                </div>
                            </div>
                        </div>
                    </article>
                    <?php endforeach; ?>
                    <?php else: ?>

                    <br><br><br><br>
                    <div class="text-center text-muted">
                        <span class="far fa-newspaperfa-4x"></span>
                        <p class="lead pad10">No hay novedades en el instituto</p>
                    </div>
                    <br><br><br><br>

                    <?php endif; ?>

                    <div class="text-center">
                        <a href="<?php echo WEBCENTROS_DOMINIO; ?>noticias/buscar/" class="btn btn-primary">Ver todas las novedades</a>
                    </div>

                    <br>

                    <?php if (isset($config['content_html']['bottom']) && count($config['content_html']['bottom'])): ?>
                    <?php foreach ($config['content_html']['bottom'] as $content_html_bottom): ?>
                    <?php if (isset($content_html_bottom['titulo']) && ! empty($content_html_bottom['titulo'])): ?>
                    <h4><?php echo $content_html_bottom['titulo']; ?></h4>
                    <?php endif; ?>

                    <?php echo $content_html_bottom['html']; ?>
                    <?php endforeach; ?>
                    <?php endif; ?>

                </div>

                <div class="col-md-4">

                    <div class="pad10 d-none d-sm-block">
                      <div class="row">
                        <div class="col-sm-4">
                          <a href="http://www.juntadeandalucia.es/educacion/portalseneca/web/seneca/inicio" target="_blank" data-toggle="tooltip" title="" data-original-title="Ir a Portal Séneca">
                              <img src="<?php echo WEBCENTROS_DOMINIO; ?>ui-theme/img/seneca.png" alt="">
                          </a>
                        </div>

                        <div class="col-sm-4">
                          <a href="https://www.juntadeandalucia.es/educacion/portalseneca/web/pasen/inicio" target="_blank" data-toggle="tooltip" title="" data-original-title="Ir a Portal Pasen">
                              <img src="<?php echo WEBCENTROS_DOMINIO; ?>ui-theme/img/pasen.png" alt="">
                          </a>
                        </div>

                        <div class="col-sm-4">
                          <a href="http://www.juntadeandalucia.es/averroes/centros-tic/<?php echo $config['centro_codigo']; ?>/moodle2/" target="_blank" data-toggle="tooltip" title="" data-original-title="Ir a Plataforma Moodle">
                              <img src="<?php echo WEBCENTROS_DOMINIO; ?>ui-theme/img/moodle.png" alt="">
                          </a>
                        </div>
                      </div>
                    </div>

                    <br>

                    <div class="calendario pad15">
                        <div class="card-box border-primary">
                            <h5 class="card-title">Próximos eventos</h5>
                            <?php if (count($eventos)): ?>
                            <?php foreach ($eventos as $evento): ?>
                            <div class="row">
                                <div class="col-2">
                                    <div class="h6 text-center<?php echo ($evento['festivo']) ? ' text-danger' : ''; ?>">
                                        <span><?php echo strftime('%d', strtotime($evento['fechaini'])); ?></span><br>
                                        <small><?php echo strftime('%b', strtotime($evento['fechaini'])); ?></small>
                                    </div>
                                </div>
                                <div class="col-10">
                                    <h6 class="card-text text-wrap<?php echo ($evento['festivo']) ? ' text-danger' : ''; ?>"><?php echo $evento['nombre']; ?></h6>
                                    <?php if ($evento['lugar'] != ""): ?>
                                    <p class="card-text<?php echo ($evento['festivo']) ? ' text-danger' : ' text-muted'; ?>"><?php echo $evento['lugar']; ?></p>
                                    <?php endif; ?>
                                    <?php $horaini = strftime('%H:%M', strtotime($evento['fechaini'])); ?>
                                    <?php $horafin = strftime('%H:%M', strtotime($evento['fechafin'])); ?>
                                    <?php echo ($horaini != '00:00' && $horafin != '00:00') ? 'De '.$horaini.' a '.$horafin.' h.' : ''; ?>
                                </div>
                            </div>
                            <hr>
                            <?php endforeach; ?>
                            <?php else: ?>

                            <br>
                            <div class="text-center text-muted">
                                <span class="far fa-calendar fa-4x"></span>
                                <p class="lead pad10">No hay eventos próximos</p>
                            </div>
                            <br>

                            <?php endif; ?>

                            <div class="text-center">
                                <a href="<?php echo WEBCENTROS_DOMINIO; ?>eventos/" class="btn btn-primary">Ver todos los eventos</a>
                            </div>
                        </div>
                    </div>

                    <br>

                    <?php if (isset($config['sidebar_html']) && count($config['sidebar_html'])): ?>
                    <?php foreach ($config['sidebar_html'] as $sidebar): ?>
                    <?php if (isset($sidebar['html']) && ! empty($sidebar['html'])): ?>
                    <div class="card-box secondary">
                        <?php if (isset($sidebar['titulo']) && ! empty($sidebar['titulo'])): ?>
                        <h5 class="card-title"><?php echo $sidebar['titulo']; ?></h5>
                        <?php endif; ?>
                        <?php echo $sidebar['html']; ?>
                    </div>
                    <?php endif; ?>
                    <?php endforeach; ?>
                    <?php endif; ?>

                    <br>
                </div>

            </div>
        </div>
    </div>

    <?php include("inc_pie.php"); ?>
