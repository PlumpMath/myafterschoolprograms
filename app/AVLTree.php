<?php

require_once('BSTree.php');

interface InterfaceAVLTree
{
	public function balance();
	public function height();
}

class AVLTreeLeaf extends BSTreeLeaf implements InterfaceAVLTree
{
	public function balance() {}
	public function height() {
		return 0;
	}
}

class AVLTree extends BSTree implements InterfaceAVLTree
{
	protected $height;

	public function __construct($offset, $value=NULL)
	{
		parent::__construct($offset, $value);

		$this->height = 1;
	}

	public function getBalance() {
		$leftHeight = $this->children[0]->height();
		$rightHeight = $this->children[1]->height();

		$this->height = ($leftHeight > $rightHeight) ? $leftHeight : $rightHeight;

		return $rightHeight - $leftHeight;
	}

	/* from interface INTERFACEAVLTREE */

	public function balance()
	{
		$balance = $this->getBalance();

		if ($balance < -1 || $balance > 1) {
			$side = ($balance < -1) ? 0 : 1;
			$balance = ($this->children[$side] instanceof ExternalNode) ? 0 :
				$this->children[$side]->getBalance();

			if (($side === 0 && $balance <= 0) || ($side === 1 && $balance >=0)) {
				$this->rotate($side);
			} else {
				$this->children[$side]->rotate(!$side);
				$this->rotate($side);
			}
		}
	}

	public function height() {
		return $this->height;
	}
	
	/* from parent BSTree */

	public function delete($node)
	{
		if ($this->compareTo($node) > 0) {
			$this->children[0] = $this->children[0]->delete($node);
			$this->balance();
			return $this;
		} else if ($this->compareTo($node) < 0) {
			$this->children[1] = $this->children[1]->delete($node);
			$this->balance();
			return $this;
		} else if ($this->compareTo($node) === 0) {
			foreach ($this->children as $side => $child) {
			   	$replacementNode = $child->deleteLast(!$side);

				if ($replacementNode !== NULL) {
					if ($replacementNode === $child) {
						$replacementNode->children[$side] = $child->children[$side];
						$replacementNode->children[$side]->balance();
					} else {
						$replacementNode->children[$side] = $child;
					}

					$replacementNode->children[!$side] = $this->children[!$side];
					
					$replacementNode->balance();
					return $replacementNode;
				}
			}

			return new AVLTreeLeaf;
		}
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

			$this->balance();
			$child->children = [];
			return $child;
		} else {
			$deletedNode =  $child->deleteLast($side);
			$this->balance();
			return $deletedNode;
		}
	}

	public function insert($node)
	{
		if ($this->compareTo($node) >= 0) {
			$this->children[0] = $this->children[0]->insert($node);
		} else {
			$this->children[1] = $this->children[1]->insert($node);
		}

		$this->balance();
		return $this;
	}

	protected function rotate($side)
	{
		$side = ($side === 'LL'
				 || $side === false
				 || $side === 0) ? 0 : 1;

		if ($this->children[$side] instanceof ExternalNode) {
			return;
		}

		$child = $this->children[!$side];

		$class = get_class($this);

		$this->children[!$side] = new $class($this->offset, $this->value);
		$this->children[!$side]->children[$side] = $this->children[$side]->children[!$side];
		$this->children[!$side]->children[!$side] = $child;

		$this->offset = $this->children[$side]->offset;
		$this->value = $this->children[$side]->value;
		$this->children[$side] = $this->children[$side]->children[$side];
	}
}
