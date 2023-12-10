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
require_once ("include/clsCadastro.inc.php");
require_once ("include/relatorio.inc.php");
require_once ("include/Geral.inc.php");


class clsIndex extends clsBase
{
	function Formular()
	{
		$this->SetTitulo( "{$this->_instituicao} Relat�rio de Di�rias" );
		$this->processoAp = "333";
	}
}

class indice extends clsCadastro
{
	var $cod_funcionario;
	var $nome_funcionario;
	var	$data_partida;
	var $data_chegada;
	var $data_inicial;
	var $data_final;
	var $valor_total;

	function Inicializar()
	{
		@session_start();
		$this->cod_pessoa_fj = $_SESSION['id_pessoa'];
		session_write_close();
		$retorno = "Novo";
		return $retorno;
	}

	function Gerar()
	{
		$this->campoNumero("valor_inicial", "Valor Inicial", number_format($this->valor_inicial, 2, ',', ''), 20, 20);
		$this->campoNumero("valor_final", "Valor Final", number_format($this->valor_final, 2, ',', ''), 20, 20);
		$this->campoData("data_inicial", "Data Inicial", $this->data_inicial);
		$this->campoData("data_final", "Data Final", $this->data_final);
	}

	function Novo()
	{
		$this->valor_inicial = str_replace( ".", "", $this->valor_inicial );
		$this->valor_inicial = str_replace( ",", ".", $this->valor_inicial );

		$this->valor_final = str_replace( ".", "", $this->valor_final );
		$this->valor_final = str_replace( ",", ".", $this->valor_final );

		if ($this->valor_inicial != "" &&
			$this->valor_final != "")
		{
			if ($this->valor_inicial <= $this->valor_final)
			{
				if ($this->data_inicial != "" ||
					$this->data_final != "")
				{
					$AND = '';
					if ($this->data_inicial)
					{
						$data = explode("/", $this->data_inicial);
						$dia_i = $data[0];
						$mes_i = $data[1];
						$ano_i = $data[2];

						$data_inicial = $ano_i."/".$mes_i."/".$dia_i." 00:00:00";

						$AND = " AND data_pedido >= '{$data_inicial}'";
					}

					if ($this->data_final)
					{
						$data_ = explode("/", $this->data_final);
						$dia_f = $data_[0];
						$mes_f = $data_[1];
						$ano_f = $data_[2];

						$data_final = $ano_f."/".$mes_f."/".$dia_f." 23:59:59";

						$AND .= " AND data_pedido <= '{$data_final}'";
					}
				}

				$sql = "SELECT 	a.ref_funcionario, b.nome, a.data_partida, a.data_chegada, sum( COALESCE(vl100,1) + COALESCE(vl75,1) + COALESCE(vl50,1) + COALESCE(vl25,1) ) as valor FROM pmidrh.diaria a, cadastro.pessoa b WHERE a.ref_funcionario = b.idpes AND a.ativo = 't' AND (select sum(vl100+vl75+vl50+vl25) FROM pmidrh.diaria WHERE cod_diaria = a.cod_diaria) BETWEEN {$this->valor_inicial} AND {$this->valor_final} {$AND} GROUP BY a.ref_funcionario, b.nome, a.data_partida, a.data_chegada ORDER BY b.nome";

				$relatorio = new relatorios("Relat�rio de Di�rias", 200, false, "SEGPOG - Departamento de Log�stica", "A4", "Prefeitura de Itaja�\nSEGPOG - Departamento de Log�stica\nRua Alberto Werner, 100 - Vila Oper�ria\nCEP. 88304-053 - Itaja� - SC");

				//tamanho do retangulo, tamanho das linhas.
				$relatorio->novaPagina();

				$db = new clsBanco();
				$db->Consulta( $sql );
				if( $db->Num_Linhas() )
				{
					$old_funcionario = 0;
					while ( $db->ProximoRegistro() )
					{
						list( $cod_funcionario, $nome_funcionario, $data_partida, $data_chegada, $valor_total ) = $db->Tupla();

						if ($old_funcionario != $cod_funcionario )
						{
							$relatorio->novalinha( array( "Funcion�rio: {$nome_funcionario}"), 0, 13, true);
							$old_funcionario = $cod_funcionario;

							$relatorio->novalinha( array( "Data Partida", "Data Chegada", "Valor Total" ), 0, 13, true);
					}

				$data_partida = date( "d/m/Y H:i", strtotime( substr($data_partida,0,19) ) );
				$data_chegada = date( "d/m/Y H:i", strtotime( substr($data_chegada,0,19) ) );

				$relatorio->novalinha( array( $data_partida, $data_chegada, number_format($valor_total, 2, ',', '.') ),1,13);
				}

				// pega o link e exibe ele ao usuario
				$link = $relatorio->fechaPdf();
				$this->campoRotulo("arquivo","Arquivo", "<a href='" . $link . "'>Visualizar Relat�rio</a>");
			}
			else
			{
				$this->campoRotulo("aviso","Aviso", "Nenhum Funcion�rio neste relatorio.");
			}
			}
			else
			{
				$this->campoRotulo("aviso", "Aviso", "Valor Final menor que o Valor Inicial.");
			}
		}
		else
		{
			$this->campoRotulo("aviso","Aviso", "Nenhum Funcion�rio neste relatorio.");
		}

	$this->largura = "100%";
	return true;
}


	function Editar()
	{
	}

	function Excluir()
	{
	}
}

$pagina = new clsIndex();

$miolo = new indice();
$pagina->addForm( $miolo );

$pagina->MakeAll();
?>