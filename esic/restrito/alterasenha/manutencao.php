<?php
/**********************************************************************************
 Sistema e-SIC Livre: sistema de acesso a informa��o baseado na lei de acesso.
 
 Copyright (C) 2014 Prefeitura Municipal do Natal
 
 Este programa � software livre; voc� pode redistribu�-lo e/ou
 modific�-lo sob os termos da Licen�a GPL2.
***********************************************************************************/

	include_once("../inc/autenticar.php");
	include_once("../class/solicitante.class.php");
	
	
	$erro   = "";	//grava o erro, se houver, e exibe por meio de alert (javascript) atraves da funcao getErro() chamada no arquivo do formulario. ps: a fun��o � declara em inc/security.php


	//se tiver sido postado informa��o do formulario
	if($_POST['acao'])
	{

		$idusuario	= getSession("uid");
		$senhaatual	= $_POST["senhaatual"];
		$novasenha	= $_POST["novasenha"];
		$confirmasenha	= $_POST["confirmasenha"];

		
		$sql = "select chave from sis_usuario where idusuario = $idusuario";
		$rs = execQuery($sql);
		
		if(mysql_num_rows($rs) == 0)
		{
			$erro = "Usu�rio n�o encontrado";
			return false;
		}
		else
		{
			$row = mysql_fetch_array($rs);
			$chave = $row['chave'];
			
			if(md5($senhaatual) != $chave)
			{
				$erro = "Senha atual est� incorreta.";
				return false;
			}
			
			if($novasenha <> $confirmasenha)
			{
				$erro = "Nova senha n�o confere com a confirma��o";
				return false;
			}
		}

		$sql = "UPDATE sis_usuario SET
                         chave = '".md5($novasenha)."'
			WHERE idusuario = $idusuario";

		if (!execQuery($sql)) 
		{
			$erro = "Erro ao alterar senha do solicitante";
		}
                else
                {
                    Redirect("../index");
                }
	}


?>
