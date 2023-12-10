<?php
/*
COPYRIGHT 2008 - 2010 DO PORTAL PUBLICO INFORMATICA LTDA

Este arquivo e parte do programa E-ISS / SEP-ISS

O E-ISS / SEP-ISS e um software livre; voce pode redistribui-lo e/ou modifica-lo
dentro dos termos da Licenca Publica Geral GNU como publicada pela Fundacao do
Software Livre - FSF; na versao 2 da Licenca

Este sistema e distribuido na esperanca de ser util, mas SEM NENHUMA GARANTIA,
sem uma garantia implicita de ADEQUACAO a qualquer MERCADO ou APLICACAO EM PARTICULAR
Veja a Licenca Publica Geral GNU/GPL em portugues para maiores detalhes

Voce deve ter recebido uma copia da Licenca Publica Geral GNU, sob o titulo LICENCA.txt,
junto com este sistema, se nao, acesse o Portal do Software Publico Brasileiro no endereco
www.softwarepublico.gov.br, ou escreva para a Fundacao do Software Livre Inc., 51 Franklin St,
Fith Floor, Boston, MA 02110-1301, USA
*/
?>
<input name="hdInputs" id="hdInputs" type="hidden" value="1" />
<input name="hdCodemissor" id="hdCodemissor" type="hidden" value="<?php echo $CODIGO_DA_EMPRESA;?>" />
<input name="hdLimite" id="hdLimite" type="hidden" value="<?php echo mysql_num_rows($sql_servicos);?>"  />
<table width="100%" style="border:1px solid #FFFFFF" id="tblServicos" cellpadding="3">
	<tr><td>
	<div style="width:100%">
	<table width="100%">
		<tr align="center" bgcolor="#999999">
			<td width="33%" align="center"><b>Seleciona o Servi�o</b></td>
			<td width="23%" align="center"><b>Base Calc.(R$)</b></td>
			<td width="15%" align="center"><b>Aliquota(%)</b></td>
			<td width="13%" align="center"><b>ISS(R$)</b></td>
			<td width="16%" align="center"><b>ISSRetido(R$)</b></td>
		</tr>
		<tr bgcolor="#FFFFFF" align="center">
			<td>
				<select name="cmbCodServico1" style="width:200px;" id="cmbCodServico1" 
					onchange="MostraAliquota('txtAliqServico1','txtISSRetidoManual1','1');calculaISSNfe('hdInputs','1');">
						<option value="0">Selecione o Servi�o</option>	   	        
						<?php 
							while(list($codigo_empresas_servicos,$codigo,$codservico,$descricao,$aliquota,$issretido,$basecalculorpa)=mysql_fetch_array($sql_servicos))
								{	   
									print("<option value=\"$aliquota|$codigo|$issretido\"> $descricao </option>");
								}	
						?>
				</select>
			</td>
			<td>
				<input name="txtBaseCalcServico1" id="txtBaseCalcServico1" type="text" class="texto" size="10" onkeyup="MaskMoeda(this);" 
				onkeydown="return NumbersOnly(event);" 
				onblur="calculaISSNfe('hdInputs','1');" value="0,00"/>
				<font color="#FF0000">*</font>
			</td>
			<td><input name="txtAliqServico1" id="txtAliqServico1" type="text" class="texto" size="5" readonly="readonly" value="0.00" /></td>
			<td><input name="txtValorIssServico1" id="txtValorIssServico1" type="text" class="texto" size="8" readonly="readonly" value="0,00" /></td>
			<td>
				<input name="txtISSRetidoManual1" id="txtISSRetidoManual1" type="text" class="texto" size="8" value="0,00" onblur="calculaISSNfe('hdInputs','1')" 
				onkeyup="MaskMoeda(this);" onkeydown="return NumbersOnly(event);" />
			</td>
		</tr>
	</table>
	</div>
	<div id="retornoDivLinha" style="width:100%"></div>
	</td></tr>
</table>
<table width="100%" style="border:1px solid #FFFFFF">
	<tr>
		<td width="93%" align="right">
			<input name="btAdicionar" id="btAdicionar" type="button" class="botao" value="Adicionar" onclick="servicoNota('adicionar','retornoDivLinha')" />&nbsp;
			<input name="btRemover" id="btRemover" type="button" class="botao" value="Remover" onclick="servicoNota('remover','retornoDivLinha')" disabled="disabled" />
		</td>
	</tr>
	<tr>
		<td align="left"><b>Observa��es da nota: </b></td>
	</tr>
	<tr>
		<td align="center"><textarea name="txtObsNota" rows="3" cols="50"></textarea></td>
	</tr>
</table>

<table width="100%">
 <!-- busca a relacao dos servicos por empresa -->
  <tr>
    <td align="left">Valor Total das Dedu��es <?php //print ("$codigo"); ?></td>
    <td align="left">R$ 
	    <input name="txtValorDeducoes" type="text" size="12" class="texto" id="txtValorDeducoes"  style="text-align:right;" value="0,00"
    	 onkeydown="MaskMoeda(this); return NumbersOnly(event);" onblur="<?php print ("ValorIss('$regras_credito')");?>">
	<em>exemplo: 1.912,55</em></td>
  </tr>


  <tr>
    <td align="left">Total Base de C�lculo</td>
    <td align="left">R$<?php print("
	<input name=\"txtBaseCalculo\" type=\"text\" size=\"10\" class=\"texto\" id=\"txtBaseCalculo\" style=\"text-align:right;\" onkeyup=\"MaskMoeda(this);\" onkeydown=\"return NumbersOnly(event);\"
	onblur=\"ValorIss('$regras_credito')\" readonly=\"readonly\">");?><em>exemplo: 1912.55</em>
	<input name="txtBaseCalculoAux" type="hidden" id="txtBaseCalculoAux" />
	<input name="hdCalculos" id="hdCalculos" type="hidden" />
	<input name="hdValorInicial" id="hdValorInicial" type="hidden" />
	<input name="txtCredito" id="txtCredito" type="hidden" value="0" />
	<input  id="txtAliquota" name="txtAliquota" type="hidden" size="5" class="texto" readonly="yes" style="text-align:right;" value="0.00"> %
	</td>
  </tr>
   <tr>
   	<td colspan="8">
		<fieldset><legend><b>ISS</b></legend>
			<table width="100%">
				  <tr>
					<td width="31%" align="left"> 
					 Total do ISS
					</td>
					<td width="69%" align="left">R$ <input name="txtISS" type="text" size="7" class="texto" readonly="yes" style="text-align:right;" id="txtISS"></td>
			    </tr>	 
				  <tr>
					<td align="left"> 
					 Valor do ISS Retido    
					</td>
					<td align="left">
					 R$ 
					 <input id="txtIssRetido" name="txtIssRetido" type="text" size="7" class="texto" readonly="yes" value="0" style="text-align:right;" />	 
					</td>
				  </tr>	 
			 </table>
		</fieldset>	 
		  <fieldset><legend><b>INSS</b></legend>
		  	<table width="100%">
				<tr>
					<td width="31%" align="left">
						 <font color="#FF0000">**</font>% base de calculo
					</td>
					<td width="69%" align="left">
						<input name="txtINSSBCpct" id="txtINSSBCpct" type="text" size="4" class="texto" onblur="baseCalcPct('INSS')" onkeyup="MaskPercent(this)" /> % 
						= 
						R$ <input name="txtINSSBC" id="txtINSSBC" type="text" class="texto" size="10" readonly="readonly" style="text-align:right;" />
					</td>
				</tr>
				<tr>
					<td>INSS sobre a base de calculo</td>
					<td align="left">	
						<input name="txtAliquotaINSS" id="txtAliquotaINSS" type="text" size="4" class="texto" style="text-align:right;" onkeyup="MaskPercent(this)" 
						onblur="document.getElementById('txtBaseCalculo').onblur();" > %
						= 
						R$	 
						<input id="txtValorINSS" name="txtValorINSS" type="text" size="10" class="texto" readonly="yes" value="0" style="text-align:right;" disabled="disabled"/>	 
					</td>
					
				<!--</tr> 
				   <tr> 
					<td align="left"><input type="checkbox" id="INSSManual" onclick="inssmanual()" value="8" />Reter INSS manualmente</td>
					<td align="left">
					 <div id="DivINSSRetido" style="display:none;">%  &nbsp;
					  <input type="text" class="texto" size="6" id="txtPinssretido" <?php //print"onblur=\"CalculaINSS()\"";?> onkeyup="MaskPercent(this)" />
					  
					 </div>
						</td>
				  </tr>	 
				  -->
			  </table>
			</fieldset>
			<fieldset><legend><b>IRRF</b></legend>
				<table width="100%">
					<tr>
						<td width="31%" align="left">
							 <font color="#FF0000">**</font>% base de calculo
						</td>
						<td width="69%" align="left">
							<input name="txtIRRFBCpct" id="txtIRRFBCpct" type="text" size="4" class="texto" onblur="baseCalcPct('IRRF')" onkeyup="MaskPercent(this)" /> % 
							= 
							R$ <input name="txtIRRFBC" id="txtIRRFBC" type="text" class="texto" size="10" style="text-align:right;" readonly="readonly" />
						</td>
					</tr>
					<tr>
						<td width="31%" align="left">
							Al�quota de IRRF
						</td>
						<td width="69%" align="left">	
							<input name="txtIRRF" id="txtIRRF" type="text" size="4" class="texto" style="text-align:right;" onkeyup="MaskPercent(this)" 
							onblur="document.getElementById('txtBaseCalculo').onblur();" > % = 
							
							R$	 
							<input id="txtValorIRRF" name="txtValorIRRF" type="text" size="10" class="texto" readonly="yes" value="0" style="text-align:right;" disabled="disabled"/>	 
						</td>
					</tr>
					<tr>
						<td width="31%" align="left">
							Dedu��o de 
						</td>
						<td width="69%" align="left">	
							R$
							<input name="txtDeducIRRF" id="txtDeducIRRF" type="text" size="10" class="texto" style="text-align:right;" onkeyup="MaskMoeda(this);"
							onblur="document.getElementById('txtBaseCalculo').onblur();" disabled="disabled" >  = 
							
							R$	 
							<input id="txtValorFinalIRRF" name="txtValorFinalIRRF" type="text" size="10" class="texto" style="text-align:right;" readonly="readonly"/>	 
						</td>
					</tr>
				</table>
			</fieldset> 
			<!--<fieldset><legend><b>IR</b></legend>
				<table width="100%">
				   <tr> 
					<td align="left"><input type="checkbox" id="IRManual" onclick="irmanual()" value="8" />Reter IR manualmente</td>
					<td align="left">
						<div id="DivIRRetido" style="display:none;">%  &nbsp;
							<input type="text" class="texto" size="6" id="txtPirretido" <?php //print"onblur=\"CalculaIR()\"";?> style="text-align:left;" 
							onkeyup="MaskPercent(this)" />						
						</div>
					</td>
				  </tr>	 
			  </table>
		  </fieldset>
		  -->
	</td>
  </tr>
  
  <tr>
    <td align="left">Valor Total da Nota</td>
    <td align="left">R$ <input name="txtValTotal" id="txtValTotal" type="text" size="10" class="texto" readonly="yes" style="text-align:right;">
	Valor total da reten��o R$ <input name="txtValTotalRetencao" id="txtValTotalRetencao" type="text" class="texto" size="10" readonly="readonly" style="text-align:right" />
	</td>
  </tr>  
  <tr>
	<td  align="left" colspan="2">
		<input name="btInserirNota" type="submit" value="Emitir" class="botao" onclick="return ValidaFormulario('txtBaseCalculo|txtTomadorCNPJ|txtTomadorNome')">
	</td>	
  </tr>  
</table>
</form>
</fieldset>