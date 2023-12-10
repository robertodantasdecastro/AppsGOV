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
 * Faz stream de arquivo para o buffer do navegador.
 *
 * @author   Eriksen Costa Paix�o <eriksen.paixao_bs@cobra.com.br>
 * @license  http://creativecommons.org/licenses/GPL/2.0/legalcode.pt  CC GNU GPL
 * @package  Core
 * @since    Arquivo dispon�vel desde a vers�o 1.1.0
 * @version  $Id$
 */

require_once 'Utils/Mimetype.class.php';
require_once 'Utils/FileStream.class.php';

// Pega o nome do arquivo (caminho completo)
$filename = isset($_GET['filename']) ? $_GET['filename'] : NULL;

// Diret�rios p�blicos (permitidos) para stream de arquivo.
$defaultDirectories = array('tmp', 'pdf');

// Classe Mimetype
$mimetype = new Mimetype();

// Classe FileStream
$fileStream = new FileStream($mimetype, $defaultDirectories);

try {
  $fileStream->setFilepath($filename);
}
catch (Exception $e) {
  print $e->getMessage();
  exit();
}

try {
  $fileStream->streamFile();
}
catch (Exception $e) {
  print $e->getMessage();
  exit();
}

unlink($filename);