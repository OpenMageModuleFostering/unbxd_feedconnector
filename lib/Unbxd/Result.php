<?php
/**
 * Unbxd result item
 *
 * Stores all information from a result
 *
 * @category Xodoa
 * @package Elastica
 * @author antz <ananthesh@unbxd.com>
 */
class Unbxd_Result {
	/**
	 * Hit array
	 * 
	 * @var array Hit array
	 */
	protected $_hit = array();

	/**
	 * Constructs a single results object
	 *
	 * @param array $hit Hit data
	 */
	public function __construct(array $hit) {
		$this->_hit = $hit;
	}

	/**
	 * Magic function to directly access keys inside the result
	 *
	 * Returns null if key does not exist
	 *
	 * @param string $key Key name
	 * @return mixed Key value
	 */
	public function __get($key) {		
		return array_key_exists($key, $this->_hit) ? $this->_hit[$key] : null;
	}
	
	
	/*
	 * Returns the raw hit array
	 *
	 * @return array Hit array
	 */
	public function getHit() {
		return $this->_hit;
	}
}
