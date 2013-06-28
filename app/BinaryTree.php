<?php

// {{{ Comparable

/**
 * Comparable is the abstract class for all objects
 * that need to be compared. Objects that extend this
 * class can precisely control how they are compared
 * with other objects.
 */

abstract class Comparable
{
	// {{{ compareTo
	
	/**
	 * Compares two objects
	 *
	 * @param $obj    Object to compare with $this
	 * @return        Zero if equal, some positive number if $this is > $obj
	 *                or some negative number if $this < $obj
	 */

	abstract public function compareTo($obj);

	// }}}
}

// }}}
// {{{ Node

/**
 * A record that may contain links to other nodes
 * as well as a data field. Objects that implement this
 * interface will be able to:
 * . Delete a Node
 * . Determine if they have children
 * . Inserts a new child
 * . Count their children 
 */

interface Node
{
	// {{{ delete

	/**
	 * Deletes Node
	 *
	 * @param $value    Value of Node to delete
	 * @return          Deleted Node or NULL if node
	 *                  doesn't exist
	 */

	public function delete($node);

	// }}}
	// {{{ deleteLargest
	
	/**
	 * Deletes Largest Node
	 *
	 * @param $direction    "right" or "left"
	 * @param $parent       Parent Node to current Node
	 * @return              Deleted Node
	 */

	public function deleteFarthest($direction);

	// }}}
	// {{{ getHeight
	
	/**
	 * Gets Tree Height
	 *
	 * @return    Tree Height
	 */

	public function getHeight();

	// }}}
	// {{{ isEmpty

	/**
	 * Determines if there are children
	 *
	 * @return    True if this Node has children,
	 *            False otherwise
	 */

	public function isEmpty();

    // }}}
	// {{{ insert

	/**
	 * Inserts a node into children
	 *
	 * @param $value    Node to insert
	 * @return          Root node
	 */

    public function insert($node);

	// }}}
	// {{{ getSize

	/**
	 * Counts Children
	 *
	 * @return    Number of children plus self
	 */

	public function getSize();

	// }}}
	// {{{ search

	/**
	 * Gets node corresponding to value
	 *
	 * @param $node    Node with value to search for
	 * @return         Node with value or NULL
	 */

	public function search($node);

	// }}}
}

// }}}
// {{{ ExternalNode

class ExternalNode extends Comparable implements Node
{
	public function compareTo($obj)
	{
		return NULL;
	}

	public function delete($node)
	{
		return NULL;
	}

	public function deleteFarthest($direction)
	{
		return NULL;
	}

	public function getHeight()
	{
		return 0;
	}

	public function isEmpty()
	{
		return true;
    }

	public function insert($node)
	{
		return $node;
	}

	public function getSize()
	{
		return 0;
	}

	public function search($node)
	{
		return NULL;
	}
}

// }}}
// {{{ InternalNode

class InternalNode extends Comparable implements Node
{
	// {{{ properties

	protected $data;
	protected $leftChild;
	protected $rightChild;

	// }}}
	// {{{ Constructor

	public function __construct($data, $leftChild = NULL, $rightChild = NULL)
	{
		$this->data = $data;
	    $this->leftChild = (!($leftChild instanceof Node)) ? new ExternalNode() : $leftChild;
		$this->rightChild = (!($rightChild instanceof Node)) ? new ExternalNode() : $rightChild;
	}

	// }}}
	// {{{ compareTo

	public function compareTo($obj)
	{
		return $this->data - $obj->data;
	}

	// }}}
	// {{{ delete
	
	public function delete($node)
	{
		if ($this->compareTo($node) > 0) {
			//node is smaller
			$this->leftChild = $this->leftChild->delete($node);
			return $this;
		} else if ($this->compareTo($node) < 0) {
			//node is larger
			$this->rightChild = $this->rightChild->delete($node);
			return $this;
		} else {
			//nodes are equivalent
			$directions = ["left", "right"];

			for ($i = 0; $i < 2; $i += 1) {
				$child = $directions[$i] . "Child";

				$replacement = $this->$child->deleteFarthest($directions[!$i]);
			
				if ($replacement !== NULL) {
					if ($replacement === $this->$child) {
						$replacement->{"set" . ucfirst($directions[$i]) . "Child"}($this->$child->$child);
					} else {
						$replacement->{"set" . ucfirst($directions[$i]) . "Child"}($this->$child);
					}

					$replacement->{"set" . ucfirst($directions[!$i]) . "Child"}($this->{$directions[!$i] . "Child"});

					return $replacement;
				}
			}
   		}
	}

	// }}}
	// {{{ deleteFarthest

	public function deleteFarthest($direction)
	{
		$child = $direction . "Child";

	    if ($this->$child->isEmpty()) {
			//$this is farthest
			return $this;
		} else if ($this->$child->{"get" . ucfirst($direction) . "Child"}()->isEmpty()) {
			//child element is farthest
			$inverse = ($direction === "right") ? "Left" : "Right";
			$deletedNode = $this->$child;

			$this->{"set" . ucfirst($direction) . "Child"}($this->$child->{"get" . $inverse . "Child"}());

			$deletedNode->setLeftChild(new ExternalNode())->setRightChild(new ExternalNode());
			return $deletedNode;
		} else {
			//farthest is not yet visible
			return $this->{$direction . "Child"}->deleteFarthest($direction);
		}
	}

	// }}}
	// {{{ getHeight

	public function getHeight()
	{
		$leftSubTreeHeight = $this->leftChild->getHeight();
		$rightSubTreeHeight = $this->rightChild->getHeight();

		return (($leftSubTreeHeight > $rightSubTreeHeight) ? $leftSubTreeHeight : $rightSubTreeHeight) + 1;
	}
	
	// }}}
	// {{{ isEmpty

	public function isEmpty()
	{
		return false;
	}

	// }}}
	// {{{ insert
	
	public function insert($node)
	{
		if ($this->compareTo($node) >= 0) {
			$this->leftChild = $this->leftChild->insert($node);
		} else {
			$this->rightChild = $this->rightChild->insert($node);
		}

		return $this;
	}

	// }}}
	// {{{ getLeftChild

	public function getLeftChild()
	{
		return $this->leftChild;
	}

	// }}}
	// {{{ getRightChild

	public function getRightChild()
	{
		return $this->rightChild;
	}

	// }}}
	// {{{ getSize

	public function getSize()
	{
		return $this->leftChild->getSize() + $this->rightChild->getSize() + 1;
	}

	// }}}
	// {{{ search
	
	public function search($node)
	{
		if ($this->compareTo($node) > 0) {
			return $this->leftChild->search($node);
		} else if ($this->compareTo($node) < 0){
			return $this->rightChild->search($node);
		} else {
			return $this;
		}
	}

	// }}}
	// {{{ setLeftChild
	
	public function setLeftChild($node)
	{
		$this->leftChild = $node;

		return $this;
	}

	// }}}
	// {{{ setRightChild

	public function setRightChild($node)
	{
		$this->rightChild = $node;

		return $this;
	}

	// }}}
}

// }}}
