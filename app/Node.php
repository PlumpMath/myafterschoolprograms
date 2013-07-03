<?php

interface Comparable
{
	public function compareTo($obj);
}

interface InterfaceNode extends ArrayAccess, Comparable, Countable
{
	public function delete($node);

	public function height();

	public function insert($node);

	public function search($node);
}

class ExternalNode implements InterfaceNode
{
	/* from interface ARRAYACCESS */

	public function offsetExists($offset)
	{
		return false;
	}

	public function offsetGet($offset)
	{
		return NULL;
	}

	public function offsetSet($offset, $value) {}

	public function offsetUnset($offset) {}

	/* from interface COMPARABLE */

	public function compareTo($obj)
	{
		return NULL;
	}

	/* from interface COUNTABLE */

	public function count()
	{
		return 0;
	}

	/* from interface INTERFACENODE */

	public function delete($node)
	{
		return NULL;		
	}

	public function height()
	{
		return 0;
	}

	public function insert($node)
	{
		return $node;
	}

	public function search($node)
	{
		return NULL;
	}
}

class InternalNode implements InterfaceNode
{
	protected $children;
	protected $offset;
	protected $value;
	public static $uniqueID = 0;

	public function __construct()
	{
		$args = func_get_args();

		if (func_num_args() == 1) {
			$offset = ++self::$uniqueID;
			$value  = $args[0];
		} else {
			$offset = $args[0];
			$value  = $args[1];
		}

		$this->offset = $offset;
		$this->value = $value;
		$this->children = [new ExternalNode];
	}

	/* from interface ARRAYACCESS */

	public function offsetExists($offset)
	{
		$node = new InternalNode($offset, NULL);

		return ($this->search($node) instanceof InternalNode);
	}

	public function offsetGet($offset)
	{
		$node = new InternalNode($offset, NULL);

		return $this->search($node);
	}

	public function offsetSet($offset = NULL, $value)
	{
		$id = (empty($offset)) ? (++self::$uniqueID) : ($offset);

		$node = new InternalNode($id, $value);

		$this->insert($node);
	}

	public function offsetUnset($offset)
	{
		$node = new InternalNode($offset, NULL);

		$this->delete($node);
	}

	/* from interface COMPARABLE */

	public function compareTo($obj)
	{
		if (!isset($obj->offset)) return;

		if ($this->offset === $obj->offset) {
			return 0;
		} else if ($this->offset >= $obj->offset) {
			return 1;
		} else if ($this->offset <= $obj->offset) {
			return -1;
		}
	}

	/* from interface COUNTABLE */

	public function count()
	{
		$n = 0;

		foreach($this->children as $child) {
			$n += $child->count();
		}

		return $n + 1;
	}

	/* from interface INTERFACENODE */

	public function delete($node)
	{
		$ax = NULL;

		foreach($this->children as $key => $child) {
			if ($child->compareTo($node) === 0) {
				$ax = $child;
				unset($this->children[$key]);
				array_values($this->children);
				break;
			} else {
				$obj = $child->delete($node);

				if (!empty($obj)) {
					$ax = $obj;
					break;
				}
			}
		}

		return $ax;
	}

	public function height()
	{
		$ax = 0;

		foreach($this->children as $child) {
			$height = $child->height();

			if ($height > $ax) {
				$ax = $height;
			}
		}

		return $ax + 1;
	}

	public function insert($node)
	{
		$this->children[] = $node;
	}

	public function search($node)
	{
		$ax = NULL;

		if ($this->compareTo($node) === 0) {
			$ax = $this;
		} else {
			foreach($this->children as $child) {
				$obj = $child->search($node);

				if (!empty($obj)) {
					$ax = $obj;
					break;
				}
			}
		}

		return $ax;
	}
}
