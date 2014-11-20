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
class Index{
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