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
require_once ("include/otopic/otopicGeral.inc.php");

class clsIndex extends clsBase
{
	
	function Formular()
	{
		$this->SetTitulo( "{$this->_instituicao} i-Pauta - Super Usu�rios!" );
		$this->processoAp = "335";
	}
}

class indice extends clsListagem
{
	function Gerar()
	{
		$this->titulo = "Super Usu�rios";
		$this->addBanner( "imagens/nvp_top_intranet.jpg", "imagens/nvp_vert_intranet.jpg", "Intranet" );
		
		$this->addCabecalhos( array( "Nome") );
		
		// Paginador
		$limite = 20;
		$iniciolimit = ( $_GET["pagina_{$this->nome}"] ) ? $_GET["pagina_{$this->nome}"]*$limite-$limite: 0;

		$obj = new clsFuncionarioSu();
		$lista = $obj->lista();
		$novo = true;
		if($lista)
		{	
			foreach ($lista AS $cod) 
			{
				$cod = $cod['ref_ref_cod_pessoa_fj'];
				$novo =false;
				$obj = new clsPessoaFisica($cod);
				$detalhe = $obj->detalhe();
				$this->addLinhas( array($detalhe['nome']) );
			}
		}

		if($novo)
		{
			$this->acao = "go(\"otopic_su_cad.php\")";
			$this->nome_acao = "Novo";
		}else 
		{

			$this->acao = "go(\"otopic_su_det.php\")";
			$this->nome_acao = "Editar";
		}
		
		$this->largura = "100%";
		$this->addPaginador2( "otopic_grupos_lst.php", $total, $_GET, $this->nome, $limite );
	}
}


$pagina = new clsIndex();

$miolo = new indice();
$pagina->addForm( $miolo );

$pagina->MakeAll();

?>