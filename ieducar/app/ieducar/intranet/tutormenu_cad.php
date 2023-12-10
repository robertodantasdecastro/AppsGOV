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
require_once ("include/pmibee/beeGeral.inc.php");

class clsIndex extends clsBase
{
	function Formular()
	{
		$this->SetTitulo( "{$this->_instituicao} Tutor Menu" );
		$this->processoAp = "445";
	}
}

class indice extends clsCadastro
{
	var $cod_tutormenu,
		$nm_tutormenu;
		
	function Inicializar()
	{
		$retorno = "Novo";
		@session_start();
		 $this->pessoa_logada = $_SESSION['id_pessoa'];
		@session_write_close();
		
		$this->cod_tutormenu = $_GET['cod_tutormenu'];
		
		if($this->cod_tutormenu)
		{
			$obj = new clsTutormenu($this->cod_tutormenu);
			$detalhe  = $obj->detalhe();
			$this->nm_tutormenu = $detalhe['nm_tutormenu'];
			$this->fexcluir = true;		
			$retorno = "Editar";
		}
		$this->url_cancelar = ($retorno == "Editar") ? "tutormenu_det.php?cod_tutormenu={$this->cod_tutormenu}" : "menu_suspenso_lst.php";
		$this->nome_url_cancelar = "Cancelar";
		return $retorno;
	}

	function Gerar()
	{
		$this->campoOculto( "cod_tutormenu", $this->cod_tutormenu);
		$this->campoTexto("nm_tutormenu", "Nome", $this->nm_tutormenu,30,255,true);
	}

	function Novo() 
	{
		$obj = new clsTutormenu(false, $this->nm_tutormenu);
		if($obj->cadastra())
		{
			header("Location: menu_suspenso_lst.php");
		}
		return false;
	}

	function Editar() 
	{
		$obj = new clsTutormenu($this->cod_tutormenu, $this->nm_tutormenu);

		if($obj->edita())
		{
			header("Location: menu_suspenso_lst.php");
		}
		return false;
	}

	function Excluir()
	{
		$obj = new clsTutormenu($this->cod_tutormenu);
		$obj->exclui();
		header("Location: menu_suspenso_lst.php");
		return true;
	}

}

$pagina = new clsIndex();
$miolo = new indice();
$pagina->addForm( $miolo );
$pagina->MakeAll();
?>
