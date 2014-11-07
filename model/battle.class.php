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
		$this->_army_a->position_army(-1);
		$this->_army_b->position_army(1);
	}
	
	protected function confront_armies($cycle){
		// group soldiers by battleground coordinates
		
		$cycle_log = array();
		
		$coord = array();
		$army1 = $this->_army_a->get_soldiers();
		$sumx1 = 0;
		$sumy1 = 0;
		$count1 = 0;
		foreach($army1 as $soldier){
			if($soldier->can_figth()){
				$x = $soldier->get_x();
				$y = $soldier->get_y();
				
				$sumx1 += $x;
				$sumy1 += $y;
				++$count1;
				
				$key = (string)$x + ':' + (string)$y;
				
				if(!in_array($key, array_keys($coord))){
					$coord[$key] = array("army1"=>array());
				}
				
				array_push($coord[$key]["army1"], $soldier);
			}	
		}
			
		$army2 = $this->_army_b->get_soldiers();
		$sumx2 = 0;
		$sumy2 = 0;
		$count2 = 0;
		foreach($army2 as $soldier){
			if($soldier->can_figth()){
				$x = $soldier->get_x();
				$y = $soldier->get_y();
		
				$sumx2 += $x;
				$sumy2 += $y;
				++$count2;
				
				$key = (string)$x + ':' + (string)$y;
		
				if(!in_array($key, array_keys($coord))){
					$coord[$key] = array("army2"=>array());
				}
		
				array_push($coord[$key]["army2"], $soldier);
			}
		}
		
		// army centers
		$army1_x = $sumx1 / $count1;
		$army1_y = $sumy1 / $count1;

		$army2_x = $sumx2 / $count2;
		$army2_y = $sumy2 / $count2;
		
		foreach($coord as $xy){
			// calculate total strength of army1 at present coordinates
			$strength1 = 0;
			foreach($xy["army1"] as $soldier){
				$strength1 += $soldier->get_strength();
			}
			
			// calculate total strength of army2 at present coordinates
			$strength2 = 0;
			foreach($xy["army2"] as $soldier){
				$strength2 += $soldier->get_strength();
			}			
		}
		
		// compare strengths
		if($strength2 == 0){
			//move army 2
			foreach($xy["army2"] as $soldier){
				$soldier->decrease_energy(1);
				
				// move toward of the center of army 1
				$x = $soldier->get_x();
				$x = $soldier->get_y();
				
				if(abs($x - $army1_x) > abs($y - $army1_y)){
					$mx = abs($x - $army1_x) * abs($x - $army1_x) / ($x - $army1_x);
					$my = 0;
				}elseif(abs($x - $army1_x) < abs($y - $army1_y)){
					$mx = 0;
					$my = abs($y - $army1_y) * abs($y - $army1_y) / ($y - $army1_y);
				}else{
					$mx = abs($x - $army1_x) * abs($x - $army1_x) / ($x - $army1_x);
					$my = abs($y - $army1_y) * abs($y - $army1_y) / ($y - $army1_y);
				}

				$soldier->move($mx, $my);
				array_push($cycle_log, $soldier->get_name() . " of " . $soldier->get_army() . " moved forward");
			}
		}elseif($strength1 == 0){
			// move army 1	
			foreach($xy["army1"] as $soldier){
				$soldier->decrease_energy(1);
			
				// move toward of the center of army 1
				$x = $soldier->get_x();
				$x = $soldier->get_y();
			
				if(abs($x - $army1_x) > abs($y - $army1_y)){
					$mx = abs($x - $army1_x) * abs($x - $army1_x) / ($x - $army1_x);
					$my = 0;
				}elseif(abs($x - $army1_x) < abs($y - $army1_y)){
					$mx = 0;
					$my = abs($y - $army1_y) * abs($y - $army1_y) / ($y - $army1_y);
				}else{
					$mx = abs($x - $army1_x) * abs($x - $army1_x) / ($x - $army1_x);
					$my = abs($y - $army1_y) * abs($y - $army1_y) / ($y - $army1_y);
				}
			
				$soldier->move($mx, $my);
				array_push($cycle_log, $soldier->get_name() . " of " . $soldier->get_army() . " moved forward");
			}
		}else{
			// comfront armies (update life, energy), stay at position
			/*
			 * quick rules:
			 * in combat, soldier looses 2pt of energy
			 * if on stronger side, looses 1pt of life
			 * if on weaker side, looses 2pt of life
			 */
			$army1_deaths = 0;
			foreach($xy["army1"] as $soldier){
				if($strength1 >= $strength2){
					$soldier->decrease_energy(2);
					$soldier_decrease_life(1);
		
				}elseif($strength1 < $strength2){
					$soldier->decrease_energy(2);
					$soldier_decrease_life(2);
					
				}
				
				if($soldier->is_dead()){
					array_push($cycle_log, $soldier->get_name() . " of " . $soldier->get_army() . " died in combat");
					$army1_deaths += 1;
				}elseif($soldier->gave_up()){
					array_push($cycle_log, $soldier->get_name() . " of " . $soldier->get_army() . " gave up the battle");
				}
			}
			
			
			$army2_deaths = 0;
			foreach($xy["army2"] as $soldier){
				if($strength2 >= $strength1){
					$soldier->decrease_energy(2);
					$soldier_decrease_life(1);
			
				}elseif($strength2 < $strength2){
					$soldier->decrease_energy(2);
					$soldier_decrease_life(2);
						
				}
			
				if($soldier->is_dead()){
					array_push($cycle_log, $soldier->get_name() . " of " . $soldier->get_army() . " died in combat");
					$army2_deaths += 1;
				}elseif($soldier->gave_up()){
					array_push($cycle_log, $soldier->get_name() . " of " . $soldier->get_army() . " gave up the battle");
				}
			}
			
			// reduce bravery for each army
			foreach($xy["army1"] as $soldier){
				$soldier->decrease_bravery($army1_deaths);
				if($soldiet->has_fleed()){
					array_push($cycle_log, "coward, " . $soldier->get_name() . " of " . $soldier->get_army() . " fleed the battle");
				}
			}
			
			foreach($xy["army2"] as $soldier){
				$soldier->decrease_bravery($army2_deaths);
				if($soldiet->has_fleed()){
					array_push($cycle_log, "coward, " . $soldier->get_name() . " of " . $soldier->get_army() . " fleed the battle");
				}
			}
		}
		
		return $cycle_log;
	}
	
	public function run($log){
		$cycle = 1;
		$ended = false;
		
		while(!$ended){
			$cycle_log = $this->confront_armies($cycle);
			$log[$cycle] = $cycle_log;
			
			++$cycle;
		}
		
		return $log;
	}
}
?>