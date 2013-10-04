<?php

/**
 * @package AnyMarkExtra
 */
namespace AnyMarkExtra\Patterns;

use AnyMark\Pattern\Pattern;
use ElementTree\ElementTree;

/**
 * @package AnyMarkExtra
 */
class Abbreviation extends Pattern
{
	private $collector;

	public function __construct(AbbreviationCollector $collector)
	{
		$this->collector = $collector;
	}

	public function getRegex()
	{
		$abbreviations = $this->collector->getAbbreviations();

		if (empty($abbreviations))
		{
			return '@^(?!$)$@';
		}

		$regex = '@(?<=^|\W)(';
		foreach ($abbreviations as $abbr => $title)
		{
			$regex .= $abbr . '|';
		}
		$regex = substr($regex, 0, -1) . ')(?=\W|$)@';

		return $regex;
	}

	public function handleMatch(
		array $match, ElementTree $parent, Pattern $parentPattern = null
	) {
		if ($parentPattern == $this)
		{
			return;
		}

		$abbr = $parent->createElement('abbr');
		$abbr->setAttribute('title', $this->collector->getDefinition($match[0]));
		$text = $parent->createText($match[0]);
		$abbr->append($text);

		return $abbr;
	}
}