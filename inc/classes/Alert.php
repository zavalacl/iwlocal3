<?php

/**
  * A class for creating and displaying HTML-formatted alerts 
  *
  * @author Taylor Collins <http://www.taylorcollinsdesign.com>
  * @copyright Taylor Collins 2010-11
  * @version 1.1.0
*/

class Alert
{
	
	protected $_alerts;
	protected $_cssClass;
	
	public function __construct()
	{
		$this->_alerts = array();
	}
	
	public function addAlert($alert, $cssClass=false)
	{
		$this->_cssClass = $cssClass;
		$this->_alerts[] = $alert;	
	}
	
	public function addAlerts($alerts, $cssClass=false)
	{
		$this->_cssClass = $cssClass;
		
		if(is_array($alerts)){
			$this->_alerts = array_merge($this->_alerts, $alerts);
		} else {
			throw new Exception('Alert::addAlerts expects an array as a parameter.');	
		}
	}
	
	public function getAlerts()
	{
		return $this->_alerts;	
	}
	
	public function hasAlerts()
	{
		return ($this->_alerts) ? true : false; 	
	}
	
	public function __toString()
	{
		if($this->hasAlerts()){
			$temp = '<ul class="alerts';
			if($this->_cssClass) $temp .= ' '.$this->_cssClass;
			$temp .= '">';
			foreach($this->_alerts as $alert){
				$temp .= '<li>'.$alert.'</li>';
			}
			$temp .= '</ul>';
			
			return $temp;
		} else {
			return '';	
		}
	}
}

?>