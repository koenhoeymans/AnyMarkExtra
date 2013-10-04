<?php

require_once('TestHelper.php');

/**
 * These are the PHPMarkdownExtra tests as found in the test suite of PHPMarkdown.
 * @copyright PHPMarkdown for the tests
 */
class AnyMarkExtra_EndToEndTests_PhpMarkdownExtraTest extends \AnyMark\EndToEndTests\Support\Tidy
{
	public function createTestFor($name)
	{
		$anyMark = \AnyMark\AnyMark::setup();
		$anyMark->registerPlugin(new \AnyMarkExtra\AnyMarkExtra());

		$parsedText = $anyMark->parse(file_get_contents(
			__DIR__
			. DIRECTORY_SEPARATOR . 'PhpMarkdownExtra.mdtest'
			. DIRECTORY_SEPARATOR . $name . '.text'
		))->toString();

		$this->assertEquals(
			$this->tidy(file_get_contents(
				__DIR__
				. DIRECTORY_SEPARATOR . 'PhpMarkdownExtra.mdtest'
				. DIRECTORY_SEPARATOR . $name . '.xhtml'
			)),
			$this->tidy($parsedText)
		);
	}

	/**
	 * @test
	 */
	public function abbr()
	{
		$this->createTestFor('Abbr');
	}

	/**
	 * @test
	 */
	public function defintionLists()
	{
		$this->createTestFor('Definition Lists');
	}

	/**
	 * @test
	 */
	public function emphasis()
	{
		$this->createTestFor('Emphasis');
	}

	/**
	 * @test
	 */
	public function fencedCodeBlocks()
	{
		$this->createTestFor('Fenced Code Blocks');
	}

	/**
	 * @test
	 */
	public function fencedCodeBlocksSpecialCases()
	{
		$this->createTestFor('Fenced Code Blocks Special Cases');
	}

	/**
	 * @test
	 */
	public function footnotes()
	{
		$this->createTestFor('Footnotes');
	}

	/**
	 * @test
	 */
	public function headersWithAttributes()
	{
		$this->createTestFor('Headers with attributes');
	}

	/**
	 * @test
	 */
	public function inlineHtmlWithMarkdownContent()
	{
		$this->createTestFor('Inline HTML with Markdown content');
	}

	/**
	 * @test
	 */
	public function tables()
	{
		$this->createTestFor('Tables');
	}
}