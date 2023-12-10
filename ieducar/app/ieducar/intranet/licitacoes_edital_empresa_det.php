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
		$this->SetTitulo( "{$this->_instituicao} Edital" );
		$this->processoAp = "239";
	}
}

class indice extends clsDetalhe
{
	function Gerar()
	{
		$db = new clsBanco();
		$this->titulo = "Detalhe doa Empresa";
		$this->addBanner( "imagens/nvp_top_intranet.jpg", "imagens/nvp_vert_intranet.jpg", "Intranet" );

		$cod_empresa = @$_GET['cod_empresa'];
		
		$db->Consulta( "SELECT cod_compras_editais_empresa, cnpj, nm_empresa, email, data_hora, endereco, ref_sigla_uf, cidade, bairro, telefone, fax, cep, nome_contato FROM compras_editais_empresa WHERE cod_compras_editais_empresa = '{$cod_empresa}'" );
		$db->ProximoRegistro();
		list ( $cod_compras_editais_empresa, $cnpj, $nm_empresa, $email, $data_hora, $endereco, $ref_sigla_uf, $cidade, $bairro, $telefone, $fax, $cep, $nome_contato ) = $db->Tupla();
		
		if( $ref_sigla_uf ) 
		{
			$ref_sigla_uf = $db->CampoUnico( "SELECT nome FROM public.uf WHERE sigla_uf = '{$ref_sigla_uf}'" );
		}
		
		$this->addDetalhe( array("Nome", $nm_empresa ) );
		$this->addDetalhe( array("CNPJ", $cnpj ) );
		$this->addDetalhe( array("e-mail", $email ) );
		
		$this->addDetalhe( array("Endereco", $endereco ) );
		$this->addDetalhe( array("Estado", $ref_sigla_uf ) );
		$this->addDetalhe( array("Cidade", $cidade ) );
		$this->addDetalhe( array("Bairro", $bairro ) );
		$this->addDetalhe( array("Cep", $cep ) );
		
		$this->addDetalhe( array("Telefone", $telefone ) );
		$this->addDetalhe( array("Fax", $fax ) );
		$this->addDetalhe( array("Nome para contato", $nome_contato ) );
		
		$this->addDetalhe( array("Data de cadastro", date( "d/m/Y H:i", strtotime(substr( $data_hora,0,19) ) ) ) );
		
		$this->url_novo = "licitacoes_edital_empresa_cad.php";
		$this->url_editar = "licitacoes_edital_empresa_cad.php?cod_empresa=$cod_empresa";
		$this->url_cancelar = "licitacoes_edital_empresa_lst.php";

		$this->largura = "100%";
	}
}


$pagina = new clsIndex();

$miolo = new indice();
$pagina->addForm( $miolo );

$pagina->MakeAll();

?>