<?php
if (! isset($_POST['data_value']) || empty($_POST['data_value'])) die ('No direct script access allowed');

require_once("../../bootstrap.php");
require_once("../../config.php");

$jsondata['result'] = '';
$jsondata['data'] = array();

$data_value = htmlspecialchars($_POST['data_value']);
$dni = mysqli_real_escape_string($db_con, $data_value);

// Comprobamos el DNI/NIE del alumno/a
$result = mysqli_query($db_con, "SELECT `apellidos`, `nombre`, `unidad`, `curso` FROM `alma` WHERE `dni` = '$dni' LIMIT 1");
if (mysqli_num_rows($result)) {
  $jsondata['result'] = 'ok';

  $row = mysqli_fetch_array($result);

  $datos_alumno = array(
    "apellidos" => $row['apellidos'],
    "nombre" => $row['nombre'],
    "unidad" => $row['unidad'],
    "curso" => $row['curso']
  );

  array_push($jsondata['data'], $datos_alumno);

  mysqli_free_result($result);
}
else {
  // Comprobamos el DNI/NIE del tutor legal 1
  $result = mysqli_query($db_con, "SELECT `apellidos`, `nombre`, `unidad`, `curso` FROM `alma` WHERE `dnitutor` = '$dni'");
  if (mysqli_num_rows($result)) {
    $jsondata['result'] = 'ok';

    while ($row = mysqli_fetch_array($result)) {
      $datos_alumno = array(
        "apellidos" => $row['apellidos'],
        "nombre" => $row['nombre'],
        "unidad" => $row['unidad'],
        "curso" => $row['curso']
      );

      array_push($jsondata['data'], $datos_alumno);
      unset($datos_alumno);
    }
  }
  else {
    // Comprobamos el DNI/NIE del tutor legal 2
    $result = mysqli_query($db_con, "SELECT `apellidos`, `nombre`, `unidad`, `curso` FROM `alma` WHERE `dnitutor2` = '$dni'");
    if (mysqli_num_rows($result)) {
      $jsondata['result'] = 'ok';

      while ($row = mysqli_fetch_array($result)) {
        $datos_alumno = array(
          "apellidos" => $row['apellidos'],
          "nombre" => $row['nombre'],
          "unidad" => $row['unidad'],
          "curso" => $row['curso']
        );

        array_push($jsondata['data'], $datos_alumno);
        unset($datos_alumno);
      }
    }
  }
}

header('Content-type: application/json; charset=utf-8');
echo json_encode($jsondata);
exit();
