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
require_once ("include/pessoa/clsPessoaFj.inc.php");

class clsIndex extends clsBase
{
	
	function Formular()
	{
		$this->SetTitulo( "{$this->_instituicao} Setor" );
		$this->processoAp = "375";
	}
}

class indice extends clsDetalhe
{
	var $cod_setor;
	
	function Gerar()
	{
		$this->titulo = "Detalhe do Setor";
		$this->addBanner( "imagens/nvp_top_intranet.jpg", "imagens/nvp_vert_intranet.jpg", "Intranet" );

		$this->cod_setor = @$_GET['cod_setor'];
		$obj_setor = new clsSetor( $this->cod_setor);
		$detalhe = $obj_setor->detalhe();
		
		if( ! $detalhe )
		{
			$this->addDetalhe( array( "Erro", "Setor Inexistente" ) );
		}
		else 
		{
			$objSetor = new clsSetor( $detalhe["cod_setor"] );
			$parentes = $objSetor->getNiveis( $detalhe["cod_setor"] );
			$strParentes = "";
			$gruda = "";
			for ( $i = 0; $i < count( $parentes ); $i++ )
			{
				$objSetor = new clsSetor( $parentes[$i] );
				$detalheSetor = $objSetor->detalhe();
				$strParentes = " {$detalheSetor["nm_setor"]} - {$detalheSetor["sgl_setor"]}";
				//$gruda = " &gt; ";
				$gruda .= "&nbsp&nbsp&nbsp ";
				if($i == 0)
				{
					$this->addDetalhe( array( "Setor", $strParentes ) );
				}
				else 
				{
					$this->addDetalhe( array( "Setor", "$gruda<img src=\"imagens/nvp_setal.gif\">$strParentes" ) );
				}
			}
			
			$ref_cod_pessoa_cad = $detalhe["ref_cod_pessoa_cad"];
			$obj_pessoa_fj = new clsPessoaFj($ref_cod_pessoa_cad);
			$det = $obj_pessoa_fj->detalhe();
			
			$this->addDetalhe( array( "Respons�vel pelo cadastro", $det["nome"] ) );
			
			$ativo = $detalhe["ativo"] == 1 ? "Sim" : "N�o";
			$this->addDetalhe( array( "Ativo", $ativo ) );
			
			$no_paco = $detalhe["no_paco"] ? "Sim" : "N�o";
			$this->addDetalhe( array( "No Pa�o", $no_paco ) );
			
			if($detalhe["endereco"])
			{
				$this->addDetalhe( array( "Endere�o", $detalhe["endereco"] ) );
			}
			
			if ($detalhe["tipo"]) 
			{
				switch ($detalhe["tipo"])
				{
					case "s":
						$this->addDetalhe( array( "Tipo", "Secretaria" ) );
						break;
					case "a":
						$this->addDetalhe( array( "Tipo", "Altarquia" ) );
						break;
					case "f":
						$this->addDetalhe( array( "Tipo", "Funda��o" ) );
						break;
				}
			}
			
			if($detalhe["refIdpesResp"])
			{
				$obj_pessoa = new clsPessoa($detalhe["refIdpesResp"]);
				$det_pessoa = $obj_pessoa->detalhe();
				
				$this->addDetalhe( array( "Secretario", $det_pessoa["nome"] ) );
			}
		}
		
		if(!is_null($detalhe["ref_cod_setor"]))
		{
			$this->url_editar = "oprot_setor_cad.php?cod_setor={$this->cod_setor}&setor_atual=$detalhe[ref_cod_setor]";
		}
		else 
		{
			$this->url_editar = "oprot_setor_cad.php?cod_setor={$this->cod_setor}";
		}
		$this->url_novo = "oprot_setor_cad.php";
		$this->url_cancelar = "oprot_setor_lst.php";

		$this->largura = "100%";
	}
}

$pagina = new clsIndex();

$miolo = new indice();
$pagina->addForm( $miolo );

$pagina->MakeAll();
?>