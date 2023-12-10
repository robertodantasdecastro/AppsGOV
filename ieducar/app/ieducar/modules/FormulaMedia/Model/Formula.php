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

require_once 'CoreExt/Entity.php';
require_once 'App/Model/IedFinder.php';
require_once 'FormulaMedia/Model/TipoFormula.php';
require_once 'FormulaMedia/Validate/Formula.php';

/**
 * FormulaMedia_Model_Formula class.
 *
 * @author      Eriksen Costa Paix�o <eriksen.paixao_bs@cobra.com.br>
 * @category    i-Educar
 * @license     @@license@@
 * @package     FormulaMedia
 * @subpackage  Modules
 * @since       Classe dispon�vel desde a vers�o 1.1.0
 * @version     @@package_version@@
 */
class FormulaMedia_Model_Formula extends CoreExt_Entity
{
  /**
   * Tokens v�lidos para uma f�rmula.
   *
   * - Se: soma das notas de todas as etapas
   * - Et: total de etapas
   * - E1 a E10: nota na etapa En (fica limitado a 10 etapas)
   * - /: divis�o
   * - *: multiplica��o
   * - x: alias para *
   * - (: abre par�ntese
   * - ): fecha par�ntese
   *
   * @var array
   */
  protected $_tokens = array(
    'Se', 'Et', 'Rc',
    'E1', 'E2', 'E3', 'E4', 'E5', 'E6', 'E7', 'E8', 'E9', 'E10',
    '/', '*', 'x', '+',
    '(', ')'
  );

  /**
   * Tokens que pode ser substitu�das por valores num�ricos.
   * @var array
   */
  protected $_tokenNumerics = array(
    'Se', 'Et', 'Rc',
    'E1', 'E2', 'E3', 'E4', 'E5', 'E6', 'E7', 'E8', 'E9', 'E10'
  );

  /**
   * Atributos do model.
   * @var array
   */
  protected $_data = array(
    'instituicao'  => NULL,
    'nome'         => NULL,
    'formulaMedia' => NULL,
    'tipoFormula'  => NULL
  );

  /**
   * Refer�ncias.
   * @var array
   */
  protected $_references = array(
    'tipoFormula' => array(
      'value' => FormulaMedia_Model_TipoFormula::MEDIA_FINAL,
      'class' => 'FormulaMedia_Model_TipoFormula',
      'file'  => 'FormulaMedia/Model/TipoFormula.php'
    )
  );

  /**
   * Retorna as tokens permitidas para uma f�rmula.
   * @return array
   */
  public function getTokens()
  {
    return $this->_tokens;
  }

  /**
   * Verifica se uma token pode receber um valor num�rico.
   *
   * @param string $token
   * @return bool
   */
  public function isNumericToken($token)
  {
    return in_array($token, $this->_tokenNumerics);
  }

  /**
   * Substitui as tokens num�ricas de uma f�rmula, atrav�s de um array
   * associativo.
   *
   * <code>
   * <?php
   * $values = array(
   *   'E1' => 5,
   *   'E2' => 7,
   *   'E3' => 8,
   *   'E4' => 10,
   *   'Et' => 4,
   *   'Rc' => 0,
   *   'Se' => 30
   * );
   *
   * $formula = $formulaModel->replaceTokens($formulaModel->formulaMedia, $values);
   * </code>
   *
   * @param  string  $formula
   * @param  array   $values
   * @return string
   */
  public function replaceTokens($formula, $values = array())
  {
    $formula = $this->replaceAliasTokens($formula);

    $patterns = array();
    foreach ($values as $key => $value) {
      if ($this->isNumericToken($key)) {
        // Usa @ como delimitador para evitar problemas com o sinal de divis�o
        $patterns[$key] = '@' . $key . '@';
      }
    }

    // Usa locale en_US para evitar problemas com pontos flutuantes
    $this->getLocale()->resetLocale();

    // Substitui os tokens
    $replaced = preg_replace($patterns, $values, $formula);

    // Retorna ao locale anterior
    $this->getLocale()->setLocale();

    return $replaced;
  }

  /**
   * Troca os tokens de alias pelos usados durante a execu��o da f�rmula.
   * @param string $formula
   * @return string
   */
  public function replaceAliasTokens($formula)
  {
    return preg_replace(array('/\(/', '/\)/', '/x/'), array(' ( ', ' ) ', '*'), $formula);
  }

  /**
   *
   * @param array $values
   * @return NULL|numeric
   */
  public function execFormulaMedia(array $values = array())
  {
    $formula = $this->replaceTokens($this->formulaMedia, $values);
    return $this->_exec($formula);
  }

  /**
   * Executa um c�digo de f�rmula com eval.
   * @param string $code
   * @return NULL|numeric
   */
  protected function _exec($code)
  {
    $result = NULL;
    eval("?><?php \$result = " . $code . "; ?>");
    return $result;
  }

  /**
   * @see CoreExt_Entity_Validatable#getDefaultValidatorCollection()
   */
  public function getDefaultValidatorCollection()
  {
    $instituicoes = array_keys(App_Model_IedFinder::getInstituicoes());
    $tipoFormula  = FormulaMedia_Model_TipoFormula::getInstance();

    // Se for de recupera��o, inclui a token "Rc" como permitida.
    $formulaValidatorOptions = array();
    if (FormulaMedia_Model_TipoFormula::MEDIA_RECUPERACAO == $this->get('tipoFormula')) {
      $formulaValidatorOptions = array('excludeToken' => NULL);
    }

    return array(
      'instituicao' => new CoreExt_Validate_Choice(array('choices' => $instituicoes)),
      'nome' => new CoreExt_Validate_String(array('min' => 5, 'max' => 50)),
      'formulaMedia' => new FormulaMedia_Validate_Formula($formulaValidatorOptions),
      'tipoFormula' => new CoreExt_Validate_Choice(array('choices' => $tipoFormula->getKeys()))
    );
  }

  /**
   * @see CoreExt_Entity#__toString()
   */
  public function __toString()
  {
    return $this->nome;
  }
}