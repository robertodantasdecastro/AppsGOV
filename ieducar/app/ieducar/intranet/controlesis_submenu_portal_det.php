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
require_once ("include/clsBase.inc.php");
require_once ("include/clsDetalhe.inc.php");
require_once ("include/clsBanco.inc.php");
require_once( "include/pmicontrolesis/clsPmicontrolesisSubmenuPortal.inc.php" );
require_once( "include/pmicontrolesis/clsPmicontrolesisMenuPortal.inc.php" );

class clsIndexBase extends clsBase
{
	function Formular()
	{
		$this->SetTitulo( "{$this->_instituicao} Submenu Portal" );
		$this->processoAp = "613";
	}
}

class indice extends clsDetalhe
{
	/**
	 * Titulo no topo da pagina
	 *
	 * @var int
	 */
	var $titulo;

	var $cod_submenu_portal;
	var $ref_funcionario_cad;
	var $ref_funcionario_exc;
	var $ref_cod_menu_portal;
	var $nm_submenu;
	var $arquivo;
	var $target;
	var $title;
	var $ordem;
	var $data_cadastro;
	var $data_exclusao;
	var $ativo;

	function Gerar()
	{
		$this->titulo = "Submenu Portal - Detalhe";
		$this->addBanner( "imagens/nvp_top_intranet.jpg", "imagens/nvp_vert_intranet.jpg", "Intranet" );

		$this->cod_submenu_portal=$_GET["cod_submenu_portal"];

		$tmp_obj = new clsPmicontrolesisSubmenuPortal( $this->cod_submenu_portal );
		$registro = $tmp_obj->detalhe();
		if( class_exists( "clsPmicontrolesisMenuPortal" ) )
		{
			$obj_ref_cod_menu_portal = new clsPmicontrolesisMenuPortal( $registro["ref_cod_menu_portal"] );
			$det_ref_cod_menu_portal = $obj_ref_cod_menu_portal->detalhe();
			$registro["ref_cod_menu_portal"] = $det_ref_cod_menu_portal["nm_menu_portal"];
		}
		else
		{
			$registro["ref_cod_menu_portal"] = "Erro na geracao";
			echo "<!--\nErro\nClasse nao existente: clsPmicontrolesisMenuPortal\n-->";
		}



	if( $registro["ref_cod_menu_portal"] )
		{
			$this->addDetalhe( array( "Menu Portal", "{$registro["ref_cod_menu_portal"]}") );
		}
		if( $registro["nm_submenu"] )
		{
			$this->addDetalhe( array( "Nome Submenu", "{$registro["nm_submenu"]}") );
		}
		if( $registro["arquivo"] )
		{
			$this->addDetalhe( array( "Arquivo", "{$registro["arquivo"]}") );
		}
		if( $registro["target"] )
		{
			$registro["target"] = $registro["target"]=='S' ? '_self' : '_blank';
			$this->addDetalhe( array( "Target", "{$registro["target"]}") );
		}
		if( $registro["title"] )
		{
			$this->addDetalhe( array( "Title", "{$registro["title"]}") );
		}
		if( $registro["ordem"] )
		{
			$this->addDetalhe( array( "Ordem", "{$registro["ordem"]}") );
		}

		$this->url_novo = "controlesis_submenu_portal_cad.php";
		$this->url_editar = "controlesis_submenu_portal_cad.php?cod_submenu_portal={$registro["cod_submenu_portal"]}";
		$this->url_cancelar = "controlesis_submenu_portal_lst.php";
		$this->largura = "100%";
	}
}

// cria uma extensao da classe base
$pagina = new clsIndexBase();
// cria o conteudo
$miolo = new indice();
// adiciona o conteudo na clsBase
$pagina->addForm( $miolo );
// gera o html
$pagina->MakeAll();
?>