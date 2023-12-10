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
		$this->SetTitulo( "{$this->_instituicao} V�nculo Funcion�rios!" );
		$this->processoAp = "190";
	}
}

class indice extends clsCadastro
{
	var $nm_vinculo;
	var $cod_vinculo;

	function Inicializar()
	{
		$retorno = "Novo";
		if($_GET['cod_funcionario_vinculo'])
		{
			$this->cod_vinculo = $_GET['cod_funcionario_vinculo'];
			$db =new clsBanco();
			$db->Consulta( "SELECT nm_vinculo FROM funcionario_vinculo WHERE cod_funcionario_vinculo = $this->cod_vinculo" );
			if($db->ProximoRegistro() )
			{
				$tupla = $db->Tupla();
				$this->nm_vinculo = $tupla[0];
				$retorno = "Editar";
				$this->fexcluir = true;
			}
		}
		$this->nome_url_cancelar = "Cancelar";
		$this->url_cancelar = "funcionario_vinculo_lst.php";
		return $retorno;
	}

	function Gerar()
	{
		$this->campoOculto("cod_vinculo",$this->cod_vinculo);
		$this->campoTexto("nm_vinculo","Nome",$this->nm_vinculo,30,250,true);
	}

	function Novo() 
	{
		$db = new clsBanco();
		$db->Consulta("INSERT INTO funcionario_vinculo ( nm_vinculo ) VALUES ( '$this->nm_vinculo' )");
		echo "<script>document.location='funcionario_vinculo_lst.php';</script>";
		return true;
	}

	function Editar() 
	{
		$db = new clsBanco();
		$db->Consulta( "UPDATE funcionario_vinculo SET nm_vinculo = '$this->nm_vinculo' WHERE cod_funcionario_vinculo=$this->cod_vinculo" );
		echo "<script>document.location='funcionario_vinculo_lst.php';</script>";
		return true;
	}

	function Excluir()
	{
		$db = new clsBanco();
		$db->Consulta( "DELETE FROM funcionario_vinculo WHERE cod_funcionario_vinculo=$this->cod_vinculo" );
		echo "<script>document.location='funcionario_vinculo_lst.php';</script>";
		return true;
	}


}


$pagina = new clsIndex();

$miolo = new indice();
$pagina->addForm( $miolo );

$pagina->MakeAll();

?>
