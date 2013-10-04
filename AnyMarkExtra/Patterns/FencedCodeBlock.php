<?php

/**
 * @package AnyMarkExtra
 */
namespace AnyMarkExtra\Patterns;

use AnyMark\Pattern\Pattern;
use AnyMark\Pattern\Patterns\Code;
use ElementTree\ElementTree;
use ElementTree\Element;

/**
 * @package AnyMarkExtra
 */
class FencedCodeBlock extends Code
{
	public function getRegex()
	{
		return '@
			(?<=\n|^)

			~{3,}(?<attr>.+)?
			\n+
			(?<code>(\n|.)+?)
			\n+
			~{3,}

			(?=\n|$)
		@x';
	}

	public function handleMatch(
		array $match, ElementTree $parent, Pattern $parentPattern = null
	) {
		$code = $this->createCodeReplacement($match['code'] . "\n", true, $parent);

		$this->addAttributes($code->getChildren()[0], $match['attr']);

		return $code;
	}

	private function addAttributes(Element $pre, $attrMatch)
	{
		$attributes = $this->getAttributes($attrMatch);

		foreach ($attributes as $attribute => $values)
		{
			if ($values === array())
			{
				continue;
			}
			$pre->setAttribute($attribute, implode(' ', $values));
		}
	}

	private function getAttributes($attrMatch)
	{
		$attributes['class'] = $this->getClasses($attrMatch);
		$attributes['id'] = $this->getIds($attrMatch);

		return $attributes;
	}

	private function getIds($attrMatch)
	{
		preg_match_all(
			'@
				(?<=
					~~~
					|
					\s
					|
					{
				)

				\#
				(?<id>\S+?)

				(?=}|\s|$)
			@x',
			$attrMatch,
			$matches
		);

		return $matches['id'];
	}

	private function getClasses($attrMatch)
	{
		$regex =
		'@
			(?<=
				^
				|
				\s
				|
				{
			)
			\.?
			(?<class>[^#\s{}]+?)

			(?=}|\s|$)
		@x';

		preg_match_all($regex, $attrMatch, $matches);

		return $matches['class'];
	}
}