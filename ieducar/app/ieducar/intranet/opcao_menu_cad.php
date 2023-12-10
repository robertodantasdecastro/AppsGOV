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

class clsIndex extends clsBase
{
	function Formular()
	{
		$this->SetTitulo( "{$this->_instituicao} Op��o Menu" );
		$this->processoAp = "475";
	}
}

class indice extends clsCadastro
{
	var $idpes,
		$tipo_menu;
		
	function Inicializar()
	{
		$retorno = "Editar";

		@session_start();
		 $this->idpes = $_SESSION['id_pessoa'];
		@session_write_close();
		
		if($this->idpes)
		{
			$db = new clsBanco();
			$this->tipo_menu = $db->UnicoCampo("SELECT tipo_menu FROM funcionario WHERE ref_cod_pessoa_fj = '$this->idpes'");
		}
		$this->url_cancelar = "opcao_menu_det.php";
		$this->nome_url_cancelar = "Cancelar";
		return $retorno;
	}

	function Gerar()
	{
		$opcao = array("0"=>"Menu Padr�o","1"=> "Menu Suspenso");
		$this->campoRadio("tipo_menu","Tipo do Menu",$opcao,$this->tipo_menu);
		$this->campoOculto("idpes",$this->idpes);
	}

	function Novo() 
	{
		return false;
	}

	function Editar() 
	{
		$db = new clsBanco();
		$db->Consulta("UPDATE funcionario SET tipo_menu='$this->tipo_menu' WHERE ref_cod_pessoa_fj = '$this->idpes' ");
		
		@session_start();
		$_SESSION['tipo_menu'] = $this->tipo_menu;
		@session_write_close();
		
		header("Location: opcao_menu_det.php");
		return false;
	}

	function Excluir()
	{
		return true;
	}

}

$pagina = new clsIndex();
$miolo = new indice();
$pagina->addForm( $miolo );
$pagina->MakeAll();
?>
