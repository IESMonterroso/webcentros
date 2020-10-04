<?php defined('WEBCENTROS_DIRECTORY') OR exit('No direct script access allowed');

$unidad = $_SESSION['unidad'];

$query_evaluables = mysqli_query($db_con, "SELECT DISTINCT notas_cuaderno.profesor AS nomprofesor, asignaturas.NOMBRE AS nomasignatura, notas_cuaderno.id AS idactividad, notas_cuaderno.nombre AS nomactividad, notas_cuaderno.fecha AS fecactividad FROM notas_cuaderno JOIN asignaturas ON notas_cuaderno.asignatura = asignaturas.CODIGO WHERE notas_cuaderno.curso LIKE '%$unidad%' AND notas_cuaderno.visible_nota=1 order by fecactividad desc");
?>
<a name="evaluables"></a>
<br>
<?php $query_acteva = mysqli_query($db_con,"SELECT id, fechaini, unidades, nombre, asignaturas FROM calendario WHERE unidades like '%".$unidad."%' and date(fechaini)>'".$config['curso_inicio']."' and categoria > '2' order by fechaini"); ?>
<?php if (mysqli_num_rows($query_acteva)): 

$GLOBALS['db_con'] = $db_con;

// CALENDARIO
$dia_actual = date('d');

$dia  = isset($_GET['dia'])  ? $_GET['dia']  : date('d');
$mes  = isset($_GET['mes'])  ? $_GET['mes']  : date('n');
$anio = isset($_GET['anio']) ? $_GET['anio'] : date('Y');

$semana = 1;

for ($i = 1; $i <= date('t', strtotime($anio.'-'.$mes)); $i++) {
  
  $dia_semana = date('N', strtotime($anio.'-'.$mes.'-'.$i));
  
  $calendario[$semana][$dia_semana] = $i;
  if ($dia_semana == 7) $semana++;
  
}


// NAVEGACION
$mes_ant = $mes - 1;
$anio_ant = $anio;

if ($mes == 1) {
  $mes_ant = 12;
  $anio_ant = $anio - 1;
}


$mes_sig = $mes + 1;
$anio_sig = $anio;

if ($mes == 12) {
  $mes_sig = 1;
  $anio_sig = $anio + 1;
}

// HTML CALENDARIO MENSUAL
function vista_mes ($calendario, $dia, $mes, $anio, $unidad) {
  
  // Corrección en mes
  ($mes < 10) ? $mes = '0'.$mes : $mes = $mes;
  
  echo '<div class"table-responsive">'; 
  echo '<table id="calendar" class="table table-bordered">';
  echo '  <thead class="thead-dark">';
  echo '    <tr>';
  echo '      <th class="text-center">Lunes</th>';
  echo '      <th class="text-center">Martes</th>';
  echo '      <th class="text-center">Miércoles</th>';
  echo '      <th class="text-center">Jueves</th>';
  echo '      <th class="text-center">Viernes</th>';
  echo '    </tr>';
  echo '  </thead>';
  echo '  <tbody>';
  
  foreach ($calendario as $dias) {
    echo '    <tr>';
  
    for ($i = 1; $i <= 5; $i++) {
      
      if ($i > 5) {
        if (isset($dias[$i]) && ($mes == date('m')) && ($dias[$i] == date('d'))) {
          echo '      <td class="text-muted today" width="14.28%">';
        }
        else {
          echo '      <td class="text-muted" width="14.28%">';
        }
      }
      else {
        if (isset($dias[$i]) && ($mes == date('m')) && ($dias[$i] == date('d'))) {
          echo '      <td class="today" width="14.28%">';
        }
        else {
          echo '      <td width="14.28%">';
        }
      }
      
      if (isset($dias[$i])) {

        echo '        <p class="lead text-right"><strong>'.$dias[$i].'</strong></p>';
        
        // Corrección en día
        ($dias[$i] < 10) ? $dia0 = '0'.$dias[$i] : $dia0 = $dias[$i];
        
        
        // Consultamos los calendarios privados del equipo educativo de la unidad
        $result_equipo_educativo = mysqli_query($GLOBALS['db_con'], "SELECT DISTINCT prof, (SELECT idea FROM departamentos WHERE departamentos.nombre = h.prof) AS idea FROM horw AS h WHERE a_grupo = '$unidad'");
        while ($row_equipoeducativo = mysqli_fetch_assoc($result_equipo_educativo)) {
          
          $result_calendarios = mysqli_query($GLOBALS['db_con'], "SELECT id, color FROM calendario_categorias WHERE profesor = '".$row_equipoeducativo['idea']."' AND espublico=0");
          while ($calendario = mysqli_fetch_assoc($result_calendarios)) {
          
            $result_eventos = mysqli_query($GLOBALS['db_con'], "SELECT id, nombre, descripcion, fechaini, horaini, fechafin, horafin, asignaturas FROM calendario WHERE categoria='".$calendario['id']."' AND YEAR(fechaini)='$anio' AND MONTH(fechaini)='$mes' AND unidades LIKE '%$unidad%' ORDER BY horaini ASC, horafin ASC");
  
            while ($eventos = mysqli_fetch_assoc($result_eventos)) {
            
              $exp_asignaturas = explode('; ', $eventos['asignaturas']);
              $nomasignatura = trim($exp_asignaturas[0], ';');
              
              $horaini = substr($eventos['horaini'], 0, -3);
              $horafin = substr($eventos['horafin'], 0, -3);
              
              if ($eventos['fechaini'] != $eventos['fechafin'] && ($eventos['fechaini'] == $anio.'-'.$mes.'-'.$dia0)) {
                $hora_evento = 'Desde las '.$horaini;
              }
              else if ($eventos['fechaini'] != $eventos['fechafin'] && ($eventos['fechafin'] == $anio.'-'.$mes.'-'.$dia0)) {
                $hora_evento = 'Hasta las '.$horafin;
              }
              else if($eventos['fechaini'] != $eventos['fechafin'] || ($eventos['fechaini'] == $eventos['fechafin'] && $eventos['horaini'] == $eventos['horafin'])) {
                $hora_evento = 'Todo el día';
              }
              else {
                $hora_evento = $horaini.' - '.$horafin;
              }
              
              if ($anio.'-'.$mes.'-'.$dia0 >= $eventos['fechaini'] && $anio.'-'.$mes.'-'.$dia0 <= $eventos['fechafin']) {
                echo '<div class="badge badge-pill badge-primary label" data-toggle="tooltip" title="'.$eventos['descripcion'].'"><p><strong>'.$hora_evento.': '.$nomasignatura.'</strong></p>'.$eventos['nombre'].'</div>';
              }
              
              unset($nomasignatura);
              unset($horaini);
              unset($horafin);
            }
            mysqli_free_result($result_eventos);
          }
          mysqli_free_result($result_calendarios);
        }
        mysqli_free_result($result_equipo_educativo);
        
        
        // Consultamos los calendarios públicos
        $result_calendarios = mysqli_query($GLOBALS['db_con'], "SELECT id, color FROM calendario_categorias WHERE espublico=1");
        while ($calendario = mysqli_fetch_assoc($result_calendarios)) {
          
          $result_eventos = mysqli_query($GLOBALS['db_con'], "SELECT id, nombre, descripcion, fechaini, horaini, fechafin, horafin, departamento, observaciones FROM calendario WHERE categoria='".$calendario['id']."' AND YEAR(fechaini)='$anio' AND MONTH(fechaini)='$mes' AND unidades LIKE '%$unidad%' ORDER BY horaini ASC, horafin ASC");
          
          while ($eventos = mysqli_fetch_assoc($result_eventos)) {
            
            $horaini = substr($eventos['horaini'], 0, -3);
            $horafin = substr($eventos['horafin'], 0, -3);
            
            if ($eventos['fechaini'] != $eventos['fechafin'] && ($eventos['fechaini'] == $anio.'-'.$mes.'-'.$dia0)) {
              $hora_evento = 'Desde las '.$horaini;
            }
            else if ($eventos['fechaini'] != $eventos['fechafin'] && ($eventos['fechafin'] == $anio.'-'.$mes.'-'.$dia0)) {
              $hora_evento = 'Hasta las '.$horafin;
            }
            else if($eventos['fechaini'] != $eventos['fechafin'] || ($eventos['fechaini'] == $eventos['fechafin'] && $eventos['horaini'] == $eventos['horafin'])) {
              $hora_evento = 'Todo el día';
            }
            else {
              $hora_evento = $horaini.' - '.$horafin;
            }
            
            if ($anio.'-'.$mes.'-'.$dia0 >= $eventos['fechaini'] && $anio.'-'.$mes.'-'.$dia0 <= $eventos['fechafin']) {
              echo '<div class="badge badge-success label" data-toggle="tooltip" data-html="true" title="<p class=text-left>'.$eventos['descripcion'].'</p>"><p><strong>'.$hora_evento.'</strong></p>'.$eventos['nombre'].'</div>';
            }
            
            unset($horaini);
            unset($horafin);
          }
          mysqli_free_result($result_eventos);
        }
        mysqli_free_result($result_calendarios);
        
        // FESTIVOS
        $result = mysqli_query($GLOBALS['db_con'], "SELECT fecha, nombre FROM festivos");
        while ($festivo = mysqli_fetch_assoc($result)) {
          
          if ($festivo['fecha'] == $anio.'-'.$mes.'-'.$dia0) {
            echo '<div class="badge badge-info label" data-toggle="tooltip" title="'.$festivo['nombre'].'">'.$festivo['nombre'].'</div>';
          }
        }
        mysqli_free_result($result);
        unset($festivo);
        
        
      }
      else {
        echo '&nbsp;';
      }
      
      echo '      </td>';
    }
    echo '    </tr>';
  }
  
  echo '  </tbody>';
  echo '</table>';
  echo '</div>';

}

?>    
    
    <style type="text/css">
    .label {
      display: block;
      white-space: normal;
      text-align: left;
      margin-top: 5px;
      font-size: 0.9em;
      font-weight: 400;
    }
    
    p.lead {
      margin-bottom: 0;
    }
    
    </style> 
    
    <!-- SCAFFOLDING -->
    <div class="row">
      
      <!-- COLUMNA CENTRAL -->
      <div class="col-md-12">
          <ul id="nav_actividades" class="nav nav-tabs nav-tabs-neutral justify-content-center bg-primary" role="tablist">
            <li class="nav-item"><a class="nav-link active" href="#calendario_act" role="tab" data-toggle="tab">Calendario de actividades</a></li>
            <li class="nav-item"><a class="nav-link" href="#resultados_act" role="tab" data-toggle="tab">Resultados de las actividades</a></li>
          </ul>

          <br>         

          <div class="tab-content">
            <div class="tab-pane active" id="calendario_act">
              
              <!-- TITULO DE LA PAGINA -->
              <br>
              <h3>Calendario de actividades de <?php echo $unidad; ?></h3>   

              <a name="calendario_act"></a>
             
              <div class="float-left">
                <legend class="text-muted"><?php echo strftime('%B, %Y', strtotime($anio.'-'.$mes)); ?></legend>  
              </div>  

            <div class="float-right">           
              <div class="btn-group">
                <a href="?mes=<?php echo $mes_ant; ?>&anio=<?php echo $anio_ant; ?>&unidad=<?php echo $unidad; ?>#evaluables" class="btn btn-default">&laquo;</a>
                <a href="?mes=<?php echo date('n'); ?>&anio=<?php echo date('Y'); ?>&unidad=<?php echo $unidad; ?>#evaluables" class="btn btn-default">Hoy</a>
                <a href="?mes=<?php echo $mes_sig; ?>&anio=<?php echo $anio_sig; ?>&unidad=<?php echo $unidad; ?>#evaluables" class="btn btn-default">&raquo;</a>
              </div>            
            </div>

            <div class="clearfix"></div>
            <br class="hidden-print">
          
            <?php vista_mes($calendario, $dia, $mes, $anio, $unidad); ?>
            
          </div>

          <div class="tab-pane" id="resultados_act">
            
            <a name="resultados_act"></a>
            <br>
            <h3>Resultados de actividades evaluables</h3>

            <?php if(mysqli_num_rows($query_evaluables)): ?> 
            <table class="table table-bordered table-striped">
              <thead class="thead-dark">
                <tr>
                  <th>Actividad</th>
                  <th>Asignatura</th>
                  <th>Fecha</th>
                  <th>Calificación</th>
                </tr>
              </thead>
              <tbody>
                <?php while ($actividad = mysqli_fetch_array($query_evaluables)): ?>
                <tr>
                  <td><?php echo $actividad['nomactividad'];  ?></td>
                  <td><?php echo $actividad['nomasignatura']; ?></td>
                  <td><?php echo $actividad['fecactividad'];  ?></td>
                  <?php
                  $query_calificacion = mysqli_query($db_con, "SELECT nota FROM datos WHERE claveal='$claveal' AND id='".$actividad['idactividad']."'");
                  $actividad_nota = mysqli_fetch_array($query_calificacion);
                  ?>
                  <td><?php echo $actividad_nota['nota']; ?></td>
                </tr>
                <?php endwhile; ?>
              </tbody>
            </table>
            </div>

          </div><!-- /.tab-content -->

      </div><!-- /.col-md-12 -->
      
    </div><!-- /.row -->

<?php else: ?>

<div class="justify-content-center">
  <p class="lead text-muted text-center p-5">No se han registrado actividades evaluables</p>
</div>

<?php endif; ?>



<?php else: ?>

<div class="justify-content-center">
  <p class="lead text-muted text-center p-5">No se han registrado resultados de actividades evaluables</p>
</div>

<?php endif; ?>

