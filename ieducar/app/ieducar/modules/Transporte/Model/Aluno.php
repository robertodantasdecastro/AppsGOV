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
 * Este programa � distribu�do na expectativa de que seja �til, por�m, SEM
 * NENHUMA GARANTIA; nem mesmo a garantia impl�cita de COMERCIABILIDADE OU
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
 * @package     Transporte
 * @subpackage  Modules
 * @since       Arquivo dispon�vel desde a vers�o 1.2.0
 * @version     $Id$
 */

require_once 'CoreExt/Entity.php';

/**
 * Transporte_Model_Aluno class.
 *
 * @author      Eriksen Costa Paix�o <eriksen.paixao_bs@cobra.com.br>
 * @category    i-Educar
 * @license     @@license@@
 * @package     Transporte
 * @subpackage  Modules
 * @since       Classe dispon�vel desde a vers�o 1.2.0
 * @version     @@package_version@@
 */
class Transporte_Model_Aluno extends CoreExt_Entity
{
  protected $_data = array(
    'aluno'       => NULL,
    'responsavel' => NULL,
    'user'        => NULL,
    'created_at'  => NULL,
    'updated_at'  => NULL
  );

  protected $_references = array(
    'responsavel' => array(
      'value' => NULL,
      'class' => 'Transporte_Model_Responsavel',
      'file'  => 'Transporte/Model/Responsavel.php'
    )
  );

  public function __construct($options = array())
  {
    parent::__construct($options);
    unset($this->_data['id']);
  }

  public function getDefaultValidatorCollection()
  {
    require_once 'Transporte/Model/Responsavel.php';
    $responsavel = Transporte_Model_Responsavel::getInstance();

    return array(
      'aluno'       => new CoreExt_Validate_Numeric(),
      'responsavel' => new CoreExt_Validate_Choice(array(
        'choices' => $responsavel->getKeys())
      ),
      'user'        => new CoreExt_Validate_Numeric()
    );
  }
}