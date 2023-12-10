<?php
/*
COPYRIGHT 2008 - 2010 DO PORTAL PUBLICO INFORMATICA LTDA

Este arquivo e parte do programa E-ISS / SEP-ISS

O E-ISS / SEP-ISS e um software livre; voce pode redistribui-lo e/ou modifica-lo
dentro dos termos da Licenca Publica Geral GNU como publicada pela Fundacao do
Software Livre - FSF; na versao 2 da Licenca

Este sistema e distribuido na esperanca de ser util, mas SEM NENHUMA GARANTIA,
sem uma garantia implicita de ADEQUACAO a qualquer MERCADO ou APLICACAO EM PARTICULAR
Veja a Licenca Publica Geral GNU/GPL em portugues para maiores detalhes

Voce deve ter recebido uma copia da Licenca Publica Geral GNU, sob o titulo LICENCA.txt,
junto com este sistema, se nao, acesse o Portal do Software Publico Brasileiro no endereco
www.softwarepublico.gov.br, ou escreva para a Fundacao do Software Livre Inc., 51 Franklin St,
Fith Floor, Boston, MA 02110-1301, USA
*/
?>
<?php 
// inicia a sess�o verificando se jah esta com o usuario logado, se estiver entra na p�gina admin
session_name("contador");
session_start();
header("Cache-Control: no-cache, must-revalidate");
if(!(isset($_SESSION["empresa"])))
{   
	echo "
		<script>
			alert('Acesso Negado!');
			window.location='login.php';
		</script>
	";
}else{?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>e-Nota</title>
<script src="../scripts/padrao.js" language="javascript" type="text/javascript"></script>
<script src="../scripts/java_site.js" language="javascript" type="text/javascript"></script>
<script src="../scripts/java_emissor_contador.js" language="javascript" type="text/javascript"></script>
<link href="../css/padrao_emissor.css" rel="stylesheet" type="text/css" />
</head>

<body>
<center>
<table width="700" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td><?php include("../include/topo.php"); ?></td>
  </tr>
  <tr>
    <td bgcolor="#FFFFFF" height="400" valign="top" align="center">
	
<!-- frame central inicio --> 	
<table border="0" cellspacing="0" cellpadding="0" height="100%">
  <tr>
    <td width="170" align="left" background="../img/menus/menu_fundo.jpg" valign="top"><?php include("inc/menu.php"); ?></td>
    <td width="590" bgcolor="#FFFFFF" valign="top" align="center">
	<img src="../img/cabecalhos/cadastro.jpg" />
    
<!-- frame central lateral direita inicio -->	
<table border="0" align="center" cellpadding="0" cellspacing="1">
    <tr>
      <td width="10" height="10" bgcolor="#FFFFFF"></td>
	  <td width="170" align="center" bgcolor="#FFFFFF" rowspan="3">Atualizar Cadastro</td>
      <td width="400" bgcolor="#FFFFFF"></td>
	</tr>
	<tr>
	  <td height="1" bgcolor="#CCCCCC"></td>
      <td bgcolor="#CCCCCC"></td>
	</tr>
	<tr>
	  <td height="10" bgcolor="#FFFFFF"></td>
      <td bgcolor="#FFFFFF"></td>
	</tr>
	<tr>
		<td colspan="3" height="1" bgcolor="#CCCCCC"></td>
	</tr>
	<tr>
		<td height="60" colspan="3" bgcolor="#CCCCCC">
		<?php include("inc/empresas_principal.php"); ?>
		</td>
	</tr>
	<tr>
    	<td height="1" colspan="3" bgcolor="#CCCCCC"></td>
	</tr>
</table>         





<!-- frame central lateral direita fim -->	
	</td>
  </tr>
</table>


<!-- frame central fim --> 	
	</td>
  </tr>
  <tr>
    <td><?php include("inc/rodape.php"); ?></td>
  </tr>
</table>
</center>

</body>
</html>
<?php }?>
