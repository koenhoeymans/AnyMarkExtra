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

	/**
	 * 'marker' => firstOccurenceNumber
	 * 
	 * @var array
	 */
	private $uniqueCount = array();

	/**
	 * 'marker' => occurenceNumber
	 * 
	 * @var array
	 */
	private $occurenceCount = array();

	public function __construct(FootnoteDefinitionCollection $collection)
	{
		$this->collection = $collection;
	}

	public function register(EventMapper $mapper)
	{
		$mapper->registerForEvent(
			'AfterParsingEvent',
			function(AfterParsingEvent $event) {
				$this->renumberReference($event->getTree());
			}
		);
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
		if (isset($this->occurenceCount[$match['marker']]))
		{
			$this->occurenceCount[$match['marker']]++;
			$occurenceCount = $this->occurenceCount[$match['marker']];
		}
		else
		{
			$this->occurenceCount[$match['marker']] = 1;
			$this->uniqueCount[$match['marker']] = count($this->uniqueCount) + 1;
			$occurenceCount = '';
		}

		$sup = $parent->createElement('sup');
		$sup->setAttribute('id', 'fnref' . $occurenceCount . ':' . $match['marker']);
		$a = $parent->createElement('a');
		$a->setAttribute('href', '#fn:' . $match['marker']);
		$a->setAttribute('rel', 'footnote');
		$a->append($parent->createText($this->uniqueCount[$match['marker']]));
		$sup->append($a);

		return $sup;
	}

	private function renumberReference(ElementTree $tree)
	{
		$count = 1;

		$query = $tree->createQuery();
		$fnRefs = $query->find($query->allElements($query->lAnd(
			$query->withParentElement($query->withName('sup')),
			$query->withName('a')
		)));
		foreach ($fnRefs as $fnRef)
		{
			$id = $fnRef->getParent()->getAttributeValue('id');
			if (!$id || (substr($id, 0, 6) !== 'fnref:'))
			{
				continue;
			}

			$refTxt = $fnRef->getChildren()[0];
			$fnRef->remove($refTxt);
			$fnRef->append($fnRef->createText($count));
			$count++;
		}
	}
}