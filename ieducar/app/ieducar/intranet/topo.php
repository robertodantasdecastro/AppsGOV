<?php
  require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/bootstrap.php';
  $entityName = $GLOBALS['coreExt']['Config']->app->entity->name;
?>

<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel=stylesheet type='text/css' href='styles/reset.css' />
    <link rel=stylesheet type='text/css' href='styles/portabilis.css' />
  </head>

  <body>
    <div id="cabecalho" class="texto-normal">
      <div id="ccorpo">        
        <p><a id="logo" href="/">
        <p><img src="imagens/logoP.png" alt="São João do Rio do Peixe"></a> <span id="status"><span id="entidade"><img src="imagens/logoIeducar.png" alt="i-Educar"><?php //echo $entityName; ?></span></span></p>
      </div>
    </div>
  </body>
</html>
