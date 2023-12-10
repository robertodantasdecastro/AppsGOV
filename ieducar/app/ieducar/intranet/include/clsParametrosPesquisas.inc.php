<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
*																	     *
*	@author Prefeitura Municipal de Itaja�								 *
*	@updated 29/03/2007													 *
*   Pacote: i-PLB Software P�blico Livre e Brasileiro					 *
*																		 *
*	Copyright (C) 2006	PMI - Prefeitura Municipal de Itaja�			 *
*						ctima@itajai.sc.gov.br					    	 *
*																		 *
*	Este  programa  �  software livre, voc� pode redistribu�-lo e/ou	 *
*	modific�-lo sob os termos da Licen�a P�blica Geral GNU, conforme	 *
*	publicada pela Free  Software  Foundation,  tanto  a vers�o 2 da	 *
*	Licen�a   como  (a  seu  crit�rio)  qualquer  vers�o  mais  nova.	 *
*																		 *
*	Este programa  � distribu�do na expectativa de ser �til, mas SEM	 *
*	QUALQUER GARANTIA. Sem mesmo a garantia impl�cita de COMERCIALI-	 *
*	ZA��O  ou  de ADEQUA��O A QUALQUER PROP�SITO EM PARTICULAR. Con-	 *
*	sulte  a  Licen�a  P�blica  Geral  GNU para obter mais detalhes.	 *
*																		 *
*	Voc�  deve  ter  recebido uma c�pia da Licen�a P�blica Geral GNU	 *
*	junto  com  este  programa. Se n�o, escreva para a Free Software	 *
*	Foundation,  Inc.,  59  Temple  Place,  Suite  330,  Boston,  MA	 *
*	02111-1307, USA.													 *
*																		 *
* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

require_once( "include/Geral.inc.php" );

/**
 * Classe de parametriza��o dos dados a serem informados para as listagens gen�ricas.
 *
 * @author Adriano Erik Weiguert Nagasava
 */
class clsParametrosPesquisas
{
	/**
	 * Deve ser utilizado para informar se haver� submit (1) ou n�o na p�gina (0).
	 *
	 * @var int
	 */
	var $submit;

	/**
	 * Deve ser usado para informar os nomes dos campos a serem utilizados.
	 *
	 * @var array
	 */
	var $campo_nome;

	/**
	 * Deve ser utilizado para informar o tipo do campo ("text" ou "select");
	 *
	 * @var array
	 */
	var $campo_tipo;

	/**
	 * Deve ser usado para informar os nomes dos campos a serem utilizados como indice.
	 *
	 * @var array
	 */
	var $campo_indice;

	/**
	 * Deve ser usado para informar os nomes dos campos a serem utilizados como valores.
	 *
	 * @var array
	 */
	var $campo_valor;

	/**
	 * Deve ser utilizado para informar se ser� uma pesquisa de pessoa f�sica (F), pessoa jur�dica (J) ou pessoa f�sica e jur�dica (FJ).
	 *
	 * @var char
	 */
	var $pessoa;

	/**
	 * Deve ser usado para indicar se deseja que apare�a o bot�o de novo (S) ou n�o (N) na pesquisa de pessoa.
	 *
	 * @var char
	 */
	var $pessoa_novo;

	/**
	 * Deve ser usado para indicar se deseja que a tela seja aberta num iframe ("frame") ou para abrir na pr�pria janela ("window").
	 *
	 * @var string
	 */
	var $pessoa_tela;

	/**
	 * Deve ser usado para informar o nome do campo para onde ser� retornado o valor "0", indicando que deve ser feito um novo cadastro de pessoa.
	 *
	 * @var string
	 */
	var $pessoa_campo;

	/**
	 * Deve ser usado para indicar se deseja que ap�s o usu�rio selecionar uma pessoa, ela seja redirecionada pra uma tela de cadastro com as informa��es da pessoa selecionada (S) ou n�o (N).
	 *
	 * @var char
	 */
	var $pessoa_editar;

	/**
	 * Deve ser usado para indicar em qual sistema a pessoa f�sica est�/ser� cadastrada.
	 *
	 * @var int
	 */
	var $ref_cod_sistema;

	/**
	 * Deve ser usado para indicar se na inclus�o o CPF da pessoa � obrigat�rio ("S") ou n�o ("N").
	 *
	 * @var int
	 */
	var $pessoa_cpf;

	/**
	 * Construtor da classe
	 *
	 * @return clsParametrosPesquisas
	 */
	function clsParametrosPesquisas() {
		$this->campo_nome 	= array();
		$this->campo_tipo 	= array();
		$this->campo_valor 	= array();
		$this->campo_indice = array();
	}

	/**
	 * Pega todos os atributos da classe e joga num array e retorna este array serializado e codificado para url.
	 *
	 * @return array
	 */
	function serializaCampos() {
		$parametros_serializados["submit"] 		    = $this->submit;
		$parametros_serializados["campo_nome"]      = $this->campo_nome;
		$parametros_serializados["campo_tipo"]	    = $this->campo_tipo;
		$parametros_serializados["campo_indice"]    = $this->campo_indice;
		$parametros_serializados["campo_valor"]	    = $this->campo_valor;
		$parametros_serializados["pessoa"]		    = $this->pessoa;
		$parametros_serializados["pessoa_novo"]	    = $this->pessoa_novo;
		$parametros_serializados["pessoa_tela"]	    = $this->pessoa_tela;
		$parametros_serializados["pessoa_campo"]    = $this->pessoa_campo;
		$parametros_serializados["pessoa_editar"]   = $this->pessoa_editar;
		$parametros_serializados["ref_cod_sistema"] = $this->ref_cod_sistema;
		$parametros_serializados["pessoa_cpf"]		= $this->pessoa_cpf;
		$parametros_serializados				    = serialize( $parametros_serializados );
		$parametros_serializados				    = urlencode( $parametros_serializados );
		return $parametros_serializados;
	}

	/**
	 * Recebe os atributos em um array serializado, "deserializa o array e preenche os atributos.
	 *
	 * @param array $parametros_serializados
	 */
	function deserializaCampos( $parametros_serializados ) {
		$parametros_serializados = str_replace( "\\", null, $parametros_serializados );
		$parametros_serializados = unserialize( $parametros_serializados );
		$this->submit			 = $parametros_serializados["submit"];
		$this->campo_nome		 = $parametros_serializados["campo_nome"];
		$this->campo_tipo		 = $parametros_serializados["campo_tipo"];
		$this->campo_indice		 = $parametros_serializados["campo_indice"];
		$this->campo_valor		 = $parametros_serializados["campo_valor"];
		$this->pessoa			 = $parametros_serializados["pessoa"];
		$this->pessoa_novo		 = $parametros_serializados["pessoa_novo"];
		$this->pessoa_tela		 = $parametros_serializados["pessoa_tela"];
		$this->pessoa_campo		 = $parametros_serializados["pessoa_campo"];
		$this->pessoa_editar	 = $parametros_serializados["pessoa_editar"];
		$this->ref_cod_sistema   = $parametros_serializados["ref_cod_sistema"];
		$this->pessoa_cpf		 = $parametros_serializados["pessoa_cpf"];
	}

	/**
	 * Gera um array com todos os atributos da classe.
	 *
	 * @return array
	 */
	function geraArrayComAtributos() {
		$parametros_serializados["submit"] 		  	= $this->submit;
		$parametros_serializados["campo_nome"]    	= $this->campo_nome;
		$parametros_serializados["campo_tipo"]	  	= $this->campo_tipo;
		$parametros_serializados["campo_indice"]  	= $this->campo_indice;
		$parametros_serializados["campo_valor"]	  	= $this->campo_valor;
		$parametros_serializados["pessoa"]		  	= $this->pessoa;
		$parametros_serializados["pessoa_novo"]	  	= $this->pessoa_novo;
		$parametros_serializados["pessoa_tela"]	  	= $this->pessoa_tela;
		$parametros_serializados["pessoa_campo"] 	= $this->pessoa_campo;
		$parametros_serializados["pessoa_editar"] 	= $this->pessoa_editar;
		$parametros_serializados["ref_cod_sistema"] = $this->ref_cod_sistema;
		$parametros_serializados["pessoa_cpf"]		= $this->pessoa_cpf;
		return $parametros_serializados;
	}

	/**
	 * Preenche os atributos com os valores de um array.
	 *
	 * @param array $parametros_serializados
	 */
	function preencheAtributosComArray( $parametros_serializados ) {
		$this->submit		   = $parametros_serializados["submit"];
		$this->campo_nome	   = $parametros_serializados["campo_nome"];
		$this->campo_tipo	   = $parametros_serializados["campo_tipo"];
		$this->campo_indice    = $parametros_serializados["campo_indice"];
		$this->campo_valor	   = $parametros_serializados["campo_valor"];
		$this->pessoa		   = $parametros_serializados["pessoa"];
		$this->pessoa_novo	   = $parametros_serializados["pessoa_novo"];
		$this->pessoa_tela	   = $parametros_serializados["pessoa_tela"];
		$this->pessoa_campo	   = $parametros_serializados["pessoa_campo"];
		$this->pessoa_editar   = $parametros_serializados["pessoa_editar"];
		$this->ref_cod_sistema = $parametros_serializados["ref_cod_sistema"];
		$this->pessoa_cpf	   = $parametros_serializados["pessoa_cpf"];
	}

	/**
	 * Adiciona um novo campo do tipo texto a ser buscado na pesquisa e setado ap�s ela.
	 *
	 * @param string $campo_nome
	 * @param string $campo_valor
	 */
	function adicionaCampoTexto( $campo_nome, $campo_valor ) {
		$this->campo_nome[]   = $campo_nome;
		$this->campo_tipo[]   = "text";
		$this->campo_indice[] = 0;
		$this->campo_valor[]  = $campo_valor;
	}

	/**
	 * Adiciona um novo campo do tipo select a ser buscado na pesquisa e setado ap�s ela.
	 *
	 * @param string $campo_nome
	 * @param string $campo_indice
	 * @param string $campo_valor
	 */
	function  adicionaCampoSelect( $campo_nome, $campo_indice, $campo_valor ) {
		$this->campo_nome[]   = $campo_nome;
		$this->campo_tipo[]   = "select";
		$this->campo_indice[] = $campo_indice;
		$this->campo_valor[]  = $campo_valor;
	}

	/**
	 * Seta o nome do campo especificado na posi��o indicada.
	 *
	 * @param int $posicao
	 * @param string $valor
	 */
	function setCampoNome( $posicao, $valor ) {
		$this->campo_nome[$posicao] = $valor;
	}

	/**
	 * Caso seja passada a posi��o do campo por par�metro, retorna o nome do campo especificado, sen�o, retorna um array com os nomes de todos os campos.
	 *
	 * @param int $posicao
	 * @return string or array
	 */
	function getCampoNome( $posicao = null ) {
		if ( is_numeric( $posicao ) )
			return $this->campo_nome[$posicao];
		else {
			return $this->campo_nome;
		}
	}

	/**
	 * Seta o tipo do campo especificado na posi��o indicada como "text".
	 *
	 * @param int $posicao
	 */
	function setCampoTipoTexto( $posicao ) {
		$this->campo_tipo[$posicao] = "text";
	}

	/**
	 * Seta o tipo do campo especificado na posi��o indicada como "select".
	 *
	 * @param int $posicao
	 */
	function setCampoTipoSelect( $posicao ) {
		$this->campo_tipo[$posicao] = "select";
	}

	/**
	 * Caso seja passada a posi��o do campo por par�metro, retorna o tipo do campo especificado, sen�o, retorna um array com os tipos de todos os campos.
	 *
	 * @param int $posicao
	 * @return string or array
	 */
	function getCampoTipo( $posicao = null ) {
		if ( is_numeric( $posicao ) ) {
			return $this->campo_tipo[$posicao];
		}
		else
			return $this->campo_tipo;
	}

	/**
	 * Seta o indice do campo especificado na posi��o indicada.
	 *
	 * @param int $posicao
	 * @param string $valor
	 */
	function setCampoIndice( $posicao, $valor ) {
		$this->campo_indice[$posicao] = $valor;
	}

	/**
	 * Caso seja passada a posi��o do campo por par�metro, retorna o indice do campo especificado, sen�o, retorna um array com os indices de todos os campos.
	 *
	 * @param int $posicao
	 * @return int or array
	 */
	function getCampoIndice( $posicao = null ) {
		if ( is_numeric( $posicao ) )
			return $this->campo_indice[$posicao];
		else
			return $this->campo_indice;
	}

	/**
	 * Seta o nome do campo que ser� buscado na tabela na posi��o indicada.
	 *
	 * @param int $posicao
	 * @param string $valor
	 */
	function setCampoValor( $posicao, $valor ) {
		$this->campo_valor[$posicao] = $valor;
	}

	/**
	 * Caso seja passada a posi��o do campo por par�metro, retorna o nome do campo que ser� buscado na tabela, sen�o, retorna um array com todos os nomes dos campos que ir�o ser buscados na tabela.
	 *
	 * @param int $posicao
	 * @return string or array
	 */
	function getCampoValor( $posicao = null ) {
		if ( is_numeric( $posicao ) )
			return $this->campo_valor[$posicao];
		else
			return $this->campo_valor;
	}

	/**
	 * Deve ser passado o valor 1 caso a p�gina tenha "auto-submit" ou o valor 0 caso n�o tenha.
	 *
	 * @param int $submit
	 */
	function setSubmit( $submit ) {
		$this->submit = $submit;
	}

	/**
	 * Retorna 1 caso a p�gina tenha "auto-submit" ou o 0 caso n�o tenha.
	 *
	 * @return int
	 */
	function  getSubmit() {
		return $this->submit;
	}

	/**
	 * Deve ser passado 'F' se for pesquisar uma pessoa f�sica, 'J' se for pesquisar uma pessoa jur�dica e 'FJ' se n�o importar o tipo da pessoa que ir� ser pesquisada.
	 * opcoes: ('F' || 'J' || "FJ" || "FUNC")
	 *
	 * @param string $pessoa
	 */
	function setPessoa( $pessoa ) {
		$this->pessoa = $pessoa;
	}

	/**
	 * Retorna 'F' se for pesquisar uma pessoa f�sica, 'J' se for pesquisar uma pessoa jur�dica e 'FJ' se n�o importar o tipo da pessoa que ir� ser pesquisada.
	 *
	 * @return string
	 */
	function getPessoa() {
		return  $this->pessoa;
	}

	/**
	 * Deve ser passado 'S' se deseja que apare�a o bot�o de novo na pesquisa de pessoa ou 'N' caso n�o deseje.
	 *
	 * @param char $pessoa_novo
	 */
	function setPessoaNovo( $pessoa_novo ) {
		$this->pessoa_novo = $pessoa_novo;
	}

	/**
	 * Retorna 'S' se deseja que apare�a o bot�o de novo na pesquisa de pessoa ou 'N' caso n�o deseje.
	 *
	 * @return char
	 */
	function getPessoaNovo() {
		return $this->pessoa_novo;
	}

	/**
	 * Deve ser passado "frame" para indicar se deseja que a tela seja aberta num iframe ou "window" para abrir na pr�pria janela.
	 *
	 * @param string $pessoa_tela
	 */
	function setPessoaTela( $pessoa_tela ) {
		$this->pessoa_tela = $pessoa_tela;
	}

	/**
	 * Retorna "frame" para indicar se deseja que a tela seja aberta num iframe ou "window" para abrir na pr�pria janela.
	 *
	 * @return string
	 */
	function getPessoaTela() {
		return $this->pessoa_tela;
	}

	/**
	 * Deve ser passado o nome do campo para onde ser� retornado o valor "0", indicando que deve ser feito um novo cadastro de pessoa.
	 *
	 * @param string $pessoa_campo
	 */
	function setPessoaCampo( $pessoa_campo ) {
		$this->pessoa_campo = $pessoa_campo;
	}

	/**
	 * Retorna o nome do campo para onde ser� retornado o valor "0", indicando que deve ser feito um novo cadastro de pessoa.
	 *
	 * @return string
	 */
	function getPessoaCampo() {
		return $this->pessoa_campo;
	}

	/**
	 * Deve ser passado 'S' para indicar se deseja que ap�s o usu�rio selecionar uma pessoa, ela seja redirecionada pra uma tela de cadastro com as informa��es da pessoa selecionada ou 'N' caso n�o deseje.
	 *
	 * @param char $pessoa_editar
	 */
	function setPessoaEditar( $pessoa_editar ) {
		$this->pessoa_editar = $pessoa_editar;
	}

	/**
	 * Retorna 'S' para indicar se deseja que ap�s o usu�rio selecionar uma pessoa, ela seja redirecionada pra uma tela de cadastro com as informa��es da pessoa selecionada ou 'N' caso n�o deseje.
	 *
	 * @return char
	 */
	function  getPessoaEditar() {
		return $this->pessoa_editar;
	}

	/**
	 * Deve ser usado para passar o c�digo do sistema em que a pessoa f�sica est�/ser� cadastrada.
	 *
	 * @param int $ref_cod_sistema
	 */
	function setCodSistema( $ref_cod_sistema ) {
		$this->ref_cod_sistema = $ref_cod_sistema;
	}

	/**
	 * Retorna o c�digo do sistema em que a pessoa f�sica est�/ser� cadastrada.
	 *
	 * @return int
	 */
	function  getCodSistema() {
		return $this->ref_cod_sistema;
	}

	/**
	 * Deve ser usado para passar o "S" caso o CPF seja obrigat�rio na inclus�o de uma pessoa ou "N"
	 * caso contr�rio.
	 *
	 * @param int $pessoa_cpf
	 */
	function setPessoaCPF( $pessoa_cpf ) {
		$this->pessoa_cpf = $pessoa_cpf;
	}

	/**
	 * Retorna o "S" se o CPF for obrigat�rio na inclus�o de uma pessoa ou "N" caso n�o seja.
	 *
	 * @return char
	 */
	function  getPessoaCPF() {
		return $this->pessoa_cpf;
	}
}

?>