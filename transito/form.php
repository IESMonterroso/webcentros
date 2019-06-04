<?php if ($alumno) { ?>
<?
$ya_hay=mysqli_query($db_con,"select * from transito_datos where claveal='$claveal'");
if (mysqli_num_rows($ya_hay)>0) {
	$proc=1;
	while ($ya=mysqli_fetch_array($ya_hay)) {
		${$ya[2]}=$ya[3];
	}
}
?>
<?php 
 if (stristr($repeticion,"2")==TRUE) {$r2="checked";}
 if (stristr($repeticion,"4")==TRUE) {$r4="checked";}
 if (stristr($repeticion,"6")==TRUE) {$r6="checked";}
 ?>
<?php if ($asiste==0 or $asiste=="") {$as0="checked";}elseif ($asiste==1) {$as1="checked";}elseif ($asiste==2) {$as2="checked";}elseif ($asiste==3) {$as3="checked";}else{$asiste=="";} ?>
<?php 
 if (stristr($dificultad,"1")==TRUE) {$d1="checked";}
 if (stristr($dificultad,"2")==TRUE) {$d2="checked";}
 if (stristr($dificultad,"3")==TRUE) {$d3="checked";}
 if (stristr($dificultad,"4")==TRUE) {$d4="checked";}
 if (stristr($dificultad,"5")==TRUE) {$d5="checked";}
 if (stristr($dificultad,"6")==TRUE) {$d6="checked";}
 if (stristr($dificultad,"7")==TRUE) {$d7="checked";}
?>
<?php 
 if (stristr($refuerzo,"Leng")==TRUE) {$ref1="checked";}
 if (stristr($refuerzo,"Mat")==TRUE) {$ref2="checked";}
 if (stristr($refuerzo,"Ing")==TRUE) {$ref3="checked";}
?>
<?php 
 if (stristr($adcurrsign,"1")==TRUE) {$acs="checked";}
 if (stristr($adcurrnosign,"1")==TRUE) {$acns="checked";}
 if (stristr($necadcurrsign,"1")==TRUE) {$nacs="checked";}
 if (stristr($necadcurrnosign,"1")==TRUE) {$nacns="checked";}
?>
<?php 
 if ($acompanamiento) {$acomp="checked";}
 if ($exento) {$exen="checked";}
?>
<?php 
if ($PT_AL=="SI") {$ptal1="checked";}elseif ($PT_AL=="NO") {$ptal2="checked";}
if ($PT_AL_aula=="Aula") {$ptalaula1="checked";}elseif ($PT_AL_aula=="Fuera") {$ptalaula2="checked";}
?>
<?php 
 if ($atal) {$atl="checked";}
 if ($necatal) {$necatl="checked";}
?>
<?php if ($nacion==1) {$n1="checked";}elseif ($nacion==2) {$n2="checked";}elseif ($nacion==3) {$n3="checked";}elseif ($nacion==4) {$n4="checked";} ?>
<?php if ($integra==1) {$int1="checked";}elseif ($integra==2) {$int2="checked";}elseif ($integra==3) {$int3="checked";}elseif ($integra==4) {$int4="checked";}elseif ($integra==5) {$int5="checked";} ?>
<?php if ($relacion==1) {$rel1="checked";}elseif ($relacion==2) {$rel2="checked";}elseif ($relacion==3) {$rel3="checked";}?>
<?php if ($disruptivo==1) {$dis1="checked";}elseif ($disruptivo==2) {$dis2="checked";}elseif ($disruptivo==3) {$dis3="checked";}?>
<?php if ($expulsion==1) {$exp1="checked";}elseif ($expulsion==2) {$exp2="checked";}?>


<form  method="post">
<input type="hidden" name="auth" value="1" />
<input type="hidden" name="colegi" value="<?php echo $colegio;?>" />
<input type="hidden" name="unidad" value="<?php echo $unidad;?>" />
<input type="hidden" name="alumno" value="<?php echo $alumno;?>" />

<div class="form-group">
<legend class="muted">TUTOR</legend>
<label class="form-check-label">
  <input type="text" class="form-control" name="tutor" value="<?php echo $tutor;?>" placeholder="Nombre y Apellidos del Tutor del Grupo" required style="width:350px">
</label>
</div>
<hr>

<legend class="muted">ÁMBITO ACADÉMICO</legend>

<h5 class="text-info">Cursos Repetidos</h5>
<div class="form-check form-check-inline">
  <input class="form-check-input" type="checkbox" name="repeticion[]" value="2 " <?php echo $r2;?>> 
<label class="form-check-label">2º Curso &nbsp;&nbsp;&nbsp;
</label> 
  <input class="form-check-input" type="checkbox" name="repeticion[]" value="4 " <?php echo $r4;?>> 
<label class="form-check-label">4º Curso &nbsp;&nbsp;&nbsp;
</label>
  <input class="form-check-input" type="checkbox" name="repeticion[]" value="6 " <?php echo $r6;?>> 
<label class="form-check-label">6º Curso
</label>
</div>
<br>
<hr>
<h5 class="text-info">Nº de Suspensos</h5>
<div class="form-group">
<label class="form-check-label">1ª Evaluación</label>
<select name="susp1" class="form-control col-md-2">
  <option><?php echo $susp1;?></option>
  <option>1</option>
  <option>2</option>
  <option>3</option>
  <option>4</option>
  <option>5</option>
  <option>6</option>
  <option>7</option>
</select>
<label class="form-check-label">2ª Evaluación</label>
<select name="susp2" class="form-control col-md-2">
  <option><?php echo $susp2;?></option>
  <option>1</option>
  <option>2</option>
  <option>3</option>
  <option>4</option>
  <option>5</option>
  <option>6</option>
  <option>7</option>
</select>
<label class="form-check-label">3ª Evaluación</label>
<select name="susp3" class="form-control col-md-2">
  <option><?php echo $susp3;?></option>
  <option>1</option>
  <option>2</option>
  <option>3</option>
  <option>4</option>
  <option>5</option>
  <option>6</option>
  <option>7</option>
  </select>
</div>
<hr>

<h5 class="text-info">Notas Finales</h5>
<div class="form-group">
<label class="form-check-label">Lengua</label>
<select name="leng" class="form-control col-md-2" >
<option><?php echo $leng;?></option>
  <option>1</option>
  <option>2</option>
  <option>3</option>
  <option>4</option>
  <option>5</option>
  <option>6</option>
  <option>7</option>
  <option>8</option>
  <option>9</option>
  <option>10</option>
</select>
<label class="form-check-label">Matemáticas</label>
<select name="mat" class="form-control col-md-2" >
  <option><?php echo $mat;?></option>
  <option>1</option>
  <option>2</option>
  <option>3</option>
  <option>4</option>
  <option>5</option>
  <option>6</option>
  <option>7</option>
  <option>8</option>
  <option>9</option>
  <option>10</option>
</select>
<label class="form-check-label">Inglés</label>
<select name="ing" class="form-control col-md-2" >
<option><?php echo $ing;?></option>
  <option>1</option>
  <option>2</option>
  <option>3</option>
  <option>4</option>
  <option>5</option>
  <option>6</option>
  <option>7</option>
  <option>8</option>
  <option>9</option>
  <option>10</option>
</select>
<label class="form-check-label">Conocimiento</label>
<select name="con" class="form-control col-md-2" >
<option><?php echo $con;?></option>
  <option>1</option>
  <option>2</option>
  <option>3</option>
  <option>4</option>
  <option>5</option>
  <option>6</option>
  <option>7</option>
  <option>8</option>
  <option>8</option>
  <option>10</option>
</select>
<label class="form-check-label">Ed. Física</label>
<select name="edfis" class="form-control col-md-2" >
<option><?php echo $edfis;?></option>
  <option>1</option>
  <option>2</option>
  <option>3</option>
  <option>4</option>
  <option>5</option>
  <option>6</option>
  <option>7</option>
  <option>8</option>
  <option>9</option>
  <option>10</option>
</select>
<label class="form-check-label">Música</label>
<select name="mus" class="form-control col-md-2" >
<option><?php echo $mus;?></option>
  <option>1</option>
  <option>2</option>
  <option>3</option>
  <option>4</option>
  <option>5</option>
  <option>6</option>
  <option>7</option>
  <option>8</option>
  <option>9</option>
  <option>10</option>
</select>
<label class="form-check-label">Plástica</label>
<select name="plas" class="form-control col-md-2" >
<option><?php echo $plas;?></option>
  <option>1</option>
  <option>2</option>
  <option>3</option>
  <option>4</option>
  <option>5</option>
  <option>6</option>
  <option>7</option>
  <option>8</option>
  <option>9</option>
  <option>10</option>
</select>
</div>
<hr>

<h5 class="text-info">Asistencia</h5>
<div class="form-check form-check-inline">
<label class="form-check-label">
  <input type="radio" name="asiste" value="0" <?echo $as0;?>> Sin faltas de asistencia &nbsp;&nbsp;
</label>
<label class="form-check-label">
  <input type="radio" name="asiste" value="1" <?echo $as1;?>> Presenta faltas de asistencia &nbsp;&nbsp;
</label>
<label class="form-check-label">
  <input type="radio" name="asiste" value="2" <?echo $as2;?>> Falta más de lo normal &nbsp;&nbsp;
</label>
<label class="form-check-label">
  <input type="radio" name="asiste" value="3" <?echo $as3;?>> Absentismo &nbsp;&nbsp;
</label>
</div>
<hr>

<h5 class="text-info">Dificultades de Aprendizaje</h5>
<div class="form-check">

  <input class="form-check-input" type="checkbox" name="dificultad[]" value="1" <?php echo $d1;?>> <label class="form-check-label">Tiene carencias en aprendizajes básicos: "falta de base"
</label><br>
<label class="form-check-label">
  <input class="form-check-input" type="checkbox" name="dificultad[]" value="2" <?php echo $d2;?>>  Tiene dificultades en la lectura
</label><br>
<label class="form-check-label">
  <input class="form-check-input" type="checkbox" name="dificultad[]" value="3" <?php echo $d3;?>>  Tiene dificultades de comprensión oral / escrita
</label><br>
<label class="form-check-label">
  <input class="form-check-input" type="checkbox" name="dificultad[]" value="4" <?php echo $d4;?>>  Tiene dificultades de expresión oral / escrita
</label><br>
<label class="form-check-label">
  <input class="form-check-input" type="checkbox" name="dificultad[]" value="5" <?php echo $d5;?>>  Tiene dificultades de razonamiento matemático
</label><br>
<label class="form-check-label">
  <input class="form-check-input" type="checkbox" name="dificultad[]" value="6" <?php echo $d6;?>>  Tiene dificultades en hábitos /  método de estudio
</label><br>
<label class="form-check-label">
  <input class="form-check-input" type="checkbox" name="dificultad[]" value="7" <?php echo $d7;?>>  Tiene dificultades de cálculo.
</label>
</div>
<hr>
<br>

<legend class="muted">REFUERZOS, ATENCIÓN A LA DIVERSIDAD, ADAPTACIONES</legend>
<h5 class="text-info">Refuerzos</h5>
<h6 class="text-success">Ha tenido Refuerzo:</h6>
<div class="form-check form-check-inline">
<label class="form-check-label">
  <input class="form-check-input" type="checkbox" name="refuerzo[]" value="Lengua " <?php echo $ref1;?>> Lengua&nbsp;&nbsp;
</label>
<label class="form-check-label">
  <input class="form-check-input" type="checkbox" name="refuerzo[]" value="Matemáticas " <?php echo $ref2;?>>Matemáticas&nbsp;&nbsp;
</label>
<label class="form-check-label">
  <input class="form-check-input" type="checkbox" name="refuerzo[]" value="Inglés " <?php echo $ref3;?>>Inglés
</label>
</div>
<br><br>
<h6 class="text-success">Necesita Refuerzo:</h6>
<p class="help-block">En caso necesario señalar orden de preferencia del Refuerzo.</p>
<div class="form-group">
<label class="form-check-label">&nbsp;&nbsp;Lengua</label>
<select name="necreflen" class="form-control col-md-2">
<option><?php echo $necreflen;?></option>
  <option>1</option>
  <option>2</option>
  <option>3</option>
</select>
&nbsp;&nbsp;
<label class="form-check-label">Matemáticas</label>
<select name="necrefmat" class="form-control col-md-2">
<option><?php echo $necrefmat;?></option>
  <option>1</option>
  <option>2</option>
  <option>3</option>
</select>
&nbsp;&nbsp;
<label class="form-check-label">Inglés</label>
<select name="necrefing" class="form-control col-md-2">
<option><?php echo $necrefing;?></option>
  <option>1</option>
  <option>2</option>
  <option>3</option>
</select>
</div>

<h6 class="text-success">Exención</h6>
<div class="form-check form-check-inline">
<label class="form-check-label">
  <input class="form-check-input" type="checkbox" name="exento" value="1" <?php echo $exen;?>> Alumnado que por sus dificultades no se le recomienda cursar optativa
</label>
</div>
<br><br>
<h6 class="text-success">Programa de Acompañamiento Escolar</h6>
<div class="form-check form-check-inline">
<label class="form-check-label">
  <input class="form-check-input" type="checkbox" name="acompanamiento" value="1" <?php echo $acomp;?>> Se aconseja asistencia al Programa de Acompañamiento Escolar
</label>
</div>
<hr>

<h5 class="text-info">Medidas de Atención a la Diversidad</h5>
<h6 class="text-success">Ha tenido Adaptación Curricular:</h6>
<div class="form-group">
<label class="form-check-label">Areas cursadas en la Adaptación Curricular Significativa
  <input type="text" class="form-control" style="width:400px" name="areasadcurrsign" value="<?php echo $areasadcurrsign;?>">
</label>
<br><br>
<label class="form-check-label">Areas cursadas en la Adaptación Curricular No Significativa
  <input type="text" class="form-control" style="width:400px" name="areasadcurrnosign" value="<?php echo $areasadcurrnosign;?>" >
</label>
<br><br>
<h6 class="text-success">Necesita Adaptación Curricular:</h6>
<label class="form-check-label">Areas cursadas en la Adaptación Curricular Significativa
  <input type="text" class="form-control" style="width:400px" name="necareasadcurrsign" value="<?php echo $necareasadcurrsign;?>">
</label>
<br><br>
<label class="form-check-label">Areas cursadas en la Adaptación Curricular No Significativa
  <input type="text" class="form-control" style="width:400px" name="necareasadcurrnosign" value="<?php echo $necareasadcurrnosign;?>" >
</label>
</div>
<br>
<h6 class="text-success">¿Ha sido atendido por PT o AL?</h6>
<div class="form-check form-check-inline">
<label class="form-check-label">
  <input type="radio" name="PT_AL" value="SI" <?php echo $ptal1;?>> Sí
</label>
&nbsp;
<label class="form-check-label">
  <input type="radio" name="PT_AL" value="NO" <?php echo $ptal2;?>> No
</label>
&nbsp;
<label class="form-check-label">
  <input type="radio" name="PT_AL_aula" value="Aula" <?php echo $ptalaula1;?>> Dentro del Aula
</label>
&nbsp;
<label class="form-check-label">
  <input type="radio" name="PT_AL_aula" value="Fuera" <?php echo $ptalaula2;?>> Fuera del Aula
</label>
</div>
<hr>

<h5 class="text-info">Alumnado de otra nacionalidad</h5>
<div class="form-check form-check-inline">
<label class="form-check-label">
  <input type="radio" name="nacion" value="4" <?php echo $n4;?>> No conoce el español
</label>
&nbsp;
<label class="form-check-label">
  <input type="radio" name="nacion" value="1" <?php echo $n1;?>> Nociones básicas de español
</label>
<label class="form-check-label">
  <input type="radio" name="nacion" value="2" <?php echo $n2;?>> Dificultades en lectura y escritura
</label>
<label class="form-check-label">
  <input type="radio" name="nacion" value="3" <?php echo $n3;?>> Puede seguir el Currículo
</label>
</div>
<br>
<br>
<div class="form-check form-check-inline">
<label class="form-check-label">
  <input class="form-check-input" type="checkbox" name="atal" value="SI" <?php echo $atl;?>>Ha sido atendido en el aula de ATAL
</label>
&nbsp;&nbsp;
<label class="form-check-label">
  <input class="form-check-input" type="checkbox" name="necatal" value="SI" <?php echo $necatl;?>> Necesita asistir al aula de ATAL
</label>
</div>
<hr>
<br>

<legend class="muted">ÁMBITO SOCIAL Y DE LA PERSONALIDAD</legend>
<h5 class="text-info">Integración en el Aula</h5>
<div class="form-check form-check-inline">
<label class="form-check-label">
  <input type="radio" name="integra" value="5" <?php echo $int5;?> required> Líder
</label>&nbsp;&nbsp;
<label class="form-check-label">
  <input type="radio" name="integra" value="1" <?php echo $int1;?> required> Integrado
</label>&nbsp;&nbsp;
<label class="form-check-label">
  <input type="radio" name="integra" value="2" <?php echo $int2;?> required> Poco integrado
</label>&nbsp;&nbsp;
<label class="form-check-label">
  <input type="radio" name="integra" value="3" <?php echo $int3;?> required> Se aísla
</label>&nbsp;&nbsp;
<label class="form-check-label">
  <input type="radio" name="integra" value="4" <?php echo $int4;?> required> Alumno rechazado
</label>
</div>
<hr>

<h5 class="text-info">Actitud, comportamiento, estilo de aprendizaje</h5>
<label for="actitud">Colaborador/a, Trabajador, Atento, Impulsivo.. Indicar los aspectos más significativos</label>
<div class="form-group">
<textarea name="actitud" rows="5" class="form-control" style="border: 1px solid #ccc;" required><?php echo $actitud;?></textarea>
</div>
<hr>
<h5 class="text-info">Lo que mejor "funciona" con el Alumno</h5>
<div class="form-group">
<textarea name="funciona" rows="5" class="form-control" style="border: 1px solid #ccc;"><?php echo $funciona;?></textarea>
</div>
<hr>
<br>

<legend class="muted">RELACIÓN COLEGIO - FAMILIA</legend>
<h5 class="text-info">Tipo de relación con el Colegio</h5>
<div class="form-check">
<label class="form-check-label">
  <input type="radio" name="relacion" value="3" <?php echo $rel3;?> required> Colaboración constante
</label>&nbsp;&nbsp;
<br>
<label class="form-check-label">
  <input type="radio" name="relacion" value="1" <?php echo $rel1;?> required> Colaboración sólo cuando el Centro la ha solicitado
</label>&nbsp;&nbsp;
<br>
<label class="form-check-label">
  <input type="radio" name="relacion" value="2" <?php echo $rel2;?> required> Demanda constante por parte de los Padres
</label>
</div>
<hr>

<h5 class="text-info">Razones para la ausencia de relación con el Colegio</h5>
<p class="help-block">En caso de ausencia completa de relación de los padres con el Colegio señalar si es posible las razones de la misma.</p>
<div class="form-group">
<textarea name="norelacion" rows="3" class="form-control" style="border: 1px solid #ccc;"><?php echo $norelacion;?></textarea>
</div>
<hr>
<br>



<legend class="muted">DISCIPLINA</legend>
<h5 class="text-info">&nbsp;&nbsp;&nbsp;Comportamiento disruptivo</h5>
<div class="form-check">
<label class="form-check-label">
  <input type="radio" name="disruptivo" value="3" <?php echo $dis3;?> required> Nunca
</label>
<label class="form-check-label">
  <input type="radio" name="disruptivo" value="1" <?php echo $dis1;?> required> Ocasionalmente
</label>
<label class="form-check-label">
  <input type="radio" name="disruptivo" value="2" <?php echo $dis2;?> required> Alumno disruptivo
</label>
<hr>
<h5 class="text-info">El alumno ha sido expulsado en alguna ocasión</h5>
<label class="form-check-label">
  <input type="radio" name="expulsion" value="1" <?php echo $exp1;?> required> No
</label>
<label class="form-check-label">
  <input type="radio" name="expulsion" value="2" <?php echo $exp2;?> required> Sí
</label>
</div>

<hr>
<br>
<legend class="muted">OBSERVACIONES</legend>
<p class="help-block">Otros aspectos a reseñar (agrupamientos, datos médicos, autonomía, etc).</p>
<div class="form-group">
<textarea name="observaciones" rows="5" class="form-control" style="border: 1px solid #ccc;"><?php echo $observaciones;?></textarea>
</div>

<hr>
<div class="form-group">
<input type="submit" class="btn btn-lg btn-info hidden-print" name="submit0" value="<?php if ($proc==1) {echo "Actualizar datos";}else{echo "Enviar datos";}?>">
&nbsp;&nbsp;&nbsp;&nbsp;
<input type="submit" class="btn btn-lg btn-danger hidden-print" name="reset" value="Borrar datos del alumno" />
</div>
</form>
<?php 
}
?>
<hr>
<br>

