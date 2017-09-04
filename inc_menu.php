<?php if (! defined("WEBCENTROS_DOMINIO")) die ('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no" name="viewport">
    
    <!-- FAVICON -->
    <link rel="apple-touch-icon" sizes="76x76" href="<?php echo WEBCENTROS_DOMINIO; ?>ui-theme/img/apple-icon.png">
    <link rel="icon" type="image/png" href="<?php echo WEBCENTROS_DOMINIO; ?>ui-theme/img/favicon.ico">
    
    <title><?php echo ($pagina['titulo'] != '') ? strip_tags($pagina['titulo'])." - ".$config['centro_denominacion'] : $config['centro_denominacion']; ?> - Instituto de Educación Secundaria de <?php echo $config['centro_localidad']; ?></title>

    <?php if (! isset($pagina['meta']['canonical']) || $pagina['meta']['canonical'] = 0): ?>
    <link rel="canonical" href="<?php echo WEBCENTROS_DOMINIO.ltrim($_SERVER['REQUEST_URI'], '/'); ?>">
    <?php endif; ?>
    
    <?php if (! isset($pagina['meta']['robots']) || $pagina['meta']['robots'] = 0): ?>
    <!-- SEO -->
    <meta name="robots" content="index, follow">

    <?php if (isset($pagina['meta']['meta_autor'])): ?><meta name="author" content="<?php echo $pagina['meta']['meta_autor']; ?>"><?php endif; ?>
    <meta name="description" content="<?php echo $pagina['meta']['meta_description']; ?>">

    <meta itemprop="name" content="<?php echo $pagina['meta']['meta_title']; ?>">
    <meta itemprop="description" content="<?php echo $pagina['meta']['meta_description']; ?>">

    <meta property="og:title" content="<?php echo $pagina['meta']['meta_title']; ?>">
    <?php if (isset($pagina['meta']['meta_autor'])): ?>
    <meta property="og:author" content="<?php echo $pagina['meta']['meta_autor']; ?>">
    <?php endif; ?>
    <meta property="og:description" content="<?php echo $pagina['meta']['meta_description']; ?>">	
    <meta property="og:type" content="<?php echo $pagina['meta']['meta_type']; ?>">	
    <meta property="og:locale" content="<?php echo $pagina['meta']['meta_locale']; ?>">	
    <meta property="og:site_name" content="<?php echo $config['centro_denominacion']; ?>">	
    <meta property="og:url" content="<?php echo WEBCENTROS_DOMINIO.ltrim($_SERVER['REQUEST_URI'], '/'); ?>">

    <meta name="twitter:card" content="summary" />
    <meta name="twitter:description" content="<?php echo $pagina['meta']['meta_description']; ?>">
    <meta name="twitter:title" content="<?php echo $pagina['meta']['meta_title']; ?>">

    <?php else: ?>
    <meta name="robots" content="noindex, nofollow">
    <?php endif; ?>

    <link rel="dns-prefetch" href="//fonts.googleapis.com">
    <link rel="dns-prefetch" href="//maxcdn.bootstrapcdn.com">
    <link rel="dns-prefetch" href="//code.jquery.com">
    <link rel="dns-prefetch" href="//cdnjs.cloudflare.com">

    <!-- Fonts and icons -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet" />
    <!-- CSS Files -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
    <link href="<?php echo WEBCENTROS_DOMINIO; ?>ui-theme/css/now-ui-kit.css" rel="stylesheet" />
    <link href="<?php echo WEBCENTROS_DOMINIO; ?>ui-theme/css/my-style.css" rel="stylesheet" />
    <link href="<?php echo WEBCENTROS_DOMINIO; ?>ui-theme/css/personalize.css" rel="stylesheet" />
</head>

<body class="index-page sidebar-collapse">

    <nav class="navbar navbar-expand-lg fixed-top bg-primary">
        <div class="container">
            <div class="navbar-translate">
                <a class="navbar-brand" href="<?php echo WEBCENTROS_DOMINIO; ?>">
                    <img src="<?php echo WEBCENTROS_DOMINIO; ?>ui-theme/img/logo.png" width="30" height="30" class="d-inline-block" alt="">
                    <?php echo $config['centro_denominacion']; ?>
                </a>
                <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navigation" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-bar bar1"></span>
                    <span class="navbar-toggler-bar bar2"></span>
                    <span class="navbar-toggler-bar bar3"></span>
                </button>
            </div>

            <div class="collapse navbar-collapse" id="navigation">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="menInstituto" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Instituto
                        </a>
                        <div class="dropdown-menu" aria-labelledby="menuInstituto">
                            <h6 class="dropdown-header">Organización</h6>
                            <a class="dropdown-item" href="<?php echo WEBCENTROS_DOMINIO; ?>instituto/equipo-directivo">Equipo directivo</a>
                            <a class="dropdown-item" href="<?php echo WEBCENTROS_DOMINIO; ?>instituto/departamentos">Departamentos</a>
                            <div class="dropdown-divider"></div>
                            <h6 class="dropdown-header">Información académica</h6>
                            <a class="dropdown-item" href="<?php echo WEBCENTROS_DOMINIO; ?>instituto/contacto">Información y contacto</a>
                            <a class="dropdown-item" href="<?php echo WEBCENTROS_DOMINIO; ?>instituto/calendario">Calendario escolar</a>
                            <a class="dropdown-item" href="<?php echo WEBCENTROS_DOMINIO; ?>instituto/actividades-extraescolares">Actividades extraescolares</a>
                            <?php if (isset($config['biblioteca']) && ! empty($config['biblioteca'])): ?>
                            <a class="dropdown-item" href="<?php echo $config['biblioteca']; ?>" target="_blank">Biblioteca</a>
                            <?php endif; ?>
                            <?php if (isset($config['imagenes']) && ! empty($config['imagenes'])): ?>
                            <a class="dropdown-item" href="<?php echo $config['imagenes']; ?>" target="_blank">Imágenes y reportajes</a>
                            <?php endif; ?>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="menuOfertaEducativa" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Oferta educativa
                        </a>
                        <div class="dropdown-menu" aria-labelledby="menuOfertaEducativa">
                            <h6 class="dropdown-header">Educación Obligatoria</h6>
                            <a class="dropdown-item" href="<?php echo WEBCENTROS_DOMINIO; ?>oferta-educativa/educacion-secundaria-obligatoria">Educación Secundaria Obligatoria</a>
                            <?php if (isset($config['educacion_bachiller']) && $config['educacion_bachiller']): ?>
                            <div class="dropdown-divider"></div>
                            <h6 class="dropdown-header">Bachillerato</h6>
                            <a class="dropdown-item" href="<?php echo WEBCENTROS_DOMINIO; ?>oferta-educativa/bachillerato">Bachillerato</a>
                            <?php endif; ?>
                            <?php if (isset($config['educacion_cfgb']) && count($config['educacion_cfgb'])): ?>
                            <div class="dropdown-divider"></div>
                            <h6 class="dropdown-header">Formación Profesional Básico</h6>
                            <?php foreach ($config['educacion_cfgb'] as $cfgb): ?>
                            <a class="dropdown-item" href="<?php echo WEBCENTROS_DOMINIO; ?>oferta-educativa/formacion-profesional/<?php echo $cfgb['alias']; ?>"><?php echo $cfgb['nombre']; ?></a>
                            <?php endforeach; ?>
                            <?php endif; ?>
                            <?php if (isset($config['educacion_cfgm']) && count($config['educacion_cfgm'])): ?>
                            <div class="dropdown-divider"></div>
                            <h6 class="dropdown-header">Formación Profesional de Grado Medio</h6>
                            <?php foreach ($config['educacion_cfgm'] as $cfgm): ?>
                            <a class="dropdown-item" href="<?php echo WEBCENTROS_DOMINIO; ?>oferta-educativa/formacion-profesional/<?php echo $cfgm['alias']; ?>"><?php echo $cfgm['nombre']; ?></a>
                            <?php endforeach; ?>
                            <?php endif; ?>
                            <?php if (isset($config['educacion_cfgs']) && count($config['educacion_cfgs'])): ?>
                            <div class="dropdown-divider"></div>
                            <h6 class="dropdown-header">Formación Profesional de Grado Superior</h6>
                            <?php foreach ($config['educacion_cfgs'] as $cfgs): ?>
                            <a class="dropdown-item" href="<?php echo WEBCENTROS_DOMINIO; ?>oferta-educativa/formacion-profesional/<?php echo $cfgs['alias']; ?>"><?php echo $cfgs['nombre']; ?></a>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo WEBCENTROS_DOMINIO; ?>documentos">Documentos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo WEBCENTROS_DOMINIO; ?>alumnado">Alumnado</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo WEBCENTROS_DOMINIO; ?>intranet" target="_blank">Intranet</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item active">
                        <a class="nav-link" href="<?php echo WEBCENTROS_DOMINIO; ?>instituto/contacto">
                            <i class="now-ui-icons travel_info"></i>
                            <p>Te informamos</p>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
    <div class="wrapper">
        
    <?php if (! empty($pagina['titulo'])): ?>
    <div class="my-header d-flex align-content-center justify-content-center flex-wrap">
        <div class="container">
            <h2 class="title"><?php echo $pagina['titulo']; ?></h2>
        </div>
    </div>
    <?php endif; ?>

    