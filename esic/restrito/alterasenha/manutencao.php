<?php
/**********************************************************************************
 Sistema e-SIC Livre: sistema de acesso a informação baseado na lei de acesso.
 
 Copyright (C) 2014 Prefeitura Municipal do Natal
 
 Este programa é software livre; você pode redistribuí-lo e/ou
 modificá-lo sob os termos da Licença GPL2.
***********************************************************************************/

	include_once("../inc/autenticar.php");
	include_once("../class/solicitante.class.php");
	
	
	$erro   = "";	//grava o erro, se houver, e exibe por meio de alert (javascript) atraves da funcao getErro() chamada no arquivo do formulario. ps: a função é declara em inc/security.php


	//se tiver sido postado informação do formulario
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
			$erro = "Usuário não encontrado";
			return false;
		}
		else
		{
			$row = mysql_fetch_array($rs);
			$chave = $row['chave'];
			
			if(md5($senhaatual) != $chave)
			{
				$erro = "Senha atual está incorreta.";
				return false;
			}
			
			if($novasenha <> $confirmasenha)
			{
				$erro = "Nova senha não confere com a confirmação";
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
