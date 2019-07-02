<?php
class EventManager
{
	private static $instance;
	private $pool = array();
	
	private function __construct()
	{
		// Here you can initialize Database connection and fill event pool
		// by fetching events and handlers
	}
	
	private function __clone()
	{}
	
	/**
	 * Singleton.
	 * There should be only one instance of EventManager at the runtime.
	 * 
	 * @return EventManager $instance
	 */
	public static function getInstance()
	{
		if (self::$instance === null) {
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	/**
	 * Adds handler by event name to the pool of handlers
	 * 
	 * @param string $eventName
	 * @param array $handler Handler class and Handler method as array elements
	 * 
	 * @throw Exception in case if the handler is already registered for event
	 * @return void
	 */
	public function addEventHandler($eventName, array $handler)
	{
		$fullName = $this->prepareHandler($handler);
		$handlers = $this->getHandlers($eventName);
		if (in_array($fullName, $handlers)) {
			throw new Exception(sprintf(
				'Handler %s is already registered for Event %s',
				$fullName,
				$eventName
			));
		}
		$this->pool[] = array(
			'event' => $eventName,
			'handler' => $fullName
		);
	}
	
	/**
	 * Removes specified handler from pool by event name
	 *
	 * @param string $eventName
	 * @param array $handler Handler class and Handler method as array elements
	 * @return void
	 */
	public function removeEventHandler($eventName, array $handler)
	{
		$fullName = $this->prepareHandler($handler);
		$handlers = $this->getHandlers($eventName);
		if (in_array($fullName, $handlers)) {
			$id = array_search($fullName, $handlers);
			unset($this->pool[$id]);
		}
	}
	
	private function prepareHandler($handler)
	{
		list($name, $method) = $handler;
		return $name . '::' . $method;
	}

	private function getHandlers($eventName)
	{
		$handlers = array();
		foreach ($this->pool as $index => $item) {
			if ($item['event'] === $eventName) {
				$handlers[$index] = $item['handler'];
			}
		}
		return $handlers;		
	}
	
	/**
	 * @param Event $event - instance of fired Event
	 * @return bool $result Stops calling handlers if one of them returns false
	 */
	public function callHandlers(Event $event)
	{
		$name = $event->getName();
		$handlers = $this->getHandlers($name);
		foreach ($handlers as $handler) {
			$result = $handler($event);
			if ($result === false) {
				return false;
			}
		}
		return true;
	}
}
