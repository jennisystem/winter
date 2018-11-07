<?php
include "Winter.php";
$cp = new Winter("LoginConf.xml");
$cp->init();

while(true){
	$cp->loopFunction();
}

?>