<?php

/**
 * @package AnyMarkExtra
 */
namespace AnyMarkExtra\Patterns;

use AnyMark\Pattern\Pattern;
use ElementTree\ElementTree;
use AnyMark\Pattern\Patterns\ManualHtmlInline;

/**
 * @package AnyMarkExtra
 */
class ManualHtmlWithMarkdownInline extends ManualHtmlInline
{

	public function getRegex()
	{
		return parent::getRegex();
	}

	public function handleMatch(
		array $match, ElementTree $parent, Pattern $parentPattern = null
	) {
		$element = parent::handleMatch($match, $parent, $parentPattern);

		if (!$element)
		{
			return;
		}

		$element->setAttribute('markdown', 'md');
		$element->removeAttribute('markdown');

		return $element;
	}
}