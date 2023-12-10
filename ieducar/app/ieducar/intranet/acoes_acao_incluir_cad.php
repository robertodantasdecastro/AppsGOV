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
$desvio_diretorio = "";
require_once ("include/clsBase.inc.php");
require_once ("include/clsCadastro.inc.php");
require_once("include/pmiacoes/geral.inc.php");
require_once( "include/Geral.inc.php" );

class clsIndex extends clsBase
{
	function Formular()
	{
		$this->renderMenu = false;
		$this->renderMenuSuspenso = false;
		$this->SetTitulo( "{$this->_instituicao} Sistema de Cadastro de A&ccedil;&oatilde;es do Governo - Inclus�o de a��o!" );
		$this->processoAp = "551";
	}
}

class indice extends clsCadastro
{
	var $pessoa_logada;
	var $cod_acao_governo;
	var $setor;
	var $status = 1;

	function Inicializar()
	{

		$cod_acao_governo = @$_GET['cod_acao_governo'];
		$this->status = @$_GET['status'];
		@session_start();
		 $this->pessoa_logada = $_SESSION['id_pessoa'];
		@session_write_close();

		if(!(int)$cod_acao_governo)
			header("Location: acoes_acao_lst.php");

		//Objeto Perturba��o
		$obj_acao_governo = new clsPmiacoesAcaoGoverno($cod_acao_governo);
		$det_acao_governo = $obj_acao_governo->detalhe();

		if(!$det_acao_governo = $obj_acao_governo->detalhe() )
			header("Location: acoes_acao_lst.php");


		$obj_funcionario = new clsFuncionario($this->pessoa_logada);
		$detalhe_func = $obj_funcionario->detalhe();
		$setor_funcionario = $detalhe_func["ref_cod_setor_new"];

		//*
		$obj = new clsSetor();
		$setor_pai = array_shift(array_reverse($obj->getNiveis($setor_funcionario)));
		//*
		$obj_secretaria_responsavel = new clsPmiacoesSecretariaResponsavel($setor_pai);
		$obj_secretaria_responsavel_det = $obj_secretaria_responsavel->detalhe();
		$obj_acao = new clsPmiacoesAcaoGoverno($cod_acao_governo);
		$obj_acao_det = $obj_acao->detalhe();
		$status = $obj_acao_det["status_acao"];
		$isSecom = $setor_pai == 4327 ? true : false;

		if(($obj_secretaria_responsavel_det != false && $status == 0) || $status == 1 || $isSecom)
		{
				$ac =$this->status ? "inclu�da" : "removida";
				$obj_acao = new clsPmiacoesAcaoGoverno($cod_acao_governo,null,null,null,null,null,null,null,$this->status);
				if($obj_acao->edita())
					echo "<script>alert('A��o $ac com sucesso');window.location=\"acoes_acao_det.php?cod_acao_governo={$cod_acao_governo}\";</script>";

		}

		header("Location: acoes_acao_lst.php");
		die;
	}

	function Gerar()
	{


	}

	function Novo()
	{

		return true;
	}

	function Editar()
	{

		return false;
	}

	function Excluir()
	{

		return false;
	}

}

$pagina = new clsIndex();
$miolo = new indice();
$pagina->addForm( $miolo );
$pagina->MakeAll();
?>
