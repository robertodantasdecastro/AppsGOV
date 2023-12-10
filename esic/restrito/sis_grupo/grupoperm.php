<?php
/**********************************************************************************
 Sistema e-SIC Livre: sistema de acesso a informação baseado na lei de acesso.
 
 Copyright (C) 2014 Prefeitura Municipal do Natal
 
 Este programa é software livre; você pode redistribuí-lo e/ou
 modificá-lo sob os termos da Licença GPL2.
***********************************************************************************/

include "../inc/autenticar.php";

checkPerm("CHPERM");

if ($_GET["idgrupo"] and !$_POST["updt"]) {
	include "../inc/topo.php";
	$idgrupo = $_GET["idgrupo"];
        $nomegrupo = $_GET["nomegrupo"];
        
	$query = "select a.idacao, 
                        a.denominacao, 
                        p.idpermissao,
                        t.nome as tela,
                        m.nome as menu,
                        t.idtela,
                        m.idmenu
                    from 
                          sis_acao a
                    join  sis_tela t on t.idtela = a.idtela
                    join  sis_menu m on m.idmenu = t.idmenu
                    left join sis_permissao p on a.idacao = p.idacao and p.idgrupo = $idgrupo
                    where a.status = 'A'
                          and t.ativo = 1
                          and m.ativo = 1
                    order by m.idmenu, t.idtela, a.denominacao";
	
	$rs = execQuery($query);
	
	?>
	<style>
		.sistema{padding-left:0px; border-bottom:1px solid #000000;}
		.menu{padding-left:14px;}
		.tela{padding-left:27px;}
		.acao{padding-left:65px;}
		
		span {
			font-size: 9px; 
			font-family: Courier New;
			cursor: pointer; cursor: hand;
			border: 1px solid #c5c5c5;
			vertical-align: top;
		}
	</style>
	<script>
		function fecha(linha){
			
			if(document.getElementById('det_'+linha).style.display == "none"){
				document.getElementById('det_'+linha).style.display = "";
				document.getElementById(linha).innerHTML = "&nbsp;-&nbsp;";
			} else{
				document.getElementById('det_'+linha).style.display = "none";
				document.getElementById(linha).innerHTML = "&nbsp;+&nbsp;";
			}
		
		
		}
		
		function marcaTodos(checked,tab){
		   var elementos = document.getElementById("form1").elements;
		   
		   for (i=0;i<elementos.length;i++)
			  if(elementos[i].type == "checkbox")
				if(elementos[i].id.indexOf(tab) >= 0)
					elementos[i].checked=checked;
		} 
		
	
	</script>
	
	<h1>Permiss&otilde;es do Perfil <?php echo $nomegrupo; ?></h1><br>
	<form name="permissoes" method="post" action="<?php echo $PHP_SELF."?idgrupo=$idgrupo&grupo=$nomegrupo";?>" id="form1">
	<table cellspacing="0">
	<?php 
	$menu = "";
	$tela = "";
	$cont = 0;
	$contAcao = 0;
	while($row = mysql_fetch_array($rs)){

		if($cont>0){
			if($menu <> $row['idmenu']){
				?></td></tr></table><?php
			}
			if($tela <> $row['idtela']){
				?></td></tr></table><?php
			}
		}
	
		if($menu <> $row['idmenu']){
			?>
			<tr><td class="menu"><b><span id="menu_<?php echo $row['idmenu'];?>_" onclick="fecha(this.id)">&nbsp;+&nbsp;</span> <input type="checkbox" id="chk_sis_<?php echo $row['idsistema'];?>_"  onclick="marcaTodos(this.checked,'menu_<?php echo $row['idmenu'];?>_')"> <?php echo $row['menu'];?></b></td></tr>
			<tr id="det_menu_<?php echo $row['idmenu'];?>_" style="display:none"><td><table width="100%">
			<?php
		}
		if($tela <> $row['idtela']){
			?>
			<tr><td class="tela"><b><span id="tela_<?php echo $row['idtela'];?>_" onclick="fecha(this.id)">&nbsp;+&nbsp;</span> <input type="checkbox" id="chk_sis_<?php echo $row['idsistema'];?>_menu_<?php echo $row['idmenu'];?>_"  onclick="marcaTodos(this.checked,'tela_<?php echo $row['idtela'];?>_')"> <?php echo $row['tela'];?></b></td></tr>
			<tr id="det_tela_<?php echo $row['idtela'];?>_" style="display:none"><td class="acao"><table width="100%">
			<?php

			$contAcao = 0;
		}
		
		if (!empty($row["idpermissao"]))
			$checked = "checked";
		else
			$checked = "";
		
		if ($contAcao == 0)
			echo "<tr>";
		else if ($contAcao%4 == 0)
			echo "</tr><tr>";
			
		?><td style="padding-right:5px"><input type="checkbox" id="chk_sis_<?php echo $row['idsistema'];?>_menu_<?php echo $row['idmenu'];?>_tela_<?php echo $row['idtela'];?>_" name="acao[]" value="<?php echo $row["idacao"];?>" <?php echo $checked;?>><?php echo $row["denominacao"];?></td><?php
		?><!-- tr><td class="acao"><input type="checkbox" id="chk_sis_< ?php echo $row['idsistema'];?>_menu_< ?php echo $row['idmenu'];?>_tela_<?php echo $row['idtela'];?>" name="acao[]" value="<?php echo $row["idacao"];?>" < ?php echo $checked;?>>< ?php echo $row["denominacao"];?></td></tr --><?php
		
		$menu = $row['idmenu'];
		$tela = $row['idtela'];
		$cont++;
		$contAcao++;
	}?>
	</td></tr></table> <!-- fecha tab sistema -->
	</td></tr></table> <!-- fecha tab menu -->
	</td></tr></table> <!-- fecha tab tela -->	
	</table> <!-- fecha tab principal -->
	<br />
	<input type="submit" name="updt" value="Atualizar" class="botaoformulario" > <input type="button"  class="botaoformulario" value="Voltar" onclick="history.go(-1)"> 
	</form>

<?php } elseif ($_POST["updt"] and $_GET["idgrupo"]) {
	
	$idgrupo = $_GET["idgrupo"];
	$login = $_GET["login"];
	$acao = $_POST["acao"];
	$query = "delete from sis_permissao where idgrupo='$idgrupo'";
	execQuery($query) or die("Erro atualizando as permissoes");

	if (!empty($acao)) {
		foreach ($acao as $valor) {
			if (ctype_digit($valor)) {
				$query = "insert into sis_permissao(idacao,idgrupo) values('$valor','$idgrupo')";
				execQuery($query) or die("Erro ao atualizar as permissoes");
			} else {
				die("Valor inv&aacute;lido.".$valor);
			}
		}
	}
	
	logger("Modificou permissoes.");
	
	Redirect("index.php?idgrupo=$idgrupo&login=$login");	
} else {
	Redirect("../inc/index.php");
}
	
include "../inc/rodape.php";
?>
