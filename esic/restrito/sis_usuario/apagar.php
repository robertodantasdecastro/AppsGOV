<?php
/**********************************************************************************
 Sistema e-SIC Livre: sistema de acesso a informa��o baseado na lei de acesso.
 
 Copyright (C) 2014 Prefeitura Municipal do Natal
 
 Este programa � software livre; voc� pode redistribu�-lo e/ou
 modific�-lo sob os termos da Licen�a GPL2.
***********************************************************************************/

  include("../inc/autenticar.php");
  
  checkPerm("DELUSR");
  
  $codigo = $_REQUEST["codigo"];

  $sql = "DELETE from sis_usuario where idusuario = $codigo ";
  
  if(!execQuery($sql))
  {
    echo "<script>alert('Nao foi possivel excluir este usuario. Pode estar em uso.');</script>";
  }
  else
  {
	logger("Excluiu Usuario");  
  }

  echo "<script>document.location='index.php';</script>";
?>