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
		$this->SetTitulo( "{$this->_instituicao} Conex�es!" );
		$this->processoAp = "158";
	}
}

class indice extends clsListagem
{
	function Gerar()
	{
		$this->titulo = "Conex�es";
		$this->addBanner( "imagens/nvp_top_intranet.jpg", "imagens/nvp_vert_intranet.jpg", "Intranet" );

		$this->addCabecalhos( array( "Data Hora", "Local do Acesso", "Ip Interno", "Pessoa") );

		// Paginador
		$limite = 30;
		$iniciolimit = ( $_GET["pagina_{$this->nome}"] ) ? $_GET["pagina_{$this->nome}"]*$limite-$limite: 0;

		$sql = "SELECT b.data_hora, b.ip_externo, b.ip_interno, n.nome FROM acesso b, cadastro.pessoa n WHERE b.cod_pessoa=n.idpes ";

		if (!empty($_GET['status']))
		{
			if ($_GET['status'] == 'P')
			{
				$where .= " AND ip_externo = '200.215.80.163'";
			}
			else if ($_GET['status'] == 'X')
			{
				$where .= " AND ip_externo <> '200.215.80.163'";
			}
		}
		if(!empty($_GET['data_inicial']))
		{
			$data = explode("/", $_GET['data_inicial']);
			$where .= " AND data_hora >= '{$data[2]}-{$data[1]}-{$data[0]}'";
		}

		if(!empty($_GET['data_final']))
		{
			$data = explode("/", $_GET['data_final']);
			$where .= " AND data_hora <= '{$data[2]}-{$data[1]}-{$data[0]}'";
		}

		if(!empty($_GET['ip_pesquisa']))
		{
			$where .= " AND ( (ip_interno like ('{$_GET['ip_pesquisa']}')) OR (ip_externo like ('{$_GET['ip_pesquisa']}')) )";
		}

		if(!empty($_GET['pessoa_nome']))
		{
			$nome_pessoa = str_replace(" ", "%", $_GET['pessoa_nome']);
			$where .= " AND n.nome LIKE ('%{$nome_pessoa}%')";
		}

		$db = new clsBanco();
		$total = $db->UnicoCampo("SELECT count(*) FROM acesso b, cadastro.pessoa n WHERE b.cod_pessoa=n.idpes $where");

		$sql .= " $where ORDER BY b.data_hora DESC LIMIT $iniciolimit, 30";
	//	die($sql);
		$db->Consulta( $sql );
		while ( $db->ProximoRegistro() )
		{
			list ($data_hora, $ip_externo, $ip_interno, $nm_pessoa) = $db->Tupla();

			$local = $ip_externo == '200.215.80.163' ? 'Prefeitura' : 'Externo - '.$ip_externo;
			$ip_interno = $ip_interno=='NULL' ? "&nbsp" : $ip_interno;

			$this->addLinhas( array("<img src='imagens/noticia.jpg' border=0>$data_hora", $local, $ip_interno, $nm_pessoa ) );
		}

		$opcoes[""] = "Escolha uma op��o...";
		$opcoes["P"] = "Prefeitura";
		$opcoes["X"] = "Externo";

		$this->campoLista( "status", "Status", $opcoes, $_GET['status'] );

		$this->campoData("data_inicial","Data Inicial",$_GET['data_inicial']);
		$this->campoData("data_final","Data Final",$_GET['data_final']);

		$this->campoTexto("ip_pesquisa","IP", $_GET['ip_pesquisa'], 30, 30);
		$this->campoTexto("pessoa_nome","Funcion�rio", $_GET['pessoa_nome'], 30, 150);

		$this->addPaginador2( "conexoes_todos_lst.php", $total, $_GET, $this->nome, $limite );

		$this->largura = "100%";
	}
}


$pagina = new clsIndex();

$miolo = new indice();
$pagina->addForm( $miolo );

$pagina->MakeAll();

?>