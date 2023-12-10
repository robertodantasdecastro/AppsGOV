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
	function edita(idtiposolicitacao,nome,instancia){
		document.getElementById("nome").value = nome;
		document.getElementById("idtiposolicitacao").value = idtiposolicitacao;
		document.getElementById("instancia").value = instancia;
		document.getElementById("acao").value = "Alterar";
		document.getElementById("nome").focus();
	}

        function abreordenacao(campo)
        {
            document.getElementById('show_'+campo).style.display = "none";  
            document.getElementById('edit_'+campo).style.display = "";
        }

        function cancelaordenacao(campo)
        {
            document.getElementById('show_'+campo).style.display = "";  
            document.getElementById('edit_'+campo).style.display = "none";
        }

	function ordena(idtiposolicitacao,idtiposolicitacao_seguinte){
		document.getElementById("idtiposolicitacao").value = idtiposolicitacao;
                document.getElementById("idtiposolicitacao_seguinte").value = idtiposolicitacao_seguinte;
		document.getElementById("formulario").submit();
	}

        function limpa(){
		document.getElementById("nome").value = "";
		document.getElementById("idtiposolicitacao").value = "";
		document.getElementById("instancia").value = "";
		document.getElementById("acao").value = "Incluir";
		document.getElementById("nome").focus();
	}        
</script>

<form method="post" id="formulario">
<input type="hidden" name="idtiposolicitacao" value="<?php echo $idtiposolicitacao;?>" id="idtiposolicitacao">
<input type="hidden" name="idtiposolicitacao_seguinte" value="<?php echo $idtiposolicitacao_seguinte;?>" id="idtiposolicitacao_seguinte">
<table class="lista">
  <tr>
    <td>Nome:</td>
    <td>
	<input type="text" name="nome" value="<?php echo $nome;?>" maxlength="50" size="50" id="nome" />
    </td>
  </tr>
  <tr>
    <td>Instância:</td>
    <td>
	<select name="instancia" id="instancia">
            <option value="" <?php echo (empty($instancia))?"selected":""; ?>>---</option>
            <option value="I" <?php echo ($instancia=="I")?"selected":""; ?>><?php echo Solicitacao::getDescricaoTipoInstancia("I");?></option>
            <option value="S" <?php echo ($instancia=="S")?"selected":""; ?>><?php echo Solicitacao::getDescricaoTipoInstancia("S");?></option>
            <option value="U" <?php echo ($instancia=="U")?"selected":""; ?>><?php echo Solicitacao::getDescricaoTipoInstancia("U");?></option>
        </select>
	</td>
  </tr>
  <tr>
	<td align="center" colspan="2">
            <br>
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