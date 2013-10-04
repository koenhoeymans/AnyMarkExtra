<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class AnyMarkExtra_Patterns_AbbreviationCollectorPluginTest extends \PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->eventMapper = new \AnyMark\UnitTests\Support\EventMapperMock();
		$this->collector = new \AnyMarkExtra\Patterns\AbbreviationCollectorPlugin();

		$this->collector->register($this->eventMapper);
	}

	/**
	 * @test
	 */
	public function collectsAllAbbreviationsInText()
	{
		$text = 'paragraph

*[foo]: bar
*[this]: that

paragraph';
		$callback = $this->eventMapper->getCallback();
		$event = new \AnyMark\Events\BeforeParsing($text);
		$callback($event);

		$this->assertEquals(
			array('foo' => 'bar', 'this' => 'that'),
			$this->collector->getAbbreviations()
		);
	}

	/**
	 * @test
	 */
	public function whiteSpaceBeforeColonIsAllowed()
	{
		$text = 'paragraph
	
*[foo] : bar
	
paragraph';
		$callback = $this->eventMapper->getCallback();
		$event = new \AnyMark\Events\BeforeParsing($text);
		$callback($event);

		$this->assertEquals(
			array('foo' => 'bar'),
			$this->collector->getAbbreviations()
		);
	}

	/**
	 * @test
	 */
	public function removesAbbreviationDefinitionsFromText()
	{
			$text = 'paragraph

*[foo]: bar
*[this]: that

paragraph';

			$result = 'paragraph


paragraph';
		$callback = $this->eventMapper->getCallback();
		$event = new \AnyMark\Events\BeforeParsing($text);
		$callback($event);

		$this->assertEquals($result, $event->getText());
	}
}