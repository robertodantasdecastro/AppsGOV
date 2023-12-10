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
require_once ("include/pmiacoes/geral.inc.php");
require_once( "include/Geral.inc.php" );

class clsIndex extends clsBase
{
	function Formular()
	{
		$this->SetTitulo( "{$this->_instituicao} Sistema de Cadastro de A��es do Governo - Cadastro de Categorias" );
		$this->processoAp = "552";
	}
}

class indice extends clsCadastro
{
	var $pessoa_logada;
	
	var $cod_categoria,
		$nm_categoria;
		
	function Inicializar()
	{
		$retorno = "Novo";
		@session_start();
		 $this->pessoa_logada = $_SESSION['id_pessoa'];
		@session_write_close();
		
		$this->cod_categoria = $_GET['cod_categoria'];
		
		if($this->cod_categoria)
		{
			$obj = new clsPmiacoesCategoria($this->cod_categoria);
			$detalhe  = $obj->detalhe();
			$this->nm_categoria = $detalhe['nm_categoria'];
			
			
			$obj_acao = new clsPmiacoesAcaoGovernoCategoria();
			$lista = $obj_acao->lista($this->cod_categoria);
			if(!$lista)
				$this->fexcluir = true;		
			
			
			$retorno = "Editar";
		}
		$this->url_cancelar = ($retorno == "Editar") ? "acoes_categoria_det.php?cod_categoria={$this->cod_categoria}" : "acoes_categoria_lst.php";
		$this->nome_url_cancelar = "Cancelar";
		return $retorno;
	}

	function Gerar()
	{
		$this->campoOculto("cod_categoria", $this->cod_categoria);
		$this->campoOculto("pessoa_logada", $this->pessoa_logada);
		$this->campoTexto("nm_categoria", "Nome", $this->nm_categoria,30,255,true);
	}
 
	function Novo() 
	{
		@session_start();
		 $this->pessoa_logada = $_SESSION['id_pessoa'];
		@session_write_close();
		$obj = new clsPmiacoesCategoria(null, null, $this->pessoa_logada, $this->nm_categoria, null, null, 1);
		if($obj->cadastra())
		{
			header("Location: acoes_categoria_lst.php");
		}
		return false;
	}

	function Editar() 
	{
		@session_start();
		 $this->pessoa_logada = $_SESSION['id_pessoa'];
		@session_write_close();
		$obj = new clsPmiacoesCategoria($this->cod_categoria, $this->pessoa_logada, null, $this->nm_categoria, null, null, 1);
		if($obj->edita())
		{
			header("Location: acoes_categoria_lst.php");
		}
		return false;
	}

	function Excluir()
	{
		@session_start();
		 $this->pessoa_logada = $_SESSION['id_pessoa'];
		@session_write_close();
		
		$obj_acao = new clsPmiacoesAcaoGovernoCategoria();
		$lista = $obj_acao->lista($this->cod_categoria);
		if($lista)	 
			echo "<script>alert('N�o � poss�vel excluir o registro! \n Existe a��o utilizando esta categoria');window.location = \"acoes_categoria_lst.php\";</script>";
		
			$obj = new clsPmiacoesCategoria($this->cod_categoria, $this->pessoa_logada, null, $this->nm_categoria, null, null, 0);	
		$obj->excluir();
		header("Location: acoes_categoria_lst.php");
		return true;
	}
}

$pagina = new clsIndex();
$miolo = new indice();
$pagina->addForm( $miolo );
$pagina->MakeAll();
?>
