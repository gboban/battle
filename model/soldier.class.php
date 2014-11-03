<?php
class Soldier{
	private $_army_name = "";
	private $_name = "";
	private $_pos_x = 0;
	private $_pos_y = 0;
	
	/*
	 * all values must range 0..100
	 */
	private $_energy = 0;
	private $_experience = 0;
	private $_life = 0;
	private $_strength = 0;
	private $_bravery = 0;
	
	public function __construct($name, $energy, $experience, $life, $strength, $bravery){
		$this->_name = $name;
		
		// @todo add verification thart values are between 0 and 100 inclusive
		$this->_energy = $energy;
		$this->_experience = $experience;
		$this->_life = $life;
		$this->_strength = $strength;
		$this->_bravery = $bravery;	
	}
	
	public function get_name(){
		return $this->_name;
	}
	
	public function get_energy(){
		return $this->_energy;
	}
	
	public function decrease_energy($ammount){
		$this->_energy -= $ammount;
		if($this->_energy < 0){
			$this->_energy = 0;
		}
		
		// @todo log if soldiers give up (energy==0)
		
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
	
		// @todo log if soldier dies
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
	
		// @todo log if soldier flees
		
		return $this->_bravery;
	}
	
	public function get_x(){
		return $this->_pos_x;
	}
	
	public function get_y(){
		return $this->_pos_y;
	}
	
	public function move($x, $y){
		$this->_pos_x += $x;
		$this->_pos_y += $y;
		
		// @todo log move
	}
	
	public function gave_up(){
		return !($this->_energy > 0);
	}
	
	public function is_alive(){
		return $this->_life > 0;
	}
	
	public function has_fleed(){
		return $this->_bravery > 0;
	}
}
?>