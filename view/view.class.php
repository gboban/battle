<?php
class View{
	private $_data = null;
	private $_template = null;
	
	public function __construct($an_template, $dataset = null){
		$this->_template = $an_template;
		
		if($dataset != null){
			$this->_data = $dataset;
		}
	}
	
	public function render(){
		require($this->_template);
	}
}
?>