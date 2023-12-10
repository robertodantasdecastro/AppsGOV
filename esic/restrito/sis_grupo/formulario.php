<?php
/**********************************************************************************
 Sistema e-SIC Livre: sistema de acesso a informação baseado na lei de acesso.
 
 Copyright (C) 2014 Prefeitura Municipal do Natal
 
 Este programa é software livre; você pode redistribuí-lo e/ou
 modificá-lo sob os termos da Licença GPL2.
***********************************************************************************/


//retorna erro de uma ação se houver (em alert do javascript) 
getErro($erro);

?>
<script>
	function edita(idgrupo,nome,descricao, ativo){
		document.getElementById("nome").value = nome;
		document.getElementById("idgrupo").value = idgrupo;
		document.getElementById("descricao").value = descricao;
		document.getElementById("ativo").value = (ativo=="2")?"0":"1";
		document.getElementById("acao").value = "Alterar";
		document.getElementById("nome").focus();
	}
	
        function limpa(){
		document.getElementById("nome").value = "";
		document.getElementById("idgrupo").value = "";
		document.getElementById("descricao").value = "";
		document.getElementById("ativo").value = "";
		document.getElementById("acao").value = "Incluir";
		document.getElementById("nome").focus();
	}        
</script>

<form method="post">
<input type="hidden" name="idgrupo" value="<?php echo $idgrupo;?>" id="idgrupo">
<table class="lista">
  <tr>
    <td>Nome:</td>
    <td>
	<input type="text" name="nome" value="<?php echo $nome;?>" maxlength="30" size="30" id="nome" />
    </td>
  </tr>
  <tr>
    <td>Descrição:</td>
    <td>
	<input type="text" name="descricao" value="<?php echo $descricao;?>" maxlength="70" size="50" id="descricao" />
    </td>
  </tr>
  <tr>
    <td>Status:</td>
    <td>
	<select name="ativo" id="ativo">
            <option value="" <?php echo (empty($ativo))?"selected":""; ?>>---</option>
            <option value="1" <?php echo ($ativo=="1")?"selected":""; ?>>Ativo</option>
            <option value="2" <?php echo ($ativo=="2")?"selected":""; ?>>Inativo</option>
        </select>
	</td>
  </tr>
  <tr>
	<td align="center" colspan="2">
		<input type="submit" value="buscar" class="botaoformulario" name="buscar" id="buscar" />
		<input type="submit" value="<?php echo $acao;?>" class="botaoformulario" name="acao" id="acao" />
		<input type="button" value="Limpar" name="limpar" class="botaoformulario" onclick="limpa()" />
	</td>	
  </tr>
</table>
</form>

<script>
	//seta o foco
	document.getElementById("nome").focus();
</script>