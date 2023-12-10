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
	class clsCronometro
	{
		var $tempo_inicial;
		var $tempo_ultimo;
		var $tempo_total;
		var $tomadas = array();

		function clsCronometro()
		{
			$this->tempo_inicial = $this->get_microtime();
			$this->tempo_ultimo = $this->tempo_inicial;
			$this->tempo_total = 0;
		}

		function get_microtime()
		{
			list( $usec, $sec ) = explode( " ", microtime() );
			return $usec + $sec;
		}

		function getTempoTotal()
		{
			return $this->tempo_total;
		}

		function marca( $str_nome = "" )
		{
			if( ! $str_nome )
			{
				$str_nome = "checkpoint " . count( $this->tomadas ) + 1;
			}
			$mictime = $this->get_microtime();
			$this->tomadas[] = array( "nome" => $str_nome, "tempo_absoluto" => $mictime - $this->tempo_inicial, "diferenca_ultimo" => $mictime - $this->tempo_ultimo, "mictime" => $mictime );
			$this->tempo_ultimo = $mictime;
			$this->tempo_total = $this->tempo_ultimo - $this->tempo_inicial;
		}

		function get_tabela( $html = false)
		{
			if( $html )
			{
				$retorno = "<table border=\"1\" cellpadding=\"2\"><tr><td>Nome</td><td>Tempo Absoluto</td><td>Diferenca Ultimo</td><td>MicroTime</td></tr>";
				foreach ( $this->tomadas AS $tomada )
				{
					$retorno .= "<tr>";
					$retorno .= "<td>{$tomada["nome"]}</td>";
					$retorno .= "<td>" . number_format( $tomada["tempo_absoluto"], 10, ",", "." ) . "</td>";
					$retorno .= "<td>" . number_format( $tomada["diferenca_ultimo"], 10, ",", "." ) . "</td>";
					$retorno .= "<td>{$tomada["mictime"]}</td>";
					$retorno .= "</tr>";
				}
				$retorno .= "</table>";
			}
			else
			{
				$retorno = "\n\nCronometro\n";
				foreach ( $this->tomadas AS $tomada )
				{
					$retorno .= "\tnome: {$tomada["nome"]}\n";
					$retorno .= "\ttempo absoluto: " . number_format( $tomada["tempo_absoluto"], 10, ",", "." ) . "\n";
					$retorno .= "\tdiferenca ultimo: " . number_format( $tomada["diferenca_ultimo"], 10, ",", "." ) . "\n";
					$retorno .= "\tmicrotime: {$tomada["mictime"]}\n\n";
				}
			}
			return $retorno;
		}
	}
?>