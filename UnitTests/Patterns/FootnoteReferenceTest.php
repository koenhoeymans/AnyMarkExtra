<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class AnyMarkExtra_Patterns_FootnoteReferenceTest extends \AnyMark\UnitTests\Support\PatternReplacementAssertions
{
	public function setup()
	{
		$this->collection = $this->getMock('AnyMarkExtra\\Patterns\\FootnoteDefinitionCollection');
		$this->footnote = new \AnyMarkExtra\Patterns\FootnoteReference($this->collection);
	}

	public function getPattern()
	{
		return $this->footnote;
	}

	/**
	 * @test
	 */
	public function replacesFootnotesInText()
	{
		$this->collection
			->expects($this->atLeastOnce())
			->method('definitionExists')
			->with('foo')
			->will($this->returnValue(true));

		$text = "Footnote.[^foo]";
		$output = '<sup id="fnref:foo"><a href="#fn:foo" rel="footnote">1</a></sup>';

		$this->assertEquals($output, $this->applyPattern($text)->toString());
	}

	/**
	 * @test
	 */
	public function doesntNeedSpaceBeforeAndAfter()
	{
		$this->collection
			->expects($this->atLeastOnce())
			->method('definitionExists')
			->with('foo')
			->will($this->returnValue(true));

		$text = "Footnote[^foo].";
		$output = '<sup id="fnref:foo"><a href="#fn:foo" rel="footnote">1</a></sup>';

		$this->assertEquals($output, $this->applyPattern($text)->toString());
	}

	/**
	 * @test
	 */
	public function doesntReplaceUndefinedFootnoteMarkers()
	{
		$this->collection
			->expects($this->atLeastOnce())
			->method('definitionExists')
			->with('foo')
			->will($this->returnValue(null));

		$text = "Footnote.[^foo]";

		$this->assertEquals(null, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function reusesFootnoteMarkersOnSameReferences()
	{
		$this->collection
			->expects($this->atLeastOnce())
			->method('definitionExists')
			->with('foo')
			->will($this->returnValue(true));

		$text = "Footnote.[^foo]\n\nOther.[^foo]";
		$this->applyPattern($text);
		$output = '<sup id="fnref2:foo"><a href="#fn:foo" rel="footnote">1</a></sup>';

		$this->assertEquals($output, $this->applyPattern('Other.[^foo]')->toString());		
	}
}