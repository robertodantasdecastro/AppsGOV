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
		$this->SetTitulo( "{$this->_instituicao} Licita&ccedil;&otilde;es - finalizar!" );
		$this->processoAp = "159";
	}
}

class indice extends clsListagem
{
	function Gerar()
	{
		$this->titulo = "Licita&ccedil;&otilde;es - finalizadas";
		$this->addBanner( "imagens/nvp_top_intranet.jpg", "imagens/nvp_vert_intranet.jpg", "Intranet" );
		
		$this->addCabecalhos( array( "Data", "Objeto") );
		
		
		$db = new clsBanco();
		$db->Consulta( "SELECT count(0) FROM compras_licitacoes l, compras_pregao_execucao c WHERE c.ref_cod_compras_final_pregao is NULL AND c.ref_cod_compras_licitacoes = l.cod_compras_licitacoes");
		
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
		
		$db->Consulta( "SELECT cod_compras_licitacoes, 				               data_hora, numero							  FROM compras_licitacoes, 								   compras_pregao_execucao		                   WHERE ref_cod_compras_licitacoes =   					   cod_compras_licitacoes ");
			  
		while ($db->ProximoRegistro())
		{
			list ($cod_licitacao, $datahora, $numero) = $db->Tupla();

			$this->addLinhas( array( "<a href='licitacoes_finalizadas_det.php?id_licitacao={$cod_licitacao}'><img src='imagens/noticia.jpg' border=0>{$datahora}</a>", "<a href='licitacoes_finalizar_cad.php?id_licitacao={$cod_licitacao}'>{$numero}</a>" ) );
		}
		$this->paginador("licitacoes_finalizadas_lst.php?",$total_tmp,$limite,@$_GET['pos_atual']);
		$this->largura = "100%";
	}
}


$pagina = new clsIndex();

$miolo = new indice();
$pagina->addForm( $miolo );

$pagina->MakeAll();

?>