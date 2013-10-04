<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class AnyMarkExtra_Patterns_HeaderWithAttributesTest extends \AnyMark\UnitTests\Support\PatternReplacementAssertions
{
	public function setup()
	{
		$this->pattern = new \AnyMarkExtra\Patterns\HeaderWithAttributes();
	}

	protected function getPattern()
	{
		return $this->pattern;
	}

	private function createHeader($tag, $content)
	{
		$header = new \ElementTree\ElementTreeElement($tag);
		$header->append($header->createText($content));

		return $header;
	}

	/**
	 * @test
	 */
	public function setexCanHaveIdAttribute()
	{
		$text = "

header  {#id}
======

";
		$header = $this->createHeader('h1', 'header');
		$header->setAttribute('id', 'id');

		$this->assertEquals($header->toSTring(), $this->applyPattern($text)->toString());
	}

	/**
	 * @test
	 */
	public function atxCanHaveIdAttribute()
	{
		$text = "

## header  {#id}

";
		$header = $this->createHeader('h2', 'header');
		$header->setAttribute('id', 'id');

		$this->assertEquals($header->toSTring(), $this->applyPattern($text)->toString());
	}

	/**
	 * @test
	 */
	public function setexCanHaveClassAttribute()
	{
		$text = "

header  {.class}
======

";
		$header = $this->createHeader('h1', 'header');
		$header->setAttribute('class', 'class');

		$this->assertEquals($header->toSTring(), $this->applyPattern($text)->toString());
	}

	/**
	 * @test
	 */
	public function atxCanHaveClassAttribute()
	{
		$text = "

## header  {.class}

";
		$header = $this->createHeader('h2', 'header');
		$header->setAttribute('class', 'class');

		$this->assertEquals($header->toSTring(), $this->applyPattern($text)->toString());
	}

	/**
	 * @test
	 */
	public function classesDontNeedWhitespaceSeperation()
	{
		$text = "

header  {.two.classes}
======

";
		$header = $this->createHeader('h1', 'header');
		$header->setAttribute('class', 'two classes');

		$this->assertEquals($header->toSTring(), $this->applyPattern($text)->toString());
	}

	/**
	 * @test
	 */
	public function idStopsAtClassDot()
	{
		$text = "

header  {#id.class}
======

";
		$header = $this->createHeader('h1', 'header');
		$header->setAttribute('class', 'class');
		$header->setAttribute('id', 'id');

		$this->assertEquals($header->toSTring(), $this->applyPattern($text)->toString());
	}
}