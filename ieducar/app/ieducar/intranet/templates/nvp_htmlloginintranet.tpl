<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
  <head>
    <title>Intranet</title>
    <meta http-equiv='Content-Type' content='text/html; charset=ISO-8859-1' />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="-1" />

    <link rel=stylesheet type='text/css' href='styles/reset.css?rand=3'>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300' rel='stylesheet' type='text/css'>
    <link rel=stylesheet type='text/css' href='styles/login.css?rand=5'>

    <script type='text/javascript' src='//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js'></script>
    <script type="text/javascript">
      var $j = jQuery.noConflict();

      function onDev() {
        return window.location.hostname.indexOf('local') > -1;
      }

      function onDemo() {
        return window.location.hostname.indexOf('demonstracao') > -1;
      }

      function setupDemo() {
        if (! onDemo()) { return; }

        $j('.show.only.on.demo').show();
        $j('input#login').val('admin');
        $j('input#senha').val('educacao');
      }

      function initialize() {
        setupDemo();
      }

      if( ! onDev()) {
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-54965320-1', 'auto');
        ga('send', 'pageview');
      }

      $j(function() {
        initialize();

        configureTeacherExternalLink();

        function configureTeacherExternalLink() {
          var url = "http://professor.ieducativa.com.br/#login?api_host=" + document.origin;
          $j('.external.links .teacher').attr('href', url);
        }
      });
    </script>

  </head>
  <body>
    <div id="flash-container">
      <!--[if lt IE 7]>
        <p style="min-height: 32px;" class="flash update-browser"><strong>Seu navegador est&aacute desatualizado.</strong> Para melhor navega&ccedil;&atildeo  no sistema, por favor, atualize seu navegador.<a href="http://br.mozdev.org/download/" target="_blank"><img style="margin-top:4px;" src="http://www.mozilla.org/contribute/buttons/110x32bubble_r_pt.png" alt="Firefox" width="110" height="32" style="border-style:none;" title="Mozilla Firefox" /></a></p>
      <![endif]-->
    </div>

    <div class="body container">
      <div class="login header">
        <div class="partnership">
          <div class="title invisible %PARTNER_DISPLAY_CLASS%">Uma parceria:</div>

          <div class="logos">
            <a href="http://ieducativa.com.br" alt="link para site da ieducativa" target="_blank"><img class="logo" src="https://ieducativa.s3.amazonaws.com/cdn/logos/ieducativa-300.png"/></a>
            <span class="and invisible %PARTNER_DISPLAY_CLASS%">&amp;</span>
            <a class="%PARTNER_DISPLAY_CLASS%" href="%PARTNER_URL%" alt="link para site do parceiro" target="%PARTNER_URL_TARGET%"><img class="partner logo" src="%PARTNER_LOGO_URL%"/></a>
          </div>
        </div>
        <h2>Bem vindo ao iEducar</h2>
      </div>

      <div id="login-form" class="">
        <form action="" method="post">
        <table>
          <tbody>
            <tr class="hidden show only on demo">
              <td>
                <p class="notice">Ambiente de demonstra&ccedil;&atilde;o.</p>
              </td>
            </tr>
            <tr>
              <td>
                <!-- #&ERROLOGIN&# -->
              </td>
            </tr>
          <tr>
            <td>
              <input type="text" name="login" id="login" placeholder="Usu&aacute;rio">
            </td>
          </tr>

          <tr>
            <td>
              <input type="password" name="senha" id="senha" placeholder="Senha">
            </td>
          </tr>
          <tr>
            <td><!-- #&RECAPTCHA&# --></td>
          </tr>
          <tr>
            <td>
              <input type="submit" class="submit" src="imagens/nvp_bot_entra_webmail.jpg" value="Acessar">
            </td>
          </tr>
          <tr>
            <td>
              <a class="forget-password" href="/module/Usuario/RedefinirSenha">Esqueceu sua senha?</a>
            </td>
          </tr>
        </tbody></table>
        </form>
      </div> <!-- end login-form -->

      <div class="external links">
        <a class="teacher module" href="#" target="_blank">Acesso do professor</a>
      </div>

      <!-- <div id="service-info">
        <p class="requiriments title">Requisitos</p>
        <p class="explanation">Para melhor uso do sistema, recomendamos:</p>
        <ul class="requiriments unstyled">
          <li>- Navegador <a target="_blank" class="light decorated" href="https://www.google.com/intl/pt-BR/chrome/browser/">Google Chrome</a> ou <a target="_blank" class="light decorated" href="http://br.mozdev.org/download/">Mozilla Firefox</a></li>
          <li>- Leitor relat&oacute;rios PDF <a target="_blank" class="light decorated" href="http://get.adobe.com/br/reader/">Adobe Reader</a> ou <a target="_blank" class="light decorated" href="http://www.foxitsoftware.com/downloads#reader">Foxit</a></li>
        </ul>
      </div> -->

    </div> <!-- end corpo -->

    <div id="rodape" class="texto-normal">
      <p>
        <!--Portabilis Tecnologia-->
      </p>
    </div> <!-- end rodape -->

  </body>
</html>
