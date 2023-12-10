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
		$this->SetTitulo( "{$this->_instituicao} Menu" );
		$this->processoAp = "35";
	}
}

class indice extends clsDetalhe
{
	function Gerar()
	{
		$this->titulo = "Detalhe de Menu";
		$this->addBanner( "imagens/nvp_top_intranet.jpg", "imagens/nvp_vert_intranet.jpg", "Intranet" );

		$id_item = @$_GET['id_item'];

		$db = new clsBanco();
		$db->Consulta( "SELECT cat.nm_menu, sub.cod_menu_submenu, sub.cod_sistema, sub.nm_submenu, sub.arquivo, sub.title FROM menu_submenu AS sub, menu_menu AS cat WHERE cod_menu_submenu={$id_item} AND cod_menu_menu = ref_cod_menu_menu" );
		if ($db->ProximoRegistro())
		{
			list ( $categoria, $id_item, $id_sistema, $nome, $arquivo, $alt) = $db->Tupla();
			$this->addDetalhe( array("Nome", $nome) );
			$this->addDetalhe( array("Categoria", $categoria) );
			$this->addDetalhe( array("Arquivo", $arquivo) );
			$this->addDetalhe( array("Title", $alt) );
			if ($id_sistema == '2')
			{
				$objPessoa = new clsPessoaFisica();
				$objPessoaFj = new clsPessoaFj();
				$dba = new clsBanco();
				//$dba->Consulta( "SELECT nm_pessoa FROM pessoa_fj WHERE cod_pessoa_fj in (SELECT ref_ref_cod_pessoa_fj FROM menu_funcionario WHERE ref_cod_menu_submenu in ({$id_item}, 0)) ORDER BY nm_pessoa" );
				$lista_id = array();
				$dba->Consulta ( "SELECT ref_ref_cod_pessoa_fj FROM menu_funcionario WHERE ref_cod_menu_submenu in ({$id_item}, 0)" );
				while ($dba->ProximoRegistro())
				{
					list($cod) = $dba->Tupla();
					$lista_id[] = $cod; 
				}
				if( count( $lista_id ) )
				{
					$pessoas = $objPessoaFj->lista(false, false, false, false, $lista_id);
				}
				/*
				while ($dba->ProximoRegistro())
				{
					//list($nome_) = $dba->Tupla();
					list($nome_) = $objPessoa->queryRapida($ref_ref_cod_pessoa, "nome");
					$this->addDetalhe( array("Autorizados", "{$nome_}") );
				}
				*/
				if( count( $pessoas ) )
				{
					foreach ($pessoas as $pessoa)
					{
						//print_r( $pessoa );
						//list($nome_) = $objPessoa->queryRapida($pessoa["idpes"], "nome");
						$this->addDetalhe( array("Autorizados", "{$pessoa["nome"]}") );
					}
				}
			}

		}
		$this->url_novo = "menu_cad.php";
		$this->url_editar = "menu_cad.php?id_item={$id_item}";
		$this->url_cancelar = "menu_lst.php";

		$this->largura = "100%";
	}
}


$pagina = new clsIndex();

$miolo = new indice();
$pagina->addForm( $miolo );

$pagina->MakeAll();

?>