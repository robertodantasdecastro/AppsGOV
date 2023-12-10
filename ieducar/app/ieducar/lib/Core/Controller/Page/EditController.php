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
 * @author    Eriksen Costa Paix�o <eriksen.paixao_bs@cobra.com.br>
 * @category  i-Educar
 * @license   @@license@@
 * @package   Core_Controller
 * @since     Arquivo dispon�vel desde a vers�o 1.1.0
 * @version   $Id$
 */

require_once 'include/clsCadastro.inc.php';
require_once 'Core/Controller/Page/Validatable.php';
require_once 'App/Model/NivelAcesso.php';

/**
 * Core_Controller_Page_EditController abstract class.
 *
 * Prov� um page controller padr�o para p�ginas de edi��o e cria��o de registros.
 *
 * @author    Eriksen Costa Paix�o <eriksen.paixao_bs@cobra.com.br>
 * @category  i-Educar
 * @license   @@license@@
 * @package   Core_Controller
 * @since     Classe dispon�vel desde a vers�o 1.1.0
 * @todo      Documentar a API
 * @todo      Definir o atributo $_formMap que � diferente do atributo
 *            semelhante dos outros controllers (view|list)
 * @todo      Documentar as op��es new_success e edit_success
 * @version   @@package_version@@
 */
abstract class Core_Controller_Page_EditController
  extends clsCadastro
  implements Core_Controller_Page_Validatable
{
  /**
   * Array associativo de um elemento de formul�rio, usado para a defini��o
   * de labels, nome de campos e defini��o de qual campo foi invalidado por
   * CoreExt_DataMapper::isValid().
   *
   * @var array
   */
  protected $_formMap = array();

  /**
   * Determina se "Cadastrar" ou "Atualizar" s�o a��es dispon�veis na interface.
   * @var bool
   */
  protected $_saveOption = FALSE;

  /**
   * Determina se "Excluir" � uma a��o dispon�vel na interface.
   * @var bool
   */
  protected $_deleteOption = FALSE;

  /**
   * Determina o n�vel de acesso necess�rio para as a��es de Cadastro/Exclus�o.
   * @var int
   */
  protected $_nivelAcessoOption = App_Model_NivelAcesso::INSTITUCIONAL;

  /**
   * Determina um caminho para redirecionar o usu�rio caso seus privil�gios de
   * acesso sejam insuficientes.
   * @var string
   */
  protected $_nivelAcessoInsuficiente = NULL;

  /**
   * @var clsPermissoes
   */
  protected $_clsPermissoes = NULL;

  /**
   * Chama o construtor da superclasse para atribuir $tipoacao do $_POST.
   */
  public function __construct()
  {
    $this->setDataMapper($this->getDataMapper());

    // Adiciona novos itens de configura��o
    $this->_options = $this->_options + array(
      'save_action'               => $this->_saveOption,
      'delete_action'             => $this->_deleteOption,
      'nivel_acesso'              => $this->_nivelAcessoOption,
      'nivel_acesso_insuficiente' => $this->_nivelAcessoInsuficiente
    );

    // Configura bot�es padr�o
    if (0 < $this->getRequest()->id) {
      $this->setOptions(array(
        'url_cancelar' => array(
          'path'    => 'view',
          'options' => array('query' => array('id' => $this->getRequest()->id))
        )
      ));
    }

    $this->_preConstruct();
    parent::__construct();
    $this->_postConstruct();
  }

  /**
   * Subclasses podem sobrescrever esse m�todo para executar opera��es antes
   * da chamada ao construtor de clsCadastro().
   */
  protected function _preConstruct()
  {
  }

  /**
   * Subclasses podem sobrescrever esse m�todo para executar opera��es ap�s
   * a chamada ao construtor de clsCadastro().
   */
  protected function _postConstruct()
  {
  }

  /**
   * Retorna um label de um item de formul�rio.
   * @param string $key
   * @return string
   */
  protected function _getLabel($key)
  {
    return $this->_formMap[$key]['label'];
  }

  /**
   * Retorna uma string de ajuda para um item de formul�rio.
   * @param string $key
   * @return string
   */
  protected function _getHelp($key)
  {
    return $this->_formMap[$key]['help'];
  }

  /**
   * Retorna o atributo de CoreExt_Entity para recuperar o valor de um item
   * de formul�rio.
   * @param string $key
   * @return mixed
   */
  protected function _getEntity($key)
  {
    return $this->_formMap[$key]['entity'];
  }

  /**
   * Retorna um label de um item de formul�rio atrav�s do nome de um atributo de
   * CoreExt_Entity.
   * @param string $key
   * @return string
   */
  protected function _getEntityLabel($key)
  {
    foreach ($this->_formMap as $oKey => $map) {
      if ($key == $map['entity'] || $key == $oKey) {
        return $map['label'];
      }
    }
  }

  /**
   * @see Core_Controller_Page_Validatable#getValidators()
   */
  public function getValidators()
  {
    return array();
  }

  /**
   * Sobrescreve o m�todo Inicializar() de clsCadastro com opera��es padr�es
   * para o caso de uma CoreExt_Entity que use o campo identidade id.
   *
   * Seu comportamento pode ser alterado sobrescrevendo-se os m�todos _initNovo
   * e _initEditar.
   *
   * O retorno desse m�todo � usado em RenderHTML() que define qual m�todo de
   * sua API (Novo, Editar, Excluir ou Gerar) ser� chamado.
   *
   * @return string
   * @see    clsCadastro#RenderHTML()
   * @see    clsCadastro#Inicializar()
   * @todo   Controle de permiss�o
   */
  public function Inicializar()
  {
    if ($this->_initNovo()) {
      return "Novo";
    }

    if ($this->getOption('save_action')) {
      $this->_hasPermissaoCadastra();
    }

    // Habilita bot�o de exclus�o de registro
    if ($this->getOption('delete_action')) {
      $this->fexcluir = $this->_hasPermissaoExcluir();
    }

    if ($this->_initEditar()) {
      return "Editar";
    }
  }

  /**
   * Verifica se o usu�rio possui privil�gios de cadastro para o processo.
   * @return bool|void Redireciona caso a op��o 'nivel_acesso_insuficiente' seja
   *   diferente de NULL.
   */
  protected function _hasPermissaoCadastra()
  {
    return $this->getClsPermissoes()->permissao_cadastra(
      $this->getBaseProcessoAp(),
      $this->getOption('id_usuario'),
      $this->getOption('nivel_acesso'),
      $this->getOption('nivel_acesso_insuficiente')
    );
  }

  /**
   * Verifica se o usu�rio possui privil�gios de cadastro para o processo.
   * @return bool
   */
  protected function _hasPermissaoExcluir()
  {
    return $this->getClsPermissoes()->permissao_excluir(
      $this->getBaseProcessoAp(),
      $this->getOption('id_usuario'),
      $this->getOption('nivel_acesso')
    );
  }

  /**
   * Setter.
   * @param clsPemissoes $instance
   * @return CoreExt_Controller_Page_Abstract Prov� interface flu�da
   */
  public function setClsPermissoes(clsPermissoes $instance)
  {
    $this->_clsPermissoes = $instance;
    return $this;
  }

  /**
   * Getter.
   * @return clsPermissoes
   */
  public function getClsPermissoes()
  {
    if (is_null($this->_clsPermissoes)) {
      require_once 'include/pmieducar/clsPermissoes.inc.php';
      $this->setClsPermissoes(new clsPermissoes());
    }
    return $this->_clsPermissoes;
  }

  /**
   * Hook de execu��o para verificar se CoreExt_Entity � novo. Verifica
   * simplesmente se o campo identidade foi passado na requisi��o HTTP e, se n�o
   * for, cria uma inst�ncia de CoreExt_Entity vazia.
   *
   * @return bool
   */
  protected function _initNovo()
  {
    if (!isset($this->getRequest()->id)) {
      $this->setEntity($this->getDataMapper()->createNewEntityInstance());
      return TRUE;
    }
    return FALSE;
  }

  /**
   * Hook de execu��o para verificar se CoreExt_Entity � existente atrav�s do
   * campo identidade passado pela requisi��o HTTP.
   *
   * @return bool
   */
  protected function _initEditar()
  {
    try {
      $this->setEntity($this->getDataMapper()->find($this->getRequest()->id));
    } catch(Exception $e) {
      $this->mensagem = $e;
      return FALSE;
    }
    return TRUE;
  }

  /**
   * Insere um novo registro no banco de dados e redireciona para a p�gina
   * definida pela op��o "new_success".
   * @see clsCadastro#Novo()
   */
  public function Novo()
  {
    if ($this->_save()) {
      $params = '';
      if (0 < count($this->getOption('new_success_params')) &&
          is_array($this->getOption('new_success_params'))) {
        $params = '?' . http_build_query($this->getOption('new_success_params'));
      }

      $this->redirect($this->getDispatcher()->getControllerName() . '/' .
      $this->getOption('new_success') . $params);
    }
    return FALSE;
  }

  /**
   * Atualiza um registro no banco de dados e redireciona para a p�gina
   * definida pela op��o "edit_success".
   *
   * Possibilita o uso de uma query string padronizada, usando o array
   * armazenado na op��o "edit_success_params"
   *
   * @see clsCadastro#Editar()
   */
  public function Editar()
  {
    if ($this->_save()) {
      if (0 < count($this->getOption('edit_success_params')) &&
          is_array($this->getOption('edit_success_params'))) {
        $params = http_build_query($this->getOption('edit_success_params'));
      }
      else {
        $params = 'id=' . floatval($this->getEntity()->id);
      }
      $this->redirect($this->getDispatcher()->getControllerName() . '/'
                      . $this->getOption('edit_success')
                      . '?' . $params);
    }
    return FALSE;
  }

  /**
   * Apaga um registro no banco de dados e redireciona para a p�gina indicada
   * pela op��o "delete_success".
   * @see clsCadastro#Excluir()
   */
  function Excluir()
  {
    if (isset($this->getRequest()->id)) {
      if ($this->getDataMapper()->delete($this->getRequest()->id)) {
        if (is_array($this->getOption('delete_success_params'))) {
          $params = http_build_query($this->getOption('delete_success_params'));
        }

        $this->redirect(
          $this->getDispatcher()->getControllerName() . '/' .
          $this->getOption('delete_success') .
          (isset($params) ? '?' . $params : '')
        );
      }
    }
    return FALSE;
  }

  /**
   * Implementa uma rotina de cria��o ou atualiza��o de registro padr�o para
   * uma inst�ncia de CoreExt_Entity que use um campo identidade.
   * @return bool
   * @todo Atualizar todas as Exception de CoreExt_Validate, para poder ter
   *   certeza que o erro ocorrido foi gerado de alguma camada diferente, como
   *   a de conex�o com o banco de dados.
   */
  protected function _save()
  {
    $data = array();

    foreach ($_POST as $key => $val) {
      if (array_key_exists($key, $this->_formMap)) {
        $data[$key] = $val;
      }
    }

    // Verifica pela exist�ncia do field identity
    if (isset($this->getRequest()->id) && 0 < $this->getRequest()->id) {
      $entity = $this->setEntity($this->getDataMapper()->find($this->getRequest()->id));
    }

    if (isset($entity)) {
      $this->getEntity()->setOptions($data);
    }
    else {
      $this->setEntity($this->getDataMapper()->createNewEntityInstance($data));
    }

    try {
      $this->getDataMapper()->save($this->getEntity());
      return TRUE;
    }
    catch (Exception $e) {
      // TODO: ver @todo do docblock
      $this->mensagem = 'Erro no preenchimento do formul�rio. ';
      return FALSE;
    }
  }
}