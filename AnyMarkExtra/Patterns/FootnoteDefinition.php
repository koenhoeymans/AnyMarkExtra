<?php

/**
 * @package AnyMarkExtra
 */
namespace AnyMarkExtra\Patterns;

use AnyMark\Pattern\Pattern;
use ElementTree\ElementTree;
use ElementTree\Element;
use Epa\Plugin;
use Epa\EventMapper;
use AnyMark\PublicApi\BeforeParsingEvent;
use AnyMark\PublicApi\AfterParsingEvent;

/**
 * @package AnyMarkExtra
 */
class FootnoteDefinition extends Pattern implements Plugin, FootnoteDefinitionCollection
{
	private $regex =
		'@
			(?<=^|\n)
			(\[|{)\^(?<marker>.+?)(]|}):
				(?<spacing_after_marker>[ ]|\n([ ]{4}|\t))
				(?<def>.+(\n\s*\n?([ ]{4}|\t).+)*)
			(?=\n\S|\n\n(?!([ ]{4}|\t))|$)
		@x';

	private $knownIds = array();

	public function register(EventMapper $mapper)
	{
		$mapper->registerForEvent(
				'AfterParsingEvent',
				function(AfterParsingEvent $event) {
					$this->moveDefinitions($event->getTree());
				})
			->first();
	}

	public function getRegex()
	{
		return $this->regex;
	}

	private function adjustDefinitionMarks($text)
	{
		return preg_replace_callback(
			$this->regex,
			function($match) {
				$this->knownIds[] = $match['marker'];
				return '{^' . $match['marker'] . '}:'
					. $match['spacing_after_marker']
					. $match['def'];
			},
			$text
		);
	}

	/**
	 * @see \AnyMarkExtra\Patterns\FootnoteDefinitionCollection::definitionExists()
	 */
	public function definitionExists($marker)
	{
		return in_array($marker, $this->knownIds);
	}

	public function handleMatch(
		array $match, ElementTree $parent, Pattern $parentPattern = null
	) {
		$content = preg_replace("@\n[ ]{4}@", "\n", $match['def']);
		$li = $parent->createElement('li');
		$li->setAttribute('footnoteDef', 'true');
		$li->setAttribute('id', 'fn:' . $match['marker']);
		$li->append($parent->createText("\n\n" . $content));

		return $li;
	}

	private function moveDefinitions(ElementTree $tree)
	{
		if (empty($this->knownIds))
		{
			return;
		}

		$ol = $this->createFnDefList($tree);

		$defs = $this->getFootNoteDefsPerId($tree);
		foreach ($defs as $def)
		{
			$tree->remove($def);
		}

		# we only get the refs that are not inside a definition
		$refs = $this->getFootnoteRefsPerId($tree);

		foreach ($refs as $id => $ref)
		{
			if (!isset($defs[$id]))
			{
				continue;
			}
			$this->appendFnDef($ol, $id, $defs[$id], $ref);
			unset($defs[$id]);
		}

		# also get references inside other definitions
		$refs = $this->getFootnoteRefsPerId($tree);

		# only defs that are referenced from other def are left
		foreach ($defs as $id => $def)
		{
			$this->appendFnDef($ol, $id, $def, $refs[$id]);
		}
	}

	private function getFootnoteRefsPerId(ElementTree $tree)
	{
		$fnRefsPerId = array();

		$query = $tree->createQuery();
		$fnRefs = $query->find($query->allElements($query->withName('sup')));
		foreach ($fnRefs as $fnRef)
		{
			$id = $fnRef->getAttributeValue('id');
			if (!$id || (substr($id, 0, 5) !== 'fnref'))
			{
				continue;
			}
			$fnRefsPerId[substr($id, strpos($id, ':')+1)][] = $fnRef;
		}

		return $fnRefsPerId;
	}

	private function getFootnoteDefsPerId(ElementTree $tree)
	{
		$query = $tree->createQuery();
		$allDefs = $query->find($query->allElements(
			$query->lAnd(
				$query->withName('li'),
				$query->withAttribute($query->withName('footnoteDef'))
			)
		));

		$fnDefs = array();
		foreach ($allDefs as $def)
		{
			$tree->remove($def);
			$ref = substr($def->getAttributeValue('id'), 3);
			$fnDefs[$ref] = $def;
		}

		return $fnDefs;
	}

	private function createFnDefList(ElementTree $tree)
	{
		$div = $tree->createElement('div');
		$div->setAttribute('class', 'footnotes');
		$tree->append($div);
		$div->append($tree->createElement('hr'));
		$ol = $tree->createElement('ol');
		$div->append($ol);

		return $ol;
	}

	private function getParaToAppendFnDefTo(Element $fnDef)
	{
		$fnDefElements = $fnDef->getChildren();
		$lastChild = end($fnDefElements);

		$query = $fnDef->createQuery();
		$allParagraphs = $query->find($query->allElements($query->withName('p')));
		$lastPara = end($allParagraphs);

		$query = $fnDef->createQuery();
		$allText = $query->find($query->allText());
		$lastText = end($allText);
		if ($lastText)
		{
			if (str_replace(' ', '', $lastText->toString()) === '')
			{
				$fnDef->remove($lastText);
				return $this->getParaToAppendFnDefTo($fnDef);
			}
		}

		if (!in_array($lastChild, $allParagraphs))
		{
			$lastPara = $fnDef->createElement('p');
			$fnDef->append($lastPara);
		}

		return $lastPara;
	}

	private function appendFnDef(Element $ol, $id, $def, array $refs)
	{
		$para = $this->getParaToAppendFnDefTo($def);
		if ($para->getChildren() !== array())
		{
			$para->append($def->createText('&#160;'));
		}
		$def->removeAttribute('footnoteDef');
		$ol->append($def);

		$count = 1;
		foreach ($refs as $ref)
		{
			if ($count>1)
			{
				$para->append($para->createText(' '));
			}
			$countRef = ($count === 1) ? '' : $count;
			$a = $def->createElement('a');
			$a->setAttribute('href', '#fnref' . $countRef . ':' . $id);
			$a->setAttribute('rev', 'footnote');
			$a->append($def->createText('&#8617;'));
			$para->append($a);
			$count++;
		}
	}
}