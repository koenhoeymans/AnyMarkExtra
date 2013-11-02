<?php

/**
 * @package AnyMarkExtra
 */
namespace AnyMarkExtra;

use AnyMarkExtra\Patterns\Footnote;
use AnyMarkExtra\Patterns\Abbreviation;
use Epa\EventMapper;
use Epa\Plugin;
use AnyMark\PublicApi\EditPatternConfigurationEvent;

/**
 * @package AnyMarkExtra
 */
class AnyMarkExtra implements Plugin
{
	private $abbreviationCollector;

	private $footnoteDefinition;

	public function register(EventMapper $mapper)
	{
		$mapper->registerForEvent(
			'EditPatternConfigurationEvent',
			function (EditPatternConfigurationEvent $event) {
				$this->addPatterns($event->getPatternConfig());
			}
		);

		$this->footnoteDefinition = new \AnyMarkExtra\Patterns\FootnoteDefinition();
		$this->footnoteReference = new \AnyMarkExtra\Patterns\FootnoteReference($this->footnoteDefinition);
		$this->footnoteDefinition->register($mapper);
		$this->footnoteReference->register($mapper);

		$this->abbreviationDefinition = new \AnyMarkExtra\Patterns\AbbreviationDefinition();
		$this->abbreviation = new \AnyMarkExtra\Patterns\Abbreviation($this->abbreviationDefinition);
		$this->abbreviationDefinition->register($mapper);
	}

	private function addPatterns(EditPatternConfigurationEvent $config)
	{
		$config->setImplementation(
			'fencedCodeBlock', new \AnyMarkExtra\Patterns\FencedCodeBlock()
		);
		$config->setImplementation(
			'definitionList', new \AnyMarkExtra\Patterns\DefinitionList()
		);
		$config->setImplementation(
			'table', new \AnyMarkExtra\Patterns\Table()
		);
		$config->setImplementation(
			'definitionTerm', new \AnyMarkExtra\Patterns\DefinitionTerm()
		);
		$config->setImplementation(
			'definitionDescription', new \AnyMarkExtra\Patterns\DefinitionDescription()
		);
		$config->setImplementation(
			'abbreviation',	$this->abbreviation
		);
		$config->setImplementation(
			'abbreviationDefinition', $this->abbreviationDefinition
		);
		$config->setImplementation(
			'emphasis', new \AnyMarkExtra\Patterns\Emphasis()
		);
		$config->setImplementation(
			'strong', new \AnyMarkExtra\Patterns\Strong()
		);
		$config->setImplementation(
			'footnoteReference', $this->footnoteReference
		);
		$config->setImplementation(
			'footnoteDefinition', $this->footnoteDefinition
		);
		$config->setImplementation(
			'header', new \AnyMarkExtra\Patterns\HeaderWithAttributes()
		);
		$config->setImplementation(
			'manualHtmlBlock', new \AnyMarkExtra\Patterns\ManualHtmlWithMarkdownBlock()
		);
		$config
			->add('block')
			->toParent('manualHtmlBlock')
			->last();
		$config
			->add('definitionList')
			->toParent('root')
			->first();
		$config
			->add('definitionList')
			->toParent('block')
			->first();
		$config
			->add('table')
			->toParent('root')
			->first();
		$config
			->add('table')
			->toAlias('block')
			->first();
		$config
			->add('block')
			->toParent('table')
			->first();
		$config
			->add('inline')
			->toParent('table')
			->last();
		$config
			->add('definitionTerm')
			->toParent('definitionList')
			->first();
		$config
			->add('definitionDescription')
			->toParent('definitionList')
			->last();
		$config
			->add('block')
			->toParent('definitionDescription')
			->first();
		$config
			->add('codeIndented')
			->toParent('definitionDescription')
			->first();
		$config
			->add('fencedCodeBlock')
			->toParent('root')
			->first();
		$config
			->add('fencedCodeBlock')
			->toParent('block')
			->first();
		$config
			->add('fencedCodeBlock')
			->toParent('textualList')
			->first();
		$config
			->add('abbreviation')
			->toAlias('inline')
			->last();
		$config
			->add('abbreviationDefinition')
			->toAlias('block')
			->after('fencedCodeBlock');
		$config
			->add('footnoteReference')
			->toAlias('inline')
			->before('hyperlink');
		$config
			->add('block')
			->toParent('footnoteDefinition')
			->first();
		$config
			->add('footnoteDefinition')
			->toAlias('block')
			->after('fencedCodeBlock');
	}
}