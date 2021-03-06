<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class AnyMarkExtra_Patterns_DefinitionListTest extends \AnyMark\UnitTests\Support\PatternReplacementAssertions
{
	public function setup()
	{
		$this->dl = new \AnyMarkExtra\Patterns\DefinitionList();
	}

	public function getPattern()
	{
		return $this->dl;
	}

	public function createDl($text)
	{
		$dl = $this->elementTree()->createElement('dl');
		$text = $this->elementTree()->createText($text);
		$dl->append($text);

		return $dl;
	}

	/**
	 * @test
	 */
	public function aDlConsistsOfTermFollowedOnNewLineByColonAndDescription()
	{
		$text =
'paragraph

a term
:	explanation

paragraph';

		$this->assertEquals(
			$this->createDl("a term\n:	explanation"), $this->applyPattern($text)
		);
	}

	/**
	 * @test
	 */
	public function canBeStartOfInputString()
	{
				$text =
'term
:	explanation

paragraph';

		$this->assertEquals(
			$this->createDl("term\n:	explanation"), $this->applyPattern($text)
		);
	}

	/**
	 * @test
	 */
	public function canBeEndOfInputString()
	{
		$text =
'paragraph

term
:	explanation';

		$this->assertEquals(
			$this->createDl("term\n:	explanation"), $this->applyPattern($text)
		);
	}

	/**
	 * @test
	 */
	public function thereCanBeMultipleDescriptions()
	{
		$text =
'paragraph

term c
:	explanation x
:	explanation y

paragraph';

		$this->assertEquals(
			$this->createDl("term c\n:	explanation x\n:	explanation y"), $this->applyPattern($text)
		);
	}

	/**
	 * @test
	 */
	public function thereCanBeMultipleTermsAndDescriptionsWithParagraphs()
	{
		$text =
'paragraph

term a
term b
term c
:	explanation x

	Continuation of explanation.

:	explanation y

	Continuation of explanation.

		code

paragraph';

		$listText =
'term a
term b
term c
:	explanation x

	Continuation of explanation.

:	explanation y

	Continuation of explanation.

		code';

		$this->assertEquals($this->createDl($listText), $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function aDefinitionListCanContainMultipleTermsEachWithDescription()
	{
		$text =
'paragraph

term
:	term explanation

other term
:	other term explanation

paragraph';

		$listText =
'term
:	term explanation

other term
:	other term explanation';

		$this->assertEquals($this->createDl($listText), $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function canHaveMultipleLinesPerTerm()
	{
		$text ='
paragraph

Term 1
:   Definition 1 line 1 ...
Definition 1 line 2
:   Definition 2 line 1 ...
Definition 2 line 2

paragrap';

		$listText =
'Term 1
:   Definition 1 line 1 ...
Definition 1 line 2
:   Definition 2 line 1 ...
Definition 2 line 2';

		$this->assertEquals($this->createDl($listText), $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function codeShouldNotBeMistakenForDefinition()
	{
		$text = "

This paragraph is followed by:

	: dd

";

		$this->assertEquals(null, $this->applyPattern($text));
	}
}