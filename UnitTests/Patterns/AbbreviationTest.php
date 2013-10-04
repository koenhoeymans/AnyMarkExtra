<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class AnyMarkExtra_Patterns_AbbreviationTest extends \AnyMark\UnitTests\Support\PatternReplacementAssertions
{
	public function setup()
	{
		$this->collector = $this->getMock('AnyMarkExtra\\Patterns\\AbbreviationCollector');
		$this->abbr = new \AnyMarkExtra\Patterns\Abbreviation($this->collector);
	}

	public function getPattern()
	{
		return $this->abbr;
	}

	/**
	 * @test
	 */
	public function replacesAbbreviationInText()
	{
		$this->collector
			->expects($this->atLeastOnce())
			->method('getAbbreviations')
			->will($this->returnValue(array('HTML' => 'hyp mark lang', 'foo' => 'bar')));
		$this->collector
			->expects($this->atLeastOnce())
			->method('getDefinition')
			->with('HTML')
			->will($this->returnValue('hyp mark lang'));

		$text = "We will use HTML as an example.";
		$output = '<abbr title="hyp mark lang">HTML</abbr>';

		$this->assertEquals($output, $this->applyPattern($text)->toString());
	}
}