<?php

/**
 * @package AnyMarkExtra
 */
namespace AnyMarkExtra\Patterns;

use AnyMark\Pattern\Pattern;
use AnyMark\PublicApi\AfterParsingEvent;
use ElementTree\ElementTree;
use ElementTree\Element;
use Epa\Plugin;
use Epa\EventMapper;

/**
 * @package AnyMarkExtra
 */
class FootnoteReference extends Pattern implements Plugin
{
	private $collection;

	public function __construct(FootnoteDefinitionCollection $collection)
	{
		$this->collection = $collection;
	}

	public function register(EventMapper $mapper)
	{
		$mapper->registerForEvent(
			'AfterParsingEvent',
			function(AfterParsingEvent $event) {
				$this->renumber($event->getTree());
			});
	}

	public function getRegex()
	{
		return "@\[\^(?<marker>.+?)]@";
	}

	public function handleMatch(
		array $match, ElementTree $parent, Pattern $parentPattern = null
	) {
		if (!$this->collection->definitionExists($match['marker']))
		{
			return;
		}

		$sup = $parent->createElement('sup');
		$sup->setAttribute('id', 'fnref:' . $match['marker']);
		$a = $parent->createElement('a');
		$a->setAttribute('href', '#fn:' . $match['marker']);
		$a->setAttribute('rel', 'footnote');
		$a->append($parent->createText('1'));
		$sup->append($a);

		return $sup;
	}

	private function renumber(ElementTree $tree)
	{
		$uniqueCount = 1;
		$refsAlreadyCounted = array(); // #fnref:reference => total unique before
		$occurenceCount = array(); // #fnref:reference => occurence count

		foreach ($this->getAllReferences($tree) as $fnRef)
		{
			$refTxt = $fnRef->getChildren()[0];
			$fnRef->remove($refTxt);
			$reference = $fnRef->getAttributeValue('href');
			if (!isset($refsAlreadyCounted[$reference]))
			{
				$fnRef->append($fnRef->createText($uniqueCount));
				$refsAlreadyCounted[$reference] = $uniqueCount;
				$uniqueCount++;

				# if definition with reference inside came before reference in
				# text it will be placed at the end and should have eg id=fnref2:
				$fnRef->getParent()->setAttribute(
					'id', 'fnref:' . substr($reference, 4)
				);
				$occurenceCount[$reference] = 1;
			}
			else
			{
				$fnRef->append($fnRef->createText($refsAlreadyCounted[$reference]));

				# see remark above about references in definitions
				$occurenceCount[$reference] = $occurenceCount[$reference] +1;
				$fnRef->getParent()->setAttribute(
					'id',
					'fnref' . $occurenceCount[$reference] . ':' . substr($reference, 4)
				);
			}
		}
	}

	private function getAllReferences(ElementTree $tree)
	{
		$query = $tree->createQuery();
		$fnRefs = $query->find($query->allElements($query->lAnd(
			$query->withParentElement($query->withName('sup')),
			$query->withName('a')
		)));

		foreach ($fnRefs as $key => $fnRef)
		{
			$id = $fnRef->getParent()->getAttributeValue('id');
			if (!$id || (substr($id, 0, 5) !== 'fnref'))
			{
				unset($fnRefs[$key]);
			}
		}

		return $fnRefs;
	}
}