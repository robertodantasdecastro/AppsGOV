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
		$this->SetTitulo( "{$this->_instituicao} Licita&ccedil;&otilde;es - Status de finaliza��o!" );
		$this->processoAp = "138";
	}
}

class indice extends clsListagem
{
	function Gerar()
	{
		$this->titulo = "Licita&ccedil;&otilde;es - Status de finaliza��o";
		$this->addBanner( "imagens/nvp_top_intranet.jpg", "imagens/nvp_vert_intranet.jpg", "Intranet" );
		
		$this->addCabecalhos( array( "Status") );
		
		
		$db = new clsBanco();
		$db->Consulta( "SELECT count(0) FROM compras_final_pregao" );
		$db->ProximoRegistro();
		list ($total) = $db->Tupla();
		$total_tmp = $total;
		$iniciolimit = (@$_GET['iniciolimit']) ? @$_GET['iniciolimit'] : "0";
		$limite = 10;
		if ($total > $limite)
		{
			$iniciolimit_ = $iniciolimit *$limite;
			$limit = " LIMIT {$iniciolimit_}, $limite";
		}		
		
		$db->Consulta( "SELECT cod_compras_final_pregao, nm_final FROM compras_final_pregao ORDER BY nm_final ASC {$limit}" );
		while ($db->ProximoRegistro())
		{
			list ($cod, $nome) = $db->Tupla();

			$this->addLinhas( array( "<a href='licitacoes_statusfinal_cad.php?cod=$cod'><img src='imagens/noticia.jpg' border=0>$nome</a>" ) );
		}
		$this->paginador("licitacoes_statusfinal_lst.php?",$total_tmp,$limite,@$_GET['pos_atual']);
		
		$this->acao = "go(\"licitacoes_statusfinal_cad.php\")";
		$this->nome_acao = "Novo";
		
		$this->largura = "100%";
	}
}


$pagina = new clsIndex();

$miolo = new indice();
$pagina->addForm( $miolo );

$pagina->MakeAll();

?>