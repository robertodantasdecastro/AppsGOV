<?php
/**********************************************************************************
 Sistema e-SIC Livre: sistema de acesso a informa��o baseado na lei de acesso.
 
 Copyright (C) 2014 Prefeitura Municipal do Natal
 
 Este programa � software livre; voc� pode redistribu�-lo e/ou
 modific�-lo sob os termos da Licen�a GPL2.
***********************************************************************************/

require_once("../inc/security.php");

$login 		= $_POST["login"];
$password 	= $_POST["password"];
$tipo 		= $_REQUEST['t'];
$msg 		= "";

//usado pra troca de sic, chamado no arquivo topo atrav�s do select de sics
$sic = $_GET['sic'];
if(!empty($sic))
    if(!atualizaUnidadeUsuario($sic))
        echo "<script>alert('Usu�rio n�o pertence ao SIC que est� tentando acessar!');</script>";
//----------------------   
    
if(usaRecaptcha($login))
	$usarecap = true;
else
	$usarecap = false;


if ($_POST['btsub'])
{

	if($usarecap)
	{
		$error = null;
                $recaptcha_ok = (strtoupper($_POST["palavra"]) == strtoupper($_SESSION["palavra"]));
	}
	else
		$recaptcha_ok = true;
	
	if($recaptcha_ok)
	{
		if(autentica($login, $password, $tipo)) 
		{
			Redirect("../index");	
		}
		else 
		{
			$msg = "<font color='red'>Erro: falha no login.</font>";
			$usarecap = true;
		}
	} 
	else
		$msg = "<font color='red'>Erro: falha no login.</font>";
}





include("../inc/topo.php"); 
?>
        <div id="principal">
			<div id="banner">
				<img src="../css/img/sic.png" />
			</div>
            
                        <?php if (empty($_SESSION[SISTEMA_CODIGO])) { ?>
			<div id="login">
				<form action="index.php" method="post">
				<div class="titulo_caixa_login"> Acesse a �rea restrita do sistema</div>
				<span class="Mensagem">Preencha o Nome do Usu�rio e senha para acessar o Sistema de Informa��es.</span>
				
				<div id="campos">
					<table cellpadding="1" cellspacing="5" width="80%">
						<tr align="right">
							<td>
								<span class="labelLogin"><label for="login">Usu�rio: </label> </span>
							</td>
							<td>
								<span class="inputLogin"><input type="text" name="login" maxlength="20"> </span>
							</td>
						</tr>
						<tr align="right">	
							<td>
								<span class="labelLogin"><label for="Senha">Senha: </LABEL> </span>
							</td>
							<td>
								<span class="inputLogin"><input type="password" name="password" maxlength="100"> </span>	
							</td>							
						</tr>
                                                <?php if ($usarecap) { ?>
                                                <tr>
                                                    <td colspan="2" align="right">
                                                        <br>
                                                        <img src="../inc/captcha.php?l=150&a=50&tf=20&ql=5" id="imgcaptcha">
                                                        <img src="../img/refresh.gif" title="Clique aqui para recarregar a imagem" alt="Clique aqui para recarregar a imagem" onclick="getElementById('imgcaptcha').src ='../inc/captcha.php?l=150&a=50&tf=20&ql=5';">
                                                        <br><span class="labelLogin">Informe o c�digo acima:</span><br><input type="text" name="palavra"  />
                                                    </td>
                                                </tr>
                                                <?php } ?>

                                                <tr align="right">
							<td>
							</td>
							<td>
								<br><input type="submit" class="inputBotao" name="btsub" value="Entrar">
							</td>
						</tr>
						<tr>
							<td colspan="2">
								<a class="class_cadastrese" href="../cadastro">Cadastre-se</a> | 
								<a class="class_senha" href="../reset">Esqueci a senha</a>
							</td>
						</tr>						
					</table>						
				</div>
				
				</form>
			</div>
                        <?php } else {?>
                        <div id="login">
				<span class="Mensagem">
                                    <br>
                                    Ol� <?php echo getSession("nomeusuario");?>! 
                                    <br><br>
                                    Voc� est� no SIC <b><?php echo getSession('siglasecretaria')." - ".getSession('nomesecretaria');?></b>
                                    <br><br>
                                    Caso n�o seja voc� [<a href="../index/logout.php" class="class_cadastrese">clique aqui</a>]
                                    
                                </span>                            
                        </div>
                        <?php } ?>
		</div>
					
        <div id="notificacoes">
			<div id="linha"></div>
			
			<div id="links">
				<table width="100%">
					<tr>
						<th width="40%">
							SIC - Servi�o de informa��o ao Cidad�o	
						</th>
						<th width="30%">
							Lei de Acesso
						</th>
						<th width="30%">
							Links �teis
						</th>
					</tr>
					<tr>
						<td>
							<a href="../manual/informacao.php">Como pedir uma informa��o</a>
						</td>
						<td>
							<a href="../manual/decreto.php">Decreto</a>
						</td>
						<td>
							<a href="http://www.acessoainformacao.gov.br/acessoainformacaogov/">Acesso � informa��o CGU</a>
						</td>						
					</tr>
					<tr>
						<td>
							<a href="../manual/pedido.php">Como acompanhar seu pedido</a>
						</td>
						<td>
							<a href="../manual/LegislacaoRelacionada.php">Legisla��o relacionada</a>
						</td>
						<td>
							<a href="http://portal2.tcu.gov.br/portal/page/portal/TCU/transparencia">Acesso � informa��o TCU</a>
						</td>						
					</tr>
					<tr>
						<td>
							<a href="../manual/recurso.php">Como entrar com um recurso</a>
						</td>
						<td>
							<a href="../manual/LEIN5162013.pdf" target="_blank">Lei Municipal</a>
						</td>
						<td>
							
						</td>
					</tr>					
				</table>			
			</div>
			<div id="postagens"></div>
        </div>

<?php include("../inc/rodape.php"); ?>
