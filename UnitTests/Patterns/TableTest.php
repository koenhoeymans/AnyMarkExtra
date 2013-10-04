<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class AnyMarkExtra_Patterns_TableTest extends \AnyMark\UnitTests\Support\PatternReplacementAssertions
{
	public function setup()
	{
		$this->pattern = new \AnyMarkExtra\Patterns\Table();
	}

	protected function getPattern()
	{
		return $this->pattern;
	}

	/**
	 * @test
	 */
	public function createsTable()
	{
		$text = '

Header 1  | Header 2
--------- | ---------
Cell 1    | Cell 2
Cell 3    | Cell 4

';

		$table = new \ElementTree\ElementTreeElement('table');
		$thead = $table->createElement('thead');
		$table->append($thead);
		$tr = $table->createElement('tr');
		$thead->append($tr);
		$th = $table->createElement('th');
		$tr->append($th);
		$th->append($table->createText('Header 1'));
		$th = $table->createElement('th');
		$tr->append($th);
		$th->append($table->createText('Header 2'));
		$tbody = $table->createElement('tbody');
		$table->append($tbody);
		$tr = $table->createElement('tr');
		$tbody->append($tr);
		$td = $table->createElement('td');
		$tr->append($td);
		$td->append($table->createText('Cell 1'));
		$td = $table->createElement('td');
		$tr->append($td);
		$td->append($table->createText('Cell 2'));
		$tr = $table->createElement('tr');
		$tbody->append($tr);
		$td = $table->createElement('td');
		$tr->append($td);
		$td->append($table->createText('Cell 3'));
		$td = $table->createElement('td');
		$tr->append($td);
		$td->append($table->createText('Cell 4'));

		$this->assertEquals($table->toString(), $this->applyPattern($text)->toString());
	}

	/**
	 * @test
	 */
	public function tableCanHaveThreeRows()
	{
		$text = '

Header 1  | Header 2  | Header 3
--------- | --------- | ---------
Cell 1    | Cell 2    | Cell 5
Cell 3    | Cell 4    | Cell 6

';
		
		$table = new \ElementTree\ElementTreeElement('table');
		$thead = $table->createElement('thead');
		$table->append($thead);
		$tr = $table->createElement('tr');
		$thead->append($tr);
		$th = $table->createElement('th');
		$tr->append($th);
		$th->append($table->createText('Header 1'));
		$th = $table->createElement('th');
		$tr->append($th);
		$th->append($table->createText('Header 2'));
		$th = $table->createElement('th');
		$tr->append($th);
		$th->append($table->createText('Header 3'));
		$tbody = $table->createElement('tbody');
		$table->append($tbody);
		$tr = $table->createElement('tr');
		$tbody->append($tr);
		$td = $table->createElement('td');
		$tr->append($td);
		$td->append($table->createText('Cell 1'));
		$td = $table->createElement('td');
		$tr->append($td);
		$td->append($table->createText('Cell 2'));
		$td = $table->createElement('td');
		$tr->append($td);
		$td->append($table->createText('Cell 5'));
		$tr = $table->createElement('tr');
		$tbody->append($tr);
		$td = $table->createElement('td');
		$tr->append($td);
		$td->append($table->createText('Cell 3'));
		$td = $table->createElement('td');
		$tr->append($td);
		$td->append($table->createText('Cell 4'));
		$td = $table->createElement('td');
		$tr->append($td);
		$td->append($table->createText('Cell 6'));

		$this->assertEquals($table->toString(), $this->applyPattern($text)->toString());		
	}

	/**
	 * @test
	 */
	public function tableCanHaveLeadingPipes()
	{
		$text = '

| Header 1  | Header 2
| --------- | ---------
| Cell 1    | Cell 2
| Cell 3    | Cell 4

';

		$table = new \ElementTree\ElementTreeElement('table');
		$thead = $table->createElement('thead');
		$table->append($thead);
		$tr = $table->createElement('tr');
		$thead->append($tr);
		$th = $table->createElement('th');
		$tr->append($th);
		$th->append($table->createText('Header 1'));
		$th = $table->createElement('th');
		$tr->append($th);
		$th->append($table->createText('Header 2'));
		$tbody = $table->createElement('tbody');
		$table->append($tbody);
		$tr = $table->createElement('tr');
		$tbody->append($tr);
		$td = $table->createElement('td');
		$tr->append($td);
		$td->append($table->createText('Cell 1'));
		$td = $table->createElement('td');
		$tr->append($td);
		$td->append($table->createText('Cell 2'));
		$tr = $table->createElement('tr');
		$tbody->append($tr);
		$td = $table->createElement('td');
		$tr->append($td);
		$td->append($table->createText('Cell 3'));
		$td = $table->createElement('td');
		$tr->append($td);
		$td->append($table->createText('Cell 4'));

		$this->assertEquals($table->toString(), $this->applyPattern($text)->toString());		
	}

	/**
	 * @test
	 */
	public function tableCanHaveTailingPipes()
	{
		$text = '

Header 1  | Header 2  |
--------- | --------- |
Cell 1    | Cell 2    |
Cell 3    | Cell 4    |

';

		$table = new \ElementTree\ElementTreeElement('table');
		$thead = $table->createElement('thead');
		$table->append($thead);
		$tr = $table->createElement('tr');
		$thead->append($tr);
		$th = $table->createElement('th');
		$tr->append($th);
		$th->append($table->createText('Header 1'));
		$th = $table->createElement('th');
		$tr->append($th);
		$th->append($table->createText('Header 2'));
		$tbody = $table->createElement('tbody');
		$table->append($tbody);
		$tr = $table->createElement('tr');
		$tbody->append($tr);
		$td = $table->createElement('td');
		$tr->append($td);
		$td->append($table->createText('Cell 1'));
		$td = $table->createElement('td');
		$tr->append($td);
		$td->append($table->createText('Cell 2'));
		$tr = $table->createElement('tr');
		$tbody->append($tr);
		$td = $table->createElement('td');
		$tr->append($td);
		$td->append($table->createText('Cell 3'));
		$td = $table->createElement('td');
		$tr->append($td);
		$td->append($table->createText('Cell 4'));

		$this->assertEquals($table->toString(), $this->applyPattern($text)->toString());
	}

	/**
	 * @test
	 */
	public function tableCanHaveLeadingAndTrailingPipes()
	{
		$text = '

| Header 1  | Header 2  |
| --------- | --------- |
| Cell 1    | Cell 2    |
| Cell 3    | Cell 4    |

';

		$table = new \ElementTree\ElementTreeElement('table');
		$thead = $table->createElement('thead');
		$table->append($thead);
		$tr = $table->createElement('tr');
		$thead->append($tr);
		$th = $table->createElement('th');
		$tr->append($th);
		$th->append($table->createText('Header 1'));
		$th = $table->createElement('th');
		$tr->append($th);
		$th->append($table->createText('Header 2'));
		$tbody = $table->createElement('tbody');
		$table->append($tbody);
		$tr = $table->createElement('tr');
		$tbody->append($tr);
		$td = $table->createElement('td');
		$tr->append($td);
		$td->append($table->createText('Cell 1'));
		$td = $table->createElement('td');
		$tr->append($td);
		$td->append($table->createText('Cell 2'));
		$tr = $table->createElement('tr');
		$tbody->append($tr);
		$td = $table->createElement('td');
		$tr->append($td);
		$td->append($table->createText('Cell 3'));
		$td = $table->createElement('td');
		$tr->append($td);
		$td->append($table->createText('Cell 4'));

		$this->assertEquals($table->toString(), $this->applyPattern($text)->toString());		
	}

	/**
	 * @test
	 */
	public function tableCanBeOneColumnOneRowWithLeadingPipes()
	{
		$text = '

| Header
| ------
| Cell

';

		$table = new \ElementTree\ElementTreeElement('table');
		$thead = $table->createElement('thead');
		$table->append($thead);
		$tr = $table->createElement('tr');
		$thead->append($tr);
		$th = $table->createElement('th');
		$tr->append($th);
		$th->append($table->createText('Header'));
		$tbody = $table->createElement('tbody');
		$table->append($tbody);
		$tr = $table->createElement('tr');
		$tbody->append($tr);
		$td = $table->createElement('td');
		$tr->append($td);
		$td->append($table->createText('Cell'));

		$this->assertEquals($table->toString(), $this->applyPattern($text)->toString());
	}

	/**
	 * @test
	 */
	public function tableCanBeOneColumnOneRowWithTrailingPipes()
	{
		$text = '

Header |
------ |
Cell   |

';

		$table = new \ElementTree\ElementTreeElement('table');
		$thead = $table->createElement('thead');
		$table->append($thead);
		$tr = $table->createElement('tr');
		$thead->append($tr);
		$th = $table->createElement('th');
		$tr->append($th);
		$th->append($table->createText('Header'));
		$tbody = $table->createElement('tbody');
		$table->append($tbody);
		$tr = $table->createElement('tr');
		$tbody->append($tr);
		$td = $table->createElement('td');
		$tr->append($td);
		$td->append($table->createText('Cell'));

		$this->assertEquals($table->toString(), $this->applyPattern($text)->toString());
	}

	/**
	 * @test
	 */
	public function tableCanBeOneColumnOneRowWithLeadingAndTrailingPipes()
	{
		$text = '

| Header |
| ------ |
| Cell   |

';

		$table = new \ElementTree\ElementTreeElement('table');
		$thead = $table->createElement('thead');
		$table->append($thead);
		$tr = $table->createElement('tr');
		$thead->append($tr);
		$th = $table->createElement('th');
		$tr->append($th);
		$th->append($table->createText('Header'));
		$tbody = $table->createElement('tbody');
		$table->append($tbody);
		$tr = $table->createElement('tr');
		$tbody->append($tr);
		$td = $table->createElement('td');
		$tr->append($td);
		$td->append($table->createText('Cell'));

		$this->assertEquals($table->toString(), $this->applyPattern($text)->toString());
	}

	/**
	 * @test
	 */
	public function cellCanBeLeftAligned()
	{
		$text = '

| Header
| :-----
| Cell

';

		$table = new \ElementTree\ElementTreeElement('table');
		$thead = $table->createElement('thead');
		$table->append($thead);
		$tr = $table->createElement('tr');
		$thead->append($tr);
		$th = $table->createElement('th');
		$th->setAttribute('align', 'left');
		$tr->append($th);
		$th->append($table->createText('Header'));
		$tbody = $table->createElement('tbody');
		$table->append($tbody);
		$tr = $table->createElement('tr');
		$tbody->append($tr);
		$td = $table->createElement('td');
		$td->setAttribute('align', 'left');
		$tr->append($td);
		$td->append($table->createText('Cell'));

		$this->assertEquals($table->toString(), $this->applyPattern($text)->toString());
	}

	/**
	 * @test
	 */
	public function cellCanBeRightAligned()
	{
		$text = '

| Header
| -----:
| Cell

';

		$table = new \ElementTree\ElementTreeElement('table');
		$thead = $table->createElement('thead');
		$table->append($thead);
		$tr = $table->createElement('tr');
		$thead->append($tr);
		$th = $table->createElement('th');
		$th->setAttribute('align', 'right');
		$tr->append($th);
		$th->append($table->createText('Header'));
		$tbody = $table->createElement('tbody');
		$table->append($tbody);
		$tr = $table->createElement('tr');
		$tbody->append($tr);
		$td = $table->createElement('td');
		$td->setAttribute('align', 'right');
		$tr->append($td);
		$td->append($table->createText('Cell'));

		$this->assertEquals($table->toString(), $this->applyPattern($text)->toString());
	}

	/**
	 * @test
	 */
	public function cellCanBeCenterAligned()
	{
		$text = '

| Header
| :----:
| Cell

';

		$table = new \ElementTree\ElementTreeElement('table');
		$thead = $table->createElement('thead');
		$table->append($thead);
		$tr = $table->createElement('tr');
		$thead->append($tr);
		$th = $table->createElement('th');
		$th->setAttribute('align', 'center');
		$tr->append($th);
		$th->append($table->createText('Header'));
		$tbody = $table->createElement('tbody');
		$table->append($tbody);
		$tr = $table->createElement('tr');
		$tbody->append($tr);
		$td = $table->createElement('td');
		$td->setAttribute('align', 'center');
		$tr->append($td);
		$td->append($table->createText('Cell'));

		$this->assertEquals($table->toString(), $this->applyPattern($text)->toString());		
	}

	/**
	 * @test
	 */
	public function cellsCanBeDifferentlyAligned()
	{
		$text = '

| Header 1  | Header 2  | Header 3 | Header 4
| --------- | :-------- | -------- | --------
| Cell 1    | Cell 2    | Cell 5   | Cell 7
| Cell 3    | Cell 4    | Cell 6   | Cell 8

';

		$table = new \ElementTree\ElementTreeElement('table');
		$thead = $table->createElement('thead');
		$table->append($thead);
		$tr = $table->createElement('tr');
		$thead->append($tr);
		$th = $table->createElement('th');
		$tr->append($th);
		$th->append($table->createText('Header 1'));
		$th = $table->createElement('th');
		$tr->append($th);
		$th->append($table->createText('Header 2'));
		$th->setAttribute('align', 'left');
		$th = $table->createElement('th');
		$tr->append($th);
		$th->append($table->createText('Header 3'));
		$th = $table->createElement('th');
		$tr->append($th);
		$th->append($table->createText('Header 4'));
		$tbody = $table->createElement('tbody');
		$table->append($tbody);
		$tr = $table->createElement('tr');
		$tbody->append($tr);
		$td = $table->createElement('td');
		$tr->append($td);
		$td->append($table->createText('Cell 1'));
		$td = $table->createElement('td');
		$tr->append($td);
		$td->append($table->createText('Cell 2'));
		$td->setAttribute('align', 'left');
		$td = $table->createElement('td');
		$tr->append($td);
		$td->append($table->createText('Cell 5'));
		$td = $table->createElement('td');
		$tr->append($td);
		$td->append($table->createText('Cell 7'));
		$tr = $table->createElement('tr');
		$tbody->append($tr);
		$td = $table->createElement('td');
		$tr->append($td);
		$td->append($table->createText('Cell 3'));
		$td = $table->createElement('td');
		$tr->append($td);
		$td->append($table->createText('Cell 4'));
		$td->setAttribute('align', 'left');
		$td = $table->createElement('td');
		$tr->append($td);
		$td->append($table->createText('Cell 6'));
		$td = $table->createElement('td');
		$tr->append($td);
		$td->append($table->createText('Cell 8'));

		$this->assertEquals($table->toString(), $this->applyPattern($text)->toString());		
	}

	/**
	 * @test
	 */
	public function cellCanBeEmpty()
	{
		$text = '

| Header 1  | Header 2  |
| --------- | --------- |
| Cell 1    |           |
| Cell 3    | Cell 4    |

';

		$table = new \ElementTree\ElementTreeElement('table');
		$thead = $table->createElement('thead');
		$table->append($thead);
		$tr = $table->createElement('tr');
		$thead->append($tr);
		$th = $table->createElement('th');
		$tr->append($th);
		$th->append($table->createText('Header 1'));
		$th = $table->createElement('th');
		$tr->append($th);
		$th->append($table->createText('Header 2'));
		$tbody = $table->createElement('tbody');
		$table->append($tbody);
		$tr = $table->createElement('tr');
		$tbody->append($tr);
		$td = $table->createElement('td');
		$tr->append($td);
		$td->append($table->createText('Cell 1'));
		$td = $table->createElement('td');
		$tr->append($td);
		$td->append($table->createText(''));
		$tr = $table->createElement('tr');
		$tbody->append($tr);
		$td = $table->createElement('td');
		$tr->append($td);
		$td->append($table->createText('Cell 3'));
		$td = $table->createElement('td');
		$tr->append($td);
		$td->append($table->createText('Cell 4'));

		$this->assertEquals($table->toString(), $this->applyPattern($text)->toString());	
	}
}