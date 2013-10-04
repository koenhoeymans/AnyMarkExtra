<?php

/**
 * @package AnyMarkExtra
 */
namespace AnyMarkExtra\Patterns;

use AnyMark\Pattern\Pattern;
use ElementTree\ElementTree;

/**
 * @package AnyMarkExtra
 */
class Table extends Pattern
{

	public function getRegex()
	{
		return
		'@
			(?<=\n|^)
			(?<thead>([|].+|.+[|].*)+)\n
			(?<alignment>([|]?[ ]*:?-+:?[ ]*[|]?)+)\n
			(?<rows>((([|].+|.+[|].*)+)\n)+)
			(?=\n|$)
		@x';
	}

	public function handleMatch(
		array $match, ElementTree $parent, Pattern $parentPattern = null
	) {
		$alignment = $this->getAlignment($match);
		$table = $parent->createElement('table');
		$thead = $table->createElement('thead');
		$table->append($thead);
		$trhead = $table->createElement('tr');
		$thead->append($trhead);
		$headings = array_values($this->getHeadings($match));
		foreach ($headings as $key => $heading)
		{
			$th = $table->createElement('th');
			$trhead->append($th);
			$th->append($table->createText(trim($heading)));
			if (isset($alignment[$key]))
			{
				$th->setAttribute('align', $alignment[$key]);
			}
		}

		$tbody = $table->createElement('tbody');
		$table->append($tbody);
		$rows = $this->getRows($match);
		foreach ($rows as $row)
		{
			$tr = $table->createElement('tr');
			$tbody->append($tr);
			$fields = array_values($this->getFields($row));
			foreach ($fields as $key => $field)
			{
				$td = $table->createElement('td');
				$tr->append($td);
				$td->append($table->createText(trim($field)));
				if (isset($alignment[$key]))
				{
					$td->setAttribute('align', $alignment[$key]);
				}
			}
		}

		return $table;
	}

	private function getHeadings($match)
	{
		$theads = explode('|', $match['thead']);
		$lastKey = count($theads)-1;
		if (empty($theads[$lastKey]))
		{
			unset($theads[$lastKey]);
		}
		if (empty($theads[0]))
		{
			unset($theads[0]);
		}

		return $theads;
	}

	private function getRows($match)
	{
		return preg_split("@\n(?=.)@", $match['rows']);
	}

	private function getFields($row)
	{
		$rows = explode('|', $row);
		$lastKey = count($rows)-1;
		if (empty($rows[$lastKey]) || ($rows[$lastKey] === "\n"))
		{
			unset($rows[$lastKey]);
		}
		if (empty($rows[0]))
		{
			unset($rows[0]);
		}

		return $rows;
	}

	private function getAlignment($match)
	{
		$alignment = array();
		$fields = $this->getFields($match['alignment']);
		$fields = array_values($fields);
		foreach ($fields as $row => $field)
		{
			$value = trim($field);
			if ((substr($value, 0, 1) === ':') && (substr($value, -1) === ':'))
			{
				$alignment[$row] = 'center';
			}
			elseif (substr($value, 0, 1) === ':')
			{
				$alignment[$row] = 'left';
			}
			elseif (substr($value, -1) === ':')
			{
				$alignment[$row] = 'right';
			}
		}

		return $alignment;
	}
}