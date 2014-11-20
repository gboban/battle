<?php
/**
 *
 * @author  Goran Boban gboban70(at)gmail.com
 * @version 1.0
 * @since   2014-10-31
 */
/**
 * This program is free software: you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation, either version 3 of the License, or
 (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
require("soldier.class.php");

class Army{
	private $_army_name = "";
	private $_soldiers;
	
	public function __construct($army_name, $nrofsoldiers, $WARRIOR_NAMES){
		
		if($nrofsoldiers > count($WARRIOR_NAMES)){
			throw new Exception("Not enough names for given number of soldiers (maximum number: ".count($WARRIOR_NAMES).")");
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
			
			$this->_soldiers[] = $new_soldier;
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
	
	public function get_survivors(){
		$survivors = array();
		foreach($this->_soldiers as $soldier){
			if(
					$soldier->is_alive() &&
					!$soldier->gave_up() &&
					!$soldier->has_fleed()
					){
				$survivors[] = $soldier;
			}
		}
		
		return $survivors;
	}
	
	public function get_indolents(){
		$indolents = array();
		foreach($this->_soldiers as $soldier){
			if($soldier->gave_up()){
				$indolents[] = $soldier;
			}
		}
		
		return $indolents;
	}
	
	public function get_cowards(){
		$cowards = array();
		foreach($this->_soldiers as $soldier){
			if($soldier->has_fleed()){
				$cowards[] = $soldier;
			}
		}
		
		return $cowards;
	}
	
	public function get_deceased_heroes(){
		$deceased = array();
		foreach($this->_soldiers as $soldier){
			if($soldier->is_dead()){
				$deceased[] = $soldier;
			}
		}
		
		return $deceased;
	}
}
?>