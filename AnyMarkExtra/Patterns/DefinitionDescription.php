<?php

/**
 * @package AnyMarkExtra
 */
namespace AnyMarkExtra\Patterns;

use AnyMark\Pattern\Pattern;
use ElementTree\ElementTree;

/**
 * @package
 */
class DefinitionDescription extends Pattern
{
	/**
	 * Definition term
	 * :   Colon up to three spaces, definition after one
	 *     or more spaces.
	 * 
	 *     A blank line to create paragraphs. New paragraphs
	 *     indented (at least) four spaces or a tab.
	 * 
	 * Definition term
	 * :   Multiple descriptions also can contain paragraphs
	 *     or other markup.
	 * 
	 *     A paragraph is created by leaving a blank line before
	 *     as explained above.
	 * 
	 * :   Multiple descriptions are separated with a colon.
	 * 
	 * Definition term
	 * 
	 * :   Description with one sentence being a paragraph
	 *     by leaving a blank line before.
	 * 
	 * Term A
	 * Term B
	 * 
	 * :   Multiple terms can exist for one or more descriptions.
	 * 
	 */
	public function getRegex()
	{
		return
			'@
			(?<=(?<newline_before>\n\n)|\n)

			:
			(?<post_colon_indent>[ ]{0,3})
	
			(?<description>
				.+
				(
					\n(?![ ]{0,3}:\s).+
					|
					\n\n([ ]{4}|\t).+
				)*?
			)
	
			(?=
				\n[ ]{0,3}:[ ]
				|
				\n\n[ ]{0,3}\S
				|
				(\n)*$
			)
			@x';
	}

	public function handleMatch(
		array $match, ElementTree $parent, Pattern $parentPattern = null
	) {
		# unindent
		$contents = preg_replace(
			"@^" . $match['post_colon_indent'] . "@",
			"^",
			$match['description']
		);
		$contents = preg_replace(
			"@\n" . $match['post_colon_indent'] . " @",
			"\n",
			$match['description']
		);

		if (!empty($match['newline_before']))
		{
			$contents = "\n\n" . $contents . "\n\n";
		}

		$dd = $parent->createElement('dd');
		$dd->append($parent->createText($contents));

		return $dd;
	}
}