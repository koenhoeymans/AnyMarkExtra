<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class AnyMarkExtra_Patterns_EmphasisTest extends \AnyMark\UnitTests\Support\PatternReplacementAssertions
{
	public function setup()
	{
		$this->pattern = new \AnyMarkExtra\Patterns\Emphasis();
	}

	protected function getPattern()
	{
		return $this->pattern;
	}

	/**
	 * @test
	 */
	public function noEmphasisForIntrawordUnderscores()
	{
		$text = "not em_ph_asized text.";
		$this->assertEquals(null, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function noEmphasisForIntrawordUnderscores2()
	{
		$text = "not _emph_asized text.";
		$this->assertEquals(null, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function noEmphasisForIntrawordUnderscores3()
	{
		$text = "not em_phasized_ text.";
		$this->assertEquals(null, $this->applyPattern($text));
	}
}