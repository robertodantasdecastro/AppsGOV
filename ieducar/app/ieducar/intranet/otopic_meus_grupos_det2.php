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

class indice extends clsDetalhe
{
	function Gerar()
	{
		@session_start();
		$id_visualiza = $_SESSION['id_pessoa'];
		@session_write_close();
		
		$cod_grupo = $_GET['cod_grupo'];

		$this->titulo = "Detalhe do Grupo";
		$this->addBanner( "imagens/nvp_top_intranet.jpg", "imagens/nvp_vert_intranet.jpg", "Intranet", false);

		/* 
			Verifica se o Usu�rio atual est� cadastrado no grupo,
			caso nao esteja, redireciona para entrada
		*/
		$obj = new clsGrupoPessoa($id_visualiza,$cod_grupo);
		$detalhe_pessoa = $obj->detalhe();
		$obj = new clsGrupoModerador($id_visualiza,$cod_grupo);
		$detalhe_moderador = $obj->detalhe();
		
		$obj = new clsFuncionarioSu($id_visualiza);
		
		if(!$obj->detalhe())
		{
			
			if ($detalhe_moderador && $detalhe_pessoa['ativo']!= 1) 
			{
				if( $detalhe_moderador['ativo'] != 1)
				{
					header("Location: otopic_meus_grupos_lst.php");
				}
			}elseif($detalhe_pessoa['ativo']!= 1)
			{
				header("Location: otopic_meus_grupos_lst.php");
			}
		}
		$obj = new clsGrupos($cod_grupo);
		$detalhe = $obj->detalhe();
		
		$this->addDetalhe(array("Nome", $detalhe['nm_grupo']));
		$this->addDetalhe(array("Data de Cria��o", date("d/m/Y", strtotime(substr($detalhe['data_cadastro'],0,19)))  ));
		
		$this->url_cancelar = "otopic_meus_grupos_det.php?cod_grupo=$cod_grupo";
		$this->largura = "100%";
	}
}


class Listas extends clsListagem
{
	function Gerar()
	{
		@session_start();
		$id_visualiza = $_SESSION['id_pessoa'];
		@session_write_close();
		$this->nome = "Form1";

		$this->titulo = "T�picos Sugeridos";
		$this->addBanner(false,false,false,false );
	
		$cod_membro = $_GET['cod_membro'];
		$cod_grupo = $_GET['cod_grupo'];
		
		$this->addCabecalhos( array( "T�pico", "Respons�vel" , "Status" ) );

		// Paginador
		$limite = 10;
		$iniciolimit = ( $_GET["pagina_{$this->nome}"] ) ? $_GET["pagina_{$this->nome}"]*$limite-$limite: 0;
	
		$obj = new clsReuniao();
		/*  Pega lista de Reunioes Finalizadas, Verifica e mostra os Topicos Finalizados 
			que nao foram finalizados nessa reuniao 
		*/
		$lista = $obj->lista(false,$cod_grupo,false,false,false,false,false,false,true);
		if($lista)
		{
			foreach ($lista as $reuniao) {
				$obj = new clsTopicoReuniao();
				$lista = $obj->lista(false,false,false,false,false,false,false,$reuniao['cod_reuniao']);
				if($lista)
				{
					foreach ($lista as $topicos) {
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
		$obj = new clsTopico();
		$lista = $obj->lista(false,$cod_grupo,false,false,false,false,false,1,$iniciolimit,$limite,false,$topico_comprometidos);
		
		if($lista)
		{
			foreach ($lista as $topicos) 
			{
				$total = $topicos['total'];
				$obj = new clsPessoaFj($topicos['ref_idpes_cad']);
				$detalhe = $obj->detalhe();
				$nome = $detalhe['nome'];
				
				$obj = new clsTopicoReuniao($topicos['cod_topico']);
				$status = $obj->detalhe() ? "Pendente" : "Novo";
				if(strlen($topicos['assunto']) > 60 )
				{
					
					$descricao = substr($topicos['assunto'],0,60)."...";
				}else 
				{
					$descricao = $topicos['assunto'];
				}
				$this->addLinhas( array("<a href='otopic_topicos_cad.php?cod_topico={$topicos['cod_topico']}&cod_grupo=$cod_grupo'>{$descricao}</a>", $nome, $status) );
			}
			$this->array_botao = array("Imprimir (Jato)", "Imprimir (Laser)");
			$this->array_botao_url = array("otopic_meus_grupos_imprime_topicos_sugeridos.php?cod_membro=$cod_membro&cod_grupo=$cod_grupo&imprimir=jato", "otopic_meus_grupos_imprime_topicos_sugeridos.php?cod_membro=$cod_membro&cod_grupo=$cod_grupo&imprimir=laser");
		}						

		$obj = new clsGrupoModerador($id_visualiza,$cod_grupo);
		$detalhe_moderador = $obj->detalhe();
		$obj = new clsFuncionarioSu($id_visualiza);
		if(!$obj->detalhe() || $detalhe_moderador['ativo'] == 1 )
		{
			$this->acao = "go(\"otopic_topicos_cad.php?cod_grupo=$cod_grupo\")";
			$this->nome_acao = "Novo T�pico";
		}
		
		$this->largura = "100%";
		$this->addPaginador2( "otopic_meus_grupos_det2.php", $total, $_GET, $this->nome, $limite );
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
		
		$this->addCabecalhos( array( "T�pico", "Respons�vel") );

		// Paginador
		$limite = 10;
		$iniciolimit = ( $_GET["pagina_{$this->nome}"] ) ? $_GET["pagina_{$this->nome}"]*$limite-$limite: 0;
		
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
					$this->addLinhas( array("{$descricao}", $nome) );
				}
				//pdf
				$this->array_botao = array("Imprimir (Jato)", "Imprimir (Laser)");
				$this->array_botao_url = array("otopic_meus_grupos_imprime_topicos_aguardando.php?cod_membro=$cod_membro&cod_grupo=$cod_grupo&imprimir=jato", "otopic_meus_grupos_imprime_topicos_aguardando.php?cod_membro=$cod_membro&cod_grupo=$cod_grupo&imprimir=laser");
			}
		}						

		$this->largura = "100%";
		$this->addPaginador2( "otopic_meus_grupos_det2.php", $total, $_GET, $this->nome, $limite );
		
	}
}

class Listas3 extends clsListagem
{
	function Gerar()
	{
		@session_start();
		$id_visualiza = $_SESSION['id_pessoa'];
		@session_write_close();
		$this->nome = "Form4";

		$this->titulo = "T�picos Finalizados";
		$this->addBanner( );
	
		$cod_membro = $_GET['cod_membro'];
		$cod_grupo = $_GET['cod_grupo'];
		
		$this->addCabecalhos( array( "T�pico", "Respons�vel") );

		// Paginador
		$limite = 10;
		$iniciolimit = ( $_GET["pagina_{$this->nome}"] ) ? $_GET["pagina_{$this->nome}"]*$limite-$limite: 0;

		$obj = new clsReuniao();
		/*  Pega lista de Reunioes Finalizadas, Verifica e mostra os Topicos Finalizados 
			que nao foram finalizados nessa reuniao 
		*/
		$lista = $obj->lista(false,$cod_grupo,false,false,false,false,false,false,true);
		if($lista)
		{
			foreach ($lista as $reuniao) {
				$obj = new clsTopicoReuniao();
				$lista = $obj->lista(false,false,false,false,false,false,false,$reuniao['cod_reuniao']);
				if($lista)
				{
					foreach ($lista as $topicos) {
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
				if($detalhe['finalizado'])
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
					$this->addLinhas( array("{$descricao}", $nome) );
				}
				//pdf
				$this->array_botao = array("Imprimir (Jato)", "Imprimir (Laser)");
				$this->array_botao_url = array("otopic_meus_grupos_imprime_topicos_finalizados.php?cod_membro=$cod_membro&cod_grupo=$cod_grupo&imprimir=jato", "otopic_meus_grupos_imprime_topicos_finalizados.php?cod_membro=$cod_membro&cod_grupo=$cod_grupo&imprimir=laser");
			}
			
		}						

		$this->largura = "100%";
		$this->addPaginador2( "otopic_meus_grupos_det2.php", $total, $_GET, $this->nome, $limite );
	}
}

class lista_reunioes extends clsListagem
{
	function Gerar()
	{
		@session_start();
		$id_visualiza = $_SESSION['id_pessoa'];
		@session_write_close();
		
		$this->nome = "Form4";
		$this->titulo = "Reuni�es";
		$this->addBanner(false,false,false,false );
		
		$cod_membro = $_GET['cod_membro'];
		$cod_grupo = $_GET['cod_grupo'];
		
		$this->addCabecalhos( array( "Descri��o", "Data Inicio", "Data Fim", "Status" ) );

		// Paginador
		$limite = 10;
		$iniciolimit = ( $_GET["pagina_{$this->nome}"] ) ? $_GET["pagina_{$this->nome}"]*$limite-$limite: 0;

		$obj = new clsReuniao();
		$lista = $obj->lista(false,$cod_grupo,"data_fim_real DESC",false,false,$iniciolimit,$limite);
		if($lista)
		{
			foreach ($lista as $reuniao) {
				$total = $reuniao['total'];
				$data_inicio = date("d/m/Y H:i", strtotime(substr($reuniao['data_inicio_marcado'],0,19)));
				$data_fim = date("d/m/Y H:i", strtotime(substr($reuniao['data_fim_marcado'],0,19)));
				$finalizada = $reuniao['data_fim_real'] ? "Finalizada" : "Aguardando";
				$finalizada = $reuniao['data_inicio_real'] && !$reuniao['data_fim_real'] ? "Andamento" : $finalizada;
				if(strlen($reuniao['descricao']) > 60 )
				{
					
					$descricao = substr($reuniao['descricao'],0,60)."...";
				}else 
				{
					$descricao = $reuniao['descricao'];
				}
				$this->addLinhas( array("<a title='{$reuniao['descricao']}' href='otopic_reunioes_det.php?cod_reuniao={$reuniao['cod_reuniao']}&cod_grupo=$cod_grupo'>{$descricao}</a>", $data_inicio,$data_fim, $finalizada) );

			}
		
		}

		$obj = new clsReuniao();
		/*  Pega lista de Reunioes Finalizadas, Verifica e mostra os Topicos Finalizados 
			que nao foram finalizados nessa reuniao 
		*/
		$lista = $obj->lista(false,$cod_grupo,false,false,false,false,false,false,true);
		if($lista)
		{
			foreach ($lista as $reuniao) {
				$obj = new clsTopicoReuniao();
				$lista = $obj->lista(false,false,false,false,false,false,false,$reuniao['cod_reuniao']);
				if($lista)
				{
					foreach ($lista as $topicos) {
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
			foreach ($lista as $reuniao) 
			{
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
		$obj = new clsTopico();
		$lista = $obj->lista(false,$cod_grupo,false,false,false,false,false,1,$iniciolimit,$limite,false,$topico_comprometidos);
			
		/* 
			Verifica se o usu�rio � moderador para poder inserir uma nova reuni�o e se existem t�picos
		 	para que se possa formar uma nova reuniao, caso nao exista nenhum t�pico, n�o mostra o bot�o
		 	de nova reuniao.
		*/
		$obj_moderador = new clsGrupoModerador($id_visualiza,$cod_grupo);
		$detalhe_moderador = $obj_moderador->detalhe();
		if( $detalhe_moderador && $detalhe_moderador['ativo'] == 1 && $lista)
		{
			$this->acao = "go(\"otopic_reunioes_cad.php?cod_grupo=$cod_grupo\")";
			$this->nome_acao = "Nova Reuni�o";
		}
		
		$this->largura = "100%";
		$this->addPaginador2( "otopic_meus_grupos_det2.php", $total, $_GET, $this->nome, $limite );
	}
}

$pagina = new clsIndex();

$miolo = new indice();
$pagina->addForm( $miolo );

$miolo = new lista_reunioes();
$pagina->addForm( $miolo );

$miolo = new Listas();
$pagina->addForm( $miolo );

$miolo = new Listas2();
$pagina->addForm( $miolo );

$miolo = new Listas3();
$pagina->addForm( $miolo );

$pagina->MakeAll();

?>