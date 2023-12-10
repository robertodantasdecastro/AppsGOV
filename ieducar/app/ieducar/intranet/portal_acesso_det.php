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
require_once( "include/portal/clsPortalAcesso.inc.php" );

class clsIndexBase extends clsBase
{
	function Formular()
	{
		$this->SetTitulo( "{$this->_instituicao} Acesso" );
		$this->processoAp = "666";
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

	var $cod_acesso;
	var $data_hora;
	var $ip_externo;
	var $ip_interno;
	var $cod_pessoa;
	var $obs;
	var $sucesso;

	function Gerar()
	{
		@session_start();
		$this->pessoa_logada = $_SESSION['id_pessoa'];
		session_write_close();

		$this->titulo = "Acesso - Detalhe";
		$this->addBanner( "imagens/nvp_top_intranet.jpg", "imagens/nvp_vert_intranet.jpg", "Intranet" );

		$this->cod_acesso=$_GET["cod_acesso"];

		$tmp_obj = new clsPortalAcesso( $this->cod_acesso );
		$registro = $tmp_obj->detalhe();

		if( ! $registro )
		{
			header( "location: portal_acesso_lst.php" );
			die();
		}


		if( $registro["cod_acesso"] )
		{
			$this->addDetalhe( array( "Acesso", "{$registro["cod_acesso"]}") );
		}
		if( $registro["data_hora"] )
		{
			$this->addDetalhe( array( "Data Hora", dataFromPgToBr( $registro["data_hora"], "d/m/Y H:i" ) ) );
		}
		if( $registro["ip_externo"] )
		{
			$this->addDetalhe( array( "Ip Externo", "{$registro["ip_externo"]}") );
		}
		if( $registro["ip_interno"] )
		{
			$this->addDetalhe( array( "Ip Interno", "{$registro["ip_interno"]}") );
		}
		if( $registro["cod_pessoa"] )
		{
			$this->addDetalhe( array( "Pessoa", "{$registro["cod_pessoa"]}") );
		}
		if( $registro["obs"] )
		{
			$this->addDetalhe( array( "Obs", "{$registro["obs"]}") );
		}
		if( ! is_null( $registro["sucesso"] ) )
		{
			$this->addDetalhe( array( "Sucesso", dbBool( $registro["sucesso"] ) ? "Sim": "N�o" ) );
		}


		$this->url_novo = "portal_acesso_cad.php";
		$this->url_editar = "portal_acesso_cad.php?cod_acesso={$registro["cod_acesso"]}";

		$this->url_cancelar = "portal_acesso_lst.php";
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