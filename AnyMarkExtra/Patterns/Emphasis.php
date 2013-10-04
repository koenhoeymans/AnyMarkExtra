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
class Emphasis extends \AnyMark\Pattern\Patterns\Emphasis
{
	public function getRegex()
	{
		return
		'@
			(?<!\\\)
			(

			[*]
			(?=\S)
				(
					(?R)
					|
					[^*]
					|
					([*]([^*]|(?2))+?(?<=\S)[*])
				)+?
			(?<=\S)
			(?<!\\\)
			[*]

			|

			(?<!\w)
			[_]
			(?=\S)
				(
					(?R)
					|
					[^_]
					|
					([_]([^_]|(?6))+?(?<=\S)[_])
				)+?
			(?<=\S)
			(?<!\\\)
			[_]
			(?!\w)
	
			)
		@x';
	}

	public function handleMatch(
		array $match, ElementTree $parent, Pattern $parentPattern = null
	) {
		if (substr($match[0], 0, 2) === '**' && substr($match[0], -2) === '**')
		{
			return;
		}
		if (substr($match[0], 0, 2) === '__' && substr($match[0], -2) === '__')
		{
			return;
		}

		$em = $parent->createElement('em');
		$em->append($parent->createText(substr($match[0], 1, -1)));

		return $em;
	}
}