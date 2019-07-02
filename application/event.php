<?php
class Event
{
	private $name;
	private $parameters;
	private $errors;
	
	public function __construct($name, array $parameters)
	{
		$this->name = $name;
		$this->parameters = $parameters;
	}
	
	public function getName()
	{
		return $this->name;
	}
	
	public function getParameter($name)
	{
		if (isset($this->parameters[$name])) {
			return $this->parameters[$name];
		}
		return null;
	}
	
	public function getParameters()
	{
		return $this->parameters;
	}
	
	public function setParameter($name, $value)
	{
		$this->parameters[$name] = $value;
	}
	
	public function hasErrors()
	{
		return !empty($this->errors);
	}
	
	public function getErrors()
	{
		return $this->errors;
	}
	
	public function addError($message, $code)
	{
		$this->errors[$code] = $message;
	}
	
	public function send()
	{
		$manager = EventManager::getInstance();
		$manager->callHandlers($this);
	}
}
