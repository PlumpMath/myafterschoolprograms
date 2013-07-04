<?php

require_once "PHPUnit/Autoload.php";
require_once "AVLTree.php";

class AVLTreeTest extends PHPUnit_Framework_TestCase
{
	protected $AVLTree;

	public function setUp()
	{
		$this->AVLTree = new AVLTree(0, 0);
		
		/**/
		for($i = 0; $i < 10; $i ++) {
			$this->AVLTree[mt_rand(0,10)] = $i;
		}
		/**/

		print_r($this->AVLTree);
	}

	public function testTree()
	{
		$actual = $this->AVLTree->isBST();

		$this->assertEquals(true, $actual);
	}
}
