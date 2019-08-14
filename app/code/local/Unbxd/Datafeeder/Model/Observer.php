<?php
/*@author Pooja
 *
* Observer to capture order event from frontend
* It captures the event and sends the ordered product Ids to inremmental feed upload in FeedManager
*/


class Unbxd_Datafeeder_Model_Observer
{

	var $websiteMap ;

	/*Mapping between siteId & websiteName */
	private function getSite($websiteId) {

		if(!isset($this->websiteMap)) {
			$sites=Mage::app()->getWebsites();
			foreach( $sites as $eachSite){
				$this->websiteMap[$eachSite->getWebsiteId()] = $eachSite->getName();
			}
		}
		return $this->websiteMap[$websiteId];
	}

	public function logorderStockupdate($observer)
	{
		$updateIds = array();
		$siteIds = array();
		$event = $observer->getEvent();
		if(!$event->hasData('order')) {
			Mage::getSingleton('unbxd_datafeeder/feed_filemanager')->log('Order data not present in the event');
			return;
		}
		$order = $observer->getEvent()->getOrder();
		$items = $order->getAllItems();
		//get entity_id of all the ordered products
		foreach ($items as $item)
		{
			//get product from Order_Sales item object
			$product = $item->getProduct();
			array_push($updateIds,$product->getId());
			foreach($product->getWebsiteIds() as $websiteId){
				array_push($siteIds,$websiteId);
			}
			//get a set of siteIds from all the products
			$siteIds = array_unique($siteIds);
		}
		//update the feed for each site
		foreach($siteIds as $siteId) {
			$site = $this->getSite($siteId);
			if(!isset($site)) {
				continue;
			}

			//call incremental feed upload
			Mage::getSingleton('unbxd_datafeeder/feed_feedmanager')->processIncremental("1970-01-01 00:00:00",$site,"add",$updateIds);
		}

		return $this;
	}
	public function inventoryStockUpdate($observer)
	{
		$event = $observer->getEvent();
		if(!$event->hasData('item')) {
			Mage::getSingleton('unbxd_datafeeder/feed_filemanager')->log('Item data not present in the inventory event');
			return;
		}
		$item = $event->getItem();
		$product = $item->getProduct();
		$updateIds = array($product->getId());
		$siteIds = array();
		foreach($product->getWebsiteIds() as $websiteId){
			array_push($siteIds,$websiteId);
		}
		$siteIds = array_unique($siteIds);
		
		Mage::getSingleton('unbxd_datafeeder/feed_filemanager')->log('Inventory stock update initiated');
		foreach($siteIds as $siteId) {
			$site = $this->getSite($siteId);
			if(!isset($site)) {
				continue;
			}
		
			//call incremental feed upload
			Mage::getSingleton('unbxd_datafeeder/feed_feedmanager')->processIncremental("1970-01-01 00:00:00",$site,"add",$updateIds);
		}
	}
}