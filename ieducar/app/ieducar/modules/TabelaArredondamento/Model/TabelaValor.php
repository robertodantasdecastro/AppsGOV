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
 * @package     TabelaArredondamento
 * @subpackage  Modules
 * @since       Arquivo dispon�vel desde a vers�o 1.1.0
 * @version     $Id$
 */

require_once 'CoreExt/Entity.php';
require_once 'App/Model/IedFinder.php';

/**
 * TabelaArredondamento_Model_TabelaValor class.
 *
 * @author      Eriksen Costa Paix�o <eriksen.paixao_bs@cobra.com.br>
 * @category    i-Educar
 * @license     @@license@@
 * @package     TabelaArredondamento
 * @subpackage  Modules
 * @since       Classe dispon�vel desde a vers�o 1.1.0
 * @version     @@package_version@@
 */
class TabelaArredondamento_Model_TabelaValor extends CoreExt_Entity
{
  protected $_data = array(
    'tabelaArredondamento' => NULL,
    'nome'                 => NULL,
    'descricao'            => NULL,
    'valorMinimo'          => NULL,
    'valorMaximo'          => NULL
  );

  protected $_dataTypes = array(
    'valorMinimo' => 'numeric',
    'valorMaximo' => 'numeric'
  );

  protected $_references = array(
    'tabelaArredondamento' => array(
      'value' => NULL,
      'class' => 'TabelaArredondamento_Model_TabelaDataMapper',
      'file'  => 'TabelaArredondamento/Model/TabelaDataMapper.php'
    )
  );

  /**
   * @see CoreExt_Entity#getDataMapper()
   */
  public function getDataMapper()
  {
    if (is_null($this->_dataMapper)) {
      require_once 'TabelaArredondamento/Model/TabelaValorDataMapper.php';
      $this->setDataMapper(new TabelaArredondamento_Model_TabelaValorDataMapper());
    }
    return parent::getDataMapper();
  }

  /**
   * @see CoreExt_Entity_Validatable#getDefaultValidatorCollection()
   * @todo Implementar validador que retorne um String ou Numeric, dependendo
   *   do valor do atributo (assim como validateIfEquals().
   * @todo Implementar validador que aceite um valor de compara��o como
   *   alternativa a uma chave de atributo. (COMENTADO ABAIXO)
   */
  public function getDefaultValidatorCollection()
  {
    $validators = array();

    // Valida��o condicional
    switch ($this->tabelaArredondamento->get('tipoNota')) {
      case RegraAvaliacao_Model_Nota_TipoValor::NUMERICA:
        $validators['nome'] = new CoreExt_Validate_Numeric(
          array('min' => 0.00, 'max' => 10.0)
        );
        break;
      case RegraAvaliacao_Model_Nota_TipoValor::CONCEITUAL:
        $validators['nome'] = new CoreExt_Validate_String(
          array('min' => 1, 'max' => 5)
        );
        $validators['descricao'] = new CoreExt_Validate_String(
          array('min' => 2, 'max' => 25)
        );
        break;
    }

    $ret =
    $validators  + array(
      'valorMinimo' => new CoreExt_Validate_Numeric(array('min' => 0.00, 'max' => 9.999)),
      'valorMaximo' => new CoreExt_Validate_Numeric(array('min' => 0.001, 'max' => 10.0)),
    );
    return $ret;
  }

  public function __toString()
  {
    return $this->nome;
  }
}