<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class AnyMarkExtra_Patterns_DefinitionDescriptionTest extends \AnyMark\UnitTests\Support\PatternReplacementAssertions
{
	public function setup()
	{
		$this->dd = new \AnyMarkExtra\Patterns\DefinitionDescription();
	}

	public function getPattern()
	{
		return $this->dd;
	}

	public function createDd($text)
	{
		$dd = $this->elementTree()->createElement('dd');
		$text = $this->elementTree()->createText($text);
		$dd->append($text);

		return $dd;
	}

	/**
	 * @test
	 */
	public function aDescriptionFollowsATermOnANewLineAndStartsAfterAColon()
	{
		$text =
'term a
: explanation
';

		$this->assertEquals($this->createDd('explanation'), $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function explanationCanContinueUnindentedOnNewLine()
	{
		$text =
'term a
: explanation on
multiple lines
';

		$this->assertEquals(
			$this->createDd("explanation on\nmultiple lines"),
			$this->applyPattern($text)
		);
	}

	/**
	 * @test
	 */
	public function explanationCanContinueIndentedOnNewLine()
	{
		$text =
'term a
: explanation on
  multiple lines
';

		$this->assertEquals(
			$this->createDd("explanation on\nmultiple lines"),
			$this->applyPattern($text)
		);
	}

	/**
	 * @test
	 */
	public function descriptionCanBeTabIndented()
	{
		$text =
'term a
:   explanation on
    multiple lines
';

		$this->assertEquals(
			$this->createDd("explanation on\nmultiple lines"),
			$this->applyPattern($text)
		);
	}

	/**
	 * @test
	 */
	public function moreThanOneDescriptionForATermIsPossible()
	{
		$text =
'term a
: explanation on
more than one line
: second explanation
: third explanation
';
		$this->assertEquals(
			$this->createDd("explanation on\nmore than one line"),
			$this->applyPattern($text)
		);
	}

	/**
	 * @test
	 */
	public function aDescriptionCanBeSharedByMultipleTerms()
	{
		$text =
'term a
term b
:   explanation on
    more than one line
';
		$this->assertEquals(
			$this->createDd("explanation on\nmore than one line"),
			$this->applyPattern($text)
		);
	}

	/**
	 * @test
	 */
	public function definitionsCanHaveMultipleParagraphs()
	{
		$text =
'term a
:   explanation on
    more than one line

    explanation continues with new paragraph
';

		$this->assertEquals(
			$this->createDd("explanation on\nmore than one line\n\nexplanation continues with new paragraph"),
			$this->applyPattern($text)
		);
	}

	/**
	 * @test
	 */
	public function defintionsCanHaveMultipleDescriptionsWithMultipleParagraphs()
	{
		$text =
'term a
:   explanation on
    more than one line

    explanation continues with new paragraph

:   second explanation
';

		$this->assertEquals(
				$this->createDd("explanation on\nmore than one line\n\nexplanation continues with new paragraph"),
				$this->applyPattern($text)
		);
	}

	/**
	 * @test
	 */
	public function blankLineBeforeDescriptionLeavesSpaceForParagraph()
	{
		$text =
'term a

: explanation
';

		$this->assertEquals($this->createDd("\n\nexplanation\n\n"), $this->applyPattern($text));
	}
}