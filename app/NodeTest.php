<?php

require_once "PHPUnit/Autoload.php";
require_once "Node.php";

class NodeTest extends PHPUnit_Framework_TestCase
{
	protected $InternalNode;
	protected $ExternalNode;
	protected $Graph;
	protected $Network;

	public function setUp()
	{
		InternalNode::$uniqueID = 0;

		$this->InternalNode = new InternalNode("root", 0);
		$this->ExternalNode = new ExternalNode;
		$TemporaryNode      = new InternalNode("This is Node 1.");
		$TemporaryNode[]    = "This is Node 2.";
		$TemporaryNode[]    = "This is Node 3.";

		$this->Graph        = new InternalNode("This is Node 4.");
		$this->Graph->insert($TemporaryNode);
		$this->Graph[]      = "This is Node 5.";

		$InputNode          = new InternalNode("Input Node", NULL);
		$HiddenNode1        = new InternalNode("Hidden Node 1", 1);
		$HiddenNode2        = new InternalNode("Hidden Node 2", 2);
		$HiddenNode3        = new InternalNode("Hidden Node 3", 3);
		$OutputNode         = new InternalNode("Output Node", NULL);

		$HiddenNode1->insert($OutputNode);
		$HiddenNode2->insert($OutputNode);
		$HiddenNode3->insert($OutputNode)
			->insert($HiddenNode2);

		$InputNode->insert($HiddenNode1)
			->insert($HiddenNode2)
			->insert($HiddenNode3);

		$this->Network = $InputNode;
	}

	public function testInternalCount()
	{
		$actual   = count($this->InternalNode);
		$expected = 1;

		if ($actual === $expected) echo "Testing: InternalNode->count  ... OK!\n";

		$this->assertEquals($expected, $actual);
	}

	public function testExternalCount()
	{
		$actual   = count($this->ExternalNode);
		$expected = 0;

		if ($actual === $expected) echo "Testing: ExternalNode->count  ... OK!\n";

		$this->assertEquals($expected, $actual);
	}
	
	/**
	 * @depends testInternalCount
	 * @depends testExternalCount
	 */

	public function testGraphCount()
	{
		$actual   = count($this->Graph);
		$expected = 5;

		if ($actual === $expected) echo "Checking that InternalNode->count works with a graph  ... OK!\n";

		$this->assertEquals($expected, $actual);
	}

	public function testHeight()
	{
		$actual   = $this->Graph->height();
		$expected = 3;

		if ($actual === $expected) echo "Testing: Graph->height  ... OK!\n";

		$this->assertEquals($expected, $actual);
	}

	public function testSearch()
	{
		$actual   = isset($this->Graph[3]);
		$expected = true;

		if ($actual === $expected) echo "Testing: InternalNode->search  ... OK!\n";

		$this->assertEquals($expected, $actual);
	}

	/**
	 * @depends testSearch
	 */

	public function testDelete()
	{
		unset($this->Graph[3]);
		$actual   = isset($this->Graph[3]);
		$expected = false;

		if ($actual === $expected) echo "Testing: InternalNode->delete  ... OK!\n";

		$this->assertEquals($expected, $actual);
	}

	public function testNetwork()
	{
		unset($this->Network["Hidden Node 2"]);

		print_r($this->Network);
	}
}
