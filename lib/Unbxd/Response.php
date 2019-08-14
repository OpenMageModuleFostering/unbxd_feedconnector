<?php

/*
 * 
 * 
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
 *
 */



class UnbxdResponse
{
	var $results=array();
	
	public function __construct($results){
		$this->results=$results;		
	}
	
	/**
	 * function to get products
	 */
	
	public function getResults(){
		return $this->results["response"]["products"];		
	}
	
	
	/**
	 * get Number of search Results
	 */
	public function getNumberOfProducts(){
		return $this->results["response"]["numberOfProducts"];
	}
	
	
	/**
	 * get facets
	 * 
	 */
	public function getFacets(){
		return $this->results["facets"];
	}
	
	
	
}

?>