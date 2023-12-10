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
		$this->processoAp = "299";
	}
}

class indice extends clsCadastro
{
	var $cod_funcionario;
	var $nome_funcionario;
	var $data_partida;
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
		$this->campoData("data_inicial", "Data Inicial", $this->data_inicial);
		$this->campoData("data_final", "Data Final", $this->data_final);
	}

	function Novo()
	{
		if ($this->data_inicial != "" || $this->data_final != "")
		{
			$AND = '';
			if ($this->data_inicial)
			{
				$data = explode("/", $this->data_inicial);
				$dia_i = $data[0];
				$mes_i = $data[1];
				$ano_i = $data[2];

				$data_inicial = $ano_i."-".$mes_i."-".$dia_i." 00:00:00";

				$AND = " AND a.data_partida >= '{$data_inicial}'";
			}

			if ($this->data_final)
			{

				$data_ = explode("/", $this->data_final);
				$dia_f = $data_[0];
				$mes_f = $data_[1];
				$ano_f = $data_[2];

				$data_final = $ano_f."-".$mes_f."-".$dia_f." 23:59:59";

				$AND .= " AND a.data_chegada <= '{$data_final}'";
			}

//			if ($data_inicial <= $data_final)
//			{
				$sql = "SELECT a.ref_funcionario, b.nome, a.data_partida, a.data_chegada, sum( COALESCE(vl100,0) + COALESCE(vl75,0) + COALESCE(vl50,0) + COALESCE(vl25,0) ) as valor, a.objetivo, a.destino FROM pmidrh.diaria a, cadastro.pessoa b WHERE a.ref_funcionario = b.idpes {$AND} AND ativo = 't' GROUP BY a.ref_funcionario, b.nome, a.data_partida, a.data_chegada, a.objetivo, a.destino ORDER BY b.nome";

				$relatorio = new relatorios("Relat�rio de Di�rias", 200, false, "SEGPOG - Departamento de Log�stica", "A4", "Prefeitura de Itaja�\nSEGPOG - Departamento de Log�stica\nRua Alberto Werner, 100 - Vila Oper�ria\nCEP. 88304-053 - Itaja� - SC");

				//tamanho do retangulo, tamanho das linhas.
				$relatorio->novaPagina();

				$db = new clsBanco();
				$db->Consulta( $sql );
				if( $db->Num_Linhas() )
				{
					$old_funcionario = 0;
					$soma_valores = 0;
					while ( $db->ProximoRegistro() )
					{
						list( $cod_funcionario, $nome_funcionario, $data_partida, $data_chegada, $valor_total, $objetivo, $destino ) = $db->Tupla();

						if ($old_funcionario != $cod_funcionario )
						{
							$relatorio->novalinha( array( "Funcion�rio: {$nome_funcionario}"), 0, 13, true);
							$old_funcionario = $cod_funcionario;

							$relatorio->novalinha( array( "Data Partida", "Data Chegada", "Valor Total" ) );
						}

						$data_partida = date( "d/m/Y H:i", strtotime( substr($data_partida,0,19) ) );
						$data_chegada = date( "d/m/Y H:i", strtotime( substr($data_chegada,0,19) ) );

						$relatorio->novalinha( array( $data_partida, $data_chegada, number_format($valor_total, 2, ',', '.') ),1,13);
						$relatorio->novalinha( array( "Destino", $destino ) );
						$relatorio->novalinha( array( "Objetivo", $objetivo ) );
						$relatorio->novalinha( array( "" ) );

						$soma_valores += $valor_total;
					}

					$relatorio->novalinha( array( "" ) );
					$relatorio->novalinha( array( "Valor total do periodo:", number_format( $soma_valores, 2, ',', '.' ) ) );

					// pega o link e exibe ele ao usuario
					$link = $relatorio->fechaPdf();
					$this->campoRotulo("arquivo","Arquivo", "<a href='" . $link . "'>Visualizar Relat�rio</a>");
				}
				else
				{
					$this->campoRotulo("aviso", "Aviso", "Nenhum Funcion�rio neste relat�rio.");
				}
			//}
			//else
			//{
			//	$this->campoRotulo("aviso", "Aviso", "Data //Chegada maior que a Data Partida.");
			//}
		}
		else
		{
			$this->campoRotulo("aviso","Aviso", "Preencha os campos.");
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