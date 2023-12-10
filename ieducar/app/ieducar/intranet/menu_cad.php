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
		$this->SetTitulo( "{$this->_instituicao} Menu!" );
		$this->processoAp = "35";
	}
}

class indice extends clsCadastro
{
	var $id_item,
		$id_menupai, 
		$id_sistema,
		$nome_,
		$arquivo,
		$alt,
		$permissao;

	function Inicializar()
	{
		$retorno = "Novo";
		
		$this->id_sistema = 2;
		
		if (@$_GET['id_item'])
		{
			$this->id_item = @$_GET['id_item'];
			$db = new clsBanco();
			$db->Consulta( "SELECT ref_cod_menu_menu, cod_sistema, nm_submenu, arquivo, title, nivel FROM menu_submenu WHERE cod_menu_submenu={$this->id_item}" );
			if ($db->ProximoRegistro())
			{
				list( $this->id_menupai, $this->id_sistema, $this->nome_, $this->arquivo, $this->alt, $this->permissao) = $db->Tupla();
				$this->fexcluir = true;
				$retorno = "Editar";
			}
		}

		$this->url_cancelar = ($retorno == "Editar") ? "menu_det.php?id_item=$this->id_item" : "menu_lst.php";
		$this->nome_url_cancelar = "Cancelar";

		return $retorno;
	}

	function Gerar()
	{
		$this->campoOculto( "id_item", $this->id_item );
		
		$lista = array();
		$lista[2]="Intranet";
		
		$categoria = array();
		$db = new clsBanco();
		$db->Consulta( "SELECT cod_menu_menu, nm_menu FROM menu_menu ORDER BY nm_menu ASC" );
		while ($db->ProximoRegistro())
		{
			$categoria[$db->Campo("cod_menu_menu")] = $db->Campo("nm_menu");
		}
		$this->campoLista( "id_menupai", "Categoria",  $categoria, $this->id_menupai);

		$this->campoLista( "id_sistema", "Sistema",  $lista, $this->id_sistema);
		$this->campoTexto( "nome_", "Nome",  $this->nome_, "50", "100", true );
		
		$this->campoTexto( "arquivo", "Arquivo",  $this->arquivo, "50", "100", true );
		$opcoes = array( 1=>"P�blico", 2=>"Registrados", 3=>"Particular" );
		$this->permissao = ($this->permissao) ? $this->permissao : '3';
		$this->campoLista( "permissao", "Permiss�o", $opcoes, $this->permissao);
		
		$this->campoMemo( "alt", "Descri&ccedil;&atilde;o",  $this->alt, "47", "2", false );
		
	}

	function Novo() 
	{
		$db = new clsBanco();

		$db->Consulta( "INSERT INTO menu_submenu ( ref_cod_menu_menu, cod_sistema, nm_submenu, arquivo, title, nivel) VALUES ({$this->id_menupai}, {$this->id_sistema}, '{$this->nome_}', '{$this->arquivo}', '{$this->alt}', '{$this->permissao}')" );

		echo "<script>document.location='menu_lst.php';</script>";

		return true;
	}

	function Editar() 
	{
		$db = new clsBanco();
		$db->Consulta( "UPDATE menu_submenu SET ref_cod_menu_menu={$this->id_menupai}, cod_sistema={$this->id_sistema}, nm_submenu='{$this->nome_}', arquivo='{$this->arquivo}', title='{$this->alt}', nivel='{$this->permissao}' WHERE cod_menu_submenu = {$this->id_item}" );

		echo "<script>document.location='menu_lst.php';</script>";

		return true;
	}

	function Excluir()
	{
		$db = new clsBanco();
		
		$db->Consulta( "DELETE FROM menu_funcionario WHERE ref_cod_menu_submenu={$this->id_item}" );
		$db->Consulta( "DELETE FROM menu_submenu WHERE cod_menu_submenu={$this->id_item}" );

		echo "<script>document.location='menu_lst.php';</script>";

		return true;
	}

}


$pagina = new clsIndex();

$miolo = new indice();
$pagina->addForm( $miolo );

$pagina->MakeAll();

?>
