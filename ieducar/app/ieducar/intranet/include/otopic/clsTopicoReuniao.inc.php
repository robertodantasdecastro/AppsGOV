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


class clsTopicoReuniao
{
	var $ref_cod_topico;
	var $ref_cod_reuniao;
	var $parecer;
	var $finalizado;
	var $data_parecer;
	
	var $tabela = "pmiotopic.topicoreuniao";

	/**
	 * Construtor
	 *
	 * @return Object
	 */
	function clsTopicoReuniao( $int_ref_cod_topico = false, $int_ref_cod_reuniao = false, $str_parecer= false, $str_finalizado = false, $str_data_parecer = false )
	{
		if(is_numeric($int_ref_cod_topico))
		{
			$this->ref_cod_topico = $int_ref_cod_topico;
		}
		
		if(is_numeric($int_ref_cod_reuniao))
		{
			$this->ref_cod_reuniao = $int_ref_cod_reuniao;
		}
		
		if(is_string($str_parecer))
		{
			$this->parecer = $str_parecer;
		}
		
		if(is_numeric($str_finalizado))
		{
			$this->finalizado = $str_finalizado;
		}
		
		if(is_string($str_data_parecer))
		{
			$this->data_parecer = $str_data_parecer;
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
		if( $this->ref_cod_topico && $this->ref_cod_reuniao)
		{
			$campos = "";
			$valores= "";
			
			$db->Consulta("INSERT INTO {$this->tabela} ( ref_cod_topico, ref_cod_reuniao $campos ) VALUES ( '$this->ref_cod_topico', '{$this->ref_cod_reuniao}' )");
			return true;
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
		if( $this->ref_cod_topico && $this->ref_cod_reuniao)
		{
			$setVirgula = "";
			$set = "";
			
			if( $this->parecer )
			{
				$set .= "{$setVirgula}parecer = '{$this->parecer}'";
				$setVirgula = ", ";
				
				$set .= "{$setVirgula}data_parecer = 'NOW()'";
				$setVirgula = ", ";
			}else 
			{
				$set .= "{$setVirgula}parecer = NULL";
				$setVirgula = ", ";
				
				$set .= "{$setVirgula}data_parecer = NULL";
				$setVirgula = ", ";				
			}
			
			if( is_numeric( $this->finalizado) )
			{
				$set .= "{$setVirgula}finalizado = '{$this->finalizado}'";
				$setVirgula = ", ";
			}else 
			{
				$set .= "{$setVirgula}finalizado = NULL";
				$setVirgula = ", ";				
			}
			$db = new clsBanco();
			$db->Consulta( "UPDATE {$this->tabela} SET $set WHERE ref_cod_topico = '{$this->ref_cod_topico}' AND ref_cod_reuniao = '{$this->ref_cod_reuniao}'");
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
		if($this->ref_cod_topico && $this->ref_cod_reuniao)
		{
			$db = new clsBanco();
			$db->Consulta("DELETE FROM {$this->tabela} WHERE ref_cod_topico = '{$this->ref_cod_topico}' AND ref_cod_reuniao = '{$this->ref_cod_reuniao}'");
					
			return true;
		}
		return false;
	}
	
	function exclui_todos($cod_reuniao)
	{
		if($cod_reuniao)
		{
			$db = new clsBanco();
			//echo ("DELETE FROM {$this->tabela} WHERE ref_cod_reuniao = '{$cod_reuniao}'"); die();
			$db->Consulta("DELETE FROM {$this->tabela} WHERE ref_cod_reuniao = '{$cod_reuniao}'");
					
			return true;
		}
		return false;
	}
	
	/**
	 * Exibe uma lista baseada nos parametros de filtragem passados
	 *
	 * @return Array
	 */
	function lista( $str_parecer= false, $str_finalizado = false, $str_data_parecer_ini = false, $str_data_parecer_fim = false, $int_limite_ini = 0, $int_limite_qtd = 20, $str_order_by = false, $int_cod_reuniao = false, $int_ref_cod_topico =false )
	{
		// verificacoes de filtros a serem usados
		$where = "";
		$and = "";
		
		if( is_string( $str_parecer) )
		{
			$where .= " $and parecer ILIKE '%$str_parecer%'";
			$and = " AND ";
		}		
		if( is_numeric( $int_cod_reuniao) )
		{
			$where .= " $and ref_cod_reuniao  = '$int_cod_reuniao'";
			$and = " AND ";
		}
		
		if( is_numeric( $int_ref_cod_topico) )
		{
			$where .= " $and ref_cod_topico = '$int_ref_cod_topico'";
			$and = " AND ";
		}
			
		if( is_string( $str_finalizado) )
		{
			$where .= " $and finalizado ILIKE '%$str_finalizado%'";
			$and = " AND ";
		}
		
		if( is_string( $str_data_parecer_ini) )
		{
			$where .= " $and data_parecer_ini >= '$str_data_parecer_ini' ";
			$and = " AND ";
		}	
		
		if( is_string( $str_data_parecer_fim) )
		{
			$where .= " $and data_parecer_fim <= '$str_data_parecer_fim' ";
			$and = " AND ";
		}

		$orderBy = "";
		if( is_string( $str_order_by))
		{
			$orderBy = "ORDER BY $str_order_by";
		}
			
		if($where)
		{
			$where = " WHERE $where";
		}
		
		if($limit)
		{
			$limit = " LIMIT $int_limite_ini,$int_limite_qtd";
		}
		
		$db = new clsBanco();
		$total = $db->UnicoCampo( "SELECT COUNT(0) AS total FROM {$this->tabela} $where" );
		$db->Consulta( "SELECT ref_cod_topico, ref_cod_reuniao, parecer, finalizado, data_parecer FROM {$this->tabela} $where $orderBy $limit" );
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
		if( $this->ref_cod_topico )
		{
			$db = new clsBanco();
			$reuniao = $db->UnicoCampo( "SELECT MAX(ref_cod_reuniao) as maximo FROM {$this->tabela} WHERE  ref_cod_topico = '{$this->ref_cod_topico}' GROUP BY ref_cod_topico ");
			if($reuniao)
			{
				$where =  "AND ref_cod_reuniao = $reuniao ";
			}
			$db->Consulta( "SELECT ref_cod_topico, ref_cod_reuniao, parecer, finalizado, data_parecer FROM {$this->tabela} WHERE  ref_cod_topico = '{$this->ref_cod_topico}' $where " );
			if( $db->ProximoRegistro() )
			{
				$tupla = $db->Tupla();
				return $tupla;
			}
		}
		return false;
	}
}
?>
