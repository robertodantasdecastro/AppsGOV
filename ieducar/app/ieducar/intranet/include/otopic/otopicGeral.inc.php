<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
*																	     *
*	@author Prefeitura Municipal de Itaja�								 *
*	@updated 29/03/2007													 *
*   Pacote: i-PLB Software P�blico Livre e Brasileiro					 *
*																		 *
*	Copyright (C) 2006	PMI - Prefeitura Municipal de Itaja�			 *
*						ctima@itajai.sc.gov.br					    	 *
*																		 *
*	Este  programa  �  software livre, voc� pode redistribu�-lo e/ou	 *
*	modific�-lo sob os termos da Licen�a P�blica Geral GNU, conforme	 *
*	publicada pela Free  Software  Foundation,  tanto  a vers�o 2 da	 *
*	Licen�a   como  (a  seu  crit�rio)  qualquer  vers�o  mais  nova.	 *
*																		 *
*	Este programa  � distribu�do na expectativa de ser �til, mas SEM	 *
*	QUALQUER GARANTIA. Sem mesmo a garantia impl�cita de COMERCIALI-	 *
*	ZA��O  ou  de ADEQUA��O A QUALQUER PROP�SITO EM PARTICULAR. Con-	 *
*	sulte  a  Licen�a  P�blica  Geral  GNU para obter mais detalhes.	 *
*																		 *
*	Voc�  deve  ter  recebido uma c�pia da Licen�a P�blica Geral GNU	 *
*	junto  com  este  programa. Se n�o, escreva para a Free Software	 *
*	Foundation,  Inc.,  59  Temple  Place,  Suite  330,  Boston,  MA	 *
*	02111-1307, USA.													 *
*																		 *
* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	// arquivo que faz o require de todas as classes de otopic
	require_once( "include/otopic/clsTopico.inc.php" );	
	require_once( "include/otopic/clsTopicoReuniao.inc.php" );	
	require_once( "include/otopic/clsNotas.inc.php" );	
	require_once( "include/otopic/clsGrupos.inc.php" );	
	require_once( "include/otopic/clsGrupoPessoa.inc.php" );	
	require_once( "include/otopic/clsGrupoModerador.inc.php" );	
	require_once( "include/otopic/clsReuniao.inc.php" );	
	require_once( "include/otopic/clsParticipante.inc.php" );	
	require_once( "include/otopic/clsFuncionarioSu.inc.php" );	
	require_once( "include/otopic/clsAtendimento.inc.php" );	
	require_once( "include/otopic/clsAtendimentoPessoa.inc.php" );	
	require_once( "include/otopic/clsPessoaAuxiliar.inc.php" );	
	require_once( "include/otopic/clsPessoaObservacao.inc.php" );	
	require_once( "include/otopic/clsPessoaAuxiliarTelefone.inc.php" );	
	require_once( "include/otopic/clsSituacao.inc.php" );
	
	// includes de fora do otopic
	require_once( "include/clsEmail.inc.php" );	
	require_once( "include/clsMenuFuncionario.inc.php" );	
	require_once( "include/clsLogAcesso.inc.php" );	
	require_once( "include/Geral.inc.php" );
?>