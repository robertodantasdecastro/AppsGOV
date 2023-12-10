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
require_once( "include/pmicontrolesis/geral.inc.php" );

class clsIndexBase extends clsBase
{
	function Formular()
	{
		$this->SetTitulo( "{$this->_instituicao} Itinerario" );
		$this->processoAp = "614";
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

	var $cod_itinerario;
	var $ref_funcionario_cad;
	var $ref_funcionario_exc;
	var $numero;
	var $itinerario;
	var $retorno;
	var $horarios;
	var $descricao_horario;
	var $data_cadastro;
	var $data_exclusao;
	var $ativo;
	var $nome;

	function Gerar()
	{
		@session_start();
		$this->pessoa_logada = $_SESSION['id_pessoa'];
		session_write_close();

		$this->titulo = "Itinerario - Detalhe";
		$this->addBanner( "imagens/nvp_top_intranet.jpg", "imagens/nvp_vert_intranet.jpg", "Intranet" );

		$this->cod_itinerario=$_GET["cod_itinerario"];

		$tmp_obj = new clsPmicontrolesisItinerario( $this->cod_itinerario );
		$registro = $tmp_obj->detalhe();

		if( ! $registro )
		{
			header( "location: controlesis_itinerario_lst.php" );
			die();
		}




		if( $registro["cod_itinerario"] )
		{
			$this->addDetalhe( array( "Itinerario", "{$registro["cod_itinerario"]}") );
		}
		if( $registro["numero"] )
		{
			$this->addDetalhe( array( "Numero", "{$registro["numero"]}") );
		}
		if( $registro["itinerario"] )
		{
			$this->addDetalhe( array( "Itinerario", "{$registro["itinerario"]}") );
		}
		if( $registro["retorno"] )
		{
			$this->addDetalhe( array( "Retorno", "{$registro["retorno"]}") );
		}
		if( $registro["horarios"] )
		{
			$this->addDetalhe( array( "Horarios", "{$registro["horarios"]}") );
		}
		if( $registro["descricao_horario"] )
		{
			$this->addDetalhe( array( "Descric&atilde;o Horario", "{$registro["descricao_horario"]}") );
		}
		if( $registro["nome"] )
		{
			$this->addDetalhe( array( "Nome", "{$registro["nome"]}") );
		}

		$this->url_novo = "controlesis_itinerario_cad.php";
		$this->url_editar = "controlesis_itinerario_cad.php?cod_itinerario={$registro["cod_itinerario"]}";
		$this->url_cancelar = "controlesis_itinerario_lst.php";
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