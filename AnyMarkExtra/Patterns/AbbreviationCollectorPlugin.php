<?php

/**
 * @package AnyMarkExtra
 */
namespace AnyMarkExtra\Patterns;

use AnyMark\PublicApi\BeforeParsingEvent;
use Epa\Plugin;
use Epa\EventMapper;

/**
 * @package AnyMarkExtra
 */
class AbbreviationCollectorPlugin implements Plugin, AbbreviationCollector
{
	private $abbreviations = array();

	public function register(EventMapper $mapper)
	{
		$mapper->registerForEvent(
			'BeforeParsingEvent', function(BeforeParsingEvent $event) {
				$event->setText($this->process($event->getText()));
			}
		);
	}

	public function getAbbreviations()
	{
		return $this->abbreviations;
	}

	public function getDefinition($name)
	{
		if (isset($this->abbreviations[$name]))
		{
			return $this->abbreviations[$name];
		}
	}

	private function process($text)
	{
		return preg_replace_callback(
			'@(?<=^|\n)\*\[(?<name>.+?)][ ]*:[ ]*(?<def>.+)(?=\n|$)@x',
			function($match) { $this->save($match['name'], $match['def']); },
			$text
		);
	}

	private function save($name, $definition)
	{
		$this->abbreviations[$name] = $definition;
	}
}