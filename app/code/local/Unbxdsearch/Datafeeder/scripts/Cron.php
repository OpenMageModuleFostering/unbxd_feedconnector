<?php
require_once 'abstract.php';

class Unbxdsearch_Datafeeder_Scripts_Cron extends Mage_Shell_Abstract
{
	
	public function _getIndexer()
	{
		return Mage::helper('unbxdsearch_datafeeder/UnbxdIndexingHelper');
	}
	
	public function run(){
		$_helper = _getIndexer();
		$fromdate="1970-01-01 00:00:00";
	   	$site='Main Site';
	    	
	  	$_helper->indexUnbxdFeed($fromdate,$site);
	}
	
}


$shell = new Unbxdsearch_Datafeeder_Scripts_Cron();
$shell->run();
?>