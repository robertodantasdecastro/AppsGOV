<?php
/**********************************************************************************
 Sistema e-SIC Livre: sistema de acesso a informaчуo baseado na lei de acesso.
 
 Copyright (C) 2014 Prefeitura Municipal do Natal
 
 Este programa щ software livre; vocъ pode redistribuэ-lo e/ou
 modificс-lo sob os termos da Licenчa GPL2.
***********************************************************************************/

	include_once("../inc/autenticar.php");

        $varAreaRestrita = "inclui"; //indica se deve ser incluido o arquivo dentro da classe
		include_once(DIR_CLASSES_LEIACESSO."/solicitacao.class.php");
        include_once(DIR_CLASSES_LEIACESSO."/solicitante.class.php");
        
      
	$codigo = $_GET["codigo"];
        $acao   = $_POST["acao"];            
	
        
        //persistencia dos campos de filtro do index
        $fltnumprotocolo   = $_REQUEST["fltnumprotocolo"];
        $fltsolicitante    = $_REQUEST["fltsolicitante"];
        $fltsituacao       = $_REQUEST["fltsituacao"];
        $receber           = $_REQUEST["receber"];
        
        $parametrosIndex = "fltnumprotocolo=$fltnumprotocolo&fltsolicitante=$fltsolicitante&fltsituacao=$fltsituacao"; //parametros a ser passado para a pagina de detalhamento, fazendo com que ao voltar para o index traga as informaчѕes passadas anteriormente
        //-----
        
	//se for passado cѓdigo para ediчуo e nao tiver sido postado informaчуo do formulario busca dados do banco
	if(!$_POST['acao'] and !empty($codigo))
	{
		$acao = "Alterar";
                
				
                //recupera campos da demanda
                $sol = new Solicitacao($codigo);

                
		$idsolicitacao              = $sol->getIdSolicitacao();
                $idsolicitante              = $sol->getIdSolicitante();
                $idsolicitacaoorigem        = $sol->getIdSolicitacaoOrigem();
                $numeroprotocolo            = $sol->getNumeroProtocolo();
                $textosolicitacao           = $sol->getTextoSolicitacao();
                $idtiposolicitacao          = $sol->getIdTipoSolicitacao();
                $instancia                  = Solicitacao::getInstaciaTipoSolicitacao($idtiposolicitacao);
                $formaretorno               = $sol->getFormaRetorno();
                $situacao                   = $sol->getSituacao();
                $datasolicitacao            = $sol->getDataSolicitacao();
                $datarecebimentosolicitacao = $sol->getDataRecebimentoSolicitacao();
                $usuariorecebimento         = $sol->getUsuarioRecebimento();
                $dataprevisaoresposta       = $sol->getDataPrevisaoResposta();
                $dataprorrogacao            = $sol->getDataProrrogacao();
                $motivoprorrogacao          = $sol->getMotivoProrrogacao();
                $usuarioprorrogacao         = $sol->getUsuarioProrrogacao();
                $dataresposta               = $sol->getDataResposta();
                $resposta                   = $sol->getResposta();
                $usuarioresposta            = $sol->getUsuarioResposta();
                
                $soli = new Solicitante($idsolicitante);

                $nome               = $soli->getNome();
                $profissao          = $soli->getProfissao();
                $cpfcnpj            = $soli->getCpfCnpj();
                $escolaridade       = $soli->getEscolaridade();
                $faixaetaria        = $soli->getFaixaEtaria();
                $email              = $soli->getEmail();
                $tipotelefone       = $soli->getTipoTelefone();
                $dddtelefone        = $soli->getDDDTelefone();
                $telefone           = $soli->getTelefone();	
                $logradouro         = $soli->getLogradouro();
                $numero             = $soli->getNumero();
                $complemento        = $soli->getComplemento();
                $cep                = $soli->getCep();
                $bairro             = $soli->getBairro();
                $cidade             = $soli->getCidade();
                $uf                 = $soli->getUf();                
                
                //se tiver acao de recebimento para ser realizado
                if($receber=="sim")
                   $erro = Solicitacao::recebe($idsolicitacao);
					
                
				
	}
	else
	{
                
		//recupera valores do formulario
            
                //campos de leitura
		$idsolicitacao              = $_POST['idsolicitacao'];
                $idsolicitante              = $_POST['idsolicitante'];
                $idsolicitacaoorigem        = $_POST['idsolicitacaoorigem'];
                $numeroprotocolo            = $_POST['numeroprotocolo'];
                $textosolicitacao           = $_POST['textosolicitacao'];
                $idtiposolicitacao          = $_POST['idtiposolicitacao'];
                $instancia                  = $_POST['instancia'];
                $formaretorno               = $_POST['formaretorno'];
                $situacao                   = $_POST['situacao'];
                $datasolicitacao            = $_POST['datasolicitacao'];
                $datarecebimentosolicitacao = $_POST['datarecebimentosolicitacao'];
                $usuariorecebimento         = $_POST['usuariorecebimento'];
                $dataprevisaoresposta       = $_POST['dataprevisaoresposta'];
                $dataprorrogacao            = $_POST['dataprorrogacao'];
                $motivoprorrogacao          = $_POST['motivoprorrogacao'];
                $usuarioprorrogacao         = $_POST['usuarioprorrogacao'];
                $dataresposta               = $_POST['dataresposta'];
                $resposta                   = $_POST['resposta'];
                $usuarioresposta            = $_POST['usuarioresposta'];
                $nome                       = $_POST['nome'];
                $profissao                  = $_POST['profissao'];
                $cpfcnpj                    = $_POST['cpfcnpj'];
                $escolaridade               = $_POST['escolaridade'];
                $faixaetaria                = $_POST['faixaetaria'];
                $email                      = $_POST['email'];
                $tipotelefone               = $_POST['tipotelefone'];
                $dddtelefone                = $_POST['dddtelefone'];
                $telefone                   = $_POST['telefone'];
                $logradouro                 = $_POST['logradouro'];
                $numero                     = $_POST['numero'];
                $complemento                = $_POST['complemento'];
                $cep                        = $_POST['cep'];
                $bairro                     = $_POST['bairro'];
                $cidade                     = $_POST['cidade'];
                $uf                         = $_POST['uf'];
            
                //campos da movimentaчуo
                $idsecretariadestino        = $_POST['idsecretariadestino'];
                $despacho                   = $_POST['despacho'];
                $anexomovimentacao          = $_FILES["anexomovimentacao"]; 	
                
                //campos da finalizaчуo
                $txtresposta                = $_POST['txtresposta'];
                $tiporesposta               = $_POST['tiporesposta'];
                $arquivos                   = $_FILES["arquivos"]; 	
                
                //campos de prorrogacao
                $txtmotivoprorrogacao          = $_POST['txtmotivoprorrogacao'];
                
                
	}
	
	$erro="";

        if ($_POST['acao'])
        {
            //se for uma movimentaчуo
            if ($acao == "Enviar")
            {
                    
                    checkPerm("LDAMOVIMENTAR");

                    $erro = Solicitacao::movimenta($idsolicitacao, $idsecretariadestino, $despacho, $anexomovimentacao);

                    if (empty($erro))
                    {
                            logger("Movimentou solicitaчуo.");
                            header("Location: index.php?$parametrosIndex");
                    }
            }
            //se for uma finalizaчуo
            elseif ($acao == "Finalizar")
            {
                    checkPerm("LDARESPONDER");

                    $erro = Solicitacao::finaliza($idsolicitacao, $tiporesposta, $txtresposta, $arquivos);

                    if (empty($erro))
                    {
                            logger("Finalizou solicitaчуo.");
                            header("Location: index.php?$parametrosIndex");
                    }
            }
            //se for uma prorrogaчуo
            elseif ($acao == "Prorrogar")
            {
                    checkPerm("LDAPRORROGAR");

                    $erro = Solicitacao::prorrogar($idsolicitacao, $txtmotivoprorrogacao);

                    if (empty($erro))
                    {
                            logger("Prorrogou solicitaчуo.");
                            header("Location: index.php?$parametrosIndex");
                    }
            }
        }
?>