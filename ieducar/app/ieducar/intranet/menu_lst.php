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
require_once ("include/clsListagem.inc.php");
require_once ("include/clsBanco.inc.php");

class clsIndex extends clsBase
{
	
	function Formular()
	{
		$this->SetTitulo( "{$this->_instituicao} Menu!" );
		$this->processoAp = "35";
	}
}

class indice2 extends clsListagem
{
	function Gerar()
	{
		$this->titulo = "Menu da Intranet";
		$this->addBanner( "", "", "", true );
		
		$this->addCabecalhos( array( "Nome", "Arquivo") );
		
		
		$db = new clsBanco();
		$db->Consulta( "SELECT cod_menu_submenu, nm_submenu, arquivo FROM menu_submenu WHERE cod_sistema=2 ORDER BY nm_submenu" );
		while ($db->ProximoRegistro())
		{
			list ($id_item, $nome, $arquivo) = $db->Tupla();

			$this->addLinhas( array("<a href='menu_det.php?id_item=$id_item'><img src='imagens/noticia.jpg' border=0>$nome</a>", $arquivo) );
		}

		$this->acao = "go(\"menu_cad.php\")";
		$this->nome_acao = "Novo";

		$this->largura = "100%";
	}
}


$pagina = new clsIndex();


$miolo2 = new indice2();
$pagina->addForm( $miolo2 );

$pagina->MakeAll();

?>