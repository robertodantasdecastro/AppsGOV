<?php
/**********************************************************************************
 Sistema e-SIC Livre: sistema de acesso a informação baseado na lei de acesso.
 
 Copyright (C) 2014 Prefeitura Municipal do Natal
 
 Este programa é software livre; você pode redistribuí-lo e/ou
 modificá-lo sob os termos da Licença GPL2.
***********************************************************************************/

error_reporting(E_ERROR);

define("SISTEMA_NOME", "Lei de Acesso"); //nome do sistema para exibição em lugares diversos
define("SISTEMA_CODIGO", "lda"); //codigo para definição da lista de sessão do sistema


define("MAIL_HOST", "gate.localhost");
define("DBHOST", "localhost");
define("DBUSER", "esic");
define("DBPASS", "esiclivre");
define("DBNAME", "dbesiclivre");



define("SITELNK", "http://localhost/esic/");	//endereço principal do site
define("URL_BASE_SISTEMA", "http://localhost/esic/");	//endereço principal do site
define("DIR_CLASSES_LEIACESSO","/var/www/html/esic/class");

?>
