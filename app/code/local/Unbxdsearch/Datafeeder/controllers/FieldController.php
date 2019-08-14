<?php 

class Unbxdsearch_Datafeeder_FieldController extends  Mage_Core_Controller_Front_Action
{
	
	const FIELD_MODEL = 'datafeeder/field';
	
	public function configAction() {
		$site=$this->getRequest()->getParam("site");
		if(isset($site)){
			$site="Main Website";
		}
		$fields = Mage::getResourceSingleton(self::FIELD_MODEL)->getFields($site);
		echo json_encode($fields);
	}
	
	public function saveAction()
	{
		$params=$this->getRequest()->getParams();
		if(!isset($params["site_name"])) {
			echo json_encode(array("success"=>"false", "message"=>"Site doesnt exists"));
			return;
		}
		$site = $params["site_name"];
		unset($params["site_name"]);
		unset($params["form_key"]);
		$fields = Mage::getResourceSingleton(self::FIELD_MODEL)->updateFields($params,$site);
		echo json_encode(array("success"=>"true"));
	}
}
?>
