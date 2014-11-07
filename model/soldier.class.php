<?php
class Soldier{
	private $_army_name = "";
	private $_name = "";
	private $_pos_x = 0;
	private $_pos_y = 0;
	
	private $_cycle = 0;
	
	/*
	 * all values must range 0..100
	 */
	private $_energy = 0;
	private $_experience = 0;
	private $_life = 0;
	private $_strength = 0;
	private $_bravery = 0;
	
	public function __construct($army_name, $name, $energy, $experience, $life, $strength, $bravery){
		$this->_army_name = $army_name;
		$this->_name = $name;
		
		// @todo add verification thart values are between 0 and 100 inclusive
		$this->_energy = $energy;
		$this->_experience = $experience;
		$this->_life = $life;
		$this->_strength = $strength;
		$this->_bravery = $bravery;	
	}
	
	public function to_string(){
		$str = $this->_name . " of " . $this->_army_name;
		
		$str .= "(";
		$str .= "energy: " . $this->_energy;
		$str .= ", experience: " . $this->_experience;
		$str .= ", life: " . $this->_life;
		$str .= ", strength: " . $this->_strength;
		$str .= ", bravery: " . $this->_bravery;
		$str .= ")";
		
		return $str;
	}
	
	function get_cycle(){
		return $this->_cycle;
	}
	
	function set_cycle($a_cycle){
		$this->_cycle = $acycle;
	}
	
	public function get_name(){
		return $this->_name;
	}
	
	public function get_army(){
		return $this->_army_name;
	}
	
	public function get_energy(){
		return $this->_energy;
	}
	
	public function decrease_energy($ammount){
		$this->_energy -= $ammount;
		if($this->_energy < 0){
			$this->_energy = 0;
		}
		
		return $this->_energy;
	}
	
	public function get_experience(){
		return $this->_experience;
	}
	
	public function get_life(){
		return $this->_life;
	}
	
	public function decrease_life($ammount){
		$this->_life -= $ammount;
		if($this->_life < 0){
			$this->_life = 0;
		}
	
		return $this->_life;
	}
	
	public function get_strength(){
		return 0.01 * ($this->_strength * $this->_energy);
	}
	
	public function get_bravery(){
		return $this->_strength;
	}
	
	public function decrease_bravery($ammount){
		$this->_bravery -= $ammount;
		if($this->_bravery < 0){
			$this->_bravery = 0;
		}
		
		return $this->_bravery;
	}
	
	public function set_xy($x, $y){
		$this->_pos_x = $x;
		$this->_pos_y = $y;
	}
	
	public function get_x(){
		return $this->_pos_x;
	}
	
	public function get_y(){
		return $this->_pos_y;
	}
	
	public function set_position($x, $y){
		$this->_pos_x = $x;
		$this->_pos_y = $y;
	}
	
	public function move($x, $y){
		$this->_pos_x += $x;
		$this->_pos_y += $y;
		
		// decrement energy
		//$this->_energy -= 1;
	}
	
	public function gave_up(){
		return !($this->_energy > 0);
	}
	
	public function is_alive(){
		return $this->_life > 0;
	}
	
	public function is_dead(){
		return !$this->is_alive();
	}
	
	public function has_fleed(){
		return $this->_bravery == 0;
	}
	
	public function can_fight(){
		if($this->gave_up()) return false;
		if($this->is_dead()) return false;
		if($this->has_fleed()) return false;

		return true;
	}
}
?>