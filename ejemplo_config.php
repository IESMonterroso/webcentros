<?php if (! defined("WEBCENTROS_DOMINIO")) die ('No direct script access allowed');
/*
*   CONFIGURACIÓN DE LA PÁGINA EXTERNA PARA CENTROS EDUCATIVOS
*/

// LOGOTIPO Y FAVICON
// Añade en la carpeta ui-theme/img/ los archivos logo.png y favicon.ico con el logotipo del centro
// Se recomienda que el archivo logo.png tenga unas dimensiones de entre 400 y 500 píxeles, y favicon.ico de 32 píxeles

// COLOR PRIMARIO
// Puede cambiar el color primario del sitio por cualquier otro en formato CMYK
// Utilice la página https://www.w3schools.com/colors/colors_cmyk.asp
$config['color_primario'] = "0%, 60%, 80%, 2%";

// Puede cambiar el patrón de fondo de los títulos de las páginas modificando el número de la variable.
// También puede añadir sus propios patrones subiendo los diseños a la carpeta ui-theme/img/presets/.
// Los valores posibles son del 1 al 7.
$config['fondo_patron'] = "1";

// PLAN DE CENTRO
// Comentar / Descomentar la línea para mostrar o no mostrar, respectivamente, el Plan de Centro
// $config['plan_centro']    = "";

// SITIO WEB DE LA AMPA
// Introducir la URL de la web, blog de la AMPA
// Comentar / Descomentar la línea para mostrar o no mostrar, respectivamente, el sitio web de la AMPA
// $config['web_ampa']       = "";

// SITIO WEB DE ORIENTACIÓN
// Introducir la URL de la web, blog de la Biblioteca, por defecto, utiliza el enlace a BiblioWeb
// Comentar / Descomentar la línea para mostrar o no mostrar, respectivamente, el sitio web de la Biblioteca
// $config['web_orientacion'] = "";

// SITIO WEB DE LA BIBLIOTECA
// Introducir la URL de la web, blog de la Biblioteca, por defecto, utiliza el enlace a BiblioWeb
// Comentar / Descomentar la línea para mostrar o no mostrar, respectivamente, el sitio web de la Biblioteca
$config['web_biblioteca'] = "http://www.juntadeandalucia.es/averroes/centros-tic/".$config['centro_codigo']."/biblioweb/";

// SITIO WEB DE IMAGENES Y REPORTAJES
// Introducir la URL de la web Google Fotos, Flickr u otra donde se almacenen las imágenes y reportajes
$config['web_imagenes']   = "";

// PÁGINA DE ALUMNADO
// Comentar / Descomentar la línea para utilizar o no, respectivamente, Pasen como página de Alumnado
// $config['alumnado']['pasen']  = 1;

// Si se establece estas variables a 1 o true, el alumno podrá ver y descargar los informes de tareas y tutoría
$config['alumnado']['ver_informes_tareas']  = 0;
$config['alumnado']['ver_informes_tutoria'] = 0;
$config['alumnado']['detalles_fechorias'] = 0;

// ENLACES MENÚ SUPERIOR
// Comentar / Descomentar el bloque para mostrar o no mostrar, respectivamente, enlaces en el menú superior
// Slo se permiten dos enlaces más
/*
$config['menu_superior'][0]['titulo'] = "";
$config['menu_superior'][0]['enlace'] = "";
// $config['menu_superior'][0]['target'] = "_blank"; // Descomentar esta línea para abrir en una nueva ventana
*/
/*
$config['menu_superior'][1]['titulo'] = "";
$config['menu_superior'][1]['enlace'] = "";
// $config['menu_superior'][1]['target'] = "_blank"; // Descomentar esta línea para abrir en una nueva ventana
*/

// ICONOS BARRA LATERAL (SOLO 3 ICONOS)
// Introducir la URL de la imagen de los iconos de la barra lateral, título y enlace URL a donde dirigir a los usuarios
// Comentar / Descomentar este bloque para mostrar o no mostrar, respectivamente, los iconos en la barra lateral
$config['sidebar_icon'][0]['imagen']   = WEBCENTROS_DOMINIO."/ui-theme/img/png-icons/seneca.png";
$config['sidebar_icon'][0]['titulo']   = "Ir a Portal Séneca";
$config['sidebar_icon'][0]['enlace']   = "https://www.juntadeandalucia.es/educacion/senecav2/seneca/jsp/portal/";

$config['sidebar_icon'][1]['imagen']   = WEBCENTROS_DOMINIO.'/ui-theme/img/png-icons/pasen.png';
$config['sidebar_icon'][1]['titulo']   = "Ir a Portal Pasen";
$config['sidebar_icon'][1]['enlace']   = "https://www.juntadeandalucia.es/educacion/portalseneca/web/pasen/inicio";

$config['sidebar_icon'][2]['imagen']   = WEBCENTROS_DOMINIO.'/ui-theme/img/png-icons/moodle.png';
$config['sidebar_icon'][2]['titulo']   = "Ir a Plataforma Moodle";
$config['sidebar_icon'][2]['enlace']   = "http://www.juntadeandalucia.es/averroes/centros-tic/".$config['centro_codigo']."/moodle2/";

// OFERTA EDUCATIVA
// Comentar / Descomentar la línea para mostrar o no mostrar, respectivamente, información sobre el Bachillerato
// $config['educacion_bachiller'] = 1;

// Comentar / Descomentar la línea para mostrar o no mostrar, respectivamente, información sobre el Educación Secundaria Para Adultos (ESPA)
// $config['educacion_permanente']['espa'] = 1;

// Comentar / Descomentar la línea para mostrar o no mostrar, respectivamente, información sobre el Bachillerato para adultos
// $config['educacion_permanente']['bachillerato'] = 1;


// FORMACIÓN PROFESIONAL BÁSICA
// Es necesario descomentar el bloque correspondiente y rellenarlo de la siguiente manera:
// En el campo 'nombre' se introduce el nombre del título correspondiente, por ejemplo: Informática y Comunicaciones
// En el campo 'url' se introduce la URL de la página todofp.es donde está la información del título
// El campo 'alias' se rellena automáticamente con lo que haya escrito en el campo 'nombre' o puede escribir el alias que crea adecuado
// Puede añadir tantos bloques como necesite, solo debe cambiar el número del array multidimensional: [0], [1], [2],...

$config['educacion_cfgb'][0]['nombre'] = "Informática y Comunicaciones";
$config['educacion_cfgb'][0]['url']    = "http://www.todofp.es/que-como-y-donde-estudiar/que-estudiar/familia/loe/informatica-comunicaciones/informatica-comunicaciones.html";
$config['educacion_cfgb'][0]['alias']  = mb_strtolower(str_replace($acentos, $no_acentos, $config['educacion_cfgb'][0]['nombre']));

/*
$config['educacion_cfgb'][1]['nombre'] = "";
$config['educacion_cfgb'][1]['url']    = "";
$config['educacion_cfgb'][1]['alias']  = mb_strtolower(str_replace($acentos, $no_acentos, $config['educacion_cfgb'][1]['nombre']));
*/

// FORMACIÓN PROFESIONAL GRADO MEDIO
// Es necesario descomentar el bloque correspondiente y rellenarlo de la siguiente manera:
// En el campo 'nombre' se introduce el nombre del título correspondiente, por ejemplo: Sistemas Microinformáticos y Redes
// En el campo 'url' se introduce la URL de la página todofp.es donde está la información del título.
// Puede añadir tantos bloques como necesite, solo debe cambiar el número del array multidimensional: [0], [1], [2],...

/*
$config['educacion_cfgm'][0]['nombre'] = "";
$config['educacion_cfgm'][0]['url']    = "";
$config['educacion_cfgm'][0]['alias']  = mb_strtolower(str_replace($acentos, $no_acentos, $config['educacion_cfgm'][0]['nombre']));

$config['educacion_cfgm'][1]['nombre'] = "";
$config['educacion_cfgm'][1]['url']    = "";
$config['educacion_cfgm'][1]['alias']  = mb_strtolower(str_replace($acentos, $no_acentos, $config['educacion_cfgm'][1]['nombre']));
*/

// FORMACIÓN PROFESIONAL GRADO SUPERIOR
// Es necesario descomentar el bloque correspondiente y rellenarlo de la siguiente manera:
// En el campo 'nombre' se introduce el nombre del título correspondiente, por ejemplo: Administración de Sistemas Informáticos en Red
// En el campo 'url' se introduce la URL de la página todofp.es donde está la información del título.
// Puede añadir tantos bloques como necesite, solo debe cambiar el número del array multidimensional: [0], [1], [2],...

/*
$config['educacion_cfgs'][0]['nombre'] = "";
$config['educacion_cfgs'][0]['url']    = "";
$config['educacion_cfgs'][0]['alias']  = mb_strtolower(str_replace($acentos, $no_acentos, $config['educacion_cfgs'][0]['nombre']));

$config['educacion_cfgs'][1]['nombre'] = "";
$config['educacion_cfgs'][1]['url']    = "";
$config['educacion_cfgs'][1]['alias']  = mb_strtolower(str_replace($acentos, $no_acentos, $config['educacion_cfgs'][1]['nombre']));
*/

// ORGANIGRAMA COMPLETO DEL EQUIPO DIRECTIVO
// Completar las variables con los datos correspondientes. No es necesario borrar o comentar estas variables, si están vacías no se mostrará información
$config['eqdirectivo_direccion']['nombre']                    = $config['directivo_direccion'];
$config['eqdirectivo_direccion']['cargo']                     = "Director/a";
$config['eqdirectivo_direccion']['telefono']                  = "";
$config['eqdirectivo_direccion']['correoe']                   = "";

$config['eqdirectivo_vicedireccion']['nombre']                = "";
$config['eqdirectivo_vicedireccion']['cargo']                 = "Vicedirector/a";
$config['eqdirectivo_vicedireccion']['telefono']              =  "";
$config['eqdirectivo_vicedireccion']['correoe']               = "";

$config['eqdirectivo_jefatura']['nombre']                     = $config['directivo_jefatura'];
$config['eqdirectivo_jefatura']['cargo']                      = 'Jefe/a de estudios';
$config['eqdirectivo_jefatura']['telefono']                   = "";
$config['eqdirectivo_jefatura']['correoe']                    = "";

$config['eqdirectivo_jefatura_adjunta']['nombre']             = "";
$config['eqdirectivo_jefatura_adjunta']['cargo']              = "Jefe/a de estudios adjunto/a";
$config['eqdirectivo_jefatura_adjunta']['telefono']           = "";
$config['eqdirectivo_jefatura_adjunta']['correoe']            = "";

$config['eqdirectivo_jefatura_adjunta2']['nombre']            = "";
$config['eqdirectivo_jefatura_adjunta2']['cargo']             = "Jefe/a de estudios adjunto/a";
$config['eqdirectivo_jefatura_adjunta2']['telefono']          = "";
$config['eqdirectivo_jefatura_adjunta2']['correoe']           = "";

$config['eqdirectivo_jefatura_adultos']['nombre']             = "";
$config['eqdirectivo_jefatura_adultos']['cargo']              = "Jefe/a de estudios (EPA)";
$config['eqdirectivo_jefatura_adultos']['telefono']           = "";
$config['eqdirectivo_jefatura_adultos']['correoe']            = "";

$config['eqdirectivo_jefatura_adjunta_adultos']['nombre']     = "";
$config['eqdirectivo_jefatura_adjunta_adultos']['cargo']      = "Jefe/a de estudios adjunto/a (EPA)";
$config['eqdirectivo_jefatura_adjunta_adultos']['telefono']   = "";
$config['eqdirectivo_jefatura_adjunta_adultos']['correoe']    = "";

$config['eqdirectivo_secretaria']['nombre']                   = $config['directivo_secretaria'];
$config['eqdirectivo_secretaria']['cargo']                    = "Secretario/a";
$config['eqdirectivo_secretaria']['telefono']                 = "";
$config['eqdirectivo_secretaria']['correoe']                  = "";

$config['eqdirectivo_administrador']['nombre']                = "";
$config['eqdirectivo_administrador']['cargo']                 = "Administrador/a";
$config['eqdirectivo_administrador']['telefono']              = "";
$config['eqdirectivo_administrador']['correoe']               = "";

// DATOS DEL CENTRO EN EL DIRECTORIO COMÚN DE UNIDADES ORGÁNICAS Y OFICINAS (DIR3)
// Únicamente hay que modificar el valor del órgano gestor
$config['dir3']['oficina_contable'] = "A01004456"; // Intervención General de la Junta de Andalucía
$config['dir3']['unidad_tramitadora'] = "GE0010526"; // Centros educativos
$config['dir3']['organo_gestor'] = "";

// CÓDIGO PERSONALIZADO EN BLOQUE DE CONTENIDO DE LA PÁGINA DE INICIO
// Es necesario descomentar el bloque correspondiente y rellenarlo de la siguiente manera:
// En el campo 'titulo' se escribe el nombre del módulo, que aparecerá en la parte superior. Si se deja en blanco se mostrará el contenido HTML únicamente.
// En el campo 'html' se escribe el código HTML de lo que necesite añadir, por ejemplo: menú de enlaces, imágenes, vídeos, etc.
// ¡CUIDADO! Es necesario escribir una barra inversa para escapar el caracter comilla (') correctamente (\')
// Puede añadir tantos bloques como necesite, solo debe cambiar el número del array multidimensional: [0], [1], [2],...

/*
$config['content_html']['top'][0]['titulo'] = "";
$config['content_html']['top'][0]['html'] = '';
*/

/*
$config['content_html']['bottom'][0]['titulo'] = "";
$config['content_html']['bottom'][0]['html'] = '';
*/

// CÓDIGO PERSONALIZADO EN LA BARRA LATERAL DE LA PÁGINA DE INICIO
// Es necesario descomentar el bloque correspondiente y rellenarlo de la siguiente manera:
// En el campo 'titulo' se escribe el nombre del módulo, que aparecerá en la parte superior. Si se deja en blanco se mostrará el contenido HTML únicamente.
// En el campo 'html' se escribe el código HTML de lo que necesite añadir, por ejemplo: menú de enlaces, imágenes, vídeos, etc.
// ¡CUIDADO! Es necesario escribir una barra inversa para escapar el caracter comilla (') correctamente (\')
// Puede añadir tantos bloques como necesite, solo debe cambiar el número del array multidimensional: [0], [1], [2],...

$config['sidebar_html'][0]['titulo'] = "Enlaces de interés";
$config['sidebar_html'][0]['html'] = '
<ul class="list-unstyled">
    <li><a href="http://www.juntadeandalucia.es/educacion/portals/web/ced" target="_blank">Consejería de Educación y Deporte</a></li>
    <li><a href="https://www.educacionyfp.gob.es/" target="_blank">Ministerio de Educación y FP</a></li>
    <li><a href="https://www.juntadeandalucia.es/educacion/portaldocente/" target="_blank">Portal Docente</a></li>
    <li><a href="http://www.juntadeandalucia.es/educacion/portals/web/becas-y-ayudas" target="_blank">Portal de Becas y Ayudas</a></li>
    <li><a href="http://www.juntadeandalucia.es/educacion/webportal/web/convivencia-escolar" target="_blank">Portal de Convivencia Escolar</a></li>
    <li><a href="http://www.juntadeandalucia.es/educacion/portals/web/escolarizacion" target="_blank">Portal de Escolarización</a></li>
    <li><a href="http://www.juntadeandalucia.es/educacion/webportal/web/portal-de-igualdad" target="_blank">Portal de Igualdad</a></li>
    <li><a href="http://www.juntadeandalucia.es/educacion/webportal/web/lecturas-y-bibliotecas-escolares" target="_blank">Portal de Lectura y Biblioteca escolares</a></li>
    <li><a href="http://www.juntadeandalucia.es/educacion/webportal/web/portal-de-plurilinguismo" target="_blank">Portal de Plurilingüismo</a></li>
    <li><a href="http://www.juntadeandalucia.es/temas/estudiar/universidad/acceso.html" target="_blank">Prueba de Acceso a la Universidad</a></li>
    <li><a href="http://www.juntadeandalucia.es/temas/estudiar/fp/pruebas-acceso.html" target="_blank">Prueba de Acceso a Ciclos Formativos</a></li>
    <li><a href="https://www.juntadeandalucia.es/educacion/secretariavirtual/" target="_blank">Secretaría Virtual de los centros educativos</a></li>
</ul>
';

$config['sidebar_html'][1]['titulo'] = "";
$config['sidebar_html'][1]['html'] = '
<a href="http://www.codapa.org/wp-content/uploads/2011/09/gderechos-secundaria.pdf" target="_blank">
    <img class="img-responsive" src="'.WEBCENTROS_DOMINIO.'ui-theme/img/gderechos-secundaria.png" alt="Guía de derechos y responsabilidades de las familias andaluzas en la educación">
</a>
';

$config['sidebar_html'][2]['titulo'] = "Erasmus+ 2014-2020";
$config['sidebar_html'][2]['html'] = '
<a href="http://www.sepie.es" target="_blank">
    <img class="img-responsive" src="'.WEBCENTROS_DOMINIO.'ui-theme/img/erasmus.png" alt="Cofinanciado por el programa Erasmus+ de la Unión Europea">
</a>
';

/*
$config['sidebar_html'][2]['titulo'] = "";
$config['sidebar_html'][2]['html'] = '';
*/

// ESCOLARIZACION
$config['escolarizacion']['tramites'] = true;
$config['escolarizacion']['calendario'] = true;

// CAROUSEL
// Use el valor true si desea que el carousel cambie automaticamente las imágenes o false si desea que permanezca estático.
$config['carousel_config']['cycle'] = false;

// Comente / descomente el bloque para ocultar o mostrar, respectivamente, un carousel de imagenes en la página principal.
// En el campo 'imagen' se escribe la URL completa de la imagen. La imagen debe tener un tamaño de 1218 x 580 pixeles. Este campo es obligatorio.
// En el campo 'titulo' se escribe el título de la noticia, que aparecerá en la parte superior. Si se deja en blanco se mostrará el contenido únicamente.
// En el campo 'contenido' se escribe el texto o código HTML del contenido que desee añadir para acompañar a la imagen. Este campo es opcional.
// En el campo 'enlace' se escribe la URL a la que desee que el usuario vaya si hace clic en la imagen. Este campo es opcional.
// ¡CUIDADO! Es necesario escribir una barra inversa para escapar el caracter comilla (') correctamente (\')
// Consejo: Es importante optimizar las imagenes para la publicación en la web, de esta manera conseguimos que la página cargue mucho más rápido
// y los usuarios ahorren megas en dispositivos móviles. Utiliza la página https://imagecompressor.com/es/ para comprimir las imagenes.
// Puede añadir tantos bloques como necesite, solo debe cambiar el número del array multidimensional: [0], [1], [2],...

$config['carousel'][0]['imagen'] = WEBCENTROS_DOMINIO . 'ui-theme/img/slide/slide1.jpg?uid='.uniqid();
$config['carousel'][0]['titulo'] = 'Recopilación de información relativa al COVID-19';
$config['carousel'][0]['contenido'] = '<p>Resumen de informaciones publicadas en el portal de la Consejería de Educación y Deporte relativa a las circunstancias provocadas por la pandemia COVID-19.</p>';
$config['carousel'][0]['enlace'] = 'http://www.juntadeandalucia.es/educacion/portals/web/ced/servicios/covid-19';

$config['carousel'][1]['imagen'] = WEBCENTROS_DOMINIO . 'ui-theme/img/slide/slide2.jpg?uid='.uniqid();
$config['carousel'][1]['titulo'] = 'Escolarización 2020/21';
$config['carousel'][1]['contenido'] = '<p>Plazo de escolarización<br>1 al 30 de abril</p>';
$config['carousel'][1]['enlace'] = 'http://www.juntadeandalucia.es/educacion/portals/web/escolarizacion/infantil-a-bachillerato';

$config['carousel'][2]['imagen'] = WEBCENTROS_DOMINIO . 'ui-theme/img/slide/slide3.jpg?uid='.uniqid();
$config['carousel'][2]['titulo'] = 'Inicio del curso escolar 2019/2020';
$config['carousel'][2]['contenido'] = '<p class="mt-4 mb-2 text-center"><a class="highlight text-center" href="http://www.juntadeandalucia.es/educacion/portals/delegate/content/206a3822-8ad1-4b40-af66-9e332788b245/Dossier%20inicio%20de%20curso%2019-20" target="_blank">Dossier inicio de curso</a></p><p class="mb-2"><a class="highlight text-center" href="http://www.juntadeandalucia.es/educacion/portals/delegate/content/630d5087-3b19-4807-aa53-5941931bd115/Datos%20y%20cifras%20inicio%20curso%202019_2020" target="_blank">Datos y cifras inicio de curso</a></p>';

$config['carousel'][3]['imagen'] = WEBCENTROS_DOMINIO . 'ui-theme/img/slide/slide4.jpg?uid='.uniqid();
$config['carousel'][3]['titulo'] = 'Admisión 2019/20';
$config['carousel'][3]['contenido'] = '<p>Nuevo portal de Escolarización segura del alumnado con enfermedades crónicas</p><p class="mb-2">Alergias &middot; Diabetes &middot; Epilepsia &middot; Cardiopatías &middot; Asma &middot; Cáncer</p>';
$config['carousel'][3]['enlace'] = 'http://www.juntadeandalucia.es/educacion/portals/web/escolarizacion-segura/inicio';

// GOOGLE ANALYTICS
// Consigue el ID de seguimiento para usar la API de Google Analytics en https://analytics.google.com/analytics/
//$config['google_analytics']['tracking_id'] = 'YOUR_GA_TRACKING_ID';

// GOOGLE MAPS API
// Consigue la clave para usar la API de Google Maps Javascript en https://console.cloud.google.com/
// Puedes obtener las coordenadas de tu centro educativo en https://www.coordenadas-gps.com
//$config['google_maps']['api_key'] = 'YOUR_API_KEY';
//$config['google_maps']['latitud'] = 36.4295948;
//$config['google_maps']['longitud'] = -5.154448600000023;
//$config['google_maps']['zoom'] = 15;

// GOOGLE reCAPTCHA
// Consigue la clave para usar la API de Google reCAPTCHA v2 en https://www.google.com/recaptcha/admin/create
// Copia la clave del sitio y la clave secreta en las siguientes variables de configuración
//$config['google_recaptcha']['site_key'] = 'YOUR_SITE_KEY';
//$config['google_recaptcha']['secret'] = 'YOUR_SECRET_CODE';

// Facebook Customer Chat
// https://developers.facebook.com/docs/messenger-platform/discovery/customer-chat-plugin/
$config['facebook_chat']['page_id'] = 'YOUR_PAGE_ID';
$config['facebook_chat']['theme_color'] = '#0084ff';
$config['facebook_chat']['welcome_message'] = '¡Hola! ¿En qué te podemos ayudar?';

// Por último, renombra este fichero como config.php

// Fin de archivo config.php
