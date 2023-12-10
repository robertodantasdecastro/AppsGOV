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
require_once( "include/pmieducar/geral.inc.php" );

class clsIndexBase extends clsBase
{
	function Formular()
	{
		$this->SetTitulo( "{$this->_instituicao} i-Educar - Serie Pre Requisito" );
		$this->processoAp = "599";
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

	var $ref_cod_pre_requisito;
	var $ref_cod_operador;
	var $ref_cod_serie;
	var $valor;

	function Gerar()
	{
		@session_start();
		$this->pessoa_logada = $_SESSION['id_pessoa'];
		session_write_close();

		$this->titulo = "Serie Pre Requisito - Detalhe";
		$this->addBanner( "imagens/nvp_top_intranet.jpg", "imagens/nvp_vert_intranet.jpg", "Intranet" );

		$this->ref_cod_serie 		 = $_GET["ref_cod_serie"];
		$this->ref_cod_operador 	 = $_GET["ref_cod_operador"];
		$this->ref_cod_pre_requisito = $_GET["ref_cod_pre_requisito"];

		$tmp_obj = new clsPmieducarSeriePreRequisito( $this->ref_cod_pre_requisito, $this->ref_cod_operador, $this->ref_cod_serie );
		$registro = $tmp_obj->detalhe();

		if( ! $registro )
		{
			header( "location: educar_serie_pre_requisito_lst.php" );
			die();
		}

		if( class_exists( "clsPmieducarSerie" ) )
		{
			$obj_ref_cod_serie = new clsPmieducarSerie( $registro["ref_cod_serie"] );
			$det_ref_cod_serie = $obj_ref_cod_serie->detalhe();
			$registro["ref_cod_serie"] = $det_ref_cod_serie["nm_serie"];
		}
		else
		{
			$registro["ref_cod_serie"] = "Erro na geracao";
			echo "<!--\nErro\nClasse nao existente: clsPmieducarSerie\n-->";
		}

		if( class_exists( "clsPmieducarOperador" ) )
		{
			$obj_ref_cod_operador = new clsPmieducarOperador( $registro["ref_cod_operador"] );
			$det_ref_cod_operador = $obj_ref_cod_operador->detalhe();
			$registro["ref_cod_operador"] = $det_ref_cod_operador["nome"];
		}
		else
		{
			$registro["ref_cod_operador"] = "Erro na geracao";
			echo "<!--\nErro\nClasse nao existente: clsPmieducarOperador\n-->";
		}

		if( class_exists( "clsPmieducarPreRequisito" ) )
		{
			$obj_ref_cod_pre_requisito = new clsPmieducarPreRequisito( $registro["ref_cod_pre_requisito"] );
			$det_ref_cod_pre_requisito = $obj_ref_cod_pre_requisito->detalhe();
			$registro["ref_cod_pre_requisito"] = $det_ref_cod_pre_requisito["nome"];
		}
		else
		{
			$registro["ref_cod_pre_requisito"] = "Erro na geracao";
			echo "<!--\nErro\nClasse nao existente: clsPmieducarPreRequisito\n-->";
		}


		if( $registro["ref_cod_pre_requisito"] )
		{
			$this->addDetalhe( array( "Pre Requisito", "{$registro["ref_cod_pre_requisito"]}") );
		}
		if( $registro["ref_cod_operador"] )
		{
			$this->addDetalhe( array( "Operador", "{$registro["ref_cod_operador"]}") );
		}
		if( $registro["ref_cod_serie"] )
		{
			$this->addDetalhe( array( "Serie", "{$registro["ref_cod_serie"]}") );
		}
		if( $registro["valor"] )
		{
			$this->addDetalhe( array( "Valor", "{$registro["valor"]}") );
		}

		$obj_permissoes = new clsPermissoes();
		if( $obj_permissoes->permissao_cadastra( 599, $this->pessoa_logada, 3 ) )
		{
		$this->url_novo = "educar_serie_pre_requisito_cad.php";
		$this->url_editar = "educar_serie_pre_requisito_cad.php?ref_cod_pre_requisito={$this->ref_cod_pre_requisito}&ref_cod_operador={$this->ref_cod_operador}&ref_cod_serie={$this->ref_cod_serie}";
		}

		$this->url_cancelar = "educar_serie_pre_requisito_lst.php";
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