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
 * @package     RegraAvaliacao
 * @subpackage  Modules
 * @since       Arquivo dispon�vel desde a vers�o 1.1.0
 * @version     $Id$
 */

require_once 'CoreExt/Enum.php';

/**
 * RegraAvaliacao_Model_TipoProgressao class.
 *
 * @author      Eriksen Costa Paix�o <eriksen.paixao_bs@cobra.com.br>
 * @category    i-Educar
 * @license     @@license@@
 * @package     RegraAvaliacao
 * @subpackage  Modules
 * @since       Classe dispon�vel desde a vers�o 1.1.0
 * @version     @@package_version@@
 */
class RegraAvaliacao_Model_TipoProgressao extends CoreExt_Enum
{
  const CONTINUADA = 1;
  const NAO_CONTINUADA_AUTO_MEDIA_PRESENCA = 2;
  const NAO_CONTINUADA_AUTO_SOMENTE_MEDIA = 3;
  const NAO_CONTINUADA_MANUAL = 4;

  protected $_data = array(
    self::CONTINUADA => 'Continuada',
    self::NAO_CONTINUADA_AUTO_MEDIA_PRESENCA => 'N�o-continuada autom�tica: m�dia e presen�a',
    self::NAO_CONTINUADA_AUTO_SOMENTE_MEDIA => 'N�o-continuada autom�tica: somente m�dia',
    self::NAO_CONTINUADA_MANUAL => 'N�o-continuada manual'
  );

  public static function getInstance()
  {
    return self::_getInstance(__CLASS__);
  }
}