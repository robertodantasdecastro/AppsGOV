<?php
/**********************************************************************************
 Sistema e-SIC Livre: sistema de acesso a informa��o baseado na lei de acesso.
 
 Copyright (C) 2014 Prefeitura Municipal do Natal
 
 Este programa � software livre; voc� pode redistribu�-lo e/ou
 modific�-lo sob os termos da Licen�a GPL2.
***********************************************************************************/

error_reporting(E_ERROR);

define("SISTEMA_NOME", "Lei de Acesso"); //nome do sistema para exibi��o em lugares diversos
define("SISTEMA_CODIGO", "lda"); //codigo para defini��o da lista de sess�o do sistema


define("MAIL_HOST", "gate.localhost");
define("DBHOST", "localhost");
define("DBUSER", "esic");
define("DBPASS", "esiclivre");
define("DBNAME", "dbesiclivre");



define("SITELNK", "http://localhost/esic/");	//endere�o principal do site
define("URL_BASE_SISTEMA", "http://localhost/esic/");	//endere�o principal do site
define("DIR_CLASSES_LEIACESSO","/var/www/html/esic/class");

?>
