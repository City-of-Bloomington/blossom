<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 *
 */
abstract class ZendDbResultIterator implements ArrayAccess,SeekableIterator,Countable
{
	protected $zend_db;
	protected $select;
	protected $result = array();

	private $valid = false;
	private $cacheEnabled = true;
	private $cache = array();
	private $key;

	abstract public function find($fields=null,$order='',$limit=null,$groupBy=null);
	abstract protected function loadResult($key);

	public function __construct()
	{
		$this->zend_db = Database::getConnection();
		$this->select = new Zend_Db_Select($this->zend_db);
	}

	/**
	 * Runs the query and stores the results
	 */
	protected function populateList()
	{
		$this->result = array();
		$this->result = $this->zend_db->fetchAll($this->select);
	}

	/**
	 * @return string
	 */
	public function getSQL()
	{
		return $this->select->__toString();
	}

	// Array Access section
	/**
	 * @param int $offset
	 * @return boolean
	 */
	public function offsetExists($offset) {
		return array_key_exists($offset,$this->result);
	}
	/**
	 * Unimplemented stub requried for interface compliance
	 * @ignore
	 */
	public function offsetSet($offset,$value) { } // Read-only for now
	/**
	 * Unimplemented stub requried for interface compliance
	 * @ignore
	 */
	public function offsetUnset($offset) { } // Read-only for now
	/**
	 * @param int $offset
	 * @return mixed
	 */
	public function offsetGet($offset)
	{
		if ($this->offsetExists($offset)) {
			return $this->loadResult($offset);
		}
		else {
			throw new OutOfBoundsException('Invalid seek position');
		}
	}



	// SPLIterator Section
	/**
	 * Reset the pionter to the first element
	 */
	public function rewind() {
		$this->key = 0;
	}
	/**
	 * Advance to the next element
	 */
	public function next() {
		$this->key++;
	}
	/**
	 * Return the index of the current element
	 * @return int
	 */
	public function key() {
		return $this->key;
	}
	/**
	 * @return boolean
	 */
	public function valid() {
		return array_key_exists($this->key,$this->result);
	}
	/**
	 * @return mixed
	 */
	public function current()
	{
		return $this->loadResult($this->key);
	}
	/**
	 * @param int $index
	 */
	public function seek($index)
	{
		if (isset($this->result[$index])) {
			$this->key = $index;
		}
		else {
			throw new OutOfBoundsException('Invalid seek position');
		}
	}

	/**
	 * @return Iterator
	 */
	public function getIterator()
	{
		return $this;
	}

	// Countable Section
	/**
	 * @return int
	 */
	public function count()
	{
		return count($this->result);
	}
}
