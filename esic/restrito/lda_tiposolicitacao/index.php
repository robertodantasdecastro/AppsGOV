<?php
/**********************************************************************************
 Sistema e-SIC Livre: sistema de acesso a informação baseado na lei de acesso.
 
 Copyright (C) 2014 Prefeitura Municipal do Natal
 
 Este programa é software livre; você pode redistribuí-lo e/ou
 modificá-lo sob os termos da Licença GPL2.
***********************************************************************************/

include "manutencao.php";
include "../inc/topo.php";
include "../inc/paginacaoIni.php";	


$filtro = "";


if(!empty($nome)) $filtro .= " and nome like '%$nome%' ";
if(!empty($instancia)) $filtro .= " and instancia = '$instancia'";


$sql = "select t.*, prox.nome as proxima
        from lda_tiposolicitacao t
        left join lda_tiposolicitacao prox on prox.idtiposolicitacao = t.idtiposolicitacao_seguinte
	where 1=1 $filtro order by nome";

$rs = execQueryPag($sql);

?>
<h1>Cadastro de Instâncias</h1>
<br>

Observações:
<br>- Deverá ter cadastrado pelo menos um tipo de instância inicial e uma última.
<br>- Instâncias cadastradas como última não permitem associar uma instância seguinte.
<br>- Instâncias cadastradas como inicial não são listadas para servir como instância seguinte.
<br><br>
<!-- FORMULARIO -->
<?php include "formulario.php";?>
<br>

<!-- LISTAGEM -->
<table class="tabLista">
  <tr>
	<th></th>
        <th>Codigo</th>
	<th>Nome</th>
        <th>Tipo de Instância</th>
	<th>Próxima Instância</th>
  </tr>
  <?php
  $cor = false;
  while($registro = mysql_fetch_array($rs)){
	$click = "edita('".$registro["idtiposolicitacao"]."','".$registro["nome"]."','".$registro["instancia"]."')";
        
        if($cor)
            $corLinha = "#dddddd";
        else
            $corLinha = "#ffffff";
        $cor = !$cor;

	?>
	<tr onMouseOver="this.style.backgroundColor = getCorSelecao(true);" onMouseOut="this.style.backgroundColor = '<?php echo $corLinha;?>';" style="background-color:<?php echo $corLinha;?>;cursor:pointer; cursor:hand; ">
		<td><img src="../img/drop.png" title="Excluir Registro" onClick="apagar('<?php echo $registro["idtiposolicitacao"]; ?>');"/></td>
		<td align="left" onClick="<?php echo $click;?>"><?php echo $registro["idtiposolicitacao"]; ?></td>
		<td align="left" onClick="<?php echo $click;?>"><?php echo $registro["nome"]; ?></td>
		<td onClick="<?php echo $click;?>"><?php echo Solicitacao::getDescricaoTipoInstancia($registro["instancia"]); ?></td>
		<td align="left">
                    <span id="show_<?php echo $registro["idtiposolicitacao"]; ?>">
                        <?php echo !empty($registro["proxima"])?$registro["proxima"]:"Nenhum"; ?> 
                        <?php if($registro['instancia']!="U"){ //se nao for a ultima instancia, permite cadastrar outras como proxima ?>
                            <a href="javascript: abreordenacao('<?php echo $registro["idtiposolicitacao"]; ?>');">[Alterar]</a>
                        <?php }?>
                    </span>
                    <span id="edit_<?php echo $registro["idtiposolicitacao"]; ?>" style="display:none">
                        <select name="proximainsntancia" id="proximainstancia" onchange="ordena(<?php echo $registro['idtiposolicitacao'];?>,this.value)">
                            <option value="-1">Nenhum</option>
                            <?php 
                                //seleciona as instancias que não seja de inicio e que não esteja sendo utilizada por outro tipo de solicitação
                                $qry = "select * from lda_tiposolicitacao t
                                        where instancia != 'I' 
                                              and idtiposolicitacao != ".$registro['idtiposolicitacao']."
                                              and not exists(select p.idtiposolicitacao 
                                                             from lda_tiposolicitacao p 
                                                             where p.idtiposolicitacao_seguinte = t.idtiposolicitacao
                                                                   and p.idtiposolicitacao != ".$registro['idtiposolicitacao'].")";
                                echo $qry;
                                $rsInst = execQuery($qry);
                                while($rowInst = mysql_fetch_array($rsInst)){
                                    ?><option value="<?php echo $rowInst['idtiposolicitacao'];?>" <?php echo ($registro['idtiposolicitacao_seguinte']==$rowInst['idtiposolicitacao'])?"selected":"";?>><?php echo $rowInst['nome'];?></option><?php
                                }
                            ?>
                        </select>
                        <a href="javascript: cancelaordenacao('<?php echo $registro["idtiposolicitacao"]; ?>');">[Cancelar]</a>
                        
                    </span>
		</td>			
	</tr>
	<?php 
  } 
  
  ?>
  <tr>
	<td align="right" colspan="7">
		<?php 
			$param="";
			include("../inc/paginacaoFim.php");
		?>
	</td>
  </tr>    
</table>

<br><br>
<input type="button" value="Voltar" class="botaoformulario" name="voltar" id="voltar" onclick="location.href='../inc/menu.php'" />

<?php
  include "../inc/rodape.php";
?>