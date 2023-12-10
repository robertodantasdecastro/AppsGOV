<?php
/**********************************************************************************
 Sistema e-SIC Livre: sistema de acesso a informação baseado na lei de acesso.
 
 Copyright (C) 2014 Prefeitura Municipal do Natal
 
 Este programa é software livre; você pode redistribuí-lo e/ou
 modificá-lo sob os termos da Licença GPL2.
***********************************************************************************/

include("../inc/autenticar.php");
checkPerm("LSTSEC");

include "../inc/topo.php";
include("../inc/paginacaoPorPostIni.php");

$filtro = "";
if (($_REQUEST['acao'])) 
{
    $nome = $_REQUEST["nome"];
    $sigla = $_REQUEST["sigla"];
    $ativado = $_REQUEST["ativado"];
		
    if(!empty($nome)) $filtro.= " and s.nome like '%$nome%'";
    if(!empty($sigla)) $filtro.= " and s.sigla like '%$sigla%'";
    if(!empty($ativado)) $filtro.= " and s.ativado = $ativado";
}

$sql = "select * from sis_secretaria s
        where 1=1
        $filtro
        order by s.nome";

  
$resultado = execQueryPag($sql);

$num = mysql_num_rows($resultado);

?>
<h1>Cadastro de SIC's</h1>
<br><br>
<form action="index.php" method="post" id="formulario" target="_self">
<fieldset style="width: 50%;">
<legend>Buscar:</legend>
    <table>
    <tr>
        <td>Nome:</td>
        <td>
            <input type="text" name="nome" id="nome" value="<?php echo $nome;?>" size="30" maxlength="100" />
            Sigla:
            <input type="text" name="sigla" id="sigla" value="<?php echo $sigla;?>" size="20" maxlength="30" />
        </td>
    </tr>
    <tr>
        <td>Situação:</td>
        <td>
            <select name="ativado" id="ativado">
                <option value="">--</option>
                <option value="1" <?php echo ($ativado) == "1"?"selected":""; ?>>Ativo</option>
                <option value="0" <?php echo ($ativado) == "0" ?"selected":""; ?>>Inativo</option>
            </select>
        </td>
    </tr>  
    <tr>
            <td colspan="2" align="center">
                    <br>
                    <input type="submit" class="botaoformulario" value="Buscar" name="acao" id="acao" />
                    <input type="button" class="botaoformulario" onClick="document.location = 'cadastro.php';" value="Adicionar">
                    <input type="submit" class="botaoformulario" value="Imprimir" name="imprimir" />
            </td>
    </tr>
    </table>
</fieldset>		

<br>
<table class="tabLista">
  <tr>
	<th></th>
	<th>Codigo</th>
        <th>Nome</th>
        <th>Sigla</th>
        <th>Centralizador</th>
        <th>Status</th>
  </tr>
<?php
  $cor = false;
  while($registro = mysql_fetch_array($resultado)){
    $click = "editar('".$registro["idsecretaria"]."')";
    if($cor)
        $corLinha = "#dddddd";
    else
        $corLinha = "#ffffff";
    $cor = !$cor;
        
    ?>

  <tr onMouseOver="this.style.backgroundColor = getCorSelecao(true);" onMouseOut="this.style.backgroundColor = '<?php echo $corLinha;?>';" style="background-color:<?php echo $corLinha;?>;cursor:pointer; cursor:hand; ">
    <td><img src="../img/drop.png" title="Excluir Registro" onClick="apagar('<?php echo $registro["idsecretaria"]; ?>');"/></td>      
    <td onClick="<?php echo $click;?>"><?php echo $registro["idsecretaria"]; ?></td>
    <td onClick="<?php echo $click;?>"><?php echo $registro["nome"]; ?></td>
    <td onClick="<?php echo $click;?>"><?php echo $registro["sigla"]; ?></td>
    <td onClick="<?php echo $click;?>"><?php echo ($registro["siccentral"])?"Sim":"Não"; ?></td>
    <td onClick="<?php echo $click;?>"><?php echo ($registro["ativado"])?"Ativo":"Inativo"; ?></td>
  </tr>
  <?php } ?>
  <tr>
    <td align="right" colspan="6">
        <?php include("../inc/paginacaoPorPostFim.php");?>
    </td>
  <tr>
    <td colspan="6" align="center">		
        <input type="button" class="botaoformulario" onClick="document.location = 'cadastro.php';" value="Adicionar">
        <input type="button" value="Voltar" class="botaoformulario" name="voltar" id="voltar" onclick="location.href='../inc/menu.php'" />
    </td>
  </tr>  
</table>

</form>

<?php
  include "../inc/rodape.php";
?>