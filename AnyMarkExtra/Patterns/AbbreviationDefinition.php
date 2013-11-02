<?php

/**
 * @package AnyMarkExtra
 */
namespace AnyMarkExtra\Patterns;

use AnyMark\Pattern\Pattern;
use ElementTree\ElementTree;
use Epa\Plugin;
use Epa\EventMapper;
use AnyMark\PublicApi\AfterParsingEvent;

/**
 * @package AnyMarkExtra
 */
class AbbreviationDefinition extends Pattern implements Plugin, AbbreviationCollector
{
	private $abbreviations = array();

	public function getRegex()
	{
		return '@(^|\n)\*\[(?<name>.+?)][ ]*:[ ]*(?<def>.+)(?=\n|$)@x';
	}

	public function register(EventMapper $mapper)
	{
		$mapper->registerForEvent(
			'AfterParsingEvent',
			function(AfterParsingEvent $event) {
				$this->removeDefinitions($event->getTree());
			}
		)->first();
	}

	public function handleMatch(
		array $match, ElementTree $parent, Pattern $parentPattern = null
	) {
		$this->save($match['name'], $match['def']);

		$abbr = $parent->createElement('abbreviation');
		$abbr->setAttribute('name', $match['name']);
		$abbr->append($abbr->createText($match['def']));

		return $abbr;
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

	private function save($name, $definition)
	{
		$this->abbreviations[$name] = $definition;
	}

	private function removeDefinitions(ElementTree $tree)
	{
		$query = $tree->createQuery();
		$definitions = $query->find(
			$query->allElements($query->withName('abbreviation'))
		);
		foreach ($definitions as $defintion)
		{
			$tree->remove($defintion);
		}
	}
}