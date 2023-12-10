<?php
/**********************************************************************************
 Sistema e-SIC Livre: sistema de acesso a informação baseado na lei de acesso.
 
 Copyright (C) 2014 Prefeitura Municipal do Natal
 
 Este programa é software livre; você pode redistribuí-lo e/ou
 modificá-lo sob os termos da Licença GPL2.
***********************************************************************************/

 include "manutencao.php";
 include "../inc/topo.php";
 
 getErro($erro);
 
?>

<h1>Cadastro de SIC</h1>
<br><br>
<form action="cadastro.php" method="post" >
<input type="hidden" name="idsecretaria" value="<?php echo $idsecretaria;?>">
<table class="tabLista">
  <tr>
    <td><b>Nome:</b></td>
    <td><input type="text" name="nome" value="<?php echo $nome;?>" maxlength="100" size="50" /></td>
  </tr>
  <tr>
    <td><b>Sigla:</b></td>
    <td><input type="text" name="sigla" value="<?php echo $sigla;?>" maxlength="30" size="50" /></td>
  </tr>  
  <tr>
    <td>Responsável:</td>
    <td><input type="text" name="responsavel" value="<?php echo $responsavel;?>" maxlength="50" size="50" /></td>
  </tr>  
  <tr>
    <td>Telefone:</td>
    <td><input type="text" name="telefonecontato" value="<?php echo $telefonecontato;?>" maxlength="20" size="50" /></td>
  </tr>      
  <tr>
    <td>E-mail SIC:</td>
    <td>
        <input type="text" name="emailsic" value="<?php echo $emailsic;?>" maxlength="100" size="50" />
    </td>
  </tr>    
  <tr>
      <td colspan="2"><input type="checkbox" name="siccentral" value="1" <?php echo ($siccentral)?"checked":"";?>> É um SIC centralizador (recebe as demandas recém cadastradas)</td>
  </tr>
  <tr>
      <td><b>Status:</b></td>
    <td>
        <select name="ativado" id="ativado">
            <option value="1" <?php echo ($ativado) == "1"?"selected":""; ?>>Ativo</option>
            <option value="0" <?php echo ($ativado) == "0" ?"selected":""; ?>>Inativo</option>
        </select>
    </td>
  </tr>  
  <tr>
    <td colspan="2" align="center">
        <br>
        <input type="submit" class="botaoformulario" value="<?php echo $acao;?>" name="<?php echo $acao;?>" />
        <input type="button" class="botaoformulario" onClick="document.location = 'index.php';" value="Voltar">   
    </td>
  </tr>
</table>
</form>

<?
include "../inc/rodape.php";
?>