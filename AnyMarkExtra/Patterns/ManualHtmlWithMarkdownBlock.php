<?php

/**
 * @package AnyMarkExtra
 */
namespace AnyMarkExtra\Patterns;

use AnyMark\Pattern\Pattern;
use ElementTree\ElementTree;
use AnyMark\Pattern\Patterns\ManualHtmlBlock;

/**
 * @package AnyMarkExtra
 */
class ManualHtmlWithMarkdownBlock extends ManualHtmlBlock
{

	public function getRegex()
	{
		return parent::getRegex();
	}

	public function handleMatch(
		array $match, ElementTree $parent, Pattern $parentPattern = null
	) {
		$class = get_class($this);
		if (get_class($parentPattern) === get_class($this))
		{
			$match['empty_line_before'] = null;
		}

		$element = parent::handleMatch($match, $parent, $parentPattern);

		if (!$element)
		{
			return;
		}

		if ($element->getChildren() && !$match['comment'])
		{
			$text = $element->getChildren()[0];
			$content = $text->toString();

			if((substr($text->toString(), 0, 1) === "\n")
				&& (substr($text->toString(), 0, 2) !== "\n\n")
			) {
				$content = "\n" . $content;
			}

			$element->remove($text);
			$element->append($element->createText($content));
		}

		$element->setAttribute('markdown', 'md');
		$element->removeAttribute('markdown');

		return $element;
	}
}