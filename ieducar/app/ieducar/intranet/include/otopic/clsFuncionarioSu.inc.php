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
require_once ("include/otopic/otopicGeral.inc.php");


class clsFuncionarioSu
{
	var $ref_ref_cod_pessoa_fj;
	
	var $schema = "pmiotopic";
	var $tabela = "funcionario_su";

	/**
	 * Construtor
	 *
	 * @return Object
	 */
	function clsFuncionarioSu( $int_ref_ref_cod_pessoa_fj = false )
	{
		if(is_numeric($int_ref_ref_cod_pessoa_fj))
		{
			$this->ref_ref_cod_pessoa_fj = $int_ref_ref_cod_pessoa_fj;
		}
	}
	
	/**
	 * Fun��o que cadastra um novo registro com os valores atuais
	 *
	 * @return bool
	 */
	function cadastra()
	{
		$db = new clsBanco();
		// verifica��es de campos obrigatorios para inser��o
		if( $this->ref_ref_cod_pessoa_fj )
		{
			$db->Consulta("INSERT INTO {$this->schema}.{$this->tabela} ( ref_ref_cod_pessoa_fj ) VALUES ( '$this->ref_ref_cod_pessoa_fj' )");
			return true;
		}
		return false;
	}
	
	/**
	 * Remove o registro atual
	 *
	 * @return bool
	 */
	function exclui()
	{
		$db = new clsBanco();
		$db->Consulta("DELETE FROM $this->schema.$this->tabela ");
	}
	
	
	function detalhe()
	{
		if($this->ref_ref_cod_pessoa_fj)
		{
			$db = new clsBanco();
			$db->Consulta( "SELECT ref_ref_cod_pessoa_fj FROM {$this->schema}.{$this->tabela} WHERE ref_ref_cod_pessoa_fj = $this->ref_ref_cod_pessoa_fj" );
			$resultado = array();
			if ( $db->ProximoRegistro() ) 
			{
				$tupla = $db->Tupla();
				return  $tupla;
			}
		}
		return false;
	}
	
	/**
	 * Exibe uma lista baseada nos parametros de filtragem passados
	 *
	 * @return Array
	 */
	function lista( $int_limite_ini = false, $int_limite_qtd = false)
	{
		
		if($int_limite_ini !== false && $int_limite_qtd)
		{
			$limit = " LIMIT $int_limite_ini,$int_limite_qtd";
		}
		
		$db = new clsBanco();
		$total = $db->UnicoCampo( "SELECT COUNT(0) AS total FROM {$this->schema}.{$this->tabela} " );
		$db->Consulta( "SELECT ref_ref_cod_pessoa_fj FROM {$this->schema}.{$this->tabela} $limit" );
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
	
}
?>
