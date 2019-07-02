<?php
require_once('autoloader.php');

class Main
{
	public function sum($a, $b)
	{
		$event = new Event(
			'onBeforeSum', 
			array('operands' => array($a, $b))
		);
		$event->send();
		
		if ($event->hasErrors()) {
			return $event->getErrors();
		}
		list($a, $b) = $event->getParameter('operands');
		return $a + $b;
	}
}

$manager = EventManager::getInstance();
$manager->addEventHandler('onBeforeSum', array(Handler::class, 'handleSum'));

$object = new Main();
var_dump($object->sum(2, 3));
