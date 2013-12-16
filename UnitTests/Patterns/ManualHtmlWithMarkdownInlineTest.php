<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class AnyMarkExtra_Patterns_ManualHtmlWithMarkdownInlineTest extends \AnyMark\UnitTests\Support\PatternReplacementAssertions
{
	public function setup()
	{
		$this->pattern = new \AnyMarkExtra\Patterns\ManualHtmlWithMarkdownInline();
	}

	protected function getPattern()
	{
		return $this->pattern;
	}

	/**
	 * @test
	 */
	public function stripsMarkdownAttribute()
	{
		$text = "<div markdown=\"1\">foo</div>";

		$div = $this->elementTree()->createElement('div');
		$div->append($div->createText("foo"));

		$this->assertEquals($div, $this->applyPattern($text));
	}
}