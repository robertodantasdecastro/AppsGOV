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

require_once 'include/portal/clsPortalAcesso.inc.php';
require_once 'include/portal/clsPortalFuncionario.inc.php';
require_once 'include/portal/clsPortalMenuFuncionario.inc.php';
require_once 'include/portal/clsPortalMenuMenu.inc.php';
require_once 'include/portal/clsPortalMenuSubmenu.inc.php';
require_once 'include/portal/clsPortalNotificacao.inc.php';
require_once 'include/portal/clsPortalArtigoOpiniao.inc.php';
require_once 'include/portal/clsPortalComprasModalidade.inc.php';
require_once 'include/portal/clsPortalFiaAgenda.inc.php';
require_once 'include/portal/clsPortalFiaAplicacao.inc.php';
require_once 'include/portal/clsPortalFiaContas.inc.php';
require_once 'include/portal/clsPortalFiaFaleConosco.inc.php';
require_once 'clsPortalSmsProntuario.inc.php';