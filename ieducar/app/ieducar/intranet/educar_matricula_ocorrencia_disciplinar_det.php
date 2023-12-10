<?php
/**
 *
 * @version SVN: $Id$
 * @author  Prefeitura Municipal de Itaja�
 * @updated 29/03/2007
 * Pacote: i-PLB Software P�blico Livre e Brasileiro
 *
 * Copyright (C) 2006	PMI - Prefeitura Municipal de Itaja�
 *					ctima@itajai.sc.gov.br
 *
 * Este  programa  �  software livre, voc� pode redistribu�-lo e/ou
 * modific�-lo sob os termos da Licen�a P�blica Geral GNU, conforme
 * publicada pela Free  Software  Foundation,  tanto  a vers�o 2 da
 * Licen�a   como  (a  seu  crit�rio)  qualquer  vers�o  mais  nova.
 *
 * Este programa  � distribu�do na expectativa de ser �til, mas SEM
 * QUALQUER GARANTIA. Sem mesmo a garantia impl�cita de COMERCIALI-
 * ZA��O  ou  de ADEQUA��O A QUALQUER PROP�SITO EM PARTICULAR. Con-
 * sulte  a  Licen�a  P�blica  Geral  GNU para obter mais detalhes.
 *
 * Voc�  deve  ter  recebido uma c�pia da Licen�a P�blica Geral GNU
 * junto  com  este  programa. Se n�o, escreva para a Free Software
 * Foundation,  Inc.,  59  Temple  Place,  Suite  330,  Boston,  MA
 * 02111-1307, USA.
 *
 */
 
require_once('include/clsBase.inc.php');
require_once('include/clsDetalhe.inc.php');
require_once('include/clsBanco.inc.php');
require_once('include/pmieducar/geral.inc.php');

class clsIndexBase extends clsBase
{
	function Formular()
	{
		$this->SetTitulo( "{$this->_instituicao} i-Educar - Ocorr&ecirc;ncia Disciplinar" );
		$this->processoAp = "578";
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

	var $ref_cod_matricula;
	var $ref_cod_tipo_ocorrencia_disciplinar;
	var $sequencial;
	var $ref_usuario_exc;
	var $ref_usuario_cad;
	var $observacao;
	var $data_cadastro;
	var $data_exclusao;
	var $ativo;

	function Gerar()
	{
		@session_start();
		$this->pessoa_logada = $_SESSION['id_pessoa'];
		session_write_close();

		$this->titulo = "Matricula Ocorrencia Disciplinar - Detalhe";
		$this->addBanner( "imagens/nvp_top_intranet.jpg", "imagens/nvp_vert_intranet.jpg", "Intranet" );

		$this->sequencial=$_GET["sequencial"];
		$this->ref_cod_matricula=$_GET["ref_cod_matricula"];
		$this->ref_cod_tipo_ocorrencia_disciplinar=$_GET["ref_cod_tipo_ocorrencia_disciplinar"];

		$tmp_obj = new clsPmieducarMatriculaOcorrenciaDisciplinar( $this->ref_cod_matricula, $this->ref_cod_tipo_ocorrencia_disciplinar, $this->sequencial,null,null,null,null,null,1 );
		$registro = $tmp_obj->detalhe();
		if( ! $registro )
		{
			header( "location: educar_matricula_ocorrencia_disciplinar_lst.php?ref_cod_matricula={$this->ref_cod_matricula}" );
			die();
		}


		if( class_exists( "clsPmieducarMatricula" ) )
			{
				$obj_ref_cod_matricula = new clsPmieducarMatricula( $registro["ref_cod_matricula"] );
				$det_ref_cod_matricula = $obj_ref_cod_matricula->detalhe();
				//$registro["ref_cod_matricula"] = $det_ref_cod_matricula["ref_cod_matricula"];
			}
			else
			{
				$registro["ref_cod_matricula"] = "Erro na geracao";
				echo "<!--\nErro\nClasse nao existente: clsPmieducarMatricula\n-->";
			}

			if( class_exists( "clsPmieducarSerie" ) )
			{
				$obj_serie = new clsPmieducarSerie( $det_ref_cod_matricula["ref_ref_cod_serie"] );
				$det_serie = $obj_serie->detalhe();
				$registro["ref_ref_cod_serie"] = $det_serie["nm_serie"];
			}
			else
			{
				$registro["ref_ref_cod_serie"] = "Erro na geracao";
				echo "<!--\nErro\nClasse nao existente: clsPmieducarSerie\n-->";
			}
			if( class_exists( "clsPmieducarTipoOcorrenciaDisciplinar" ) )
			{
				$obj_ref_cod_tipo_ocorrencia_disciplinar = new clsPmieducarTipoOcorrenciaDisciplinar( $registro["ref_cod_tipo_ocorrencia_disciplinar"] );
				$det_ref_cod_tipo_ocorrencia_disciplinar = $obj_ref_cod_tipo_ocorrencia_disciplinar->detalhe();
				$registro["nm_tipo"] = $det_ref_cod_tipo_ocorrencia_disciplinar["nm_tipo"];
			}
			else
			{
				$registro["ref_cod_tipo_ocorrencia_disciplinar"] = "Erro na geracao";
				echo "<!--\nErro\nClasse nao existente: clsPmieducarTipoOcorrenciaDisciplinar\n-->";
			}

			$obj_mat_turma = new clsPmieducarMatriculaTurma();

			$det_mat_turma = $obj_mat_turma->lista($registro["ref_cod_matricula"],null,null,null,null,null,null,null,1);

			if($det_mat_turma)
				$det_mat_turma = array_shift($det_mat_turma);

		if( class_exists( "clsPmieducarTipoOcorrenciaDisciplinar" ) )
		{
			$obj_ref_cod_tipo_ocorrencia_disciplinar = new clsPmieducarTipoOcorrenciaDisciplinar( $registro["ref_cod_tipo_ocorrencia_disciplinar"] );
			$det_ref_cod_tipo_ocorrencia_disciplinar = $obj_ref_cod_tipo_ocorrencia_disciplinar->detalhe();
			$registro["nm_tipo"] = $det_ref_cod_tipo_ocorrencia_disciplinar["nm_tipo"];
		}
		else
		{
			$registro["ref_cod_tipo_ocorrencia_disciplinar"] = "Erro na geracao";
			echo "<!--\nErro\nClasse nao existente: clsPmieducarTipoOcorrenciaDisciplinar\n-->";
		}


		if( $registro["ref_cod_matricula"] )
		{
			$this->addDetalhe( array( "Matr&iacute;cula", "{$registro["ref_cod_matricula"]}") );
		}
		/**
		 * Busca nome do aluno
		 */
			if( class_exists( "clsPmieducarMatricula" ) )
			{
				$obj_ref_cod_matricula = new clsPmieducarMatricula();
				$detalhe_aluno = array_shift($obj_ref_cod_matricula->lista($this->ref_cod_matricula));
			}
			else
			{
				$registro["ref_cod_matricula"] = "Erro na geracao";
				echo "<!--\nErro\nClasse nao existente: clsPmieducarMatricula\n-->";
			}

			$obj_aluno = new clsPmieducarAluno();
			$det_aluno = array_shift($det_aluno = $obj_aluno->lista($detalhe_aluno['ref_cod_aluno'],null,null,null,null,null,null,null,null,null,1));

			$this->addDetalhe(array("Nome do Aluno",$det_aluno['nome_aluno']));
		/**
		 *
		 */

		if( $registro["ref_ref_cod_serie"] )
		{
			$this->addDetalhe( array( "S&eacute;rie", "{$registro["ref_ref_cod_serie"]}") );
		}

		if( $det_mat_turma["det_turma"] )
		{
			$this->addDetalhe( array( "Turma", "{$det_mat_turma["det_turma"]}") );
		}

		if( $registro["sequencial"] )
		{
			$this->addDetalhe( array( "N&uacute;mero da Ocorr&ecirc;ncia", "{$registro["sequencial"]}") );
		}
		if( $registro["data_cadastro"] )
		{
			if($hora = dataFromPgToBr("{$registro["data_cadastro"]}",'H:i') )
				$this->addDetalhe( array( "Hora Ocorr&ecirc;ncia", $hora ));
			$this->addDetalhe( array( "Data Ocorr&ecirc;ncia", dataFromPgToBr("{$registro["data_cadastro"]}",'d/m/Y') ));
		}
		if( $registro["ref_cod_tipo_ocorrencia_disciplinar"] )
		{
			$this->addDetalhe( array( "Tipo Ocorr&ecirc;ncia", "{$registro["nm_tipo"]}") );
		}
		if( $registro["observacao"] )
		{
			$this->addDetalhe( array( "Observa&ccedil;&atilde;o", nl2br("{$registro["observacao"]}")) );
		}

		$obj_permissoes = new clsPermissoes();
		if( $obj_permissoes->permissao_cadastra( 578, $this->pessoa_logada, 7 ) )
		{
		$this->url_novo = "educar_matricula_ocorrencia_disciplinar_cad.php?ref_cod_matricula={$registro["ref_cod_matricula"]}";
		$this->url_editar = "educar_matricula_ocorrencia_disciplinar_cad.php?ref_cod_matricula={$registro["ref_cod_matricula"]}&ref_cod_tipo_ocorrencia_disciplinar={$registro["ref_cod_tipo_ocorrencia_disciplinar"]}&sequencial={$registro["sequencial"]}";
		}

		$this->url_cancelar = "educar_matricula_ocorrencia_disciplinar_lst.php?ref_cod_matricula={$registro["ref_cod_matricula"]}";
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
