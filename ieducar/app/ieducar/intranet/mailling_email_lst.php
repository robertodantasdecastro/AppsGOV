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
$desvio_diretorio = "";
require_once ("include/clsBase.inc.php");
require_once ("include/clsListagem.inc.php");
require_once ("include/clsBanco.inc.php");

class clsIndex extends clsBase
{
	function Formular()
	{
		$this->SetTitulo( "{$this->_instituicao} Emails!" );
		$this->processoAp = "86";
	}
}

class indice extends clsListagem
{
	function Gerar()
	{
		$this->titulo = "Emails";
		$this->addBanner( "imagens/nvp_top_intranet.jpg", "imagens/nvp_vert_intranet.jpg", "Intranet");

		$nome = @$_GET['nome'];
		$email = @$_GET['email'];

		$this->campoTexto( "nome", "Nome",  $nome, "50", "250", true );
		$this->campoTexto( "email", "Email",  $email, "50", "250", true );

		$where = "";
		$whereand = "WHERE ";
		if($nome)
		{
			$where = "$whereand nm_pessoa LIKE '%{$nome}%'";
			$whereand = " and ";
		}

		if($email)
		{
			$where = "$whereand email LIKE '%{$email}%'";
		}


		$this->addCabecalhos( array( "Nome", "Email"));
		$db = new clsBanco();
		// Recurso Utilizado pelo Paginador
		$db->Consulta( "SELECT count(*) FROM mailling_email {$where}" );
		$db->ProximoRegistro();
		list ($total) = $db->Tupla();
		$total_tmp = $total;
		$iniciolimit = (@$_GET['iniciolimit']) ? @$_GET['iniciolimit'] : "0";
		$limite = 10;
		if ($total > $limite)
		{
			$iniciolimit_ = $iniciolimit * $limite;
			$limit = " LIMIT {$iniciolimit_}, $limite";
		}

		$db->Consulta( "SELECT cod_mailling_email,nm_pessoa, email FROM mailling_email {$where} ORDER BY nm_pessoa ASC {$limit}" );
		while ($db->ProximoRegistro())
		{
			list ($cod_email, $nome, $email) = $db->Tupla();
			$this->addLinhas( array("<img src='imagens/noticia.jpg' border=0>&nbsp;&nbsp;<a href='mailling_email_det.php?id_email=$cod_email'>$nome</a>", "<a href='mailling_email_det.php?id_email=$cod_email'>$email</a>") );
		}
		$this->paginador("mailling_email_lst.php?", $total_tmp,$limite,@$_GET['pos_atual']);
		$this->acao = "go(\"mailling_email_cad.php\")";
		$this->nome_acao = "Novo";
		$this->largura = "100%";
	}
}
$pagina = new clsIndex();
$miolo = new indice();
$pagina->addForm( $miolo );
$pagina->MakeAll();

?>