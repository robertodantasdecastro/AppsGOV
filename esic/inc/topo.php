<?php
/**********************************************************************************
 Sistema e-SIC Livre: sistema de acesso a informação baseado na lei de acesso.
 
 Copyright (C) 2014 Prefeitura Municipal de Cachoeira dos Índios
 
 Este programa é software livre; você pode redistribuí-lo e/ou
 modificá-lo sob os termos da Licença GPL2.
***********************************************************************************/

include("config.php");
?>
<!DOCTYPE html>
<html>
	<head>
		<title>e-SIC Livre - Cachoeira dos Índios</title>
		<!-- CSS -->
		<meta name="verify-v1" content="miqBcW00PywY1Jm7/yQP8ztDacIWFV9gQRTCmHuai9w=" />
		<!-- TAG PARA O GOOGLEBOT -->
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title>Lei de Acesso</title>

		<meta name="resource-type" content="document" />
		<meta http-equiv="pragma" content="no-cache" />
		<meta name="revisit-after" content="1" />
		<meta name="classification" content="Internet" />	
		<meta name="description" content="Portal da Prefeitura Municipal de Cachoeira dos Índios" />
		<meta name="keywords" content="Prefeitura de Cachoeira dos Índios, cachoeira, pb, noticias, serviços, esic" />
		<meta name="robots" content="ALL" />
		<meta name="distribution" content="Global" />
		<meta name="rating" content="General" />
		<meta name="author" content="Prefeitura de Cachoeira dos Índios" />
		<meta name="language" content="pt-br" />
		<meta name="doc-class" content="Completed" />
		<meta name="doc-rights" content="Public" />
		<meta http-equiv="X-UA-Compatible" content="IE=8">
		
		<link rel="stylesheet" type="text/css" href="../css/estilo.css">

              	<script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>
                <script src="../js/functions.js"></script>
	
	</head>
	<body>
		<div id="out">
			<div id="conteudo">
				<div id="cabecalho">
					<div id="faixa">
						<ul>
							<img id="home" src="../css/img/home.png" />
							<li class="opcao"><a target="_blank" href="http://cachoeiradosindios.cidadesdigitais.gov.br/">Principal</a></li>
							<img src="../css/img/pipe.png" />
							<li class="opcao"><a target="_blank" href="http://cachoeiradosindios.cidadesdigitais.gov.br/ouvidoria/">Ouvidoria</a></li>
							<img src="../css/img/pipe.png" />
							<li class="opcao"><a target="_blank" href="http://cachoeiradosindios.cidadesdigitais.gov.br/prefeitura">Secretarias e Órgãos</a></li>
							<img src="../css/img/pipe.png" />
							<li class="opcao"><a href="../faleconosco">Fale conosco</a></li>
							<img src="../css/img/pipe.png" />
							<li class="opcao"><a target="_blank" href="https://cachoeiradosindios.cidadesdigitais.gov.br/transparencia-municipal">Portal da Transparência</a></li>
						</ul>
					</div>
					
					<div id="logo">
							<a href="../index"><img src="../css/img/logo.png" /></a>
					</div>
					
					<div id="esic">
							<a><img src="../css/img/eSIC.png" /></a>
					</div>
					
                                    
					<div id="menu">
						<ul>
                                                    <?php if (!empty($_SESSION[SISTEMA_CODIGO])) { ?>
                                                            <ul>

                                                                    <li class="opcao"><a href="../index.php">Inicio</a></li>
                                                                    <img src="../css/img/pipe.png" />
                                                                    <li class="opcao"><a href="../solicitante">Alterar Cadastro</a></li>
                                                                    <img src="../css/img/pipe.png" />
                                                                    <li class="opcao"><a href="../alterasenha">Alterar Senha</a></li>		
                                                                    <img src="../css/img/pipe.png" />
                                                                    <li class="opcao"><a href="../solicitacao">Fazer Solicitação</a></li>
                                                                    <img src="../css/img/pipe.png" />
                                                                    <li class="opcao"><a href="../acompanhamento">Solicitações Realizadas</a></li>
                                                                    <img src="../css/img/pipe.png" />
                                                                    <li class="opcao"><a href="../index/logout.php">Sair</a></li>
                                                            </ul>
                                                    <?php } else {?>
                                                            <ul>
                                                                    <li class="opcao"><a href="#"></a></li>
                                                                    <li class="opcao"><a href="../index">Lei de Acesso à Informação</a></li>
                                                                    <img src="../css/img/pipe.png" />
                                                                    <li class="opcao"><a href="../manual/InformacaoMundo.php">Acesso à Informação no Mundo</a></li>
                                                                    <img src="../css/img/pipe.png" />
                                                                    <li class="opcao"><a href="../manual/LeiAcessoMundo.php">SIC'S</a></li>
                                                                    <img src="../css/img/pipe.png" />
                                                                    <li class="opcao"><a href="../manual">Manual</a></li>
                                                                    <img src="../css/img/pipe.png" />
                                                                    <li class="opcao"><a href="../estatistica">Estatística</a></li>
                                                            </ul>
                                                    <?php }?>
						</ul>
					</div>	
					</div>
					
					<div id="corpo">

