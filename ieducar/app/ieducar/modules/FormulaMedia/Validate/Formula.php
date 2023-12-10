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
 * @version     $Id$
 */

require_once 'CoreExt/Validate/Abstract.php';
require_once 'FormulaMedia/Model/Formula.php';

/**
 * FormulaMedia_Validate_Formula class.
 *
 * @author      Eriksen Costa Paix�o <eriksen.paixao_bs@cobra.com.br>
 * @category    i-Educar
 * @license     @@license@@
 * @package     FormulaMedia
 * @subpackage  Modules
 * @since       Classe dispon�vel desde a vers�o 1.1.0
 * @version     @@package_version@@
 */
class FormulaMedia_Validate_Formula extends CoreExt_Validate_Abstract
{
  /**
   * Refer�ncia para inst�ncia da classe FormulaMedia_Model_Formula do model.
   * @var FormulaMedia_Model_Formula
   */
  protected static $_model = NULL;

  /**
   * Por padr�o, exclui o tokens de nota de recupera��o.
   *
   * @see CoreExt_Validate_Abstract#_getDefaultOptions()
   */
  protected function _getDefaultOptions()
  {
    return array('excludeToken' => array('Rc'));
  }

  /**
   * @see CoreExt_Validate_Abstract#_validate()
   * @throws Exception|FormulaMedia_Validate_Exception
   */
  protected function _validate($value)
  {
    // Instancia
    if (is_null(self::$_model)) {
      self::$_model = new FormulaMedia_Model_Formula();
    }

    // Adiciona espa�os entre os par�nteses
    $value = self::$_model->replaceAliasTokens($value);

    $tokensAvailable = $this->_getTokens();
    $valueTokens     = explode(' ', $value);
    $missingTokens   = array();
    $numericTokens   = array();

    // Verifica se alguma token n�o permitida foi utilizada
    foreach ($valueTokens as $tk) {
      if ('' == ($tk = trim($tk))) {
        continue;
      }

      if (!in_array($tk, $tokensAvailable)) {
        if (!is_numeric($tk)) {
          $missingTokens[] = $tk;
        }
      }
      elseif (self::$_model->isNumericToken($tk)) {
        // Se for uma token num�rica, atribui um n�mero 1 para usar na f�rmula
        // e avaliar se n�o lan�a um erro no PHP
        $numericTokens[$tk] = 1;
      }
    }

    if (0 < count($missingTokens)) {
      throw new Exception('As vari�veis ou s�mbolos n�o s�o permitidos: ' . implode(', ', $missingTokens));
    }

    // Verifica se a f�rmula � parseada corretamente pelo PHP
    $formula = self::$_model->replaceTokens($value, $numericTokens);

    /*
     * Eval, com surpress�o de erro para evitar interrup��o do script. Se
     * retornar algum valor diferente de NULL, assume como erro de sintaxe.
     */
    $evaled = @eval('?><?php $result = ' . $formula . '; ?>');
    if (!is_null($evaled)) {
      require_once 'FormulaMedia/Validate/Exception.php';
      throw new FormulaMedia_Validate_Exception('A f�rmula apresenta erros.'
                . ' Verifique algum par�ntese faltante ou um sinal de opera��o'
                . ' matem�tica sem um operando.');
    }

    return TRUE;
  }

  /**
   * Retorna as tokens dispon�veis para o validador. Uma token pode ser
   * exclu�da usando a op��o excludeToken.
   *
   * @return array
   */
  protected function _getTokens()
  {
    $tokens = self::$_model->getTokens();
    $tokensAvailable = array();

    if ($this->_hasOption('excludeToken') &&
        is_array($this->getOption('excludeToken')) &&
        0 < count($this->getOption('excludeToken'))
    ) {
      $excludeToken = $this->getOption('excludeToken');
      foreach ($tokens as $token) {
        if (!in_array($token, $excludeToken)) {
          $tokensAvailable[] = $token;
        }
      }
    }
    else {
      $tokensAvailable = $tokens;
    }

    return $tokensAvailable;
  }
}