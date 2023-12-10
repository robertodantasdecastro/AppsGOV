<?php
/**********************************************************************************
 Sistema e-SIC Livre: sistema de acesso a informação baseado na lei de acesso.
 
 Copyright (C) 2014 Prefeitura Municipal do Natal
 
 Este programa é software livre; você pode redistribuí-lo e/ou
 modificá-lo sob os termos da Licença GPL2.
***********************************************************************************/

include("../inc/autenticar.php");
checkPerm("LDACONSULTAR");

$varAreaRestrita = "inclui"; //indica se deve ser incluido o arquivo dentro da classe
include_once(DIR_CLASSES_LEIACESSO."/solicitacao.class.php");

include "../inc/topo.php";
include("../inc/paginacaoPorPostIni.php");

$filtro = "";

$numprotocolo   = $_REQUEST["fltnumprotocolo"];
$solicitante    = $_REQUEST["fltsolicitante"];
$situacao       = $_REQUEST["fltsituacao"];
$siglaSecretariaUsuario = $_SESSION["sgsecretaria"];

$parametrosIndex = "fltnumprotocolo=$numprotocolo&fltsolicitante=$solicitante&fltsituacao=$situacao"; //parametros a ser passado para a pagina de detalhamento, fazendo com que ao voltar para o index traga as informações passadas anteriormente

if (!empty($numprotocolo)) $filtro.= " and concat(sol.numprotocolo,'/',sol.anoprotocolo) = '$numprotocolo'";
if (!empty($solicitante)) $filtro.= " and pes.nome like '%$solicitante%'";
if (!empty($situacao)) $filtro.= " and sol.situacao = '$situacao'";
    

//seleciona as solicitações
/*
 * Quando a situação for A ou T, trata da primeira tramitação do processo. 
 */
$sql = "select sol.*, 
               pes.nome as solicitante,
               ifnull(secOrigem.sigla,'Solicitante') as secretariaorigem, 
               ifnull(secDestino.sigla,'SIC Central') as secretariadestino, 
               mov.idsecretariadestino,
               mov.datarecebimento,
               mov.idmovimentacao,
               c.*,
               DATEDIFF(sol.dataprevisaoresposta, NOW()) as prazorestante,
               tip.nome as tiposolicitacao

        from lda_solicitacao sol
        join lda_tiposolicitacao tip on tip.idtiposolicitacao = sol.idtiposolicitacao
        join lda_solicitante pes on pes.idsolicitante = sol.idsolicitante
        left join lda_movimentacao mov on mov.idmovimentacao = (select max(m.idmovimentacao) from lda_movimentacao m where m.idsolicitacao = sol.idsolicitacao)
        left join sis_secretaria secOrigem on secOrigem.idsecretaria = mov.idsecretariaorigem
        left join sis_secretaria secDestino on secDestino.idsecretaria = mov.idsecretariadestino
        join lda_configuracao c
        where  1=1
            $filtro ";

/*if ($_REQUEST['imprimir']) {
    generateReport(array("!PATH" => "ouv_CategoriaProblema.jasper", "@sql" => $sql, "@usuario" => $_SESSION['usuario'], "@titulo" => "Listagem das Categorias dos Problemas"));
}*/

$rs = execQueryPag($sql);

?>
<h1>Pesquisa de Solicitações do Lei de Acesso</h1>
<br><br>
<form action="index.php" method="post" id="formulario">
<fieldset style="width: 50%;">
<legend>Buscar:</legend>
    <table align="center" width="200">
        <tr>
            <td nowrap>Nº do Protocolo:</td>
            <td><input type="text" name="fltnumprotocolo" value="<?php echo $numprotocolo; ?>" maxlength="50" size="30" /></td>
        </tr>
        <tr>
            <td>Solicitante:</td>
            <td><input type="text" name="fltsolicitante" value="<?php echo $solicitante; ?>" maxlength="50" size="30" /></td>
        </tr>
        <tr>
                <td>Situação:</td>
                <td>
                        <select name="fltsituacao" id="fltsituacao">
                                <option value="" <?php echo empty($situacao)?"selected":""; ?>>--Todos--</option>
                                <option value="A" <?php echo $situacao=="A"?"selected":""; ?>>Aberto</option>
                                <option value="T" <?php echo $situacao=="T"?"selected":""; ?>>Em tramitação</option>
                                <option value="N" <?php echo $situacao=="N"?"selected":""; ?>>Negado</option>
                                <option value="R" <?php echo $situacao=="R"?"selected":""; ?>>Respondido</option>
                        </select>
                </td>	
        </tr>
        <tr>
            <td colspan="2" align="center">
                <br>
                <input type="submit" value="Buscar" class="botaoformulario"  name="acao" />
                <!--input type="submit" value="Imprimir" name="imprimir" /-->
            </td>
        </tr>
    </table>
</fieldset>		

<br>
<table class="tabLista">
    <tr>
        <th colspan="11" align="left">
            <span style="background-color: #FFB2B2;border:1px solid #000000;">&nbsp;&nbsp;&nbsp;</span> Prazo de resposta expirado
            <span style="background-color: #FFFACD;border:1px solid #000000;">&nbsp;&nbsp;&nbsp;</span> Prazo de resposta perto de expirar
        </th>
    </tr>
    <tr>
        <th>Protocolo</th>
        <th>Tipo de Solicitação</th>
        <th>Data Solicitação</th>
        <th>Solicitante</th>
        <th>Data Envio</th>
        <th>Origem</th>
        <th>Destino</th>
        <th>Prazo Restante</th>
        <th>Previsão Resposta</th>
        <th>Prorrogado?</th>
        <th>Situação</th>
    </tr>
    <?php
    $cor = false;
    while ($registro = mysql_fetch_array($rs)) {

            if($cor)
                $corLinha = "#dddddd";
            else
                $corLinha = "#ffffff";
            $cor = !$cor;
        
            
            if (empty($registro['dataresposta']))
            {
                //se tiver passado do prazo de resposta
                if ($registro['prazorestante'] < 0)
                {
                    $corLinha = "#FFB2B2"; //vermelho - Urgente! Passou do prazo de resolução
                }
                //se faltar entre 1 e 5 dias para expirar o prazo de resposta
                elseif($registro['prazorestante'] >= 0 and $registro['prazorestante'] <= 5)
                {
                    $corLinha = "#FFFACD"; //amarelo - Alerta! Está perto de expirar
                }
            }
            $clickMovimento = $confirmacao."editar('".$registro["idsolicitacao"]."&$parametrosIndex','../lda_solicitacao/visualizar');";
            ?>
            <tr onMouseOver="this.style.backgroundColor = getCorSelecao(true);" onMouseOut="this.style.backgroundColor = '<?php echo $corLinha;?>';" style="background-color:<?php echo $corLinha;?>;cursor:pointer; cursor:hand; ">
                <td onClick="<?php echo $clickMovimento; ?>"><?php echo $registro["numprotocolo"]."/".$registro["anoprotocolo"]; ?></td>
                <td onClick="<?php echo $clickMovimento; ?>"><?php echo $registro["tiposolicitacao"]; ?></td>
                <td onClick="<?php echo $clickMovimento; ?>"><?php echo bdToDate($registro["datasolicitacao"]); ?></td>
                <td onClick="<?php echo $clickMovimento; ?>"><?php echo $registro["solicitante"]; ?></td>
                <td onClick="<?php echo $clickMovimento; ?>"><?php echo bdToDate(!empty($registro["dataenvio"])?$registro["dataenvio"]:$registro["datasolicitacao"]); ?></td>                
                <td onClick="<?php echo $clickMovimento; ?>"><?php echo strtoupper($registro["secretariaorigem"]); ?></td>
                <td onClick="<?php echo $clickMovimento; ?>"><?php echo strtoupper($registro["secretariadestino"]); ?></td>
                <td onClick="<?php echo $clickMovimento; ?>"><?php echo $registro["prazorestante"]; ?></td>
                <td onClick="<?php echo $clickMovimento; ?>"><?php echo bdToDate($registro["dataprevisaoresposta"]); ?></td>
                <td onClick="<?php echo $clickMovimento; ?>"><?php echo (!empty($registro["dataprorrogacao"]))?"Sim":"Não"; ?></td>
                <td onClick="<?php echo $clickMovimento; ?>"><?php echo Solicitacao::getDescricaoSituacao($registro["situacao"]); ?></td>
            </tr>
            <?php 
    } ?>
    <tr>
        <td align="right" colspan="12">
            <?php include("../inc/paginacaoPorPostFim.php");?>
        </td>
    </tr>
</table>
<br><br>
<input type="button" class="botaoformulario"  value="Voltar" name="voltar" id="voltar" onclick="location.href='../inc/menu.php'" />
</form>
<?php
	include "../inc/rodape.php";
?>