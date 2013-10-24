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
class HeaderWithAttributes extends \AnyMark\Pattern\Patterns\Header
{
	public function getRegex()
	{
		return
		'@
		(?<=^|\n)
		(?<setext>
			((?<pre>[-=+*^#]{3,})\n)?
			(?<text>\S.*?)(?<attr>\s+{.+})?\n
			(?<post>[-=+*^#]{3,})
		)
		(?=\n|$)

		|

		(?<=^|\n)
		(?<atx>(?J)
			(?<level>[#]{1,6})[ ]?
			(?<text>[^\n]+?)
			([ ]?[#]*)
			(?<attr>\s+{.+})?
		)
		(?=\n|$)
		@x';
	}

	public function handleMatch(
		array $match, ElementTree $parent, Pattern $parentPattern = null
	) {
		$header = parent::handleMatch($match, $parent);

		$attributes = $this->getAttributes($match);
		foreach ($attributes as $attribute => $value)
		{
			$header->setAttribute($attribute, $value);
		}

		return $header;
	}

	private function getAttributes($match)
	{
		$attributes = array();
		if (isset($match['attr']))
		{
			if ($class = $this->getClasses($match['attr']))
			{
				$attributes['class'] = $class;
			}
			if ($id = $this->getIds($match['attr']))
			{
				$attributes['id'] = $id;
			}
		}

		return $attributes;
	}

	private function getIds($match)
	{
		$regex =
		'@
			\#
			(?<id>[^#\s{}.]+)
		@x';

		preg_match_all($regex, $match, $matches, PREG_PATTERN_ORDER);

		if (empty($matches))
		{
			return array();
		}
		return implode(' ', $matches['id']);
	}

	private function getClasses($attrMatch)
	{
		$regex =
		'@
			\.
			(?<class>[^#\s{}.]+)
		@x';

		preg_match_all($regex, $attrMatch, $matches, PREG_PATTERN_ORDER);

		if (empty($matches))
		{
			return array();
		}
		return implode(' ', $matches['class']);
	}
}