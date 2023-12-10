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
		$this->SetTitulo( "{$this->_instituicao} Sistema de Cadastro de A��es do Governo - Cadastro de setores" );
		$this->processoAp = "553";
	}
}

class indice extends clsCadastro
{
	var $setor;
	var $cod_setor_old;
	var $pessoa_logada;
		
	function Inicializar()
	{
		$retorno = "Novo";
		@session_start();
		 $this->pessoa_logada = $_SESSION['id_pessoa'];
		@session_write_close();
		
		$this->setor = $_GET['cod_setor'];
		
		$obj = new clsPmiacoesSecretariaResponsavel($this->setor);
		$detalhe = $obj->detalhe();
		if($detalhe)
		{
			$this->pessoa_logada = $detalhe['ref_cod_funcionario_cad'];
		}		
		if($detalhe)
		{
			/*
			$obj = new clsSetor();
			$niveis = $obj->getNiveis($this->cod_setor);
			sort($niveis);
			if($niveis)			
			{
				foreach ($niveis as $id => $nivel) 
				{
					$objSetor = new clsSetor($nivel);
					$detalhe = $objSetor->detalhe();
					if($id == 0 )
					{
						$this->setor_0 = $detalhe['cod_setor'];	
						$this->cod_setor_old = $detalhe['cod_setor'];	
					}					
					if($id == 1 )
					{
						$this->setor_1 = $detalhe['cod_setor'];
						$this->cod_setor_old = $detalhe['cod_setor'];	
					}
					if($id == 2 )
					{
						$this->setor_2 = $detalhe['cod_setor'];
						$this->cod_setor_old = $detalhe['cod_setor'];	
					}
					if($id == 3 )
					{
						$this->setor_3 = $detalhe['cod_setor'];
						$this->cod_setor_old = $detalhe['cod_setor'];	
					}
					if($id == 4 )
					{
						$this->setor_4 = $detalhe['cod_setor'];
						$this->cod_setor_old = $detalhe['cod_setor'];	
					}			
					
				}
			}*/
		
			
			$this->fexcluir = true;	
			
			$retorno = "Editar";
		}		
		
		$this->url_cancelar = ($retorno == "Editar") ? "acoes_setor_det.php?cod_setor={$this->cod_setor}" : "acoes_setor_lst.php";
		$this->nome_url_cancelar = "Cancelar";
		return $retorno;
	}

	function Gerar()
	{			
	//	include( "include/form_setor.inc.php" );

		$obj_setores = new clsPmiacoesSecretariaResponsavel();
		$obj_setores->_campos_lista = "ref_cod_setor";
		$obj_lista = $obj_setores->lista();
		if($obj_lista){
			unset($obj_lista[array_search($this->setor,$obj_lista)]);
			$not_in = implode(",",$obj_lista);
		}
		
		$obj_setor = new clsSetor();

		$obj_setor_lista = $obj_setor->lista(null,null,null,null,null,null,null,null,null,1,0,null,null,"nm_setor",null,null,null,null,null,$not_in,$cod_setor);
		$setores = array('' => 'Selecione um setor');
		if($obj_setor_lista)
		{
			foreach ($obj_setor_lista as $secretaria)
			{
				$setores[$secretaria["cod_setor"]] = $secretaria["sgl_setor"];
				
			}
		}
		$this->campoLista("setor","Setor",$setores,$this->setor,'',false,'','','',true);
		$this->campoOculto("pessoa_logada", $this->pessoa_logada);
		$this->campoOculto("cod_setor_old", $this->setor);
	}
 
	function Novo() 
	{
		@session_start();
		 $this->pessoa_logada = $_SESSION['id_pessoa'];
		@session_write_close();
		@session_start();
		 $this->pessoa_logada = $_SESSION['id_pessoa'];
		@session_write_close();
		$obj = new clsPmiacoesSecretariaResponsavel($this->setor, $this->pessoa_logada);
		if($obj->cadastra())
		{
			header("Location: acoes_setor_lst.php");
		}
		return false;
	}

	function Editar() 
	{		
		/*if($this->setor_0)
			$this->ref_sec = $this->setor_0;
		if($this->setor_1)
			$this->ref_sec = $this->setor_1; 
		if($this->setor_2)
			$this->ref_sec = $this->setor_2;
		if($this->setor_3)
			$this->ref_sec = $this->setor_3;
		if($this->setor_4)
			$this->ref_sec = $this->setor_4;
		*/
		$obj = new clsPmiacoesSecretariaResponsavel($this->cod_setor_old);
		$obj->excluir();
		
		@session_start();
		 $this->pessoa_logada = $_SESSION['id_pessoa'];
		@session_write_close();
		$obj = new clsPmiacoesSecretariaResponsavel($this->setor, $this->pessoa_logada);
		if($obj->cadastra())
		{
			header("Location: acoes_setor_lst.php");
		}
		return false;
	}

	function Excluir()
	{
		$obj = new clsPmiacoesSecretariaResponsavel($this->cod_setor_old);
		$obj->excluir();
		header("Location: acoes_setor_lst.php");
		return true;
	}
}

$pagina = new clsIndex();
$miolo = new indice();
$pagina->addForm( $miolo );
$pagina->MakeAll();
?>
