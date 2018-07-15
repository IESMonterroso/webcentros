<?php
// CONFIGURACIÓN INICIAL
error_reporting(0);
date_default_timezone_set('Europe/Madrid');
setlocale(LC_TIME, 'es_ES.UTF-8');

// OBTENEMOS LA URL DE LA PÁGINA WEB
if ($_SERVER['SERVER_PORT'] != 80 && $_SERVER['SERVER_PORT'] != 443) $_servername = $_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'];
else $_servername = $_SERVER['SERVER_NAME'];
if (! $_SERVER['HTTPS']) $_servername = "http://".$_servername."/";
else $_servername = "https://".$_servername."/";

// DEFINIMOS UNA CONSTANTE CON EL DOMINIO DE LA WEB Y EL DIRECTORIO DONDE ESTÁ INSTALADO
define("WEBCENTROS_DOMINIO", $_servername);
define("WEBCENTROS_DIRECTORY", __DIR__);

// OBTENEMOS LA CONFIGURACIÓN DE LA INTRANET
if (@file_exists("./intranet/config.php")) require_once("./intranet/config.php");
if (@file_exists("../intranet/config.php")) require_once("../intranet/config.php");
if (@file_exists("../../intranet/config.php")) require_once("../../intranet/config.php");
if (@file_exists("../../../intranet/config.php")) require_once("../../../intranet/config.php");
if (@file_exists("../../../../intranet/config.php")) require_once("../../../../intranet/config.php");
$db_con = mysqli_connect($config['db_host'], $config['db_user'], $config['db_pass'], $config['db_name']) or die("<h1>Error " . mysqli_connect_error() . "</h1>");
mysqli_query($db_con,"SET NAMES 'utf8'");

// ESCAPE DE CARACTERES PARA REALIZAR ALIAS, NECESARIO PARA GENERAR URL AMIGABLES
$acentos = array('.','_',' ','*','--',',',';',':','¡','!','"','\'','@','#','$','%','&','/','(',')','[',']','{','}','<','>','+','|','\\','·','=','¬','?','¿','^','º','ª','`','´','ñ','Ñ','ç','Á','É','Í','Ó','Ú','á','é','í','ó','ú','À','È','Ì','Ò','Ù','à','è','ì','ò','ù','Â','Ê','Î','Ô','Û','â','ê','î','ô','û','Ä','Ë','Ï','Ö','Ü','ä','ë','ï','ö','ü');
$no_acentos = array('','-','-','','-','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','n','N','c','a','e','i','o','u','a','e','i','o','u','a','e','i','o','u','a','e','i','o','u','a','e','i','o','u','a','e','i','o','u','a','e','i','o','u','a','e','i','o','u');
$no_acentos_con_espacio = array('','-',' ','','-','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','n','N','c','a','e','i','o','u','a','e','i','o','u','a','e','i','o','u','a','e','i','o','u','a','e','i','o','u','a','e','i','o','u','a','e','i','o','u','a','e','i','o','u');

// CARGAMOS LA FUNCIÓN DE FILTROS PARA EVITAR ATAQUES XSS EN LOS CAMPOS DE FORMULARIO
require_once(WEBCENTROS_DIRECTORY.'/plugins/cleanxss.php');

// COMPROBAMOS DATOS PARA LA CARGA DE MÓDULOS
$result_libros_texto = mysqli_query($db_con, "SELECT `isbn` FROM `libros_texto`") or die (mysqli_error($db_con));
if (mysqli_num_rows($result_libros_texto)) {
	$config['libros_texto'] = 1;
}
else {
	$config['libros_texto'] = 0;
}
mysqli_free_result($result);

// FUNCIONES GENERALES
function getRealIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP']))
        return $_SERVER['HTTP_CLIENT_IP'];

    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
        return $_SERVER['HTTP_X_FORWARDED_FOR'];

    return $_SERVER['REMOTE_ADDR'];
}

function cortarTexto($texto, $numMaxCaract) {
	if (strlen($texto) <  $numMaxCaract){
		$textoCortado = $texto;
	}else{
		$textoCortado = substr($texto, 0, $numMaxCaract);
		$ultimoEspacio = strripos($textoCortado, " ");

		if ($ultimoEspacio !== false){
			$textoCortadoTmp = substr($textoCortado, 0, $ultimoEspacio);
			if (substr($textoCortado, $ultimoEspacio)){
				$textoCortadoTmp .= '...';
			}
			$textoCortado = $textoCortadoTmp;
		}elseif (substr($texto, $numMaxCaract)){
			$textoCortado .= '...';
		}
	}

	return $textoCortado;
}

function ofuscarEmail($email) {
	$result = '';

	// Encode string using oct and hex character codes
	for ($i = 0; $i < strlen($email); $i++) {
		$result .= '&#x' . dechex(ord($email[$i])) . ';';
	}

	return $result;
}

function nombreProfesorTitle($nombre) {
	return mb_convert_case($nombre, MB_CASE_TITLE, "UTF-8");
}

function obtenerHoraTutoria($db_con, $dia, $hora) {

	if (empty($dia) && empty($hora)) {
		return false;
	}
	else {
		switch ($dia) {
			case '1': $diasem = "Lunes"; break;
			case '2': $diasem = "Martes"; break;
			case '3': $diasem = "Miércoles"; break;
			case '4': $diasem = "Jueves"; break;
			case '5': $diasem = "Viernes"; break;
			case '6': $diasem = "Sábado"; break;
			case '7': $diasem = "Domingo"; break;
		}

		$result = mysqli_query($db_con, "SELECT `hora_inicio`, `hora_fin` FROM `tramos` WHERE `hora` = '$hora' LIMIT 1");

		if (mysqli_num_rows($result)) {
			$row = mysqli_fetch_array($result);

			$hora_ini = substr($row['hora_inicio'], 0, 5);
			$hora_fin = substr($row['hora_fin'], 0, 5);

			return $diasem . " de " . $hora_ini . ' a ' . $hora_fin . ' horas';
		}
		else {
			return 1;
		}
	}
}

/*
	La función cmyk_rgb convierte un color CMYK en RGB y devuelve el código CSS correspondiente
*/
function cmyk_rgb($c, $m, $y, $k) {
	$c = $c / 100;
	$m = $m / 100;
	$y = $y / 100;
	$k = $k / 100;

	$r = 1 - ($c * (1 - $k)) - $k;
	$g = 1 - ($m * (1 - $k)) - $k;
	$b = 1 - ($y * (1 - $k)) - $k;

	$r = round($r * 255);
	$g = round($g * 255);
	$b = round($b * 255);

	$rgb = 'rgb(' . $r . ', ' . $g . ', ' . $b . ')';

	return $rgb;
}
/*
	La función cmykcolor comprueba si el formato CMYK es válido y devuelve el código CSS.
	La variable $color recibe el color que el usuario ha proporcionado.
	La variable $rgb recibe el valor 1 o 0 si se desea devolver el código CSS en formato RGB.
	La variable $tono modifica el color introducido por el usuario para aclarar
	(introduciendo el valor light) u oscurecer (introduciendo el valor dark).
*/
function cmykcolor($color, $rgb = false, $tono = false) {
		$tonalidad = 0;
		$color = str_replace('%', '', $color);
		$color = str_replace(' ', '', $color);

		if ($tono !== false) {
			switch ($tono) {
				case 'light'  : $tonalidad -= 10; break;
				case 'dark'   : $tonalidad += 10; break;
				default       : $tonalidad = 0;  break;
			}
		}

		$exp_cmyk = explode(',', $color);
		if (count($exp_cmyk) != 4) {
			die('Error CMYK Color : El número de valores del formato CMYK no es válido. Debe introducir 4 valores separados por coma.');
		}
		else {
			$cvalue = trim($exp_cmyk[0]);
			$mvalue = trim($exp_cmyk[1]) + $tonalidad;
			$yvalue = trim($exp_cmyk[2]) + $tonalidad;
			$kvalue = trim($exp_cmyk[3]);

			if (! ($cvalue >= 0 && $cvalue <= 100)) {
				die('Error CMYK Color : El porcentaje de color Cyan ' . $cvalue . ' no es válido. Debe ser un valor entre 0% y 100%.');
			}
			else if (! ($mvalue >= 0 && $mvalue <= 100)) {
				die('Error CMYK Color : El porcentaje de color Magenta ' . $mvalue . ' no es válido. Debe ser un valor entre 0% y 100%.');
			}
			else if (! ($yvalue >= 0 && $yvalue <= 100)) {
				die('Error CMYK Color : El porcentaje de color Yellow ' . $yvalue . ' no es válido. Debe ser un valor entre 0% y 100%.');
			}
			else if (! ($kvalue >= 0 && $kvalue <= 100)) {
				die('Error CMYK Color : El porcentaje de color blacK ' . $yvalue . ' no es válido. Debe ser un valor entre 0% y 100%.');
			}
			else {

			}

			$cmyk = 'cmyk(' . $cvalue . '%,' . $mvalue . '%,' . $yvalue . '%,' . $kvalue . '%)';

			if (! (preg_match("/cmyk\([0-9]{0,3}%,[0-9]{0,3}%,[0-9]{0,3}%,[0-9]{0,3}%\)/i", $cmyk))) {
				return false;
			}
			else {
				if ($rgb !== false) {
					return cmyk_rgb($cvalue, $mvalue, $yvalue, $kvalue);
				}
				else {
					return $cmyk;
				}
			}
		}
}

// Fin de archivo bootstrap.php
