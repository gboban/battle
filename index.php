<?php
/*
 * this is where we handle request and create all game objects required
 * 
 * output is sent back by caling View->render()
 */
require("resources/armies.array.php");
require("resources/names.array.php");
require("model/battle.class.php");
require("view/index.class.php");

// get parameters: $_GET-a sa ?army1=50&army2=48
try{
	$army1 = intval($_GET["army1"]);
}catch(Exception $e){
	$army1 = 0;
}

try{
	$army2 = intval($_GET["army2"]);
}catch(Exception $e){
	$army2 = 0;
}

$battle = new Battle($ARMY_NAMES[0], $ARMY_NAMES[1], $army1, $army2);
$log = $battle->run();

$v = new Index('template/index.php', $log);
$v->render();
?>