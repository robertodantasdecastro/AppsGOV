<?php
/**********************************************************************************
 Sistema e-SIC Livre: sistema de acesso a informação baseado na lei de acesso.
 
 Copyright (C) 2014 Prefeitura Municipal do Natal
 
 Este programa é software livre; você pode redistribuí-lo e/ou
 modificá-lo sob os termos da Licença GPL2.
***********************************************************************************/

	include_once "manutencaousuarios.php";
	include_once "../inc/topo.php";
	
	getErro($erro);	
		
?>
<script>
	function adicionaItem(campoOrig,campoDest) 
	{
		x = campoOrig.value;
		
		if (x == "")
		{
			alert('Selecione um item!');
		}
		
		var len = campoDest.length;
		
		for(var i = 0; i < campoOrig.length; i++) 
		{
			if ((campoOrig.options[i] != null) && 
				  (campoOrig.options[i].selected)) 
			{
				
				campoDest.options[len] = new Option(campoOrig.options[i].text, campoOrig.options[i].value); 
				len++;
				campoOrig.options[i] = null;  
				i--;
			}
		}
		
		sortSelect(campoOrig);
		sortSelect(campoDest);
	}

        function addEmail(email)
        {
            campoDest = document.getElementById('participantes');
            
            var len = campoDest.length;
            campoDest.options[len] = new Option(email, email); 
            
            sortSelect(campoDest);
        }
        
	function sortSelect(obj){
		var o = new Array();
		for (var i=0; i<obj.options.length; i++){
			o[o.length] = new Option(obj.options[i].text, obj.options[i].value, obj.options[i].defaultSelected, obj.options[i].selected);
		}
		o = o.sort(
			function(a,b){ 
				if ((a.text+"") < (b.text+"")) { return -1; }
				if ((a.text+"") > (b.text+"")) { return 1; }
				return 0;
			} 
		);

		for (var i=0; i<o.length; i++){
			obj.options[i] = new Option(o[i].text, o[i].value, o[i].defaultSelected, o[i].selected);
		}
	}
	
	function selecionatudo(obj)
	{
        
		var selecionados = document.getElementById(obj);
		
		for(i=0; i<=selecionados.length-1; i++){
		
				selecionados.options[i].selected = true;
		
                }
        
        }
</script>

<h1>Usuários do Perfil <?php echo $nomegrupo?></h1>
<br><br>
<form action="usuarios.php" method="post" id="formulario">
<input type="hidden" name="idgrupo" value="<?php echo $idgrupo;?>">
<input type="hidden" id="nomegrupo" name="nomegrupo" value="<?php echo $nomegrupo;?>">
<table class="tabLista">
    <tr>
        <td colspan="2">
            <table border="0" width="100%">
                <tr>
                    <td valign="top" align="center">
                        Usuários<br>
                        <select name="usuarios" id="usuarios" title="Dê um duplo clique para selecionar todos" ondblclick="selecionatudo(this.id);" multiple="multiple" style="height: 300px; width: 300px; font-size:10">
                        <?php
                            $sql="select login from sis_usuario u where status = 'A' order by login";
                            
                            $rs = execQuery($sql);

                            while ($row = mysql_fetch_array($rs)) { 
                                if(!estaSelecionado($row['login']))
                                {
                                    ?><option value="<?php echo $row['login']; ?>" ><?php echo $row['login']; ?></option><?php
                                }
                            }
?>
                        </select>		
                    </td>
                    <td valign="middle" align="center">
                        <center>
                        <input type="button" value=">>" title="Selecionar" onclick="adicionaItem(document.getElementById('usuarios'),document.getElementById('selecionados'));"><br><br>
                        <input type="button" value="<<" title="Retirar" onclick="adicionaItem(document.getElementById('selecionados'),document.getElementById('usuarios'));">
                        </center>
                    </td>
                    <td valign="top" align="center">
                        Usuários do Perfil<br>
                        <select name="selecionados[]" id="selecionados" title="Dê um duplo clique para selecionar todos" ondblclick="selecionatudo(this.id);" multiple="multiple" style="height: 300px; width: 300px; font-size:10">
                        <?php foreach ($selecionados as $user) { ?>
                                <option value="<?php echo $user; ?>" selected>
                                        <?php echo $user; ?>
                                </option>
                        <?php } ?>
                        </select>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="2" align="center">
            <br>
            <input type="submit" class="botaoformulario"  value="<?php echo $acao;?>" name="acao" onclick="selecionatudo('selecionados');" />
            <input type="button" class="botaoformulario"  onClick="document.location = 'index.php';" value="Voltar">   
        </td>
    </tr>
</table>
</form>

<?php
  	include "../inc/rodape.php";
?>