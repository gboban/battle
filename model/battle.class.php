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
require("army.class.php");

class Battle{
	private $_army_a = null;
	private $_army_b = null;
	
	public function __construct($army1name, $army2name, $army1number, $army2number, $WARRIOR_NAMES){
		$this->_army_a = new Army($army1name, $army1number, $WARRIOR_NAMES);
		$this->_army_b = new Army($army2name, $army2number, $WARRIOR_NAMES);
		
		$this->_army_a->position_army(-1);
		$this->_army_b->position_army(1);
	}
	
	protected function confront_armies($cycle){
		// group soldiers by battleground coordinates
		//print("confront " . $cycle . "<br />");
		$cycle_log = array();
		
		$coord = array();
		$army1 = $this->_army_a->get_soldiers();

		$sumx1 = 0;
		$sumy1 = 0;
		$count1 = 0;
		foreach($army1 as $soldier){
			if($soldier->can_fight()){
				$x = $soldier->get_x();
				$y = $soldier->get_y();
				
				$sumx1 += $x;
				$sumy1 += $y;
				++$count1;
				
				$key = (string)$x . ':' . (string)$y;
				
				if(!in_array($key, array_keys($coord))){
					$coord[$key] = array();
				}
				if(!in_array("army1", array_keys($coord[$key]))){
					$coord[$key]["army1"] = array();
				}
				
				$coord[$key]["army1"][] = $soldier;
			}	
		}
			
		$army2 = $this->_army_b->get_soldiers();
		$sumx2 = 0;
		$sumy2 = 0;
		$count2 = 0;
		foreach($army2 as $soldier){
			if($soldier->can_fight()){
				$x = $soldier->get_x();
				$y = $soldier->get_y();
		
				$sumx2 += $x;
				$sumy2 += $y;
				++$count2;
				
				$key = (string)$x . ':' . (string)$y;
		
				if(!in_array($key, array_keys($coord))){
					$coord[$key] = array();
				}
				if(!in_array("army2", array_keys($coord[$key]))){
					$coord[$key]["army2"] = array();
				}
		
				$coord[$key]["army2"][] = $soldier;
			}
		}
		
		// army centers - armies should fight around "ground zero"
		$army1_x = 0;
		$army1_y = 0;

		$army2_x = 0;
		$army2_y = 0;

		foreach($coord as $xy){
			//var_dump($coord);
			//print("<hr />");
			//die("block");
			// calculate total strength of army1 at present coordinates
			$strength1 = 0;
			if(in_array("army1", array_keys($xy))){
				foreach($xy["army1"] as $soldier){
					$strength1 += $soldier->get_strength();
				}
			}
			
			// calculate total strength of army2 at present coordinates
			$strength2 = 0;
			if(in_array("army2", array_keys($xy))){
				foreach($xy["army2"] as $soldier){
					$strength2 += $soldier->get_strength();
				}	
			}	

			// compare strengths
			if($strength1 == 0){
				//move army 2 (there is no enemy)
				if(in_array("army2", array_keys($xy))){
					foreach($xy["army2"] as $soldier){
						$soldier->decrease_energy(1);
							
						// move toward of the center of army 1
						$x = $soldier->get_x();
						$y = $soldier->get_y();
							
						if(abs($x - $army1_x) > abs($y - $army1_y)){
							$mx = ($army1_x - $x) / abs($x - $army1_x);
							$my = 0;
						}elseif(abs($army1_x - $x) < abs($y - $army1_y)){
							$mx = 0;
							$my = ($army1_y - $y) / abs($y - $army1_y);
						}else{
							$mx = ($army1_x - $x) / abs($x - $army1_x);
							$my = ($army1_y - $y) / abs($y - $army1_y);
						}
			
						$soldier->move($mx, $my);
						array_push($cycle_log, $soldier->to_string() . " moved forward to (".$mx . ", " . $my.")" . $soldier->get_x() . ", " . $soldier->get_y());
					}
				}
			}elseif($strength2 == 0){
				// move army 1
				if(in_array("army1", array_keys($xy))){
					foreach($xy["army1"] as $soldier){
						$soldier->decrease_energy(1);
			
						// move toward of the center of army 1
						$x = $soldier->get_x();
						$y = $soldier->get_y();
			
						if(abs($x - $army1_x) > abs($y - $army1_y)){
							$mx = ($army1_x - $x) / abs($x - $army1_x);
							$my = 0;
						}elseif(abs($x - $army1_x) < abs($y - $army1_y)){
							$mx = 0;
							$my = ($army1_y- $y) / abs($y - $army1_y);
						}else{
							$mx = ($army1_x- $x) / abs($x - $army1_x);
							$my = ($army1_y - $y) / abs($y - $army1_y);
						}
			
						$soldier->move($mx, $my);
						array_push($cycle_log, $soldier->to_string() . " moved forward to (".$mx . ", " . $my.")" . $soldier->get_x() . ", " . $soldier->get_y());
					}
				}
			}else{
				// comfront armies (update life, energy), stay at position
				/*
				 * quick rules:
				* in combat, soldier looses 2pt of energy
				* randomly, reduce life for each strength point on the oposite side
				* 
				*/
				$army1_deaths = 0;
				if(in_array("army1", array_keys($xy))){
					for($i = 0; $i < $strength2; ++$i){
						$r = rand(0, count($xy["army1"])-1);
						$soldier = $xy["army1"][$r];
						$soldier->decrease_life(1);
					}
					
					foreach($xy["army1"] as $soldier){
						$soldier->decrease_energy(2);
							
						if($soldier->is_dead()){
							array_push($cycle_log, $soldier->to_string() . " died in combat");
							$army1_deaths += 1;
						}elseif($soldier->gave_up()){
							array_push($cycle_log, $soldier->to_string() . " gave up the battle");
						}
					}
				}
					
				$army2_deaths = 0;
				if(in_array("army2", array_keys($xy))){
					for($i = 0; $i < $strength1; ++$i){
						$r = rand(0, count($xy["army2"])-1);
						$soldier = $xy["army2"][$r];
						$soldier->decrease_life(1);
					}
						
					foreach($xy["army2"] as $soldier){
						$soldier->decrease_energy(2);
			
						if($soldier->is_dead()){
							array_push($cycle_log, $soldier->to_string() . " died in combat");
							$army2_deaths += 1;
						}elseif($soldier->gave_up()){
							array_push($cycle_log, $soldier->to_string() . " gave up the battle");
						}
					}
				}
					
				// reduce bravery for each army
				if(in_array("army1", array_keys($xy))){
					foreach($xy["army1"] as $soldier){
						$soldier->decrease_bravery($army1_deaths);
						if($soldier->has_fleed()){
							array_push($cycle_log, "coward, " . $soldier->to_string() . " fleed the battle");
						}
					}
				}
					
				if(in_array("army2", array_keys($xy))){
					foreach($xy["army2"] as $soldier){
						$soldier->decrease_bravery($army2_deaths);
						if($soldier->has_fleed()){
							array_push($cycle_log, "coward, " . $soldier->to_string() . " fleed the battle");
						}
					}
				}
			}
		}
		
		//var_dump($cycle_log);
		//print("<hr />");
		return $cycle_log;
	}
	
	public function run(){
		$data = array();
		$cycle = 1;
		$ended = false;
		
		$cycle_log = array();
		while(!$ended){
			$cycle_log[] = $this->confront_armies($cycle);
			
			$army1_lost = !$this->_army_a->can_fight();
			$army2_lost = !$this->_army_b->can_fight();
			
			$report = array();
			if($army1_lost && $army2_lost){
				$ended = true;
				$report[] = "There is no winning side - all soldiers, from both armies, died, fleed or gave up the battle";
			}elseif($army1_lost){
				$ended = true;
				$report[] = $this->_army_b->get_name() . " won the battle";
			}elseif($army2_lost){
				$ended = true;
				$report[] = $this->_army_a->get_name() . " won the battle";
			}

			++$cycle;
		}
		//var_dump($log);
		$data["log"] = $cycle_log;
		$data["report"] = array();
		$data["report"]["result"] = array();
		$data["report"]["result"] = $report;
		$data["report"]["army1"] = $this->_army_a;
		$data["report"]["army2"] = $this->_army_b;
		
		return $data;
	}
}
?>