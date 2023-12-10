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
require_once ("include/clsBanco.inc.php");
require_once ("include/Geral.inc.php");

class clsTipoLogradouro
{
	var $idtlog;
	var $descricao;

	var $tabela;
	var $schema;

	/**
	 * Construtor
	 *
	 * @return Object:clsTipoLogradouro
	 */
	function clsTipoLogradouro( $idtlog=false, $descricao=false)
	{
		$this->idtlog    = $idtlog;
		$this->descricao = $descricao;

		$this->tabela = "tipo_logradouro";
		$this->schema = "urbano";
	}

	/**
	 * Funcao que cadastra um novo registro com os valores atuais
	 *
	 * @return bool
	 */
	function cadastra()
	{
		$db = new clsBanco();
		// verificacoes de campos obrigatorios para insercao
		if( is_numeric($this->idtlog) &&                   	is_string($this->descricao))
		{
			$db->Consulta( "INSERT INTO {$this->schema}.{$this->tabela} (idtlog,  descricao) VALUES ( '{$this->idtlog}', '{$this->descricao}')" );

		}
		return false;
	}

	/**
	 * Edita o registro atual
	 *
	 * @return bool
	 */
	function edita()
	{
		// verifica campos obrigatorios para edicao
		if(is_numeric($this->idtlog) && is_string($this->descricao))
		{
			$db = new clsBanco();
			$db->Consulta( "UPDATE {$this->schema}.{$this->tabela} SET descricao = '$this->descricao' WHERE idtlog = '$this->idtlog' " );
			return true;
		}
		return false;
	}

	/**
	 * Remove o registro atual
	 *
	 * @return bool
	 */
	function exclui( )
	{
		if( is_numeric($this->idtlog) &&  is_string($this->descricao))
		{
			$db = new clsBanco();
			$db->Consulta("DELETE FROM {$this->schema}.{$this->tabela} WHERE idtlog = {$this->idtlog}");
			return true;
		}
		return false;
	}

	/**
	 * Exibe uma lista baseada nos parametros de filtragem passados
	 *
	 * @return Array
	 */
	function lista( $int_idtlog=false, $str_descricao=false, $str_ordenacao="descricao", $int_limite_ini=false, $int_limite_qtd=false )
	{
		// verificacoes de filtros a serem usados
		$whereAnd = "WHERE ";
		if(is_numeric($int_idtlog))
		{
			$where .= "{$whereAnd}idtlog = '$int_idtlog'";
			$whereAnd = " AND ";
		}
		if(is_string($str_descricao))
		{
			$where .= "{$whereAnd}descricao ILIKE '%$str_descricao%'";
		}

		$orderBy = "";
		if(is_string($str_ordenacao))
		{
			$orderBy = "ORDER BY $str_ordenacao";
		}
		$limit = "";
		if(is_numeric($int_limite_ini) && is_numeric($int_limite_qtd))
		{
			$limit = " LIMIT $int_limite_ini,$int_limite_qtd";
		}

		$db = new clsBanco();
		$db->Consulta( "SELECT COUNT(0) AS total FROM {$this->schema}.{$this->tabela} $where" );
		$db->ProximoRegistro();
		$total = $db->Campo( "total" );
		$db->Consulta( "SELECT idtlog, descricao FROM {$this->schema}.{$this->tabela} $where $orderBy $limit" );
		$resultado = array();
		while ( $db->ProximoRegistro() )
		{
			$tupla = $db->Tupla();
			$tupla["total"] = $total;
			$resultado[] = $tupla;
		}
		if( count( $resultado ) )
		{
			return $resultado;
		}
		return false;
	}

	/**
	 * Retorna um array com os detalhes do objeto
	 *
	 * @return Array
	 */
	function detalhe()
	{
		$db = new clsBanco();
		$db->Consulta("SELECT idtlog, descricao FROM {$this->schema}.{$this->tabela} WHERE idtlog = '{$this->idtlog}' ");
		if( $db->ProximoRegistro() )
		{
			$tupla = $db->Tupla();
			return $tupla;
		}
		return false;
	}
}

?>