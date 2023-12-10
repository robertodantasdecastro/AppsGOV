<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	*																	     *
	*	@author Prefeitura Municipal de Itaja�								 *
	*	@updated 29/03/2007													 *
	*   Pacote: i-PLB Software P�blico Livre e Brasileiro					 *
	*																		 *
	*	Copyright (C) 2006	PMI - Prefeitura Municipal de Itaja�			 *
	*						ctima@itajai.sc.gov.br					    	 *
	*																		 *
	*	Este  programa  �  software livre, voc� pode redistribu�-lo e/ou	 *
	*	modific�-lo sob os termos da Licen�a P�blica Geral GNU, conforme	 *
	*	publicada pela Free  Software  Foundation,  tanto  a vers�o 2 da	 *
	*	Licen�a   como  (a  seu  crit�rio)  qualquer  vers�o  mais  nova.	 *
	*																		 *
	*	Este programa  � distribu�do na expectativa de ser �til, mas SEM	 *
	*	QUALQUER GARANTIA. Sem mesmo a garantia impl�cita de COMERCIALI-	 *
	*	ZA��O  ou  de ADEQUA��O A QUALQUER PROP�SITO EM PARTICULAR. Con-	 *
	*	sulte  a  Licen�a  P�blica  Geral  GNU para obter mais detalhes.	 *
	*																		 *
	*	Voc�  deve  ter  recebido uma c�pia da Licen�a P�blica Geral GNU	 *
	*	junto  com  este  programa. Se n�o, escreva para a Free Software	 *
	*	Foundation,  Inc.,  59  Temple  Place,  Suite  330,  Boston,  MA	 *
	*	02111-1307, USA.													 *
	*																		 *
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	// script executado na cronTab a cada 30 min
	chdir( "/home/pagina/public_html/intranet/" );
	require_once( "include/clsBanco.inc.php" );
	require_once( "include/clsEmail.inc.php" );
	require_once( "include/Geral.inc.php" );
	
	// Configuracoes
	$verbose = true;
	$limite = 300;
	
	if( $verbose ) echo "verbose!\n";
	
	$emails_enviados = array();
	$conteudos = array();
	$enviados = 0;
	$db = new clsBanco();
	$db2 = new clsBanco();
	$db->Consulta( "SELECT cod_mailling_fila_envio, ref_cod_mailling_email_conteudo, ref_cod_mailling_email, ref_ref_cod_pessoa_fj, data_cadastro FROM mailling_fila_envio WHERE data_envio IS NULL ORDER BY data_cadastro DESC LIMIT $limite OFFSET 0" );
	if( $verbose ) echo "selecionados " . $db->Num_Linhas() . " emails que aguardavam na fila!\n";
	while ( $db->ProximoRegistro() ) 
	{
		list( $cod_fila, $cod_conteudo, $cod_email, $cod_funcionario, $data_cadastro ) = $db->Tupla();
		$email = "";
		if( ! $conteudos[$cod_conteudo] )
		{
			// cria o array para cada conteudo para gerenciar os e-mails que jah receberam este conteudo
			if( ! is_array( $emails_enviados[$cod_conteudo] ) )
			{
				$emails_enviados[$cod_conteudo] = array();
			}
			$db2->Consulta( "SELECT conteudo, assunto, nm_remetente, email_remetente FROM mailling_email_conteudo WHERE cod_mailling_email_conteudo = '{$cod_conteudo}'" );
			$db2->ProximoRegistro();
			list( $conteudo, $assunto, $rem_nome, $rem_email ) = $db2->Tupla();
			$conteudos[$cod_conteudo]["conteudo"] = $conteudo;
			$conteudos[$cod_conteudo]["assunto"] = $assunto;
			$conteudos[$cod_conteudo]["rem_nome"] = $rem_nome;
			$conteudos[$cod_conteudo]["rem_email"] = $rem_email;
			
			$conteudos[$cod_conteudo]["conteudo"] .= "<br /><br />D�vidas, cr�ticas ou sugest�es?<br />Ouvidoria Municipal - 0800 646 4040";
		}
		
		$removeemail = "";
		if( ! is_null( $cod_funcionario ) )
		{
			// verifica se jah houve um envio deste conteudo, para este e-mail
			$db2->Consulta( "SELECT 1 FROM mailling_fila_envio WHERE ref_ref_cod_pessoa_fj = '{$cod_funcionario}' AND ref_cod_mailling_email_conteudo = '{$cod_conteudo}' AND data_envio IS NOT NULL" );
			if( $db2->ProximoRegistro() )
			{
				// ja enviou, vamos deletar esse duplicado
				$db2->Consulta( "DELETE FROM mailling_fila_envio WHERE ref_ref_cod_pessoa_fj = '{$cod_funcionario}' AND ref_cod_mailling_email_conteudo = '{$cod_conteudo}' AND data_envio IS NULL" );
			}
			else 
			{
				$db2->Consulta( "SELECT f.email FROM funcionario_email f WHERE f.ref_ref_cod_pessoa_fj = '{$cod_funcionario}' ORDER BY cod_email ASC LIMIT 1 OFFSET 0" );
				$db2->ProximoRegistro();
				list( $email ) = $db2->Tupla();
				$objPessoa = new clsPessoaFisica();
				list( $nome ) = $objPessoa->queryRapida( $cod_funcionario, "nome" );
				$email .= "@itajai.sc.gov.br";
			}
		}
		else 
		{
			if( is_numeric( $cod_email ) )
			{
				// verifica se jah houve um envio deste conteudo, para este e-mail
				$db2->Consulta( "SELECT 1 FROM mailling_fila_envio WHERE ref_cod_mailling_email = '{$cod_email}' AND ref_cod_mailling_email_conteudo = '{$cod_conteudo}' AND data_envio IS NOT NULL AND cod_mailling_fila_envio <> '{$cod_fila}'" );
				if( $db2->ProximoRegistro() )
				{
					// ja enviou, vamos deletar esse duplicado
					$db2->Consulta( "DELETE FROM mailling_fila_envio WHERE ref_cod_mailling_email = '{$cod_email}' AND ref_cod_mailling_email_conteudo = '{$cod_conteudo}' AND data_envio IS NULL AND cod_mailling_fila_envio <> '{$cod_fila}'" );
				}
				else 
				{
					$db2->Consulta( "SELECT email, nm_pessoa FROM mailling_email WHERE cod_mailling_email = '{$cod_email}'" );
					$db2->ProximoRegistro();
					list( $email, $nome ) = $db2->Tupla();
					$removeemail = "Caso queira remover seu e-mail desta lista <a href=\"http://www.itajai.sc.gov.br/remover.php?email=$email\">clique aqui</a>";
					
					/*
					if( substr( $email, -17 ) == "@itajai.sc.gov.br" && false )
					{
						// nao envia e-mail para quem tem e-mail da prefeitura (soh se a pessoa selecionou pela outra opcao pra enviar por funcionarios)
						$email = false;
					}
					*/
				}
			}
		}
		
		if( isset( $emails_enviados[$cod_conteudo]["{$email}"] ) )
		{
			$email = false;
		}
		else 
		{
			$emails_enviados[$cod_conteudo]["{$email}"] = true;
		}
		
		if( $email )
		{
			$assunto = "[PORTAL ITAJA�] - " . $conteudos[$cod_conteudo]["assunto"];
			
			$email = limpa_acentos( $email );
			
			$objEmail = new clsEmail( $email, $assunto, $conteudos[$cod_conteudo]["conteudo"], "email_mailling_secom", $conteudos[$cod_conteudo]["rem_email"], $conteudos[$cod_conteudo]["rem_nome"], $conteudos[$cod_conteudo]["rem_email"], "html", $removeemail );
			$db2->Consulta( "UPDATE mailling_fila_envio SET data_envio = NOW() WHERE cod_mailling_fila_envio = $cod_fila" );
			if( $objEmail->envia() )
			{
				$enviados++;
				if( $verbose )
				{
					echo "-";
					if( ! ( $enviados % 50 ) ) echo "| ( $enviados )\n";
				}
			}
		}
	}
	echo "fim dos envios\n";
	if( $enviados )
	{
		echo "\nCron.Mailler - $enviados e-mails enviados.\n";
	}
?>