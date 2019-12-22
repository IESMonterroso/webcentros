<?php if (! defined("WEBCENTROS_DOMINIO")) die ('No direct script access allowed'); ?>

    <div class="section p-5 section-resources bg-clouds">
        <div class="container">
            <div class="row justify-content-around">
              <div class="col-sm-3 pb-3 pb-sm-0 text-center">
                <a href="https://www.mecd.gob.es/educacion/mc/fse/fse.html" target="_blank">
                  <img src="<?php echo WEBCENTROS_DOMINIO; ?>ui-theme/img/png-icons/fse_horizontal.png" alt="">
                </a>
              </div>
              <div class="col-sm-3 pb-3 pb-sm-0 text-center">
                <a href="https://www.educacionyfp.gob.es/" target="_blank">
                  <img src="<?php echo WEBCENTROS_DOMINIO; ?>ui-theme/img/png-icons/mefp.png" alt="">
                </a>
              </div>

              <div class="col-sm-3 pb-3 pb-sm-0 text-center">
                <a href="http://www.juntadeandalucia.es/educacion/portals/web/ced" target="_blank">
                  <img src="<?php echo WEBCENTROS_DOMINIO; ?>ui-theme/img/png-icons/ced_horizontal.png" alt="">
                </a>
              </div>
            </div>
        </div>
    </div>

    <footer class="footer">
      <div class="container text-center">
        <ul class="mb-2">
          <li><a href="<?php echo WEBCENTROS_DOMINIO; ?>aviso-legal">Aviso legal y Política de Cookies</a></li>
        </ul>
        <p class="copyright">Copyright © <?php echo date('Y'); ?>, <?php echo $config['centro_denominacion']; ?></p>
      </div>
    </footer>

    </div><!-- ./wrapper -->

    <!-- Core JS Files -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <!-- Plugin for Cookie Consent, full documentation here: https://cookieconsent.insites.com -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.1.0/cookieconsent.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.1.0/cookieconsent.min.js"></script>
    <!-- Plugin for Switches, full documentation here: http://www.jque.re/plugins/version3/bootstrap.switch/ -->
    <script src="<?php echo WEBCENTROS_DOMINIO; ?>ui-theme/js/plugins/bootstrap-switch.js"></script>
    <!-- Plugin for the Sliders, full documentation here: http://refreshless.com/nouislider/ -->
    <script src="<?php echo WEBCENTROS_DOMINIO; ?>ui-theme/js/plugins/nouislider.min.js" type="text/javascript"></script>
    <!-- Plugin for the DatePicker, full documentation here: https://github.com/uxsolutions/bootstrap-datepicker -->
    <script src="<?php echo WEBCENTROS_DOMINIO; ?>ui-theme/vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
    <script src="<?php echo WEBCENTROS_DOMINIO; ?>ui-theme/js/now-ui-kit.js" type="text/javascript"></script>
    <?php if (((date('d') >= 17 && date('m') == 12) || (date('d') <= 6 && date('m') == 1)) && stristr($_SERVER['REQUEST_URI'], '/alumnado') == false ): ?>
    <script src="<?php echo WEBCENTROS_DOMINIO; ?>ui-theme/js/snowfall.jquery.js" type="text/javascript"></script>
    <script>
    $(document).ready(function(){
        $('.navbar-lg').snowfall({
            deviceorientation: false,
            round: true,
            minSize: 1,
            maxSize:6,
            flakeColor: '#f6f6f6',
            flakeCount: 50
        });
    });
    </script>
    <?php endif; ?>
    <?php if (stristr($_SERVER['REQUEST_URI'], '/alumnado/login.php') == true): ?>
    <script>
    $(document).ready(function(){
        // Deshabilitamos el botón
        $("button[type=submit]").attr("disabled", "disabled");

        // Cuando se presione una tecla en un input del formulario
        // realizamos la validación
        $('input').keyup(function(){
                // Validamos el formulario
                var validated = true;
                if($('#user').val().length < 6) validated = false;
                if($('#clave').val().length < 6) validated = false;

        // Si el formulario es válido habilitamos el botón, en otro caso
        // lo volvemos a deshabilitar
        if(validated) $("button[type=submit]").removeAttr("disabled");
        else $("button[type=submit]").attr("disabled", "disabled");

        });

        $('input:first').trigger('keyup');
    });
    </script>
    <?php endif; ?>

    <script>
    $(".carousel").carousel({
      touch: true,
      pause: "hover",
      interval: 15000
    });

    $(".carousel-image-item").click(function() {
      window.open($(this).data('href'));
    });
    </script>

    <script>
    $(document).ready(function(){
      $('#lisAluForm').submit(function(){
        event.preventDefault();

        var data_value = $("#lisAluDni").val();
        var results = '';

        $('#lisAluForm').hide();
        $('#lisAluLoading').html("<br><div class=\"text-center\"><div class=\"fa-4x\"><i class=\"fas fa-circle-notch fa-spin\"></i></div></div>");

        setTimeout(function(){
          $.post( "./plugins/consultas/conAluLis.php", { "data_value" : data_value }, null, "json")
          .done(function( data, textStatus, jqXHR ) {
            if (textStatus == "success") {
              if (data.result == 'ok') {
                results += "<ul class=\"list-group list-group-flush\">";
                $.each(data.data, function(i, item) {
                  results += "<li class=\"list-group-item\"><strong>" + item.apellidos + ", " + item.nombre + "</strong><br><span class=\"text-info\">" + item.unidad + " (" + item.curso + ")</span></li>";
                });
                results += "</ul>";
                results += "<br>";
                results += "<div class=\"text-center\"><a class=\"btn btn-primary\" href=\"<?php echo WEBCENTROS_DOMINIO; ?>\">Realizar otra consulta</a></div>";

                $('#lisAluLoading').hide();
                $('#lisAluLoading').html();
                $('#lisAluRes').html(results);
              }
              else {
                results += "<p>Lo sentimos, no hemos encontrado resultados con este DNI/NIE. Pruebe en otro momento.</p>";
                results += "<br>";
                results += "<div class=\"text-center\"><a class=\"btn btn-primary\" href=\"<?php echo WEBCENTROS_DOMINIO; ?>\">Realizar otra consulta</a></div>";

                $('#lisAluLoading').hide();
                $('#lisAluLoading').html();
                $('#lisAluRes').html(results);
              }
            }
            else {
              results += "<p>Lo sentimos, no hemos podido comprobar su consulta. Pruebe en otro momento.</p>";
              results += "<br>";
              results += "<div class=\"text-center\"><a class=\"btn btn-primary\" href=\"<?php echo WEBCENTROS_DOMINIO; ?>\">Realizar otra consulta</a></div>";

              $('#lisAluLoading').hide();
              $('#lisAluLoading').html();
              $('#lisAluRes').html(results);
            }
          })
          .fail(function() {
            results += "<p>Lo sentimos, no hemos podido comprobar su consulta. Pruebe en otro momento.</p>";
            results += "<br>";
            results += "<div class=\"text-center\"><a class=\"btn btn-primary\" href=\"<?php echo WEBCENTROS_DOMINIO; ?>\">Realizar otra consulta</a></div>";

            $('#lisAluLoading').hide();
            $('#lisAluLoading').html();
            $('#lisAluRes').html(results);
          });
        },1500);

      });
    });
    </script>

</body>
</html>
