<?php
/**
 * Copyright 2012 Unbxd Software Private Limited

   Licensed under the Apache License, Version 2.0 (the "License");
   you may not use this file except in compliance with the License.
   You may obtain a copy of the License at

       http://www.apache.org/licenses/LICENSE-2.0
	
   Unless required by applicable law or agreed to in writing, software
   distributed under the License is distributed on an "AS IS" BASIS,
   WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
   See the License for the specific language governing permissions and
   limitations under the License.
 *
 * 
 * Created on 19-Mar-2013
 * author:antz
 * company:unbxd
 */

include("Service.php");
class Unbxd_Client {
	/**
	 * Default Unbxdsearch ruleset
	 */
	const DEFAULT_RULESET = 'search';	

	/**
	 * Default transport
	 *
	 * @var string
	 */
	const DEFAULT_TRANSPORT = 'http';
	
	protected $params = array(
		'ruleset' => self::DEFAULT_RULESET,
		'multiSelectFacet' => false,
		'filter' => array(),
		'rangeFilter' =>array(),
		'cond' => array(),
		'query' => '',
		'category-id' => ''
		);
	
	protected $address = '';
	
	/**
	 * Number of seconds after a timeout occurs for every request
	 * If using indexing of file large value necessary.
	 */
	const TIMEOUT = 300;

	/**
	 * Config with defaults
	 *
	 * @var array
	 */
	protected $_config = array(
		'ruleset' => self::DEFAULT_RULESET,
		'transport' => self::DEFAULT_TRANSPORT,		
		'timeout' => self::TIMEOUT,
		'headers' => array()	
	);
	
	

	/**
	 * Creates a new Unbxd client
	 *
	 * @param array $config OPTIONAL Additional config options
	 */
	public function __construct(array $config = array()) {
		$this->setConfig($config);
		$this->address = $this->_config['transport'].'://search.unbxdapi.com/'.$this->_config['apikey'].'/'
            .$this->_config['sitename'].'/';
	}

	/**
	 * Sets specific config values (updates and keeps default values)
	 *
	 * @param array $config Params
	 */
	public function setConfig(array $config) {
		foreach ($config as $key => $value) {
			$this->_config[$key] = $value;
		}

		return $this;
	}

	/**
	 * Returns a specific config key or the whole
	 * config array if not set
	 *
	 * @param string $key Config key
	 * @return array|string Config value
	 */
	public function getConfig($key = '') {
		if (empty($key)) {
			return $this->_config;
		}

		if (!array_key_exists($key, $this->_config)) {
			throw new Exception('Config key is not set: ' . $key);
		}

		return $this->_config[$key];
	}

	/**
	 * Sets / overwrites a specific config value
	 *
	 * @param string $key Key to set
	 * @param mixed $value Value
	 * @return Unbxd_Client Client object
	 */
	public function setConfigValue($key, $value) {
		return $this->setConfig(array($key => $value));
	}


	/**
	 * Returns host the client connects to
	 *
	 * @return string Host
	 */
	public function getSiteName() {
		return $this->getConfig('sitename');
	}

	/**
	 * Returns connection port of this client
	 *
	 * @return int Connection port
	 */
	public function getApiKey() {
		return (int) $this->getConfig('apikey');
	}

	/**
	 * Returns transport type to user
	 *
	 * @return string Transport type
	 */
	public function getTransport() {
		return $this->getConfig('transport');
	}
	

	/**
	 * sets the attribute filter
	 * @param mixed $filter array
	 * @return Unbxd_Client Client object
	 */	
	public function setFilters($filter =array()){
		if(isset($filter) && is_array($filter)){
			$this->params['filter'] = $filter;
		}
		return $this;
	}
		
	/**
	 * sets the range filter
	 * @param mixed $rangeFilter array
	 * @return Unbxd_Client Client object
	 */
	public function setRangeFilters($rangeFilter =array()){
		if(isset($rangeFilter) && is_array($rangeFilter)){
			$this->params['rangeFilter'] = $rangeFilter;
		}
		return $this;
	}		
	
	/**
	 * sets the offset
	 * @param mixed $pg integer
	 * @return Unbxd_Client Client object
	 */
	public function setOffset($pg = 0){
		if(isset($pg)){
			$this->params['start'] = $pg;
		}else{
			$this->params['start'] = 10;
		}
		return $this;
	}
	
	/**
	 * sets the limit
	 * @param mixed $limit integer
	 * @return Unbxd_Client Client object
	 */
	public function setLimit($limit = 20){
		if(isset($limit)){
			$this->params['limit'] = $limit;
		}else{
			$this->params['limit'] = 20;
		}
		
		return $this;
	}
	
	/**
	 * sets the ruleset
	 * @param mixed $ruleset string
	 * @return Unbxd_Client Client object
	 */
	public function setRuleset($ruleset = 'search'){
		if(isset($ruleset)){
			$this->params['ruleset'] = $ruleset;
		}else{
			$this->params['ruleset'] = 'search';
		}
		return $this;
	}
	
	/**
	 * sets the sort
	 * @param mixed $sorts array
	 * @return Unbxd_Client Client object
	 */
	public function setSort($sorts = array()){
		if(isset($sorts) && is_array($sorts)){
			$this->params['sort'] = $sorts;
		}	
		return $this;
	}
	
	/**
	 * sets the facet fields, This is mainly used for 	
	 * @param mixed $sorts array
	 * @return Unbxd_Client Client object
	 */
	public function setFacetFields($facetField = array()){
		if(isset($facetField) && is_array($facetField)){
			$this->params['facets'] = $facetField;
		}
		return $this;
	}
	
	/**
	 * sets the other options which can be used
	 * @param mixed $options array
	 * @return Unbxd_Client Client object
	 */
	public function setOtherOptions($options =array()){
		if(isset($options) && is_array($options)){
			$this->params['others'] = $options;
		}
		return $this;
	}
	
	/**
	 * sets the search query
	 * @param mixed $query search
	 * @return Unbxd_Client Client object
	 */
	public function setQuery($query = ''){
		if(isset($query)){
			$this->params['query'] = $query;
		}
		return $this;
	}
	
	/**
	 * sets the Category Id
	 * @param mixed $query search
	 * @return Unbxd_Client Client object
	 */
	public function setCategoryId($query = ''){
		if(isset($query)){
			$this->params['category-id'] = $query;
		}
		return $this;
	}

	/**
	 * sets the Cond
	 * @param mixed $query search
	 * @return Unbxd_Client Client object
	 */
	public function setCond($query = ''){
		if(isset($query)){
			$this->params['cond'] = $query;
		}
		return $this;
	}

	/**
	 * sets the Cond
	 * @param mixed $query search
	 * @return Unbxd_Client Client object
	 */
	public function setDebug($debug = false){
		if(isset($debug)){
			$this->params['debug'] = $debug;
		}
		return $this;
	}

	/**
	 * sets the multiSelectFacet
	 * @param mixed $multiSelectFacet boolean
	 * @return Unbxd_Client Client object
	 */
	public function setMultiSelectFacet($multiSelectFacet = false){
		if(isset($multiSelectFacet)){
			$this->params['multiSelectFacet'] = $multiSelectFacet;
		}
		return $this;

	}
	
	
	/**
	 *
	 * Search through Unbxd api
	 *
	 * @return Unbxd_ResultSet object
	 */	
	public function search(){
		$service = new 	Unbxd_Service();
		
		return $service->search($this->params,$this->address);
			
	}

		
}
