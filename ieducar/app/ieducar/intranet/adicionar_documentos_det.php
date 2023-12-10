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
require_once ("include/clsCadastro.inc.php");
require_once ("include/Geral.inc.php");
require_once ("include/clsBanco.inc.php");

class clsIndex extends clsBase
{
	
	function Formular()
	{
		$this->SetTitulo( "{$this->_instituicao} Documentos" );
	}
}

class indice extends clsDetalhe
{
	function Gerar()
	{
		$this->titulo = "Documentos";
		$this->addBanner( "imagens/nvp_top_intranet.jpg", "imagens/nvp_vert_intranet.jpg", "Intranet", false );

		$this->idpes = @$_SESSION['id_pessoa'];
		
		$objDocumento = new clsDocumento($idpes);
		$detalheDocumento = $objDocumento->detalhe();
		
		list($idpes, $rg, $data_exp_rg, $sigla_uf_exp_rg, $tipo_cert_civil, $num_termo, $num_livro, $num_folha, $data_emissao_cert_civil, $sigla_uf_cert_civil, $cartorio_cert_civil, $num_cart_trabalho, $serie_cart_trabalho, $data_emissao_cart_trabalho, $sigla_uf_cart_trabalho, $num_tit_eleitor, $zona_tit_eleitor, $secao_tit_eleitor, $idorg_exp_rg) = $objDocumento->detalhe();
		
		$this->addDetalhe( array("RG", $detalheDocumento['rg'] ) );
		$this->addDetalhe( array("Data Expedi��o", date('d/m/Y',strtotime(substr($data_exp_rg,0,19)) ) ) );
		$this->addDetalhe( array("�rg�o Expedi��o", $sigla_uf_exp_rg ) );
		$this->addDetalhe( array("Certificado Civil", $tipo_cert_civil ) );		
		$this->addDetalhe( array("Termo", $num_termo ) );
		$this->addDetalhe( array("Livro", $num_livro ) );
		$this->addDetalhe( array("Folha", $num_folha ) );
		$this->addDetalhe( array("Emiss�o Certificado Civil", $data_emissao_cert_civil) );
		$this->addDetalhe( array("Sigla Certificado Civil", $sigla_uf_cert_civil ) );
		$this->addDetalhe( array("Cart�rio", $cartorio_cert_civil ) );
		$this->addDetalhe( array("Carteira trabalho", $num_cart_trabalho ) );
		$this->addDetalhe( array("s�rie Carteira Trabalho", $serie_cart_trabalho ) );
		$this->addDetalhe( array("Emiss�o Carteira Trabalho", $data_emissao_cart_trabalho ) );
		$this->addDetalhe( array("Sigla Carteira de Trabalho", $sigla_uf_cart_trabalho ) );
		$this->addDetalhe( array("T�tulo Eleitor", $num_tit_eleitor ) );
		$this->addDetalhe( array("Zona", $zona_tit_eleitor ) );
		$this->addDetalhe( array("Se��o", $secao_tit_eleitor ) );
		$this->addDetalhe( array("�rg�o Expedi��o", $idorg_exp_rg) );
		
		$this->url_novo = "adicionar_documentos_cad.php";
		$this->url_editar = "adicionar_documentos_cad.php?idpes={$idpes}";
		$this->url_cancelar = "meusdados.php";

		$this->largura = "100%";
	}
}

	function Novo() 
	{
		$objDocumento = new clsDocumento($this->rg, $this->data_exp_rg, $this->sigla_uf_exp_rg, $this->tipo_cert_civil, $this->num_termo, $this->num_livro, $this->num_folha, $this->data_emissao_cert_civil, $this->sigla_uf_cert_civil, $this->cartorio_cert_civil, $this->num_cart_trabalho, $this->serie_cart_trabalho, $this->data_emissao_cart_trabalho, $this->sigla_uf_cart_trabalho, $this->num_tit_eleitor, $this->zona_tit_eleitor, $this->secao_tit_eleitor, $this->idorg_exp_rg );
		if( $objDocumento->cadastra() )
		{
			echo "<script>document.location='meusdados.php';</script>";
			return true;
		}
		
		return false;
	}

	function Editar() 	
	{
		$ObjDocumento = new clsDocumento($this->rg, $this->data_exp_rg, $this->sigla_uf_exp_rg, $this->tipo_cert_civil, $this->num_termo, $this->num_livro, $this->num_folha, $this->data_emissao_cert_civil, $this->sigla_uf_cert_civil, $this->cartorio_cert_civil, $this->num_cart_trabalho, $this->serie_cart_trabalho, $this->data_emissao_cart_trabalho, $this->sigla_uf_cart_trabalho, $this->num_tit_eleitor, $this->zona_tit_eleitor, $this->secao_tit_eleitor, $this->idorg_exp_rg);
		if( $ObjDocumento->edita() )
		{
			echo "<script>document.location='meusdados.php';</script>";
			return true;
		}

		return false;
	}

	function Excluir()
	{
		$ObjDocumento = new clsDocumento($this->idpes);
		$Objcallback->exclui();
		echo "<script>document.location='meusdados.php';</script>";
		return true;
	}

$pagina = new clsIndex();

$miolo = new indice();
$pagina->addForm($miolo);

$pagina->MakeAll();

?>