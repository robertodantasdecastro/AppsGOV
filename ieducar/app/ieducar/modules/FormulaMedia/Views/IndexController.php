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
 * @package     FormulaMedia
 * @subpackage  Modules
 * @since       Arquivo dispon�vel desde a vers�o 1.1.0
 * @version     $Id: /ieducar/branches/1.1.0-avaliacao/ieducar/modules/AreaConhecimento/Views/IndexController.php 791 2009-11-27T16:23:16.505103Z eriksen  $
 */

require_once 'Core/Controller/Page/ListController.php';
require_once 'FormulaMedia/Model/FormulaDataMapper.php';

/**
 * IndexController class.
 *
 * @author      Eriksen Costa Paix�o <eriksen.paixao_bs@cobra.com.br>
 * @category    i-Educar
 * @license     @@license@@
 * @package     FormulaMedia
 * @subpackage  Modules
 * @since       Classe dispon�vel desde a vers�o 1.1.0
 * @version     @@package_version@@
 */
class IndexController extends Core_Controller_Page_ListController
{
  protected $_dataMapper = 'FormulaMedia_Model_FormulaDataMapper';
  protected $_titulo     = 'Listagem de f�rmulas de c�lculo de m�dia';
  protected $_processoAp = 948;
  protected $_tableMap   = array(
    'Nome' => 'nome',
    'F�rmula de c�lculo' => 'formulaMedia',
    'Tipo f�rmula' => 'tipoFormula'
  );
}