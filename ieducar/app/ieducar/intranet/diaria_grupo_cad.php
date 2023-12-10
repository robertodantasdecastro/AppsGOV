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
require_once ("include/clsBanco.inc.php");

class clsIndex extends clsBase
{
	
	function Formular()
	{
		$this->SetTitulo( "{$this->_instituicao} Di�ria Grupo" );
		$this->processoAp = "297";
	}
}

class indice extends clsCadastro
{
	var $cod_diaria_grupo, 
		$desc_grupo;

	function Inicializar()
	{
		$retorno = "Novo";
		
		if ( isset( $_GET['cod_diaria_grupo'] ) )
		{
			$this->cod_diaria_grupo = $_GET['cod_diaria_grupo'];
			$db = new clsBanco();
			$db->Consulta( "SELECT desc_grupo FROM pmidrh.diaria_grupo WHERE cod_diaria_grupo={$this->cod_diaria_grupo}" );
			if ($db->ProximoRegistro())
			{
				list( $this->desc_grupo ) = $db->Tupla();
				$this->fexcluir = true;
				$retorno = "Editar";
			}
		}

		$this->url_cancelar = ( $retorno == "Editar" ) ? "diaria_grupo_det.php?cod_diaria_grupo={$this->cod_diaria_grupo}" : "diaria_grupo_lst.php";
		$this->nome_url_cancelar = "Cancelar";

		return $retorno;
	}

	function Gerar()
	{
		@session_start();
		$this->pessoaFj = $_SESSION['id_pessoa'];
		session_write_close();
		
		$this->campoOculto( "pessoaFj", $this->pessoaFj );
		$this->campoOculto( "cod_diaria_grupo", $this->cod_diaria_grupo );
		
		// Campo listaPesq de Funcionario
		
		$this->campoTexto( "desc_grupo", "Grupo", $this->desc_grupo, 70, 255, false, false, true );

	}

	function Novo() 
	{
		@session_start();
		$this->ref_funcionario_cadastro = $_SESSION['id_pessoa'];
		session_write_close();
		
		$campos = "";
		$values = "";
		$db = new clsBanco();

			if( $this->desc_grupo )
			{
				$campos .= "desc_grupo";
				$values .= "'{$this->desc_grupo}'";
			
			$db->Consulta( "INSERT INTO pmidrh.diaria_grupo( $campos) VALUES( $values)" );

			header( "location: diaria_grupo_lst.php" );
			die();
			return true;

			}
			else 
			{
				$this->mensagem = "Preencha corretamente o campo Grupo";
			}
		
		return false;
	}

	function Editar() 
	{
		@session_start();
		$this->ref_funcionario_cadastro = $_SESSION['id_pessoa'];
		session_write_close();
		
		$set = "";
		$db = new clsBanco();
		
		if( is_numeric( $this->cod_diaria_grupo ) )
		{
					
			if( $this->desc_grupo )
			{
				$campos .= " desc_grupo = '{$this->desc_grupo}'";

					
			$db->Consulta( "UPDATE pmidrh.diaria_grupo SET $campos WHERE cod_diaria_grupo = '{$this->cod_diaria_grupo}'" );
			header( "location: diaria_grupo_lst.php" );
			die();
			return true;
		}
		else 
		{
			$this->mensagem = "Preencha corretamente o campo Grupo de Diaria";
		}
	}
	else 
	{
		$this->mensagem = "Logue-se novamente pra realizar esta operacao";
	}
	return true;
}

	function Excluir()
	{
		if( is_numeric( $this->cod_diaria_grupo ) )
		{
			$db = new clsBanco();
			$ref_cod_diaria = $db->CampoUnico( "SELECT ref_cod_diaria_grupo FROM pmidrh.diaria WHERE ref_cod_diaria_grupo = {$this->cod_diaria_grupo}" );

			$ref_cod_diaria_valores = $db->CampoUnico( "SELECT ref_cod_diaria_grupo FROM pmidrh.diaria_valores WHERE ref_cod_diaria_grupo = {$this->cod_diaria_grupo}" );

			if (!isset($ref_cod_diaria) )
			{
			if (!isset($ref_cod_diaria_valores))
			{
				$db->Consulta( "DELETE FROM pmidrh.diaria_grupo WHERE cod_diaria_grupo = {$this->cod_diaria_grupo}" );
				header( "location: diaria_grupo_lst.php" );
				die();
				return true;
			}
			else
			{
			$this->mensagem = "C�digo da di�ria est� sendo utilizado na tabela diaria_valores.";
			}
			}
			else
			$this->mensagem = "C�digo da di�ria est� sendo utilizado na tabela diaria.";
		}
		return false;
	}
}


$pagina = new clsIndex();

$miolo = new indice();
$pagina->addForm( $miolo );

$pagina->MakeAll();
?>