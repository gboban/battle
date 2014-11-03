<?php
class Army{
	private $_army_name = "";
	private $_soldiers;
	
	public function __construct($army_name, $nrofsoldiers){
		
		if($nrofsoldiers > count($WARRIOR_NAMES)){
			throw new Exception("Not enough names for given number of soldiers");
		}
		
		$this->_army_name = $army_name;
		
		$this->_soldiers = array();
		
		// copy array of names, so we can remove names we use
		$names = $WARRIOR_NAMES;
		shuffle($names);
		
		for($i = 0; $i < $nrofsoldiers; ++$i){
			$new_soldier = new Soldier(
				$this->_army_name,	// name of this army
				array_pop($names), 	// name
				100,				// energy
				rand(0, 100),		// experience
				100, 				// life
				rand(0, 100),		// strength
				rand(0, 100)		// bravery
			);
		}
	}
	
	public function get_name(){
		return $this->_army_name;
	}
}
?>