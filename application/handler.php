<?php
class Handler
{
	public static function handleSum(Event $event)
	{
		$parameters = $event->getParameters();
		if (empty($parameters['operands'])) {
			return false;
		}
		list($a, $b) = $parameters['operands'];
		if ($a == $b) {
			$event->addError('ARGUMENT_ERROR', 'Operands cannot have the same value');
			return false;
		}
		$a *= 2;
		$b *= 3;
		$event->setParameter('operands', array($a, $b));
		return true;
	}
}
