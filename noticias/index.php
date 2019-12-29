<?php
require_once("../bootstrap.php");
require_once("../config.php");

$id = limpiarInput($_GET['id'], 'numeric');
$result = mysqli_query($db_con, "SELECT id, titulo, contenido, autor, fechapub, categoria FROM noticias WHERE id = $id AND pagina LIKE '%2%' LIMIT 1");
if (! mysqli_num_rows($result)) {
    include("../error404.php");
}

$noticia = mysqli_fetch_array($result);
$exp_autor = explode(', ', $noticia['autor']);
$autor = xss_clean(trim($exp_autor[1].' '.$exp_autor[0]));
$alias_autor = xss_clean(mb_strtolower(str_replace($acentos, $no_acentos, $noticia['autor'])));
$alias_categoria = xss_clean(mb_strtolower(str_replace($acentos, $no_acentos, $noticia['categoria'])));
$alias = xss_clean(mb_strtolower(str_replace($acentos, $no_acentos, $noticia['titulo'])));

// Obtenemos las imágenes del artículo
$result_imagenes = preg_match('/< *img[^>]*src *= *["\']?([^"\']*)/i', $noticia['contenido'], $imagenes);

if ((isset($result_imagenes) && $result_imagenes) && isset($imagenes[1])) {
  if (strpos($imagenes[1], 'data:image') !== false) {
    $exp_datos_base64 = explode(',', $imagenes[1]);
    $exp_datos = explode(';', $exp_datos_base64[0]);
    $exp_formato = explode('/', $exp_datos[0]);
    $formato = trim($exp_formato[1]);

    $ruta_imagenes = WEBCENTROS_DIRECTORY."/images/";
    $nombre_archivo = $noticia['id']."_".$alias.".".$formato;
    $ruta_imagen = $ruta_imagenes.$nombre_archivo;

    if (! file_exists($ruta_imagen)) {
      file_put_contents($ruta_imagen, file_get_contents($imagenes[1]));
    }
    
    $ruta_web_imagen = WEBCENTROS_DOMINIO."images/".$nombre_archivo;
    $pagina['meta']['meta_imagen'] = $ruta_web_imagen;
  }
  else {
    $pagina['meta']['meta_imagen'] = $imagenes[1];
  }
}


$pagina['titulo'] = strip_tags($noticia['titulo']);

// SEO
//$pagina['meta']['robots'] = 0;
//$pagina['meta']['canonical'] = 0;
$pagina['meta']['meta_title'] = $pagina['titulo'];
$pagina['meta']['meta_autor'] = $noticia['autor'];
$pagina['meta']['meta_description'] = cortarPalabras($noticia['contenido']);
$pagina['meta']['meta_type'] = "article";
$pagina['meta']['meta_locale'] = "es_ES";

include("../inc_menu.php");
?>

    <div class="section">
        <div class="container">

            <div class="row justify-content-md-center">

                <div class="col-md-9">

                    <div class="text-muted">
                        <ul class="list-inline">
                            <li class="list-inline-item"><a href="<?php echo WEBCENTROS_DOMINIO."noticias/autor/".$alias_autor; ?>" class="text-muted"><?php echo $autor; ?></a></li>
                            <li class="list-inline-item">/</li>
                            <li class="list-inline-item"><a href="<?php echo WEBCENTROS_DOMINIO."noticias/categoria/".$alias_categoria; ?>" class="text-muted"><?php echo $noticia['categoria']; ?></a></li>
                            <li class="list-inline-item">/</li>
                            <li class="list-inline-item"><?php echo strftime('%e %B %Y', strtotime($noticia['fechapub'])); ?></li>
                        </ul>
                    </div>

                    <br>

                    <div class="text-wrap">
                    <?php echo $noticia['contenido']; ?>
                    </div>

                    <br>

                    <hr>

                    <?php $url_noticia = WEBCENTROS_DOMINIO."noticias/".$noticia['id']."/".$alias; ?>
                    <div class="" style="margin-top: 10px;">
                        <a href="#" onclick="javascript:popup('http://www.facebook.com/share.php?u=<?php echo $url_noticia; ?>',550,350)" class="btn btn-default btn-sm btn-icon btn-round btn-pad5" data-toggle="tooltip" title="Compartir en Facebook"><i class="fab fa-facebook"></i></a>
                        <a href="#" onclick="javascript:popup('http://twitter.com/home?status=<?php echo $url_noticia; ?>',550,350)" class="btn btn-default btn-sm btn-icon btn-round btn-pad5" data-toggle="tooltip" title="Compartir en Twitter"><i class="fab fa-twitter"></i></a>
                        <a href="#" onclick="javascript:popup('https://www.linkedin.com/shareArticle?title=<?php echo urlencode($noticia['titulo']); ?>&url=<?php echo $url_noticia; ?>&mini=true&share_to=http%3A%2F%2Fwww.linkedin.com%2FshareArticle',550,550)" class="btn btn-default btn-sm btn-icon btn-round btn-pad5" data-toggle="tooltip" title="Compartir en LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                        <a href="whatsapp://send?text=<?php echo $url_noticia; ?>" class="btn btn-default btn-sm btn-icon btn-round btn-pad5" data-toggle="tooltip" title="Compartir en WhatsApp"><i class="fab fa-whatsapp"></i></a>
                        <a href="tg://msg_url?url=<?php echo $url_noticia; ?>&amp;text=<?php echo $noticia['titulo']; ?>" class="btn btn-default btn-sm btn-icon btn-round btn-pad5" data-toggle="tooltip" title="Compartir en Telegram"><i class="fab fa-telegram-plane"></i></a>
                        <a href="#" onclick="javascript:popup('https://getpocket.com/save?url=<?php echo $url_noticia; ?>&title=<?php echo urlencode($noticia['titulo']); ?>',550,550)" class="btn btn-default btn-sm btn-icon btn-round btn-pad5" data-toggle="tooltip" title="Compartir en Pocket"><i class="fab fa-get-pocket"></i></a>
                        <a href="mailto:?subject=<?php echo $noticia['titulo']; ?>&amp;body=<?php echo $url_noticia; ?>" class="btn btn-default btn-sm btn-icon btn-round btn-pad5" data-toggle="tooltip" title="Compartir por correo electrónico"><i class="far fa-envelope"></i></a>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <?php include("../inc_pie.php"); ?>
