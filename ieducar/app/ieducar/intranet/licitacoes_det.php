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
require_once ("include/clsDetalhe.inc.php");
require_once ("include/clsBanco.inc.php");

class clsIndex extends clsBase
{
	
	function Formular()
	{
		$this->SetTitulo( "{$this->_instituicao} Licita&ccedil;&otilde;es" );
		$this->processoAp = "29";
	}
}

class indice extends clsDetalhe
{
	function Gerar()
	{
		$this->titulo = "Detalhe da licita&ccedil;&atilde;o";
		$this->addBanner( "imagens/nvp_top_intranet.jpg", "imagens/nvp_vert_intranet.jpg", "Intranet" );

		$id_licitacao = @$_GET['id_licitacao'];

		$objPessoa = new clsPessoaFisica();
		$db = new clsBanco();
		$db->Consulta( "SELECT l.ref_ref_cod_pessoa_fj, m.nm_modalidade, l.numero, l.objeto, l.data_hora FROM compras_licitacoes l, compras_modalidade m WHERE m.cod_compras_modalidade=l.ref_cod_compras_modalidade AND cod_compras_licitacoes={$id_licitacao}" );
		if ($db->ProximoRegistro())
		{
			//list ($nm, $numero, $objeto, $data_c, $hora) = $db->Tupla();
			list ( $cod_pessoa, $nm, $numero, $objeto, $data_c, $hora ) = $db->Tupla();
			list ( $nome ) = $objPessoa->queryRapida($cod_pessoa, "nome");
			$hora = date('H:i', strtotime(substr($data_c,0,19)));
			$data_c= date('d/m/Y', strtotime(substr($data_c,0,19) ));

			$this->addDetalhe( array("Modalidade", $nm." ".$numero) );
			$this->addDetalhe( array("Objeto", $objeto) );
			$this->addDetalhe( array("Data", "{$data_c}") );
			$this->addDetalhe( array("Hora", $hora) );

		}
		$this->url_novo = "licitacoes_cad.php";
		$this->url_editar = "licitacoes_cad.php?id_licitacao=$id_licitacao";
		$this->url_cancelar = "licitacoes_lst.php";

		$this->largura = "100%";
	}
}


$pagina = new clsIndex();

$miolo = new indice();
$pagina->addForm( $miolo );

$pagina->MakeAll();

?>