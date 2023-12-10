<?php

/**
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
 * Este programa � distribu��do na expectativa de que seja �til, por�m, SEM
 * NENHUMA GARANTIA; nem mesmo a garantia impl��cita de COMERCIABILIDADE OU
 * ADEQUA��O A UMA FINALIDADE ESPEC�FICA. Consulte a Licen�a P�blica Geral
 * do GNU para mais detalhes.
 *
 * Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral do GNU junto
 * com este programa; se n�o, escreva para a Free Software Foundation, Inc., no
 * endere�o 59 Temple Street, Suite 330, Boston, MA 02111-1307 USA.
 *
 * @author      Eriksen Costa Paix�o <eriksen.paixao_bs@cobra.com.br>
 * @category    i-Educar
 * @license     @@license@@
 * @package     CoreExt_Entity
 * @subpackage  UnitTests
 * @since       Arquivo dispon�vel desde a vers�o 1.1.0
 * @version     $Id$
 */

require_once 'CoreExt/Entity.php';

/**
 * CoreExt_ChildEntityStub class.
 *
 * @author      Eriksen Costa Paix�o <eriksen.paixao_bs@cobra.com.br>
 * @category    i-Educar
 * @license     @@license@@
 * @package     CoreExt_Entity
 * @subpackage  UnitTests
 * @since       Classe dispon�vel desde a vers�o 1.1.0
 * @version     @@package_version@@
 */
class CoreExt_ChildEntityStub extends CoreExt_Entity
{
  protected $_data = array(
    'nome' => NULL,
    'sexo' => NULL,
    'tipoSanguineo' => NULL,
    'peso' => NULL,
  );

  protected $_references = array(
    'sexo' => array(
      'value' => NULL,
      'class' => 'CoreExt_EnumSexStub',
      'file'  => 'CoreExt/_stub/EnumSex.php'
    ),
    'tipoSanguineo' => array(
      'value' => NULL,
      'class' => 'CoreExt_EnumTipoSanguineoStub',
      'file'  => 'CoreExt/_stub/EnumTipoSanguineo.php',
      'null'  => TRUE
    )
  );

  protected $_dataTypes = array(
    'peso' => 'numeric'
  );

  public function getDefaultValidatorCollection()
  {
    return array();
  }
}