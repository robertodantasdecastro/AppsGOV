<?php

/*
 * i-Educar - Sistema de gest�o escolar
 *
 * Copyright (C) 2006  Prefeitura Municipal de Itaja�
 *                     <ctima@itajai.sc.gov.br>
 *
 * Este programa � software livre; voc� pode redistribu�-lo e/ou modific�-lo
 * sob os termos da Licen�a P�blica Geral GNU conforme publicada pela Free
 * Software Foundation; tanto a vers�o 2 da Licen�a, como (a seu crit�rio)
 * qualquer vers�o posterior.
 *
 * Este programa � distribu�do na expectativa de que seja �til, por�m, SEM
 * NENHUMA GARANTIA; nem mesmo a garantia impl�cita de COMERCIABILIDADE OU
 * ADEQUA��O A UMA FINALIDADE ESPEC�FICA. Consulte a Licen�a P�blica Geral
 * do GNU para mais detalhes.
 *
 * Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral do GNU junto
 * com este programa; se n�o, escreva para a Free Software Foundation, Inc., no
 * endere�o 59 Temple Street, Suite 330, Boston, MA 02111-1307 USA.
 */

/**
 * @author   Prefeitura Municipal de Itaja� <ctima@itajai.sc.gov.br>
 * @license  http://creativecommons.org/licenses/GPL/2.0/legalcode.pt  CC GNU GPL
 * @package  Core
 * @since    Arquivo dispon�vel desde a vers�o 1.0.0
 * @version  $Id$
 */

// Inclui opera��es de bootstrap.
require_once '../includes/bootstrap.php';


require_once 'include/clsBanco.inc.php';
require_once 'include/Geral.inc.php';

require_once 'include/pmidrh/clsPmidrhCargos.inc.php';
require_once 'include/pmidrh/clsPmidrhDiaria.inc.php';
require_once 'include/pmidrh/clsPmidrhDiariaGrupo.inc.php';
require_once 'include/pmidrh/clsPmidrhDiariaValores.inc.php';
require_once 'include/pmidrh/clsPmidrhLogVisualizacaoOlerite.inc.php';
require_once 'include/pmidrh/clsPmidrhPortaria.inc.php';
require_once 'include/pmidrh/clsPmidrhPortariaCamposEspeciaisValor.inc.php';
require_once 'include/pmidrh/clsPmidrhPortariaCamposTabela.inc.php';
require_once 'include/pmidrh/clsPmidrhPortariaFuncionario.inc.php';
require_once 'include/pmidrh/clsPmidrhStatus.inc.php';
require_once 'include/pmidrh/clsPmidrhTipoPortaria.inc.php';
require_once 'include/pmidrh/clsPmidrhTipoPortariaCamposEspeciais.inc.php';
require_once 'include/pmidrh/clsPmidrhPortariaCamposTabelaValor.inc.php';
require_once 'include/pmidrh/clsPmidrhPortariaResponsavel.inc.php';
require_once 'include/pmidrh/clsPmidrhPortariaAssinatura.inc.php';
require_once 'include/pmidrh/clsPmidrhUsuario.inc.php';
require_once 'include/pmidrh/clsPmidrhInstituicao.inc.php';
require_once 'include/pmidrh/clsSetor.inc.php';