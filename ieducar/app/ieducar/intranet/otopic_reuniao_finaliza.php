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
require_once ("include/clsBanco.inc.php");
require_once ("include/otopic/otopicGeral.inc.php");


class clsIndex extends clsBase
{
	
	function Formular()
	{
		$this->SetTitulo( "{$this->_instituicao} i-Pauta - Finalizar" );
		$this->processoAp = "294";
	}
}

class indice
{

	function RenderHTML()
	{
		@session_start();
		$id_pessoa = $_SESSION['id_pessoa'];
		@session_write_close();
		
		$cod_grupo = $_GET['cod_grupo'];
		$cod_reuniao = $_GET['cod_reuniao'];
		
		$data = date("Y-m-d H:i:s", time());
		
		$obj = new clsParticipante();
		$lista_participantes = $obj->lista(false,false,$cod_reuniao);
		if($lista_participantes)
		{
			foreach ($lista_participantes as $participantes) {
				if(!$participantes['data_saida'])
				{
					$data_saida = date("Y-m-d H:i:s",time());
					$obj = new clsParticipante($participantes['ref_ref_idpes'],$participantes['ref_ref_cod_grupos'],$participantes['ref_cod_reuniao'],$participantes['sequencial'],false,$data_saida);
					$obj->edita();
				}
			}
		}
		$obj = new clsReuniao($cod_reuniao,false,false,false,false,false,false,false,$data);
		$obj->edita();
		
		header("Location: otopic_reunioes_det.php?cod_reuniao=$cod_reuniao&cod_grupo=$cod_grupo");
		die();
	}
}



$pagina = new clsIndex();

$miolo = new indice();
$pagina->addForm( $miolo );

$pagina->MakeAll();

?>