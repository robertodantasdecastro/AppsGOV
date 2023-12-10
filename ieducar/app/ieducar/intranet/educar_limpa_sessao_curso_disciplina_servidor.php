<?php

/*
 * i-Educar - Sistema de gest�o de escolas
 *
 * Copyright (c) 2006   Prefeitura Municipal de Itaja�
 *                                 <ctima@itajai.sc.gov.br>
 *
 * Este programa � software livre; voc� pode redistribu��-lo e/ou modific�-lo
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
 * Apaga vari�veis de sess�o contendo dados de fun��o do servidor
 *
 * Arquivo acessado via XMLHttpRequest
 *
 * @author   Prefeitura Municipal de Itaja� <ctima@itajai.sc.gov.br>
 * @license  http://creativecommons.org/licenses/GPL/2.0/legalcode.pt  CC GNU GPL
 * @package  Core
 * @since    Arquivo dispon�vel desde a vers�o 1.0.0
 * @version  $Id$
 */

session_start();
unset($_SESSION['cursos_disciplina']);
unset($_SESSION['cursos_servidor']);
unset($_SESSION['cod_servidor']);
session_write_close();
echo "";