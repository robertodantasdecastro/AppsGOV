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

class clsTutormenu
{
	var $cod_tutormenu;
	var $nm_tutormenu;
	
	// Vari�veis que definem a tabela e o schema em que a tabela se encontra
	var $tabela;
	var $schema;

	/**
	 * Construtor
	 *
	 * @return Object:clsGrupo
	 */
	function clstutormenu( $cod_tutormenu = false, $nm_tutormenu = false )
	{
		$this->cod_tutormenu = $cod_tutormenu;
		$this->nm_tutormenu  = $nm_tutormenu;
		
		// Difini���o da tabela
		$this->tabela = "tutormenu";
		// Difini���o do schema
		$this->schema = "pmicontrolesis";
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
		if( is_string( $this->nm_tutormenu ) )
		{
			$db->Consulta( "INSERT INTO {$this->schema}.{$this->tabela} ( nm_tutormenu ) VALUES ( '$this->nm_tutormenu' )" );
			return $db->InsertId("{$this->schema}.tutormenu_cod_tutormenu_seq");
		}
		return false;
	}
	
	/**
	 * Edita o registro atual
	 *
	 * @return bool
	 */
	function edita(  )
	{
		// verifica campos obrigatorios para edicao
		if( is_numeric( $this->cod_tutormenu ) )
		{
			if( is_string($this->nm_tutormenu) )
			{
				$set .=  "SET {$where_set} nm_tutormenu = '$this->nm_tutormenu' ";
			}

			if($set)
			{
				$db = new clsBanco();
				$db->Consulta( "UPDATE {$this->schema}.{$this->tabela} $set WHERE cod_tutormenu = '{$this->cod_tutormenu}'" );
				return true;
			}
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
		// verifica se existe um ID definido para delecao
		if( is_numeric( $this->cod_tutormenu) )
		{
			$db = new clsBanco();
			$db->Consulta( "DELETE FROM {$this->schema}.{$this->tabela} WHERE cod_tutormenu = {$this->cod_tutormenu} " );
			return true;
		}
		return false;
	}
	
	/**
	 * Exibe uma lista baseada nos parametros de filtragem passados
	 *
	 * @return Array
	 */
	function lista( $str_nm_tutormenu = false, $int_limite_ini = false, $int_limite_qtd = false, $str_ordenacao = false )
	{
		// verificacoes de filtros a serem usados
		$whereAnd = "WHERE ";

		if( is_string( $str_nm_tutormenu ) )
		{
			$where .= "{$whereAnd}nm_tutormenu <= '$str_nm_tutormenu'";
			$whereAnd = " AND ";
		}
		
		$orderBy = "";
		if( is_string( $str_ordenacao ) )
		{
			$orderBy = "ORDER BY $str_ordenacao";
		}
		
		$limit = "";
		if( is_numeric( $int_limite_ini ) && is_numeric( $int_limite_qtd ) )
		{
			$limit = " LIMIT $int_limite_qtd OFFSET $int_limite_ini";
		}

		$db = new clsBanco();
		$total = $db->UnicoCampo( "SELECT COUNT(0) AS total FROM {$this->schema}.{$this->tabela} $where " );
		$db->Consulta( "SELECT cod_tutormenu, nm_tutormenu FROM {$this->schema}.{$this->tabela} $where $orderBy $limit" );
		$resultado = array();
		while ( $db->ProximoRegistro() ) 
		{
			$tupla = $db->Tupla();
			$tupla['total'] = $total;
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
		if ($this->cod_tutormenu)
		{
			$db = new clsBanco();
			$db->Consulta( "SELECT cod_tutormenu, nm_tutormenu FROM {$this->schema}.{$this->tabela} WHERE cod_tutormenu = '$this->cod_tutormenu' " );
			if( $db->ProximoRegistro() )
			{
				$tupla = $db->Tupla();
				return $tupla;
			}
			return false;
		}
	}
}
?>