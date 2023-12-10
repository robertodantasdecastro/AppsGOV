<?php
/**********************************************************************************
 Sistema e-SIC Livre: sistema de acesso a informação baseado na lei de acesso.
 
 Copyright (C) 2014 Prefeitura Municipal do Natal
 
 Este programa é software livre; você pode redistribuí-lo e/ou
 modificá-lo sob os termos da Licença GPL2.
***********************************************************************************/

  include("../inc/autenticar.php");  
  checkPerm("LSTUSR");
    
  include "../inc/topo.php";
  include("../inc/paginacaoPorPostIni.php");

  $filtro = "";
  if (($_REQUEST['acao']))
  {
		$nome 			= $_REQUEST["nome"];
		$login			= $_REQUEST["login"];
		$matricula		= $_REQUEST["matricula"];
		$cpfusuario		= $_REQUEST["cpfusuario"];
		$idsecretaria           = $_REQUEST["idsecretaria"];
		$status 		= $_REQUEST["status"];
		
		if(!empty($nome)) $filtro.= " and usuario.nome like '%$nome%'";
		if(!empty($login)) $filtro.= " and usuario.login like '%$login%'";
		if(!empty($matricula)) $filtro.= " and usuario.matricula = '$matricula'";
		if(!empty($cpfusuario)) $filtro.= " and usuario.cpfusuario = '$cpfusuario'";
		if(!empty($status)) $filtro.= " and usuario.status = '$status'";
		if(!empty($idsecretaria)) $filtro.= " and usuario.idsecretaria = '$idsecretaria'";		
  }			
  
  $sql = "select usuario.*, sigla 
		  from sis_usuario usuario, sis_secretaria secretaria 
		  where usuario.idsecretaria = secretaria.idsecretaria $filtro ";

  $rs = execQueryPag($sql);
?>
<script>
	function mudarStatus(id,status)
	{
		document.location = "ativacao.php?idusuario="+id+"&status="+status;
	}
</script>
<h1>Cadastro de Usu&aacute;rios</h1>
<br><br>
<form action="index.php" method="post">
<fieldset style="width: 80%;">
<legend>Buscar:</legend>
<table class="lista">
  <tr>
    <td>Nome:</td>
    <td>
		<input type="text" name="nome" value="<?php echo $nome?>" maxlength="50" size="20" />
		Login:
		<input type="text" name="login" value="<?php echo $login?>" maxlength="50" size="20" />
		CPF:
		<input type="text" name="cpfusuario" value="<?php echo $cpfusuario?>" maxlength="11" size="12" />
		Matricula:
		<input type="text" name="matricula" value="<?php echo $matricula?>" maxlength="6" size="8" />
	</td>
  </tr>
  <tr>
    <td>Status:</td>	
    <td>
		<select name="status">
                    <option value="">--status--</option>
                    <option value="A" <?php echo ($status=="A")?"selected":""; ?>>Ativo</option>
                    <option value="I" <?php echo ($status=="I")?"selected":""; ?>>Inativo</option>
                </select>		
		SIC:
		<select name="idsecretaria">
			<option value="">--sic--</option>
			<?php
			$rsSec = execQuery("select * from sis_secretaria order by sigla");
			while($registro = mysql_fetch_array($rsSec)){
				?>
				  <option value="<?php echo $registro["idsecretaria"]; ?>" <?php echo ($idsecretaria==$registro["idsecretaria"])?"selected":""; ?>>
					<?php echo $registro["sigla"]; ?>
				  </option>
				<?php
			}
			?>
		</select>
	</td>
  </tr>
  <tr>
    <td colspan="2" align="center">
		<br>
		<input type="submit" class="botaoformulario" value="Buscar" name="acao" />
		<input type="button" class="botaoformulario" onClick="document.location = 'cadastro.php';" value="Adicionar">
	</td>
  </tr>
</table>
</fieldset>		


<br>
<table class="tabLista">
  <tr>
    <th></th>
	<th>Codigo</th>
        <th>Usu&aacute;rio</th>
	<th>CPF</th>
	<th>Matricula</th>
        <th>Login</th>
        <th>SIC</th>
        <th>Status</th>
  </tr>
 <?php
  $cor = false;
  while($registro = mysql_fetch_array($rs)){
        $click = "editar('".$registro["idusuario"]."')";
        if($cor)
            $corLinha = "#dddddd";
        else
            $corLinha = "#ffffff";

        $cor = !$cor;
	?>
        <tr onMouseOver="this.style.backgroundColor = getCorSelecao(true);" onMouseOut="this.style.backgroundColor = '<?php echo $corLinha;?>';" style="background-color:<?php echo $corLinha;?>;cursor:pointer; cursor:hand; ">
                <td><img src="../img/drop.png" title="Excluir Registro" onClick="apagar('<?php echo $registro["idusuario"]; ?>');"/></td>  
                <td onClick="<?php echo $click;?>"><?php echo $registro["idusuario"];?></td>
                <td onClick="<?php echo $click;?>"><?php echo destacaBusca($registro["nome"],$nome); ?></td>
                <td onClick="<?php echo $click;?>"><?php echo destacaBusca($registro["cpfusuario"],$cpfusuario); ?></td>
                <td onClick="<?php echo $click;?>"><?php echo destacaBusca($registro["matricula"],$matricula); ?></td>
                <td onClick="<?php echo $click;?>"><?php echo $registro["login"]; ?></td>
                <td onClick="<?php echo $click;?>"><?php echo $registro["sigla"]; ?></td>
                <td align="center"><input type="button" value="<?php echo ($registro["status"]=="A")?"Ativo - Clique para Desativar":"Inativo - Clique para Ativar";?>" onClick="javascript:mudarStatus('<?php echo $registro["idusuario"];?>','<?php echo ($registro["status"]=="A")?"I":"A";?>');"/></td>
        </tr>
        <?php 
	} ?>
  <tr>
    <td align="right" colspan="8">
        <?php include("../inc/paginacaoPorPostFim.php");?>
    </td>
  <tr>
  <tr>
    <td colspan="8" align="center">
	<input type="button" class="botaoformulario" onClick="document.location = 'cadastro.php';" value="Adicionar">
        <input type="button" value="Voltar" class="botaoformulario"  name="voltar" id="voltar" onclick="location.href='../inc/menu.php'" />
    </td>
  </tr>
</table>
</form>
<?php
  include "../inc/rodape.php";
?>