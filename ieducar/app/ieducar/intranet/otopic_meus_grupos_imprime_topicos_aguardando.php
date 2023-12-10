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
require_once ("include/clsListagem.inc.php");
require_once ("include/otopic/otopicGeral.inc.php");
require_once ("include/clsBanco.inc.php");
require_once ("include/relatorio.inc.php");

class clsIndex extends clsBase
{
	
	function Formular()
	{
		$this->SetTitulo( "{$this->_instituicao} i-Pauta - Detalhe do Grupo" );
		$this->processoAp = "294";
	}
}

class Listas2 extends clsListagem
{
	function Gerar()
	{
		@session_start();
		$id_visualiza = $_SESSION['id_pessoa'];
		@session_write_close();
		$this->nome = "Form2";

		$this->titulo = "T�picos Aguardando em Reuni�o";
		$this->addBanner(false,false,false,false );
		
		$cod_membro = $_GET['cod_membro'];
		$cod_grupo = $_GET['cod_grupo'];
		$imprimir = $_GET['imprimir'];
		
		$this->addCabecalhos( array( "Imprimir") );

		$obj = new clsReuniao();
		/*  Pega lista de Reunioes Finalizadas, Verifica e mostra os Topicos Finalizados 
			que nao foram finalizados nessa reuniao 
		*/
		$lista = $obj->lista(false,$cod_grupo,false,false,false,false,false,false,true);
		if($lista)
		{
			foreach ($lista as $reuniao) 
			{
				$obj = new clsTopicoReuniao();
				$lista = $obj->lista(false,false,false,false,false,false,false,$reuniao['cod_reuniao']);
				if($lista)
				{
					foreach ($lista as $topicos) 
					{
						if($topicos['finalizado'])
						{
							$topico_comprometidos[] = $topicos['ref_cod_topico'];
						}
					}
				}
			}
		}
		/*  Pega lista de Reunioes n�o Finalizadas, Verifica que est�o nessa reuniao e marca como 
			comprometido
		*/	
		$obj = new clsReuniao();
		$lista = $obj->lista(false,$cod_grupo,false,false,false,false,false,true);
		if($lista)
		{
			foreach ($lista as $reuniao) {
				$obj = new clsTopicoReuniao();
				$lista = $obj->lista(false,false,false,false,false,false,false,$reuniao['cod_reuniao']);
				if($lista)
				{
					foreach ($lista as $topicos) {
							$topico_comprometidos[] = $topicos['ref_cod_topico'];
					}
				}
			}
		}
		if($topico_comprometidos)
		{
			$topico_finalizados = "";
			foreach ($topico_comprometidos as $topicos) {
				$obj = new clsTopicoReuniao($topicos);
				$detalhe = $obj->detalhe();
				if(!$detalhe['finalizado'])
				{
					$topico_finalizados[] = $topicos;
				}
			}
		}
		
		if($topico_finalizados)
		{
			$obj = new clsTopico();
			$lista = $obj->lista(false,false,false,false,false,false,false,1,$iniciolimit,$limite,"cod_topico DESC",false,$topico_finalizados);
			if($lista)
			{
				//pdf
				$objRelatorio = new relatorios("T�picos Aguardando em Reuni�o",80,false,false,"A4","Prefeitura de Itaja�\nCentro Tecnologico de Informa��o e Moderniza��o Administrativa.\nRua Alberto Werner, 100 - Vila Oper�ria\nCEP. 88304-053 - Itaja� - SC","#FFFFFF","#000000", "#FFFFFF", "#FFFFFF");
				
				if($imprimir == "jato")
				{
					foreach ($lista as $topicos) 
					{
						$total = $topicos['total'];
						$obj = new clsTopicoReuniao($topicos['cod_topico']);
						$detalhe = $obj->detalhe();
						if(strlen($topicos['assunto']) > 60 )
						{
							
							$descricao = substr($topicos['assunto'],0,60)."...";
						}else 
						{
							$descricao = $topicos['assunto'];
						}
						// Pega o Nome do respons�vel pelo T�pico
						$obj = new clsPessoaFj($topicos['ref_idpes_cad']);
						$detalhe = $obj->detalhe();
						$nome = $detalhe['nome'];
						//pdf
						$objRelatorio->novalinha(array("Descri��o: ".quebra_linhas_pdf($descricao, 70)), 0, 13*(count(explode("\n",quebra_linhas_pdf($descricao, 70) ))) , false, false, 109,false,"#FFFFFF");						
						$objRelatorio->novalinha(array("Respons�vel: ".$nome), 15, 13 , false, false, 109,false,"#FFFFFF");						
						$objRelatorio->novalinha(array(""), 15, 13 , false, false, 109,false,"#FFFFFF");						
						$objRelatorio->novalinha(array(""), 15, 13 , false, false, 109,false,"#FFFFFF");						
						$objRelatorio->novalinha(array(""), 15, 13 , false, false, 109,false,"#FFFFFF");						
								
					}
				}
				else 
				{
					foreach ($lista as $topicos) 
					{
						$total = $topicos['total'];
						$obj = new clsTopicoReuniao($topicos['cod_topico']);
						$detalhe = $obj->detalhe();
						if(strlen($topicos['assunto']) > 60 )
						{
							
							$descricao = substr($topicos['assunto'],0,60)."...";
						}else 
						{
							$descricao = $topicos['assunto'];
						}
						// Pega o Nome do respons�vel pelo T�pico
						$obj = new clsPessoaFj($topicos['ref_idpes_cad']);
						$detalhe = $obj->detalhe();
						$nome = $detalhe['nome'];
						//pdf
						$objRelatorio->novalinha(array("Descri��o: ".quebra_linhas_pdf($descricao, 70)), 0, 13*(count(explode("\n",quebra_linhas_pdf($descricao, 70) ))) , false, false, 109);						
						$objRelatorio->novalinha(array(""), 15, 13 , false, false, 109);						
						$objRelatorio->novalinha(array(""), 15, 13 , false, false, 109);						
						$objRelatorio->novalinha(array(""), 15, 13 , false, false, 109);						
						$objRelatorio->novalinha(array(""), 15, 13 , false, false, 109);						
					}
				}
				//pdf
				$link = $objRelatorio->fechaPdf();
				$this->addLinhas( array("<a href='$link'>Clique aqui para abrir o arquivo</a>" ) );
				$this->array_botao = array("Cancelar");
				$this->array_botao_url = array("otopic_meus_grupos_det2.php?cod_grupo=$cod_grupo");
			}
		}						
		$this->largura = "100%";
	}
}

$pagina = new clsIndex();

$miolo = new Listas2();
$pagina->addForm( $miolo );

$pagina->MakeAll();

?>