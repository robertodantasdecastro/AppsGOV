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
require_once ("include/pmiacoes/geral.inc.php");
class clsIndex extends clsBase
{
	function Formular()
	{
		$this->SetTitulo( "{$this->_instituicao} Sistema de Cadastro de A��es do Governo - Listagem de Categorias" );
		$this->processoAp = "552";
	}
}

class indice extends clsListagem
{
	function Gerar()
	{
		@session_start();
			$id_pessoa = $_SESSION['id_pessoa'];
		@session_write_close();
		
		$this->addBanner( "imagens/nvp_top_intranet.jpg", "imagens/nvp_vert_intranet.jpg", "Intranet",false );
		
		$this->addCabecalhos( array( "Nome do categoria") );

		// Filtros de Busca
		$this->campoTexto("categoria","Nome da Categoria","",50,255);
		$grupo = null;
		
		if($_GET['categoria'])
		{
			$categoria = @$_GET['categoria'];
		}
		
		//$this->campoTexto("unidade","Unidade","",30,255);		
		// Paginador
		$limite = 10; 
		$iniciolimit = ( $_GET["pagina_{$this->__nome}"] ) ? $_GET["pagina_{$this->__nome}"]*$limite-$limite: 0;		
		$Objcategorias = new clsPmiacoesCategoria();
		$Objcategorias->setLimite($limite, $iniciolimit);
		$Listacategorias = $Objcategorias->lista(null, null, null, 1, $categoria);
		if($Listacategorias)
		{
			foreach ($Listacategorias as $categoria) 
			{
				$this->addLinhas(array("<img src='imagens/noticia.jpg' border=0> <a href='acoes_categoria_det.php?cod_categoria={$categoria['cod_categoria']}'>{$categoria['nm_categoria']}</a>"));
				$total = $categoria['_total'];
			}
		}		
		// Paginador
		$this->addPaginador2( "acoes_categoria_lst.php", $total, $_GET, $this->__nome, $limite );		
		$this->acao = "go(\"acoes_categoria_cad.php\")";
		$this->nome_acao = "Novo";			
		
		// Define Largura da P�gina
		$this->largura = "100%";
	}
}

$pagina = new clsIndex();
$miolo = new indice();
$pagina->addForm( $miolo );
$pagina->MakeAll();

?>