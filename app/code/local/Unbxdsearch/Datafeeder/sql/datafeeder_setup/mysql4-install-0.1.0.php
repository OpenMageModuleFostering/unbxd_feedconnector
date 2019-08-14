<?php


$installer = $this;
/* @var $installer Mage_Core_Model_Resource_Setup */

$installer->startSetup();

$installer->run("

 DROP TABLE IF EXISTS `{$installer->getTable('unbxd_search_field')}`;

CREATE TABLE `{$installer->getTable('unbxd_search_field')}` (
    `field_id` int(10) unsigned NOT NULL auto_increment,
    `name` varchar(255) NOT NULL default '',
    `status` varchar(255),
    `site` varchar(255)  NOT NULL default '',
    PRIMARY KEY  (`field_id`)        
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



 DROP TABLE IF EXISTS `{$installer->getTable('unbxd_datafeeder_conf')}`;

CREATE TABLE `{$installer->getTable('unbxd_datafeeder_conf')}` (
    `uconfig_id` int(10) unsigned NOT NULL auto_increment,
    `action` varchar(255) NOT NULL default '',
    `value` varchar(255),
    PRIMARY KEY  (`uconfig_id`)
        
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		
");
$installer->endSetup();
 

