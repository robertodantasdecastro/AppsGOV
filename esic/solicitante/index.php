<?php
/**********************************************************************************
 Sistema e-SIC Livre: sistema de acesso a informa��o baseado na lei de acesso.
 
 Copyright (C) 2014 Prefeitura Municipal do Natal
 
 Este programa � software livre; voc� pode redistribu�-lo e/ou
 modific�-lo sob os termos da Licen�a GPL2.
***********************************************************************************/

 include("manutencao.php");
 
 include("../inc/topo.php");
?>
<script language="JavaScript" src="<?php echo URL_BASE_SISTEMA;?>js/XmlHttpLookup.js"></script>



<h1>Alterar Cadastro</h1>
<br>
<form action="<?php echo URL_BASE_SISTEMA;?>solicitante/index.php" id="formulario" method="post">
<input type="hidden" name="idsolicitante" value="<?php echo $idsolicitante;?>">
<?php include_once("../cadastro/formulario.php");?>
</form>
<?php 
getErro($erro);
include("../inc/rodape.php");
?>