<?php

/**
 * @package AnyMarkExtra
 */
namespace AnyMarkExtra\UnitTests\Support;

/**
 * @package AnyMarkExtra
 */
class EventMapperMock extends \AnyMark\UnitTests\Support\EventMapperMock
{
	public function registerForEvent($event, Callable $callback)
	{
		parent::registerForEvent($event, $callback);
		return $this;
	}

	public function first()
	{}
}