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
		$this->SetTitulo( "{$this->_instituicao} Tipos de Not�cias!" );
		$this->processoAp = "104";
	}
}

class indice extends clsListagem
{
	function Gerar()
	{
		$this->titulo = "Tipos ";
		$this->addBanner( "imagens/nvp_top_intranet.jpg", "imagens/nvp_vert_intranet.jpg", "Intranet");

		$this->CampoTexto("nome_","Nome",$_GET['nome_'],30,250);


		$this->addCabecalhos( array( "Nome"));
		$db = new clsBanco();

		$where = "";
		if($_GET['nome_'])
		{
			$where = "WHERE nm_tipo like '%{$_GET['nome_']}%' ";
		}

		$total = $db->UnicoCampo(" SELECT count(*) FROM not_tipo $where");
		$limite = 10;
		$iniciolimit = ( $_GET["pagina_{$this->nome}"] ) ? $_GET["pagina_{$this->nome}"] * $limite-$limite: 0;
		$db->Consulta( "SELECT cod_not_tipo,nm_tipo FROM not_tipo $where   ORDER BY nm_tipo ASC  LIMIT $iniciolimit,$limite" );
		while ($db->ProximoRegistro())
		{
			list ($cod_tipo, $nome) = $db->Tupla();
			$this->addLinhas( array("<img src='imagens/noticia.jpg' border=0>&nbsp;&nbsp;<a href='not_tipos_det.php?id_tipo=$cod_tipo'>$nome</a>") );
		}
		$this->acao = "go(\"not_tipos_cad.php\")";
		$this->nome_acao = "Novo";
		$this->addPaginador2( "not_tipos_lst.php", $total, $_GET, $this->nome, $limite );
		$this->largura = "100%";
	}
}
$pagina = new clsIndex();
$miolo = new indice();
$pagina->addForm( $miolo );
$pagina->MakeAll();

?>