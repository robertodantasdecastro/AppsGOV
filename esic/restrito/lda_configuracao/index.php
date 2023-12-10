<?php
/**********************************************************************************
 Sistema e-SIC Livre: sistema de acesso a informação baseado na lei de acesso.
 
 Copyright (C) 2014 Prefeitura Municipal do Natal
 
 Este programa é software livre; você pode redistribuí-lo e/ou
 modificá-lo sob os termos da Licença GPL2.
***********************************************************************************/

include "manutencao.php";
include "../inc/topo.php";


?>
<h1>Configuração do Lei de Acesso</h1>
<br>

<form method="post" action="index.php">
<table class="tabDetalhe">
  <tr>
    <th align="left">Prazo, em dias, para resposta da solicitação:</th>
    <td>
        <input type="text" name="prazoresposta" value="<?php echo $prazoresposta;?>" maxlength="4" size="5" id="prazoresposta" /> dias
    </td>
  </tr>
  <tr>
    <th align="left">Quantidade de dias que podera ser prorrogada a resposta da solicitação:</th>
    <td>
	<input type="text" name="qtdprorrogacaoresposta" value="<?php echo $qtdprorrogacaoresposta;?>" maxlength="4" size="5" id="qtdprorrogacaoresposta" /> dias
    </td>
  </tr>
  <tr>
    <th align="left">Prazo, em dias, para solicitação de recurso após a resposta negada:</th>
    <td>
	<input type="text" name="prazosolicitacaorecurso" value="<?php echo $prazosolicitacaorecurso;?>" maxlength="4" size="5" id="prazosolicitacaorecurso" /> dias
    </td>
  </tr>
  <tr>
    <th align="left">Prazo, em dias, para resposta ao recurso:</th>
    <td>
	<input type="text" name="prazorespostarecurso" value="<?php echo $prazorespostarecurso;?>" maxlength="4" size="5" id="prazorespostarecurso" /> dias
    </td>
  </tr>
  <tr>
    <th align="left">Quantidade de dias que poderá ser prorrogada resposta ao recurso:</th>
    <td>
	<input type="text" name="qtdeprorrogacaorecurso" value="<?php echo $qtdeprorrogacaorecurso;?>" maxlength="4" size="5" id="qtdeprorrogacaorecurso" /> dias
    </td>
  </tr>
  <tr>
    <th align="left">URL de acesso aos anexos do sistema:</th>
    <td>
	<input type="text" name="urlarquivos" value="<?php echo $urlarquivos;?>" maxlength="300" size="50" id="urlarquivos" /> 
    </td>
  </tr>
  <tr>
    <th align="left">Diretório onde será armazenado os anexos do sistema:</th>
    <td>
	<input type="text" name="diretorioarquivos" value="<?php echo $diretorioarquivos;?>" maxlength="300" size="50" id="diretorioarquivos" /> 
    </td>
  </tr>
  <tr>
    <th align="left">Nome do remetente dos e-mails que serão enviados pelo sistema:</th>
    <td>
	<input type="text" name="nomeremetenteemail" value="<?php echo $nomeremetenteemail;?>" maxlength="100" size="50" id="nomeremetenteemail" /> 
    </td>
  </tr>
  <tr>
    <th align="left">E-mail do remetente para envio de e-mails pelo sistema:</th>
    <td>
	<input type="text" name="emailremetente" value="<?php echo $emailremetente;?>" maxlength="100" size="50" id="emailremetente" /> 
    </td>
  </tr>
  <tr>
     <td colspan="2" align="center">
         <input type="submit" class="botaoformulario" value="Salvar" name="acao" id="acao" />
         <input type="button" class="botaoformulario" value="Voltar" name="voltar" id="voltar" onclick="location.href='../inc/menu.php'" />
     </td>
  </tr>
</table>
</form>

<?php
  getErro($erro);
  include "../inc/rodape.php";
?>