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

	$width = 60;
	$height = 205;
	$maxval = $_GET["maxval"];
	$linhas_horizon = 10;
	$graph_no = rand( 0, 100 );
		
	header ( "Content-type: image/png" );

	$im = @imagecreate( $width, $height ) or die( "Cannot Initialize new GD image stream" );
	$background_color = imagecolorallocate( $im, 230, 230, 230);
	$text_color = imagecolorallocate ( $im, 0, 0, 0);
	$back_line = imagecolorallocate ( $im, 220, 220, 220 );
	$inner_text = imagecolorallocate ( $im, 150, 150, 150 );
	
	// cria as linhas horizontais
	for( $i = $height - 1, $j = 0; $i > 0; $i-=$linhas_horizon, $j++ ) {
		imageline ( $im, 0, $i, $width, $i, $back_line );
		$valorAtual = ( $j * $maxval ) / 20;
		imagestring( $im, 0, 3, $i, number_format( $valorAtual, 2, ",", "." ), $inner_text );
	}
	imagepng($im);
	imagedestroy($im);
?>