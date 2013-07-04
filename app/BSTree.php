<?php

require_once('Node.php');

interface InterfaceBSTree
{
	public function deleteLast($side);
	public function isBST();
}

class BSTreeLeaf extends ExternalNode implements InterfaceBSTree
{
	public function deleteLast($side)
	{
		return NULL;
	}

	public function isBST() {
		return true;
	}
}

class BSTree extends InternalNode implements InterfaceBSTree
{
	public function __construct($offset, $value=NULL)
	{
		$class = get_class($this) . 'Leaf';
		
		$this->offset = $offset;
		$this->value = $value;
		$this->children[0] = $this->children[1] = new $class;
	}

	public function deleteLast($side)
	{
		$side = ($side === 'left'
				 || $side === false
				 || $side === 0) ? 0 : 1;

		$child = $this->children[$side];

		if ($child instanceof ExternalNode) {
			return $this;
		} else if ($child->children[$side] instanceof ExternalNode) {
			$this->children[$side] = $child->children[!$side];

			$child->children = [];
			return $child;
		} else {
			return $child->deleteLast($side);
		}
	}
	
	/* from parent INTERNALNODE */

	public function delete($node)
	{
		if ($this->compareTo($node) > 0) {
			$this->children[0] = $this->children[0]->delete($node);
			return $this;
		} else if ($this->compareTo($node) < 0) {
			$this->children[1] = $this->children[1]->delete($node);
			return $this;
		} else if ($this->compareTo($node) === 0) {
			foreach ($this->children as $side => $child) {
			   	$replacementNode = $child->deleteLast(!$side);

				if ($replacementNode !== NULL) {
					if ($replacementNode === $child) {
						$replacementNode->children[$side] = $child->children[$side];
					} else {
						$replacementNode->children[$side] = $child;
					}

					$replacementNode->children[!$side] = $this->children[!$side];
					
					return $replacementNode;
				}
			}

			$class = get_class($this) . 'Leaf';

			return new $class;
		}
	}

	public function insert($node)
	{
		if ($this->compareTo($node) >= 0) {
			$this->children[0] = $this->children[0]->insert($node);
		} else {
			$this->children[1] = $this->children[1]->insert($node);
		}

		return $this;
	}
	
	public function search($node)
	{
		if ($this->compareTo($node) === 0) {
			return $this;
		} else if ($this->compareTo($node) > 0) {
			return $this->children[0]->search($node);
		} else {
			return $this->children[1]->search($node);
		}
	}

	public function isBST()
	{
		$left  = $this->compareTo($this->children[0]);
		$right = $this->compareTo($this->children[1]);

		$left  = (empty($left)) ? 1 : $left;
		$right = (empty($right)) ? -1 : $right;

		if ($left > 0 && $right < 0) {
			return $this->children[0]->isBST() && $this->children[1]->isBST();
		} else {
			return false;
		}
	}
}
