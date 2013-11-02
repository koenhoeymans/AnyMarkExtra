<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class AnyMarkExtra_Patterns_AbbreviationDefinitionTest extends \AnyMark\UnitTests\Support\PatternReplacementAssertions
{
	public function setup()
	{
		$this->eventMapper = new \AnyMarkExtra\UnitTests\Support\EventMapperMock();
		$this->definition = new \AnyMarkExtra\Patterns\AbbreviationDefinition();
		$this->definition->register($this->eventMapper);
	}

	public function getPattern()
	{
		return $this->definition;
	}

	/**
	 * @test
	 */
	public function transformsAbbreviationsToTemporaryElements()
	{
		$text = 'paragraph

*[foo]: bar

paragraph';

		$abbr = $this->elementTree()->createElement('abbreviation');
		$abbr->setAttribute('name', 'foo');
		$abbr->append($abbr->createText('bar'));

		$this->assertEquals($abbr, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function canBeFollowedByAnotherAbbreviation()
	{
		$text = 'paragraph

*[foo]: bar
*[bar]: foo

paragraph';

		$abbr = $this->elementTree()->createElement('abbreviation');
		$abbr->setAttribute('name', 'foo');
		$abbr->append($abbr->createText('bar'));

		$this->assertEquals($abbr, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function canBePrecededByNonBlankLine()
	{
		$text = 'paragraph

not an abbr
*[foo]: bar

paragraph';

		$abbr = $this->elementTree()->createElement('abbreviation');
		$abbr->setAttribute('name', 'foo');
		$abbr->append($abbr->createText('bar'));

		$this->assertEquals($abbr, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function whiteSpaceBeforeColonIsAllowed()
	{
		$text = 'paragraph

*[foo] : bar

paragraph';

		$abbr = $this->elementTree()->createElement('abbreviation');
		$abbr->setAttribute('name', 'foo');
		$abbr->append($abbr->createText('bar'));

		$this->assertEquals($abbr, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function removesAbbreviationsAfterParsing()
	{
		$div = $this->elementTree()->createElement('div');
		$abbr = $this->elementTree()->createElement('abbreviation');
		$abbr->setAttribute('name', 'foo');
		$abbr->append($abbr->createText('bar'));
		$div->append($abbr);
		$div->append($div->createText('some text'));

		$event = new \AnyMark\Events\AfterParsing($div);
		$callback = $this->eventMapper->getCallback('AfterParsingEvent');
		$callback($event);

		$this->assertEquals('<div>some text</div>', $div->toString());
	}
}