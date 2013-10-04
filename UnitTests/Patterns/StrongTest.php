<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class AnyMarkExtra_Patterns_StrongTest extends \AnyMark\UnitTests\Support\PatternReplacementAssertions
{
	public function setup()
	{
		$this->pattern = new \AnyMarkExtra\Patterns\Strong();
	}

	protected function getPattern()
	{
		return $this->pattern;
	}

	/**
	 * @test
	 */
	public function noIntraWordEmphasisForUnderscores()
	{
		$text = "This is not a sentence with st__ro__ng text.";
		$this->assertEquals(null, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function noIntraWordEmphasisForUnderscores2()
	{
		$text = "This is not a sentence with __stro__ng text.";
		$this->assertEquals(null, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function noIntraWordEmphasisForUnderscores3()
	{
		$text = "This is not a sentence with stro__ng__ text.";
		$this->assertEquals(null, $this->applyPattern($text));
	}
}