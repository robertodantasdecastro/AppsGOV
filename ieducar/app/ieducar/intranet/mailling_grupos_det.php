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
require_once ("include/clsDetalhe.inc.php");
require_once ("include/clsBanco.inc.php");

class clsIndex extends clsBase
{
	function Formular()
	{
		$this->SetTitulo( "{$this->_instituicao} Grupos de Email!" );
		$this->processoAp = "85";
	}
}

class indice extends clsDetalhe
{
	function Gerar()
	{
		$this->titulo = "Detalhe do Grupo";
		$this->addBanner( "imagens/nvp_top_intranet.jpg", "imagens/nvp_vert_intranet.jpg", "Intranet" );
		$id_grupo = @$_GET['id_grupo'];
		$db = new clsBanco();
		$db->Consulta( "SELECT cod_mailling_grupo, nm_grupo FROM mailling_grupo WHERE cod_mailling_grupo={$id_grupo}" );
		if ($db->ProximoRegistro())
		{
			list ($cod_grupo, $nome) = $db->Tupla();
			$this->addDetalhe( array("Nome", $nome) );
		}
		$db->Consulta("SELECT nm_pessoa, email FROM mailling_grupo_email mge, mailling_email me WHERE ref_cod_mailling_grupo={$id_grupo} AND cod_mailling_email=ref_cod_mailling_email");
		while ($db->ProximoRegistro()) {
			list($nome, $email) = $db->Tupla();
			$this->addDetalhe(array("Emails Vinculados", "{$nome} - {$email}"));
		}
		
		$this->url_novo = "mailling_grupos_cad.php";
		$this->url_editar = "mailling_grupos_cad.php?id_grupo={$id_grupo}";
		$this->url_cancelar = "mailling_grupos_lst.php";
		$this->largura = "100%";
	}
}
$pagina = new clsIndex();
$miolo = new indice();
$pagina->addForm( $miolo );
$pagina->MakeAll();
?>