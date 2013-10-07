<?php

use ElementTree\ElementTreeElement;

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class AnyMarkExtra_Patterns_FootnoteDefinitionTest extends \AnyMark\UnitTests\Support\PatternReplacementAssertions
{
	public function setup()
	{
		$this->eventMapper = new \AnyMarkExtra\UnitTests\Support\EventMapperMock();
		$this->def = new \AnyMarkExtra\Patterns\FootnoteDefinition();
		$this->def->register($this->eventMapper);
	}

	public function getPattern()
	{
		return $this->def;
	}

	private function getContent(ElementTreeElement $element)
	{
		return $element->getChildren()[0]->toString();
	}

	/**
	 * @test
	 */
	public function collectsAllFootnotesInTextBeforeParsing()
	{
		$text = 'paragraph

[^foo]: bar
[^this]: that

paragraph';
		$callback = $this->eventMapper->getCallback('BeforeParsingEvent');
		$event = new \AnyMark\Events\BeforeParsing($text);
		$callback($event);

		$this->assertTrue($this->def->definitionExists('foo'));
		$this->assertTrue($this->def->definitionExists('this'));
	}

	/**
	 * @test
	 */
	public function createsListItem()
	{
		$text = 'paragraph

[^foo]: bar

paragraph';

		$this->assertTrue($this->applyPattern($text)->getName() === 'li');
	}

	/**
	 * @test
	 */
	public function setsAttributeForLaterRetrieval()
	{
		$text = 'paragraph

[^foo]: bar

paragraph';

		$this->assertEquals(
			'true', $this->applyPattern($text)->getAttributeValue('footnoteDef')
		);
	}

	/**
	 * @test
	 */
	public function setsId()
	{
		$text = 'paragraph

[^foo]: bar

paragraph';

		$this->assertEquals(
			'fn:foo', $this->applyPattern($text)->getAttributeValue('id')
		);		
	}

	/**
	 * @test
	 */
	public function footnoteCanSpanMultipleLines()
	{
		$text = 'paragraph

[^foo]: bar
    continued
    on next line

paragraph';

		$this->assertEquals(
			"\n\nbar\ncontinued\non next line",
			$this->getContent($this->applyPattern($text))
		);
	}

	/**
	 * @test
	 */
	public function descriptionCanStartOnNextLine()
	{
		$text = 'paragraph

[^foo]:
    bar

paragraph';
		$callback = $this->eventMapper->getCallback('BeforeParsingEvent');
		$event = new \AnyMark\Events\BeforeParsing($text);
		$callback($event);

		$this->assertEquals(
			"\n\nbar",
			$this->getContent($this->applyPattern($text))
		);
	}

	/**
	 * @test
	 */
	public function footnoteCanSpanMultipleLinesWithBlankLineInBetween()
	{
		$text = 'paragraph
	
[^foo]:
    para

    other para

        code block

regular paragraph';

		$this->assertEquals(
			"\n\npara\n\nother para\n\n    code block",
			$this->getContent($this->applyPattern($text))
		);
	}

	/**
	 * @test
	 */
	public function appendsFootnotesAtEndOfDocumentAfterParsing()
	{
		$text = 'paragraph

[^foo]: bar

paragraph';

		$div = new \ElementTree\ElementTreeElement('div');
		$p = $div->createElement('p');
		$div->append($p);

		$sup = $div->createElement('sup');
		$sup->setAttribute('id', 'fnref:foo');
		$a = $div->createElement('a');
		$a->setAttribute('href', '#fn:foo');
		$a->setAttribute('rel', 'footnote');
		$a->append($div->createText('foo'));
		$sup->append($a);
		$p->append($sup);

		$li = $div->createElement('li');
		$li->setAttribute('footnoteDef', 'true');
		$li->setAttribute('id', 'fn:foo');
		$div->append($li);
		$li->append($div->createElement('p'));

		$callback = $this->eventMapper->getCallback('BeforeParsingEvent');
		$event = new \AnyMark\Events\BeforeParsing($text);
		$callback($event);

		$event = new \AnyMark\Events\AfterParsing($div);
		$callback = $this->eventMapper->getCallback('AfterParsingEvent');
		$callback($event);

		$this->assertEquals(
			'<div><p><sup id="fnref:foo"><a href="#fn:foo" rel="footnote">foo</a></sup></p><div class="footnotes"><hr /><ol><li id="fn:foo"><p>&#160;<a href="#fnref:foo" rev="footnote">&#8617;</a></p></li></ol></div></div>',
			$div->toString()
		);
	}

	/**
	 * @test
	 */
	public function unusualName()
	{
		$text = 'paragraph

[^1$^!"\']: Bar!';
		$callback = $this->eventMapper->getCallback('BeforeParsingEvent');
		$event = new \AnyMark\Events\BeforeParsing($text);
		$callback($event);

		$this->assertTrue($this->def->definitionExists('1$^!"\''));
	}
}