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
require_once ("include/clsListagem.inc.php");
require_once ("include/clsBanco.inc.php");
require_once( "include/pmieducar/geral.inc.php" );

class clsIndexBase extends clsBase
{
	function Formular()
	{
		$this->SetTitulo( "{$this->_instituicao} i-Educar - Calendario Dia" );
		$this->processoAp = "621";
	}
}

class indice extends clsListagem
{
	/**
	 * Referencia pega da session para o idpes do usuario atual
	 *
	 * @var int
	 */
	var $pessoa_logada;

	/**
	 * Titulo no topo da pagina
	 *
	 * @var int
	 */
	var $titulo;

	/**
	 * Quantidade de registros a ser apresentada em cada pagina
	 *
	 * @var int
	 */
	var $limite;

	/**
	 * Inicio dos registros a serem exibidos (limit)
	 *
	 * @var int
	 */
	var $offset;

	var $ref_cod_calendario_ano_letivo;
	var $mes;
	var $dia;
	var $ref_usuario_exc;
	var $ref_usuario_cad;
	var $ref_cod_calendario_dia_motivo;
	var $ref_cod_calendario_atividade;
	var $descricao;
	var $data_cadastro;
	var $data_exclusao;
	var $ativo;
	
	var $ref_cod_escola;
	var $ref_cod_instituicao;

	function Gerar()
	{
		@session_start();
		$this->pessoa_logada = $_SESSION['id_pessoa'];
		session_write_close();

		$this->titulo = "Calendario Dia - Listagem";

		foreach( $_GET AS $var => $val ) // passa todos os valores obtidos no GET para atributos do objeto
			$this->$var = ( $val === "" ) ? null: $val;

		$this->addBanner( "imagens/nvp_top_intranet.jpg", "imagens/nvp_vert_intranet.jpg", "Intranet" );

		
		$obj_permissoes = new clsPermissoes();
		$nivel_usuario = $obj_permissoes->nivel_acesso($this->pessoa_logada);

		if(!$this->ref_cod_escola)
			$this->ref_cod_escola = $obj_permissoes->getEscola($this->pessoa_logada);
		if(!$this->ref_cod_instituicao)			
			$this->ref_cod_instituicao = $obj_permissoes->getInstituicao($this->pessoa_logada);
			
					
		$this->addCabecalhos( array(
			"Calendario Ano Letivo",
			"Dia",
			"Mes",

			"Calendario Dia Motivo"
		) );

		// Filtros de Foreign Keys
	/*	$opcoes = array( "" => "Selecione" );
		if( class_exists( "clsPmieducarCalendarioDiaMotivo" ) )
		{
			$objTemp = new clsPmieducarCalendarioDiaMotivo();
			$lista = $objTemp->lista();
			if ( is_array( $lista ) && count( $lista ) )
			{
				foreach ( $lista as $registro )
				{
					$opcoes["{$registro['cod_calendario_dia_motivo']}"] = "{$registro['nm_motivo']}";
				}
			}
		}
		else
		{
			echo "<!--\nErro\nClasse clsPmieducarCalendarioDiaMotivo nao encontrada\n-->";
			$opcoes = array( "" => "Erro na geracao" );
		}

		$this->campoLista( "ref_cod_calendario_dia_motivo", "Calendario Dia Motivo", $opcoes, $this->ref_cod_calendario_dia_motivo );
*/
		$get_escola     = 1;
		$obrigatorio    = true;
		include("include/pmieducar/educar_campo_lista.php");

	/*	$opcoes = array( "" => "Selecione" );
		if( class_exists( "clsPmieducarCalendarioAnoLetivo" ) )
		{
			$objTemp = new clsPmieducarCalendarioAnoLetivo();
			$lista = $objTemp->lista();
			if ( is_array( $lista ) && count( $lista ) )
			{
				foreach ( $lista as $registro )
				{
					$opcoes["{$registro['cod_calendario_ano_letivo']}"] = "{$registro['ano']}";
				}
			}
		}
		else
		{
			echo "<!--\nErro\nClasse clsPmieducarCalendarioAnoLetivo nao encontrada\n-->";
			$opcoes = array( "" => "Erro na geracao" );
		}
		$this->campoLista( "ref_cod_calendario_ano_letivo", "Calendario Ano Letivo", $opcoes, $this->ref_cod_calendario_ano_letivo );
*/


		// Paginador
		$this->limite = 20;
		$this->offset = ( $_GET["pagina_{$this->nome}"] ) ? $_GET["pagina_{$this->nome}"]*$this->limite-$this->limite: 0;

		$obj_calendario_dia = new clsPmieducarCalendarioDia();
		$obj_calendario_dia->setOrderby( "descricao ASC" );
		$obj_calendario_dia->setLimite( $this->limite, $this->offset );

		$lista = $obj_calendario_dia->lista(
			$this->ref_cod_calendario_ano_letivo,
			$this->mes,
			$this->dia,
			null,
			null,
			$this->ref_cod_calendario_dia_motivo,
			$this->ref_cod_calendario_atividade,
			$this->descricao_ini,
			$this->descricao_fim,
			null,
			null,
			1,
			$this->ref_cod_escola
		);

		$total = $obj_calendario_dia->_total;

		// monta a lista
		if( is_array( $lista ) && count( $lista ) )
		{
			foreach ( $lista AS $registro )
			{

				// pega detalhes de foreign_keys
				if( class_exists( "clsPmieducarCalendarioDiaMotivo" ) )
				{
					$obj_ref_cod_calendario_dia_motivo = new clsPmieducarCalendarioDiaMotivo( $registro["ref_cod_calendario_dia_motivo"] );
					$det_ref_cod_calendario_dia_motivo = $obj_ref_cod_calendario_dia_motivo->detalhe();
					$registro["ref_cod_calendario_dia_motivo"] = $det_ref_cod_calendario_dia_motivo["nm_motivo"];
				}
				else
				{
					$registro["ref_cod_calendario_dia_motivo"] = "Erro na geracao";
					echo "<!--\nErro\nClasse nao existente: clsPmieducarCalendarioDiaMotivo\n-->";
				}

				if( class_exists( "clsPmieducarCalendarioAnoLetivo" ) )
				{
					$obj_ref_cod_calendario_ano_letivo = new clsPmieducarCalendarioAnoLetivo( $registro["ref_cod_calendario_ano_letivo"] );
					$det_ref_cod_calendario_ano_letivo = $obj_ref_cod_calendario_ano_letivo->detalhe();
					$registro["ano"] = $det_ref_cod_calendario_ano_letivo["ano"];
				}
				else
				{
					$registro["ref_cod_calendario_ano_letivo"] = "Erro na geracao";
					echo "<!--\nErro\nClasse nao existente: clsPmieducarCalendarioAnoLetivo\n-->";
				}

				$this->addLinhas( array(
					"<a href=\"educar_calendario_dia_cad.php?ref_cod_calendario_ano_letivo={$registro["ref_cod_calendario_ano_letivo"]}&ano={$registro["ano"]}&mes={$registro["mes"]}&dia={$registro["dia"]}\">{$registro["ano"]}</a>",
					"<a href=\"educar_calendario_dia_cad.php?ref_cod_calendario_ano_letivo={$registro["ref_cod_calendario_ano_letivo"]}&ano={$registro["ano"]}&mes={$registro["mes"]}&dia={$registro["dia"]}\">{$registro["dia"]}</a>",
					"<a href=\"educar_calendario_dia_cad.php?ref_cod_calendario_ano_letivo={$registro["ref_cod_calendario_ano_letivo"]}&ano={$registro["ano"]}&mes={$registro["mes"]}&dia={$registro["dia"]}\">{$registro["mes"]}</a>",	
					"<a href=\"educar_calendario_dia_cad.php?ref_cod_calendario_ano_letivo={$registro["ref_cod_calendario_ano_letivo"]}&ano={$registro["ano"]}&mes={$registro["mes"]}&dia={$registro["dia"]}\">{$registro["ref_cod_calendario_dia_motivo"]}</a>"
				) );
			}
		}
		$this->addPaginador2( "educar_calendario_dia_lst.php", $total, $_GET, $this->nome, $this->limite );
		$obj_permissoes = new clsPermissoes();
		if( $obj_permissoes->permissao_cadastra( 0, $this->pessoa_logada, 0 ) )
		{
		$this->acao = "go(\"educar_calendario_dia_cad.php\")";
		$this->nome_acao = "Novo";
		}

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


