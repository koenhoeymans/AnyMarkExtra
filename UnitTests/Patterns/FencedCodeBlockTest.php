<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class AnyMarkExtra_Patterns_FencedCodeBlockTest extends \AnyMark\UnitTests\Support\PatternReplacementAssertions
{
	public function setup()
	{
		$this->pattern = new \AnyMarkExtra\Patterns\FencedCodeBlock();
	}

	public function getPattern()
	{
		return $this->pattern;
	}

	public function createFromText($text)
	{
		$pre = $this->elementTree()->createElement('pre');
		$code = $this->elementTree()->createElement('code');
		$text = $this->elementTree()->createText($text . "\n");
		$pre->append($code);
		$code->append($text);

		return $pre;
	}

	/**
	 * @test
	 */
	public function codeCanBeSurroundedByTwoLinesOfAtLeastThreeTildes()
	{
		$text = "\n\n~~~\nthe code\n~~~\n\n";

		$this->assertEquals(
			$this->createFromText('the code'), $this->applyPattern($text)
		);
	}

	/**
	 * @test
	 */
	public function doesNotNeedEmptyLineBefore()
	{
		$text = "\n\ntext\n~~~\nthe code\n~~~\n\n";

		$this->assertEquals(
			$this->createFromText('the code'), $this->applyPattern($text)
		);
	}

	/**
	 * @test
	 */
	public function endsWithBlankLineAfter()
	{
		$text = "

~~~~
In code block
~~~
Still in code block
~~~~~
Still in code block
~~~~

";
		$code = "In code block
~~~
Still in code block
~~~~~
Still in code block";

		$this->assertEquals($this->createFromText($code), $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function linebreaksBeforeCodeAreReplacedByBreakElements()
	{
		$text = "\n\n~~~\n\n\nthe code\n~~~\n\n";

		$pre = $this->elementTree()->createElement('pre');
		$code = $this->elementTree()->createElement('code');
		$pre->append($code);
		$code->append($this->elementTree()->createElement('br'));
		$code->append($this->elementTree()->createElement('br'));
		$code->append($this->elementTree()->createText("the code\n"));

		$this->assertEquals($pre->toString(), $this->applyPattern($text)->toString());
	}

	/**
	 * @test
	 */
	public function linebreaksAfterCodeAreLeftAsIs()
	{
		$text = "\n\n~~~\nthe code\n\n\n~~~\n\n";

		$pre = $this->elementTree()->createElement('pre');
		$code = $this->elementTree()->createElement('code');
		$pre->append($code);
		$code->append($this->elementTree()->createText("the code\n\n\n"));

		$this->assertEquals($pre->toString(), $this->applyPattern($text)->toString());
	}

	/**
	 * @test
	 */
	public function tildeCodeBlockIsNonGreedy()
	{
		$text = "\n\n~~~\nthe code\n~~~\n\nparagraph\n\n~~~\ncode\n~~~\n\n";

		$this->assertEquals(
			$this->createFromText('the code'), $this->applyPattern($text)
		);
	}

	/**
	 * @test
	 */
	public function canHaveClassSpecififiedWithoutDot()
	{
		$text = "\n\n~~~class\nthe code\n~~~\n\n";
		$code = $this->createFromText('the code');
		$code->getChildren()[0]->setAttribute('class', 'class');

		$this->assertEquals($code, $this->applyPattern($text));

		$text = "\n\n~~~ class\nthe code\n~~~\n\n";
		$code = $this->createFromText('the code');
		$code->getChildren()[0]->setAttribute('class', 'class');

		$this->assertEquals($code, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function canHaveClassSpecififiedWithDot()
	{
		$text = "\n\n~~~.class\nthe code\n~~~\n\n";
		$code = $this->createFromText('the code');
		$code->getChildren()[0]->setAttribute('class', 'class');

		$this->assertEquals($code, $this->applyPattern($text));

		$text = "\n\n~~~ .class\nthe code\n~~~\n\n";
		$code = $this->createFromText('the code');
		$code->getChildren()[0]->setAttribute('class', 'class');

		$this->assertEquals($code, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function canHaveOwnClassSpecifiedInAttrBlock()
	{
		$text = "\n\n~~~{.language-foo .example}\nthe code\n~~~\n\n";
		$code = $this->createFromText('the code');
		$code->getChildren()[0]->setAttribute('class', 'language-foo example');

		$this->assertEquals($code, $this->applyPattern($text));

		$text = "\n\n~~~{ .language-foo }\nthe code\n~~~\n\n";
		$code = $this->createFromText('the code');
		$code->getChildren()[0]->setAttribute('class', 'language-foo');

		$this->assertEquals($code, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function canHaveMoreThanOneClassSpecified()
	{
		$text = "\n\n~~~{ .language-foo .example }\nthe code\n~~~\n\n";
		$code = $this->createFromText('the code');
		$code->getChildren()[0]->setAttribute('class', 'language-foo example');

		$this->assertEquals($code, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function canHaveIdSpecifiedInAttrBlock()
	{
		$text = "\n\n~~~{#example}\nthe code\n~~~\n\n";
		$code = $this->createFromText('the code');
		$code->getChildren()[0]->setAttribute('id', 'example');

		$this->assertEquals($code, $this->applyPattern($text));

		$text = "\n\n~~~{ #example }\nthe code\n~~~\n\n";
		$code = $this->createFromText('the code');
		$code->getChildren()[0]->setAttribute('id', 'example');

		$this->assertEquals($code, $this->applyPattern($text));
	}

	/**
	 * @test
	 */
	public function canHaveBothIdAndClass()
	{
		$text = "\n\n~~~{.class #id}\nthe code\n~~~\n\n";
		$code = $this->createFromText('the code');
		$code->getChildren()[0]->setAttribute('id', 'id');
		$code->getChildren()[0]->setAttribute('class', 'class');

		$this->assertEquals($code, $this->applyPattern($text));

		$text = "\n\n~~~{#id .class}\nthe code\n~~~\n\n";
		$code = $this->createFromText('the code');
		$code->getChildren()[0]->setAttribute('id', 'id');
		$code->getChildren()[0]->setAttribute('class', 'class');

		$this->assertEquals($code, $this->applyPattern($text));
	}
}