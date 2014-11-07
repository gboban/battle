<?php
require("soldier.class.php");

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
	
	public function get_soldiers(){
		// return shallow copy of soldiers array?	
		return $this->_soldiers;
	}
	
	/*
	 * position army at given distance from [0][0]
	 */
	public function position_army($distance){
		// $istance is expected to be +/-1 - larger distance would only result in more cycles army spend on approach to each other
		$i = 0;
		$cntsoldiers = count($this->_soldiers);
		$start_x = - (int)($cntsoldiers / 2);
		foreach(array_keys($this->_soldiers) as $si){
			$x = $start_x + $i;
			$y = $distance;
			$this->_soldiers[$si]->set_position($x, $y);
			++$i;
		}
	}
	
	// @todo check if army can fight (there are soldier who are alive and didn't fleed or gave up)
	public function can_fight(){
		foreach($this->_soldiers as $soldier){
			if($soldier->can_fight()) return true;
		}
		
		return false;
	}
}
?>