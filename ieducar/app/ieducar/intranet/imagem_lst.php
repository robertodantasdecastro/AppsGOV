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
require_once ("include/imagem/clsPortalImagemTipo.inc.php");
require_once ("include/imagem/clsPortalImagem.inc.php");
class clsIndex extends clsBase
{
	function Formular()
	{
		$this->SetTitulo( "{$this->_instituicao} Banco de Imagens" );
		$this->processoAp = "473";
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
		$this->addCabecalhos( array( "Nome da Imagem","Imagem", "Tipo") );

		// Filtros de Busca
		$this->campoTexto("imagem","Nome Imagem ","",50,255);
		//$this->campoTexto("unidade","Unidade","",30,255);		
		// Paginador
		  
		$limite = 20;
		$iniciolimit = ( $_GET["pagina_{$this->nome}"] ) ? $_GET["pagina_{$this->nome}"]*$limite-$limite: 0;		
		$obj_menu = new clsPortalImagem();
		$obj_menu->setLimite($limite, $iniciolimit );
		$obj_menu->setOrderby("cod_imagem");
		$lista_menu = $obj_menu->lista(false,false,false,false, false, false, false, $_GET['imagem']);
		if($lista_menu)
		{
			foreach ($lista_menu as $menu) 
			{
				$obj_tipo = new clsPortalImagemTipo();
				$lista_tipo = $obj_tipo->lista($menu['ref_cod_imagem_tipo'] );				
				if($lista_tipo)
				{
					foreach ($lista_tipo as $tipo) 
					{		
						$menu['nm_imagem'] = ($menu['nm_imagem'] == "") ? "S/N":$menu['nm_imagem'] ;
						$this->addLinhas(array("<a href='imagem_det.php?cod_imagem={$menu['cod_imagem']}'  width=16 height=16><img src='imagens/noticia.jpg' border=0> {$menu['nm_imagem']}</a>","<img src='imagens/banco_imagens/{$menu['caminho']}' alt='{$menu['nm_imagem']}' title='{$menu['nm_imagem']}'  width=16 height=16>" ,$tipo['nm_tipo']));
						$total = $menu['_total'];
					}
				}
			}
		}		
		
		// Paginador
		$this->addPaginador2( "imagem_lst.php", $total, $_GET, $this->nome, $limite );		
		$this->acao = "go(\"imagem_cad.php\")";
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