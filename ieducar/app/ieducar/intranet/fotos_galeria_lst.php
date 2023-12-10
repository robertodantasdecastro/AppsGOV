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
		$this->SetTitulo( "{$this->_instituicao} Fotos!" );
		$this->processoAp = "669";
	}
}

class indice extends clsListagem
{
	function Gerar()
	{
		$this->titulo = "Fotos";
		$this->addBanner( "imagens/nvp_top_intranet.jpg", "imagens/nvp_vert_intranet.jpg", "Intranet" );
		
		$this->addCabecalhos( array( "Data", "T�tulo") );
	
		$db = new clsBanco();
		
		$db->Consulta( "SELECT count(*) FROM foto_portal f WHERE ref_cod_foto_secao = '3' OR ref_cod_foto_secao = '2'" );
		$db->ProximoRegistro();
		list ($total) = $db->Tupla();
		$total_tmp = $total;
		$limite = 15;
		$iniciolimit = (@$_GET['iniciolimit']) ? @$_GET['iniciolimit'] : "0";
		if ($total > $limite)
		{
			$iniciolimit_ = $iniciolimit * $limite;
			$limit = " LIMIT {$iniciolimit_}, $limite";
		}
		$db->Consulta( "SELECT cod_foto_portal, ref_cod_foto_secao, f.data_foto, f.titulo, f.descricao FROM foto_portal f WHERE ref_cod_foto_secao = '3' OR ref_cod_foto_secao = '2' ORDER BY f.data_foto DESC {$limit}" );
		while ($db->ProximoRegistro())
		{
			list ($id_foto, $secao, $data, $titulo, $descricao) = $db->Tupla();
			$data = date('d/m/Y', strtotime(substr($data,0,19)));

			$this->addLinhas( array("<img src='imagens/noticia.jpg' border=0>{$data}", "<a href='fotos_galeria_det.php?id_foto=$id_foto'>$titulo </a>"));
		}
		$this->paginador("fotos_galeria_lst.php?",$total_tmp,$limite,@$_GET['pos_atual']);
	
		$this->acao = "go(\"fotos_galeria_cad.php\")";
		$this->nome_acao = "Novo";

		$this->largura = "100%";
	}
}

$pagina = new clsIndex();

$miolo = new indice();
$pagina->addForm( $miolo );

$pagina->MakeAll();

?>