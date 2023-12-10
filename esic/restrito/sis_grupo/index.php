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


if(!empty($nome)) $filtro .= " and sis_grupo.nome like '%$nome%' ";
if(!empty($descricao)) $filtro .= " and sis_grupo.descricao like '%$descricao%' ";
if(!empty($ativo)) $filtro .= " and sis_grupo.ativo = ".(($ativo=="2")?"0":"1")." ";


$sql = "select *
        from sis_grupo
	where 1=1 $filtro order by nome";

$rs = execQueryPag($sql);

?>
<h1>Perfis</h1>
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
	<th>Descrição</th>
        <th>Status</th>
	<th>Acessos</th>
  </tr>
  <?php
  $cor = false;
  while($registro = mysql_fetch_array($rs)){
	$click = "edita('".$registro["idgrupo"]."','".$registro["nome"]."','".$registro["descricao"]."','".$registro["idsecretaria"]."','".$registro["ativo"]."')";
        
        if($cor)
            $corLinha = "#dddddd";
        else
            $corLinha = "#ffffff";
        $cor = !$cor;

	?>
	<tr onMouseOver="this.style.backgroundColor = getCorSelecao(true);" onMouseOut="this.style.backgroundColor = '<?php echo $corLinha;?>';" style="background-color:<?php echo $corLinha;?>;cursor:pointer; cursor:hand; ">
		<td><img src="../img/drop.png" title="Excluir Registro" onClick="apagar('<?php echo $registro["idgrupo"]; ?>');"/></td>
		<td align="left" onClick="<?php echo $click;?>"><?php echo $registro["idgrupo"]; ?></td>
		<td align="left" onClick="<?php echo $click;?>"><?php echo $registro["nome"]; ?></td>
		<td align="left" onClick="<?php echo $click;?>"><?php echo $registro["descricao"]; ?></td>
		<td onClick="<?php echo $click;?>"><?php echo $registro["ativo"]? "Ativo": "Inativo" ; ?></td>
		<td align="center">
			<input type="button" class="botaoformulario"  value="Permissoes" onClick="javascript:document.location='grupoperm.php?idgrupo=<?php echo $registro["idgrupo"];?>&grupo=<?php echo $registro["nome"]; ?>';"/>
			<input type="button" class="botaoformulario"  value="Usuarios" onClick="javascript:document.location='usuarios.php?idgrupo=<?php echo $registro["idgrupo"];?>';"/>
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