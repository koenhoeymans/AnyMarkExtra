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
class Strong extends \AnyMark\Pattern\Patterns\Strong
{
	public function getRegex()
	{
		return
		'@
			(?<![_*])
			(?<!\\\)
			(?<marker>[_*])
			(?<!\w_)
			\g{marker}
			(?=\S)
				(
					(?R)
					|
					(?!\g{marker}).
					|
					\g{marker}(?!\g{marker}).+?(?<=\S)\g{marker}
				)+?
			(?<=\S)
			(?<!\\\)
			\g{marker}
			(?(?=_)(?!_\w))
			\g{marker}
			(?!\w+\g{marker}\g{marker})
		@x';
	}

	public function handleMatch(
		array $match, ElementTree $parent, Pattern $parentPattern = null
	) {
		$marker = $match['marker'] . $match['marker'];
		if (substr($match[0], 0, 2) !== $marker || substr($match[0], -2) !== $marker)
		{
			return;
		}
		if (substr($match[0], 0, 4) === '____' && substr($match[0], -4) === '____')
		{
			return;
		}

		$strong = $parent->createElement('strong');
		$strong->append($parent->createText(substr($match[0], 2, -2)));

		return $strong;
	}
}