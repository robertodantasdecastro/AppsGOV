<?php
/**********************************************************************************
 Sistema e-SIC Livre: sistema de acesso a informação baseado na lei de acesso.
 
 Copyright (C) 2014 Prefeitura Municipal do Natal
 
 Este programa é software livre; você pode redistribuí-lo e/ou
 modificá-lo sob os termos da Licença GPL2.
***********************************************************************************/
include("manutencao.php");
include("../inc/topo.php");

?>

<style type="text/css">

</style>

<span id="span" style="width:1000px; align: left; font-family: Verdana, Arial, Helvetica, Sans-serif; font-size: 12px;">

<h1>Reenvio de Senha</h1>
<div align="center">
	<form action="<?php echo SITELNK;?>reset/index.php" id="formulario" method="post">
	Informe seu CPF ou CNPJ para enviarmos nova senha:<br>
	<table cellpadding="10" cellspacing="10">
		<tr><td>CPF/CNPJ:</td><td><input type="text" name="cpfcnpj"></td></tr>
		<tr><td>&nbsp;</td><td><input type="submit" name="btsub" class="botaoformulario" value="Enviar"></td></tr>
	</table>
	</form>
	
	<br>
	<a href="../cadastro">Não tem cadastro?</a>
        <br>&nbsp;
</div>

</span>

<?php
getErro($erro);
include("../inc/rodape.php");?>

?>