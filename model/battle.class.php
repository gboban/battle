<?php
/*
 * TODO story
 * create two armies, align them each on its side of the center of the battlefield
 * For soldiers, calculate where are most (average coord) enemy soldiers located
 * Soldiers should advance in that general direction if they do not encounter enemy soldiers
 * 
 *  For each cycle
 *  	- foreach soldier (army A), find friendly soldiers and enemy soldiers on the same coordinates
 *  	- sum the properties of the soldiers and compare sumed strength
 *  	- foreach soldier with the same coordinates
 *  		- reduce strength using sum of strength A / sum of strengths of both sides
 *  		- if soldier dies, reduce bravery by 1 for each of its cocombatants and add message to log
 *  		- if soldier gaves up add message to log
 *  		- if soldier flees add message to the log
 *  
 *  	Keep cycle counter for each soldier and complete battle to filter out soldiers
 *  	for which calculations are already done while iterating trough soldiers
 *  
 *  	For each soldier in army B, check counte and see if any of soldiers have to advance
 *  	if someone is dead, he is dead, is soldier had fleed, he head fleed etc. - just ignore them
 *  
 *  	print alive/dead/gave up/fledd statistics (add to log)
 *  	foreach army, check if there are any alive soldiers that did not fleed or gave up
 *  	if no such soldier, armi had lost the battle (except both armies tie)
 *  
 *  
 */
class Battle{
	private $_army_a = null;
	private $_army_b = null;
	
	public function __construct($army1name, $army2name, $army1number, $army2number){
		$this->_army_a = new Army($army1name, $army1number);
		$this->_army_b = new Army($army2name, $army2number);
	}
	
	protected function initialize(){
		/*
		 * sets positions of armies
		 */
	}
	
	public function run($log){
		$cycle = 0;
		$ended = false;
		
		while(!$ended){
			
		}
	}
}
?>