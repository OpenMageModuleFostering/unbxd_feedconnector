<?php
require_once 'abstract.php';

class Unbxdsearch_Datafeeder_Scripts_UnbxdFeed extends Mage_Shell_Abstract
{
	
	public function _getIndexer()
	{
		return Mage::helper('unbxdsearch_datafeeder/UnbxdIndexingHelper');
	}
	
	public function run(){
		$_helper = $this->_getIndexer();
		$fromdate="1970-01-01 00:00:00";
	   	$site='Main Website';
	    	
	  	$_helper->indexUnbxdFeed($fromdate,$site);
	}
	
}


$shell = new Unbxdsearch_Datafeeder_Scripts_UnbxdFeed();
$shell->run();
?>
